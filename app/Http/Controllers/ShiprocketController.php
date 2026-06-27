<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PickupAddress;
use App\Models\ShippingBoxSize;
use App\Services\ShiprocketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShiprocketController extends Controller
{
    protected $shiprocket;

    public function __construct(ShiprocketService $shiprocket)
    {
        $this->shiprocket = $shiprocket;
    }

    public function update(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Data can not change in demo mode.'))->error();
            return back();
        }

        $this->overWriteEnvFile('SHIPROCKET_EMAIL', $request->input('SHIPROCKET_EMAIL', ''));
        $this->overWriteEnvFile('SHIPROCKET_PASSWORD', $request->input('SHIPROCKET_PASSWORD', ''));
        $this->overWriteEnvFile('SHIPROCKET_DEFAULT_WEIGHT', $request->input('SHIPROCKET_DEFAULT_WEIGHT', '0.5'));
        $this->overWriteEnvFile(
            'SHIPROCKET_MOCK_MODE',
            $request->has('SHIPROCKET_MOCK_MODE') ? 'true' : 'false'
        );

        Artisan::call('config:clear');

        flash(translate('Shiprocket settings updated successfully'))->success();
        return back();
    }

    public function createShipmentFromList($id): RedirectResponse
    {
        try {
            $order = Order::findOrFail($id);

            $pickupAddress = $this->resolveDefaultPickupAddress();
            $boxSize = $this->resolveDefaultBoxSize();

            if ((!$pickupAddress || !$boxSize) && !$this->shiprocket->isMockMode()) {
                flash(translate('Please configure a Shiprocket pickup address and box size first, or open the order and select them manually.'))->warning();
                return redirect()->route('all_orders.show', encrypt($order->id));
            }

            $result = $this->processShipment($order, $pickupAddress, $boxSize);

            flash($result['message'])->success();
            return redirect()->route('all_orders.show', encrypt($order->id));
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function createOrderShiprocket(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
            'pickup_address_id' => 'nullable|integer',
            'pickup_location' => 'nullable|string|max:255',
            'shipping_box_size_id' => 'nullable|integer',
            'length' => 'nullable|numeric|min:0.1',
            'breadth' => 'nullable|numeric|min:0.1',
            'height' => 'nullable|numeric|min:0.1',
        ]);

        if (!$request->pickup_address_id && !$request->pickup_location) {
            return response()->json([
                'success' => false,
                'message' => translate('Please select a pickup location.'),
            ], 422);
        }

        try {
            $order = Order::findOrFail($request->order_id);
            $pickupAddress = $this->resolvePickupAddress($request);
            $boxSize = $this->resolveBoxSize($request);

            $result = $this->processShipment($order, $pickupAddress, $boxSize);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'mock' => $result['mock'],
                'payload' => $result['payload'],
                'create_response' => $result['create_response'],
                'awb_response' => $result['awb_response'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function previewPayload(Request $request, $orderId): JsonResponse
    {
        $request->validate([
            'pickup_address_id' => 'nullable|integer',
            'shipping_box_size_id' => 'nullable|integer',
        ]);

        try {
            $order = Order::findOrFail($orderId);

            $pickupAddress = $request->pickup_address_id
                ? PickupAddress::where('id', $request->pickup_address_id)->where('courier_type', 'shiprocket')->firstOrFail()
                : $this->resolveDefaultPickupAddress();

            $boxSize = $request->shipping_box_size_id
                ? ShippingBoxSize::where('id', $request->shipping_box_size_id)->where('courier_type', 'shiprocket')->firstOrFail()
                : $this->resolveDefaultBoxSize();

            if ((!$pickupAddress || !$boxSize) && !$this->shiprocket->isMockMode()) {
                return response()->json([
                    'success' => false,
                    'message' => translate('Pickup address and box size are required to preview payload.'),
                ], 422);
            }

            $pickupAddress = $pickupAddress ?: $this->mockPickupAddress();
            $boxSize = $boxSize ?: $this->mockBoxSize();

            $payload = $this->buildShiprocketPayload($order, $pickupAddress, $boxSize);

            return response()->json([
                'success' => true,
                'mock' => $this->shiprocket->isMockMode(),
                'payload' => $payload,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deliveryStatus(): JsonResponse
    {
        try {
            $orders = Order::where('shipping_method', 'shiprocket')
                ->whereNotNull('shiprocket_shipment_id')
                ->whereNotIn('delivery_status', ['delivered', 'cancelled'])
                ->get();

            $updated = 0;

            foreach ($orders as $order) {
                $tracking = $this->shiprocket->trackShipment(
                    (int) $order->shiprocket_shipment_id,
                    $order->delivery_status
                );

                $mapped = $this->mapTrackingToOrderStatus($tracking, $order->delivery_status);

                if ($this->shiprocket->isMockMode() && isset($tracking['mapped_delivery_status'])) {
                    $mapped['delivery_status'] = $tracking['mapped_delivery_status'];
                }

                DB::table('orders')->where('id', $order->id)->update([
                    'shiprocket_status' => $mapped['shiprocket_status'],
                    'shiprocket_status_code' => $mapped['shiprocket_status_code'],
                    'delivery_status' => $mapped['delivery_status'],
                ]);

                $updated++;
            }

            return response()->json([
                'success' => true,
                'message' => translate('Shiprocket delivery status sync completed.'),
                'mock' => $this->shiprocket->isMockMode(),
                'processed' => $orders->count(),
                'updated' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getToken(): JsonResponse
    {
        try {
            $token = $this->shiprocket->getToken();

            return response()->json([
                'success' => true,
                'mock' => $this->shiprocket->isMockMode(),
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function listPickupLocations(): JsonResponse
    {
        try {
            $response = $this->shiprocket->getPickupLocations();
            $addresses = collect($response['data']['shipping_address'] ?? [])
                ->map(function ($address) {
                    return [
                        'id' => $address['id'] ?? null,
                        'pickup_location' => $address['pickup_location'] ?? '',
                        'address' => $address['address'] ?? '',
                        'city' => $address['city'] ?? '',
                        'state' => $address['state'] ?? '',
                        'pin_code' => $address['pin_code'] ?? '',
                        'is_primary_location' => (int) ($address['is_primary_location'] ?? 0),
                    ];
                })
                ->filter(fn ($address) => !empty($address['pickup_location']))
                ->values();

            return response()->json([
                'success' => true,
                'mock' => $this->shiprocket->isMockMode(),
                'data' => $addresses,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function webhook(Request $request): JsonResponse
    {
        if ($request->isMethod('get')) {
            return $this->webhookPing();
        }

        try {
            $this->logWebhook('info', 'Shiprocket webhook received', [
                'ip' => $request->ip(),
                'headers' => [
                    'x-api-key' => $request->header('x-api-key'),
                    'content-type' => $request->header('content-type'),
                ],
                'payload' => $request->all(),
            ]);

            if (!$this->isValidWebhookToken($request)) {
                $this->logWebhook('warning', 'Shiprocket webhook unauthorized');

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized webhook token.',
                ], 401);
            }

            $parsed = $this->parseWebhookPayload($request->all());
            $order = $this->resolveOrderForTracking(
                $parsed['shipment_id'],
                $parsed['awb'],
                $parsed['sr_order_id'],
                $parsed['channel_order_id']
            );

            if (!$order) {
                $this->logWebhook('warning', 'Shiprocket webhook: no matching order', $parsed);

                return response()->json([
                    'success' => true,
                    'message' => 'Webhook accepted. No matching order found.',
                ]);
            }

            $mapped = $this->mapShiprocketStatusUpdate(
                $parsed['status_code'],
                $parsed['current_status'],
                $order->delivery_status
            );

            $update = [
                'shiprocket_status' => $mapped['shiprocket_status'],
                'shiprocket_status_code' => $mapped['shiprocket_status_code'],
                'delivery_status' => $mapped['delivery_status'],
                'shipping_method' => 'shiprocket',
            ];

            if ($parsed['awb'] !== '' && empty($order->tracking_code)) {
                $update['tracking_code'] = $parsed['awb'];
            }

            if ($parsed['shipment_id'] > 0 && empty($order->shiprocket_shipment_id)) {
                $update['shiprocket_shipment_id'] = $parsed['shipment_id'];
            }

            if (!empty($parsed['sr_order_id']) && empty($order->shiprocket_order_id)) {
                $update['shiprocket_order_id'] = $parsed['sr_order_id'];
            }

            DB::table('orders')->where('id', $order->id)->update($update);

            $this->logWebhook('info', 'Shiprocket webhook processed', [
                'order_id' => $order->id,
                'shiprocket_status' => $mapped['shiprocket_status'],
                'delivery_status' => $mapped['delivery_status'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed.',
                'order_id' => $order->id,
                'shiprocket_status' => $mapped['shiprocket_status'],
                'delivery_status' => $mapped['delivery_status'],
            ]);
        } catch (\Exception $e) {
            $this->logWebhook('error', 'Shiprocket webhook failed', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function webhookPing(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Webhook endpoint is reachable.',
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    protected function logWebhook(string $level, string $message, array $context = []): void
    {
        Log::channel('shiprocket_webhook')->{$level}($message, $context);
        Log::channel('daily')->{$level}($message, $context);
    }

    protected function isValidWebhookToken(Request $request): bool
    {
        $configuredToken = (string) config('services.shiprocket.webhook_token', '');
        if ($configuredToken === '') {
            return true;
        }

        $incomingToken = (string) (
            $request->header('X-Shiprocket-Webhook-Token')
            ?: $request->header('X-Webhook-Token')
            ?: $request->header('x-api-key')
            ?: $request->bearerToken()
            ?: $request->input('token', '')
        );

        return hash_equals($configuredToken, $incomingToken);
    }

    protected function parseWebhookPayload(array $payload): array
    {
        if (isset($payload[0]) && is_array($payload[0])) {
            $payload = $payload[0];
        }

        $trackingData = is_array($payload['tracking_data'] ?? null) ? $payload['tracking_data'] : [];
        $track = is_array($trackingData['shipment_track'][0] ?? null) ? $trackingData['shipment_track'][0] : [];

        $awb = (string) (
            $payload['awb']
            ?? $payload['awb_code']
            ?? $track['awb_code']
            ?? data_get($payload, 'data.awb_code')
            ?? ''
        );

        $shipmentId = (int) (
            $payload['shipment_id']
            ?? $track['shipment_id']
            ?? data_get($payload, 'data.shipment_id')
            ?? 0
        );

        $channelOrderId = (string) (
            $payload['channel_order_id']
            ?? $payload['order_id']
            ?? $track['order_id']
            ?? data_get($payload, 'data.order_id')
            ?? ''
        );

        $srOrderId = data_get($payload, 'sr_order_id')
            ?? data_get($payload, 'shiprocket_order_id')
            ?? data_get($payload, 'data.order_id');

        $statusCode = (int) (
            $payload['shipment_status_id']
            ?? $payload['current_status_id']
            ?? $payload['sr-status']
            ?? 0
        );

        if ($statusCode === 0 && is_numeric($trackingData['shipment_status'] ?? null)) {
            $statusCode = (int) $trackingData['shipment_status'];
        }

        if ($statusCode === 0 && is_numeric($payload['shipment_status'] ?? null)) {
            $statusCode = (int) $payload['shipment_status'];
        }

        if ($statusCode === 0 && !empty($payload['is_return'])) {
            $statusCode = 8;
        }

        $currentStatus = (string) (
            $payload['current_status']
            ?? $payload['shipment_status']
            ?? $payload['status']
            ?? $track['current_status']
            ?? data_get($payload, 'sr-status-label')
            ?? data_get($payload, 'scans.0.sr-status-label')
            ?? ''
        );

        if ($statusCode === 0 && $currentStatus !== '') {
            $statusCode = $this->inferStatusCodeFromText($currentStatus);
        }

        return [
            'awb' => $awb,
            'shipment_id' => $shipmentId,
            'channel_order_id' => $channelOrderId,
            'sr_order_id' => $srOrderId,
            'status_code' => $statusCode,
            'current_status' => $currentStatus,
        ];
    }

    protected function mapShiprocketStatusUpdate(int $statusCode, string $currentStatus, string $currentDeliveryStatus): array
    {
        $shiprocketStatus = $this->normalizeShiprocketStatusLabel($currentStatus);
        $deliveryStatus = $this->mapStatusCodeToDeliveryStatus($statusCode, $currentDeliveryStatus);

        if ($deliveryStatus === $currentDeliveryStatus && $currentStatus !== '') {
            $deliveryStatus = $this->mapStatusTextToDeliveryStatus($currentStatus, $currentDeliveryStatus);
        }

        if ($shiprocketStatus === 'unknown' && $statusCode > 0) {
            $shiprocketStatus = 'status_' . $statusCode;
        }

        return [
            'shiprocket_status' => $shiprocketStatus,
            'shiprocket_status_code' => $statusCode,
            'delivery_status' => $deliveryStatus,
        ];
    }

    protected function normalizeShiprocketStatusLabel(string $status): string
    {
        $status = trim($status);
        if ($status === '') {
            return 'unknown';
        }

        return strtolower(str_replace([' ', '-'], '_', $status));
    }

    protected function mapStatusCodeToDeliveryStatus(int $statusCode, string $currentDeliveryStatus): string
    {
        switch ($statusCode) {
            case 7:
                return 'delivered';
            case 8:
                return 'cancelled';
            case 42:
                return 'picked_up';
            case 17:
            case 18:
            case 6:
                return 'on_the_way';
            case 1:
            case 3:
            case 4:
            case 5:
                return 'confirmed';
            default:
                return $currentDeliveryStatus;
        }
    }

    protected function mapStatusTextToDeliveryStatus(string $currentStatus, string $currentDeliveryStatus): string
    {
        $normalized = $this->normalizeShiprocketStatusLabel($currentStatus);

        if (str_contains($normalized, 'delivered')) {
            return 'delivered';
        }

        if (str_contains($normalized, 'cancel') || str_contains($normalized, 'canceled')) {
            return 'cancelled';
        }

        if (str_contains($normalized, 'picked')) {
            return 'picked_up';
        }

        if (
            str_contains($normalized, 'transit')
            || str_contains($normalized, 'shipped')
            || str_contains($normalized, 'out_for_delivery')
            || str_contains($normalized, 'ofd')
        ) {
            return 'on_the_way';
        }

        if (
            str_contains($normalized, 'pickup')
            || str_contains($normalized, 'awb')
            || str_contains($normalized, 'manifest')
            || str_contains($normalized, 'label')
            || str_contains($normalized, 'scheduled')
        ) {
            return 'confirmed';
        }

        return $currentDeliveryStatus;
    }

    protected function inferStatusCodeFromText(string $currentStatus): int
    {
        $normalized = $this->normalizeShiprocketStatusLabel($currentStatus);

        if (str_contains($normalized, 'cancel') || str_contains($normalized, 'canceled')) {
            return 8;
        }

        if (str_contains($normalized, 'delivered')) {
            return 7;
        }

        if (str_contains($normalized, 'picked')) {
            return 42;
        }

        if (
            str_contains($normalized, 'transit')
            || str_contains($normalized, 'shipped')
            || str_contains($normalized, 'out_for_delivery')
        ) {
            return 18;
        }

        if (
            str_contains($normalized, 'pickup')
            || str_contains($normalized, 'awb')
            || str_contains($normalized, 'manifest')
            || str_contains($normalized, 'scheduled')
        ) {
            return 3;
        }

        return 0;
    }

    protected function processShipment(Order $order, PickupAddress $pickupAddress, ShippingBoxSize $boxSize): array
    {
        if (!in_array($order->delivery_status, ['pending', 'confirmed'], true)) {
            throw new \Exception(translate('Shiprocket shipment can only be created for pending or confirmed orders.'));
        }

        if ($order->shipping_method === 'shiprocket' && $order->shiprocket_shipment_id) {
            throw new \Exception(translate('This order already has a Shiprocket shipment.'));
        }

        $payload = $this->buildShiprocketPayload($order, $pickupAddress, $boxSize);
        $deliveryPincode = (string) ($payload['billing_pincode'] ?? '');
        $pickupPincode = $this->shiprocket->resolvePickupPincode($pickupAddress->address_nickname);
        $isCod = ($payload['payment_method'] ?? '') === 'COD';
        $weight = (float) ($payload['weight'] ?? config('services.shiprocket.default_weight', 0.5));

        $createResponse = $this->shiprocket->createAdhocOrder($payload);
        $shipmentId = (int) ($createResponse['shipment_id'] ?? 0);

        if (!$shipmentId) {
            throw new \Exception('Shiprocket order created but shipment_id was missing.');
        }

        $serviceability = $this->shiprocket->checkServiceability(
            $pickupPincode,
            $deliveryPincode,
            $isCod,
            $weight,
            [
                'length' => $payload['length'] ?? null,
                'breadth' => $payload['breadth'] ?? null,
                'height' => $payload['height'] ?? null,
            ]
        );

        $courierId = $this->shiprocket->getBestCourierId($serviceability);
        $awbResponse = $this->shiprocket->assignAwb($shipmentId, $courierId);
        $awbCode = $this->shiprocket->extractAwbCode($awbResponse);

        $shiprocketStatus = strtolower(str_replace(' ', '_', $createResponse['status'] ?? 'new'));
        $shiprocketStatusCode = (int) ($createResponse['status_code'] ?? 1);

        if (!$this->shiprocket->isMockMode()) {
            try {
                $tracking = $this->shiprocket->trackShipment($shipmentId, $order->delivery_status);
                $mapped = $this->mapTrackingToOrderStatus($tracking, $order->delivery_status);
                $shiprocketStatus = $mapped['shiprocket_status'];
                $shiprocketStatusCode = $mapped['shiprocket_status_code'];
            } catch (\Exception $e) {
                Log::warning('Shiprocket tracking sync after shipment create failed', [
                    'order_id' => $order->id,
                    'shipment_id' => $shipmentId,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        DB::table('orders')->where('id', $order->id)->update([
            'shipping_method' => 'shiprocket',
            'pickup_address_id' => $pickupAddress->id,
            'shiprocket_order_id' => $createResponse['order_id'] ?? null,
            'shiprocket_shipment_id' => $shipmentId,
            'shiprocket_status' => $shiprocketStatus,
            'shiprocket_status_code' => $shiprocketStatusCode,
            'tracking_code' => $awbCode,
        ]);

        $message = $this->shiprocket->isMockMode()
            ? translate('Mock Shiprocket shipment created successfully. No real order was placed.')
            : translate('Shiprocket shipment created successfully.');

        return [
            'message' => $message,
            'mock' => $this->shiprocket->isMockMode(),
            'payload' => $payload,
            'create_response' => $createResponse,
            'awb_response' => $awbResponse,
        ];
    }

    protected function resolveDefaultPickupAddress(): ?PickupAddress
    {
        $query = PickupAddress::where('courier_type', 'shiprocket')->where('status', 1);

        $primary = (clone $query)->where('is_primary', 1)->first();
        if ($primary) {
            return $primary;
        }

        $pickup = $query->orderBy('id')->first();
        if ($pickup) {
            return $pickup;
        }

        if ($this->shiprocket->isMockMode()) {
            return $this->mockPickupAddress();
        }

        return null;
    }

    protected function resolveDefaultBoxSize(): ?ShippingBoxSize
    {
        $boxSize = ShippingBoxSize::where('courier_type', 'shiprocket')
            ->orderBy('id')
            ->first();

        if ($boxSize) {
            return $boxSize;
        }

        if ($this->shiprocket->isMockMode()) {
            return $this->mockBoxSize();
        }

        return null;
    }

    protected function resolvePickupAddress(Request $request): PickupAddress
    {
        if ($request->pickup_address_id) {
            return PickupAddress::where('id', $request->pickup_address_id)
                ->where('courier_type', 'shiprocket')
                ->firstOrFail();
        }

        return new PickupAddress([
            'courier_type' => 'shiprocket',
            'address_nickname' => $request->pickup_location,
            'status' => 1,
        ]);
    }

    protected function resolveBoxSize(Request $request): ShippingBoxSize
    {
        if ($request->shipping_box_size_id) {
            return ShippingBoxSize::where('id', $request->shipping_box_size_id)
                ->where('courier_type', 'shiprocket')
                ->firstOrFail();
        }

        return new ShippingBoxSize([
            'courier_type' => 'shiprocket',
            'length' => (float) ($request->length ?? config('services.shiprocket.default_length', 10)),
            'breadth' => (float) ($request->breadth ?? config('services.shiprocket.default_breadth', 10)),
            'height' => (float) ($request->height ?? config('services.shiprocket.default_height', 10)),
        ]);
    }

    protected function mockPickupAddress(): PickupAddress
    {
        return new PickupAddress([
            'courier_type' => 'shiprocket',
            'address_nickname' => 'mock-warehouse',
            'is_primary' => 1,
            'status' => 1,
        ]);
    }

    protected function mockBoxSize(): ShippingBoxSize
    {
        return new ShippingBoxSize([
            'courier_type' => 'shiprocket',
            'length' => 10,
            'breadth' => 10,
            'height' => 10,
        ]);
    }

    protected function buildShiprocketPayload(Order $order, PickupAddress $pickupAddress, ShippingBoxSize $boxSize): array
    {
        $billing = json_decode($order->billing_address, true) ?? [];
        $shipping = json_decode($order->shipping_address, true) ?? [];
        $shippingIsBilling = empty($shipping) || $billing === $shipping;

        $billingName = $this->splitName((string) ($billing['name'] ?? 'Customer'));
        $shippingName = $this->splitName((string) ($shipping['name'] ?? $billing['name'] ?? 'Customer'));

        $orderItems = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('product_stocks', function ($join) {
                $join->on('product_stocks.product_id', '=', 'order_details.product_id')
                    ->where('product_stocks.variant', '=', '');
            })
            ->where('order_details.order_id', $order->id)
            ->select(
                'order_details.*',
                'products.name as product_name',
                'product_stocks.sku as product_sku'
            )
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'sku' => $item->product_sku ?: ('SKU' . $item->id),
                    'units' => (int) $item->quantity,
                    'selling_price' => (float) $item->price,
                ];
            })
            ->values()
            ->all();

        if (empty($orderItems)) {
            throw new \Exception('Order has no items to ship.');
        }

        $paymentMethod = $order->payment_type === 'cash_on_delivery' ? 'COD' : 'Prepaid';
        $shiprocketOrderId = $order->code ?: ('ORD-' . $order->id);
        $weight = (float) config('services.shiprocket.default_weight', 0.5);

        $payload = [
            'order_id' => (string) $shiprocketOrderId,
            'order_date' => $order->created_at->format('Y-m-d H:i'),
            'pickup_location' => $pickupAddress->address_nickname,
            'billing_customer_name' => $billingName['first'],
            'billing_last_name' => $billingName['last'],
            'billing_address' => (string) ($billing['address'] ?? ''),
            'billing_city' => (string) ($billing['city'] ?? ''),
            'billing_pincode' => (string) ($billing['postal_code'] ?? ''),
            'billing_state' => (string) ($billing['state'] ?? ''),
            'billing_country' => (string) ($billing['country'] ?? 'India'),
            'billing_email' => (string) ($billing['email'] ?? ''),
            'billing_phone' => (string) ($billing['phone'] ?? ''),
            'shipping_is_billing' => $shippingIsBilling,
            'order_items' => $orderItems,
            'payment_method' => $paymentMethod,
            'sub_total' => (float) $order->grand_total,
            'length' => (float) $boxSize->length,
            'breadth' => (float) $boxSize->breadth,
            'height' => (float) $boxSize->height,
            'weight' => $weight,
        ];

        if (!$shippingIsBilling) {
            $payload['shipping_customer_name'] = $shippingName['first'];
            $payload['shipping_last_name'] = $shippingName['last'];
            $payload['shipping_address'] = (string) ($shipping['address'] ?? '');
            $payload['shipping_city'] = (string) ($shipping['city'] ?? '');
            $payload['shipping_pincode'] = (string) ($shipping['postal_code'] ?? '');
            $payload['shipping_state'] = (string) ($shipping['state'] ?? '');
            $payload['shipping_country'] = (string) ($shipping['country'] ?? 'India');
            $payload['shipping_email'] = (string) ($shipping['email'] ?? $billing['email'] ?? '');
            $payload['shipping_phone'] = (string) ($shipping['phone'] ?? '');
        }

        return $payload;
    }

    protected function mapTrackingToOrderStatus(array $tracking, string $currentDeliveryStatus): array
    {
        $trackingData = $tracking['tracking_data'] ?? [];
        $shipmentStatus = (int) ($trackingData['shipment_status'] ?? 0);
        $currentStatus = (string) ($trackingData['shipment_track'][0]['current_status'] ?? '');

        return $this->mapShiprocketStatusUpdate($shipmentStatus, $currentStatus, $currentDeliveryStatus);
    }

    protected function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return [
            'first' => $parts[0] ?? $fullName,
            'last' => $parts[1] ?? '',
        ];
    }

    protected function overWriteEnvFile($type, $val)
    {
        if (env('DEMO_MODE') != 'On') {
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = '"' . trim($val) . '"';
                if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                    file_put_contents($path, str_replace(
                        $type . '="' . env($type) . '"',
                        $type . '=' . $val,
                        file_get_contents($path)
                    ));
                } else {
                    file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
                }
            }
        }
    }

    protected function resolveOrderForTracking(
        int $shipmentId,
        string $awbCode,
        $shiprocketOrderId,
        string $channelOrderId = ''
    ): ?Order {
        if ($shipmentId > 0) {
            $order = Order::where('shiprocket_shipment_id', $shipmentId)->first();
            if ($order) {
                return $order;
            }
        }

        if ($awbCode !== '') {
            $order = Order::where('tracking_code', $awbCode)->first();
            if ($order) {
                return $order;
            }
        }

        if ($channelOrderId !== '') {
            $order = Order::where('code', $channelOrderId)->first();
            if ($order) {
                return $order;
            }
        }

        if (!empty($shiprocketOrderId)) {
            $order = Order::where('shiprocket_order_id', $shiprocketOrderId)->first();
            if ($order) {
                return $order;
            }
        }

        return null;
    }
}
