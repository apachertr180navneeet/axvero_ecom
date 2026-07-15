<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class OrderController extends Controller
{
    protected function adminUser(Request $request): User
    {
        $bearer = $request->bearerToken();
        if (!$bearer) {
            abort(
                401,
                json_encode([
                    "result" => false,
                    "message" => translate("Unauthenticated"),
                ]),
            );
        }
        $user = PersonalAccessToken::findToken($bearer)?->tokenable;
        if (
            !$user ||
            !($user instanceof User) ||
            $user->user_type !== "admin"
        ) {
            abort(
                403,
                json_encode([
                    "result" => false,
                    "message" => translate("Forbidden"),
                ]),
            );
        }
        return $user;
    }

    public function index(Request $request)
    {
        $this->adminUser($request);
        $query = Order::with("orderDetails", "user");

        if ($request->delivery_status) {
            $query->where("delivery_status", $request->delivery_status);
        }

        if ($request->payment_status) {
            $query->where("payment_status", $request->payment_status);
        }

        if ($request->type === "inhouse") {
            $adminId = User::where("user_type", "admin")->first()->id;
            $query->where("seller_id", $adminId);
        } elseif ($request->type === "seller") {
            $query->where(
                "seller_id",
                "!=",
                User::where("user_type", "admin")->first()->id,
            );
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    "code",
                    "like",
                    "%" . $request->search . "%",
                )->orWhereHas("user", function ($u) use ($request) {
                    $u->where("name", "like", "%" . $request->search . "%");
                });
            });
        }

        $orders = $query
            ->orderBy("created_at", "desc")
            ->paginate($request->per_page ?? 20);

        return response()->json(["result" => true, "data" => $orders]);
    }

    public function show(Request $request, $id)
    {
        $this->adminUser($request);
        $order = Order::with("orderDetails.product", "user")->find($id);
        if (!$order) {
            return response()->json(
                ["result" => false, "message" => translate("Order not found")],
                404,
            );
        }

        return response()->json(["result" => true, "data" => $order]);
    }

    public function updateDeliveryStatus(Request $request, $id)
    {
        $this->adminUser($request);
        $request->validate(["delivery_status" => "required|string"]);

        $order = Order::find($id);
        if (!$order) {
            return response()->json(
                ["result" => false, "message" => translate("Order not found")],
                404,
            );
        }
        $order->delivery_status = $request->delivery_status;
        $order->save();

        return response()->json([
            "result" => true,
            "message" => translate("Delivery status updated successfully"),
        ]);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $this->adminUser($request);
        $request->validate(["payment_status" => "required|string"]);

        $order = Order::find($id);
        if (!$order) {
            return response()->json(
                ["result" => false, "message" => translate("Order not found")],
                404,
            );
        }
        $order->payment_status = $request->payment_status;
        $order->save();

        return response()->json([
            "result" => true,
            "message" => translate("Payment status updated successfully"),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->adminUser($request);
        $order = Order::find($id);
        if (!$order) {
            return response()->json(
                ["result" => false, "message" => translate("Order not found")],
                404,
            );
        }
        $order->delete();

        return response()->json([
            "result" => true,
            "message" => translate("Order deleted successfully"),
        ]);
    }

    public function createShipment(Request $request, $id)
    {
        $this->adminUser($request);
        $order = Order::find($id);
        if (!$order) {
            return response()->json(
                ["result" => false, "message" => translate("Order not found")],
                404,
            );
        }
        if (
            !in_array($order->delivery_status, ["pending", "confirmed"], true)
        ) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate(
                        "Shipment can only be created for pending or confirmed orders",
                    ),
                ],
                422,
            );
        }
        if (
            $order->shipping_method === "shiprocket" &&
            $order->shiprocket_shipment_id
        ) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate(
                        "This order already has a Shiprocket shipment",
                    ),
                ],
                422,
            );
        }

        $request->validate([
            "pickup_location" => "nullable|string|max:255",
            "length" => "nullable|numeric|min:0.1",
            "breadth" => "nullable|numeric|min:0.1",
            "height" => "nullable|numeric|min:0.1",
        ]);

        try {
            $shiprocket = app(ShiprocketService::class);

            $billing = json_decode($order->billing_address, true) ?? [];
            $shipping = json_decode($order->shipping_address, true) ?? [];
            $shippingIsBilling = empty($shipping) || $billing === $shipping;

            $billingName = $billing["name"] ?? $order->user->name ?? "Customer";
            $billingNameParts = explode(" ", $billingName, 2);

            $orderItems = DB::table("order_details")
                ->join("products", "products.id", "=", "order_details.product_id")
                ->leftJoin("product_stocks", function ($join) {
                    $join->on("product_stocks.product_id", "=", "order_details.product_id")
                        ->where("product_stocks.variant", "=", "");
                })
                ->where("order_details.order_id", $order->id)
                ->select(
                    "order_details.*",
                    "products.name as product_name",
                    "product_stocks.sku as product_sku",
                )
                ->get()
                ->map(function ($item) {
                    return [
                        "name" => $item->product_name,
                        "sku" => $item->product_sku ?: ("SKU" . $item->id),
                        "units" => (int) $item->quantity,
                        "selling_price" => (float) $item->price,
                    ];
                })
                ->values()
                ->all();

            if (empty($orderItems)) {
                return response()->json(
                    ["result" => false, "message" => translate("Order has no items to ship")],
                    422,
                );
            }

            $paymentMethod = $order->payment_type === "cash_on_delivery" ? "COD" : "Prepaid";
            $pickupLocation = $request->pickup_location ?? "mock-warehouse";
            $length = $request->length ?? (float) config("services.shiprocket.default_length", 10);
            $breadth = $request->breadth ?? (float) config("services.shiprocket.default_breadth", 10);
            $height = $request->height ?? (float) config("services.shiprocket.default_height", 10);
            $weight = (float) config("services.shiprocket.default_weight", 0.5);

            $payload = [
                "order_id" => (string) ($order->code ?: ("ORD-" . $order->id)),
                "order_date" => $order->created_at->format("Y-m-d H:i"),
                "pickup_location" => $pickupLocation,
                "billing_customer_name" => $billingNameParts[0] ?? "Customer",
                "billing_last_name" => $billingNameParts[1] ?? "",
                "billing_address" => (string) ($billing["address"] ?? $order->shipping_address ?? ""),
                "billing_city" => (string) ($billing["city"] ?? ""),
                "billing_pincode" => (string) ($billing["postal_code"] ?? ""),
                "billing_state" => (string) ($billing["state"] ?? ""),
                "billing_country" => (string) ($billing["country"] ?? "India"),
                "billing_email" => (string) ($billing["email"] ?? $order->user->email ?? ""),
                "billing_phone" => (string) ($billing["phone"] ?? $order->user->phone ?? ""),
                "shipping_is_billing" => $shippingIsBilling,
                "order_items" => $orderItems,
                "payment_method" => $paymentMethod,
                "sub_total" => (float) $order->grand_total,
                "length" => (float) $length,
                "breadth" => (float) $breadth,
                "height" => (float) $height,
                "weight" => $weight,
            ];

            if (!$shippingIsBilling) {
                $shippingName = $shipping["name"] ?? $billing["name"] ?? "Customer";
                $shippingNameParts = explode(" ", $shippingName, 2);
                $payload["shipping_customer_name"] = $shippingNameParts[0] ?? "Customer";
                $payload["shipping_last_name"] = $shippingNameParts[1] ?? "";
                $payload["shipping_address"] = (string) ($shipping["address"] ?? "");
                $payload["shipping_city"] = (string) ($shipping["city"] ?? "");
                $payload["shipping_pincode"] = (string) ($shipping["postal_code"] ?? "");
                $payload["shipping_state"] = (string) ($shipping["state"] ?? "");
                $payload["shipping_country"] = (string) ($shipping["country"] ?? "India");
                $payload["shipping_email"] = (string) ($shipping["email"] ?? $payload["billing_email"]);
                $payload["shipping_phone"] = (string) ($shipping["phone"] ?? "");
            }

            // Step 1: Create adhoc order in Shiprocket
            $createResponse = $shiprocket->createAdhocOrder($payload);
            $shipmentId = (int) ($createResponse["shipment_id"] ?? 0);
            $shiprocketOrderId = $createResponse["order_id"] ?? null;

            if (!$shipmentId) {
                return response()->json(
                    ["result" => false, "message" => translate("Failed to create shipment in Shiprocket")],
                    500,
                );
            }

            // Step 2: Check courier serviceability
            $deliveryPincode = $shippingIsBilling
                ? ($billing["postal_code"] ?? "110001")
                : ($shipping["postal_code"] ?? "110001");
            $isCod = $paymentMethod === "COD";

            $serviceability = $shiprocket->checkServiceability(
                $shiprocket->isMockMode()
                    ? config("services.shiprocket.mock_pickup_pincode", "110001")
                    : "110001",
                $deliveryPincode,
                $isCod,
                $weight,
                ["length" => $length, "breadth" => $breadth, "height" => $height],
            );

            // Step 3: Get best courier and assign AWB
            $courierId = $shiprocket->getBestCourierId($serviceability);
            $awbResponse = $shiprocket->assignAwb($shipmentId, $courierId);
            $awbCode = $shiprocket->extractAwbCode($awbResponse);

            // Step 4: Request pickup
            $pickupResponse = $shiprocket->requestPickup($shipmentId);

            $shiprocketStatus = strtolower(str_replace(" ", "_", $createResponse["status"] ?? "new"));
            $shiprocketStatusCode = (int) ($createResponse["status_code"] ?? 1);

            DB::table("orders")
                ->where("id", $order->id)
                ->update([
                    "shipping_method" => "shiprocket",
                    "shiprocket_order_id" => $shiprocketOrderId,
                    "shiprocket_shipment_id" => $shipmentId,
                    "shiprocket_status" => $shiprocketStatus,
                    "shiprocket_status_code" => $shiprocketStatusCode,
                    "tracking_code" => $awbCode,
                ]);

            return response()->json([
                "result" => true,
                "message" => $shiprocket->isMockMode()
                    ? translate("Mock shipment created successfully")
                    : translate("Shipment created successfully"),
                "data" => [
                    "shiprocket_order_id" => $shiprocketOrderId,
                    "shiprocket_shipment_id" => $shipmentId,
                    "tracking_code" => $awbCode,
                    "status" => $shiprocketStatus,
                    "status_code" => $shiprocketStatusCode,
                    "pickup_status" => $pickupResponse["pickup_status"] ?? null,
                    "is_mock" => $shiprocket->isMockMode(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(
                ["result" => false, "message" => $e->getMessage()],
                500,
            );
        }
    }

    public function trackShipment(Request $request, $id)
    {
        $this->adminUser($request);
        $order = Order::find($id);
        if (!$order) {
            return response()->json(
                ["result" => false, "message" => translate("Order not found")],
                404,
            );
        }
        if ($order->shipping_method !== "shiprocket") {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate("Not a Shiprocket shipment"),
                ],
                422,
            );
        }
        if (!$order->shiprocket_shipment_id && !$order->tracking_code) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate("Shipment not created yet"),
                ],
                422,
            );
        }

        try {
            $shiprocket = app(ShiprocketService::class);
            $tracking = $order->shiprocket_shipment_id
                ? $shiprocket->trackShipment(
                    (int) $order->shiprocket_shipment_id,
                    $order->delivery_status,
                )
                : $shiprocket->trackByAwb((string) $order->tracking_code);

            $trackingData = $tracking["tracking_data"] ?? [];
            $shipmentStatus = (int) ($trackingData["shipment_status"] ?? 0);
            $currentStatus =
                (string) ($trackingData["shipment_track"][0][
                    "current_status"
                ] ?? "unknown");
            $mappedStatus = $tracking["mapped_delivery_status"] ?? null;

            $statusMap = [
                7 => "delivered",
                8 => "cancelled",
                42 => "picked_up",
                17 => "on_the_way",
                18 => "on_the_way",
                6 => "on_the_way",
            ];
            $deliveryStatus =
                $mappedStatus ??
                ($statusMap[$shipmentStatus] ?? $order->delivery_status);

            DB::table("orders")
                ->where("id", $order->id)
                ->update([
                    "shiprocket_status" => strtolower(
                        str_replace(" ", "_", $currentStatus),
                    ),
                    "shiprocket_status_code" => $shipmentStatus,
                    "delivery_status" => $deliveryStatus,
                ]);

            return response()->json([
                "result" => true,
                "message" => translate("Tracking fetched"),
                "data" => [
                    "tracking_code" => $order->tracking_code,
                    "shiprocket_status" => strtolower(
                        str_replace(" ", "_", $currentStatus),
                    ),
                    "shiprocket_status_code" => $shipmentStatus,
                    "delivery_status" => $deliveryStatus,
                    "current_status" => $currentStatus,
                    "raw_tracking" => $tracking,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(
                ["result" => false, "message" => $e->getMessage()],
                500,
            );
        }
    }

    public function pickupLocations(Request $request)
    {
        $this->adminUser($request);
        try {
            $shiprocket = app(ShiprocketService::class);
            $locations = $shiprocket->getPickupLocations();
            return response()->json([
                "result" => true,
                "data" => $locations["data"]["shipping_address"] ?? [],
            ]);
        } catch (\Exception $e) {
            return response()->json(
                ["result" => false, "message" => $e->getMessage()],
                500,
            );
        }
    }
}
