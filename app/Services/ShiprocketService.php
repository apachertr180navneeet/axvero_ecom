<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ShiprocketService
{
    protected $baseUrl;
    protected $email;
    protected $password;
    protected $defaultWeight;
    protected $mockPickupPincode;

    public function __construct()
    {
        $this->baseUrl = config('services.shiprocket.base_url');
        $this->email = config('services.shiprocket.email');
        $this->password = config('services.shiprocket.password');
        $this->defaultWeight = (float) config('services.shiprocket.default_weight', 0.5);
        $this->mockPickupPincode = (string) config('services.shiprocket.mock_pickup_pincode', '110001');
    }

    public function isMockMode(): bool
    {
        return filter_var(config('services.shiprocket.mock_mode'), FILTER_VALIDATE_BOOLEAN);
    }

    public function ensureConfigured(): void
    {
        if (!$this->isMockMode() && (empty($this->email) || empty($this->password))) {
            throw new \Exception(
                'Shiprocket credentials not configured. Enable mock mode for local testing or add client API keys.'
            );
        }
    }

    public function getToken(): string
    {
        $this->ensureConfigured();

        if ($this->isMockMode()) {
            return 'mock-shiprocket-token';
        }

        return Cache::remember('shiprocket_token', now()->addMinutes(55), function () {
            $response = Http::post($this->baseUrl . '/auth/login', [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Shiprocket authentication failed: ' . $response->body());
            }

            $token = $response->json()['token'] ?? null;
            if (!$token) {
                throw new \Exception('Shiprocket authentication failed: token missing in response.');
            }

            return $token;
        });
    }

    public function getPickupLocations(): array
    {
        $this->ensureConfigured();

        if ($this->isMockMode()) {
            return $this->mockPickupLocationsResponse();
        }

        return $this->authenticatedGet('/settings/company/pickup');
    }

    public function resolvePickupPincode(string $pickupLocationNickname): string
    {
        if ($this->isMockMode()) {
            return $this->mockPickupPincode;
        }

        $locations = $this->getPickupLocations();
        $addresses = $locations['data']['shipping_address'] ?? [];

        foreach ($addresses as $address) {
            if (($address['pickup_location'] ?? '') === $pickupLocationNickname) {
                return (string) ($address['pin_code'] ?? $this->mockPickupPincode);
            }
        }

        throw new \Exception(
            'Pickup location "' . $pickupLocationNickname . '" not found in Shiprocket account.'
        );
    }

    public function createAdhocOrder(array $orderData): array
    {
        $this->ensureConfigured();

        if ($this->isMockMode()) {
            return $this->mockCreateOrderResponse($orderData);
        }

        return $this->authenticatedPost('/orders/create/adhoc', $orderData);
    }

    /** @deprecated Use createAdhocOrder() */
    public function createOrder(array $orderData): array
    {
        return $this->createAdhocOrder($orderData);
    }

    public function checkServiceability(
        string $pickupPostcode,
        string $deliveryPostcode,
        bool $isCod,
        float $weight,
        array $dimensions = []
    ): array {
        $this->ensureConfigured();

        if ($this->isMockMode()) {
            return $this->mockServiceabilityResponse();
        }

        $query = [
            'pickup_postcode' => $pickupPostcode,
            'delivery_postcode' => $deliveryPostcode,
            'cod' => $isCod ? 1 : 0,
            'weight' => $weight,
        ];

        if (!empty($dimensions['length'])) {
            $query['length'] = $dimensions['length'];
        }
        if (!empty($dimensions['breadth'])) {
            $query['breadth'] = $dimensions['breadth'];
        }
        if (!empty($dimensions['height'])) {
            $query['height'] = $dimensions['height'];
        }

        return $this->authenticatedGet('/courier/serviceability/', $query);
    }

    public function getBestCourierId(array $serviceability): int
    {
        $couriers = $serviceability['data']['available_courier_companies'] ?? [];

        if (empty($couriers)) {
            throw new \Exception('No courier available for this route.');
        }

        return (int) ($couriers[0]['courier_company_id'] ?? $couriers[0]['courier_id'] ?? 0);
    }

    public function assignAwb(int $shipmentId, int $courierId): array
    {
        $this->ensureConfigured();

        if ($this->isMockMode()) {
            return $this->mockAssignAwbResponse($shipmentId, $courierId);
        }

        return $this->authenticatedPost('/courier/assign/awb', [
            'shipment_id' => $shipmentId,
            'courier_id' => $courierId,
        ]);
    }

    public function requestPickup(int $shipmentId): array
    {
        $this->ensureConfigured();

        if ($this->isMockMode()) {
            return $this->mockRequestPickupResponse($shipmentId);
        }

        $token = $this->getToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/courier/generate/pickup', [
            'shipment_id' => [$shipmentId],
        ]);

        if ($response->status() === 401) {
            Cache::forget('shiprocket_token');
            $token = $this->getToken();
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/courier/generate/pickup', [
                'shipment_id' => [$shipmentId],
            ]);
        }

        if ($response->successful()) {
            return $response->json();
        }

        $message = (string) (data_get($response->json(), 'message') ?? $response->body());
        if ($this->isPickupAlreadyQueuedMessage($message)) {
            return [
                'pickup_status' => 1,
                'response' => [
                    'pickup_scheduled_date' => null,
                    'shipment_id' => $shipmentId,
                ],
                'already_queued' => true,
                'message' => $message,
            ];
        }

        throw new \Exception('Shiprocket API request failed: ' . $response->body());
    }

    public function trackShipment(int $shipmentId, ?string $currentDeliveryStatus = null): array
    {
        $this->ensureConfigured();

        if ($this->isMockMode()) {
            return $this->mockTrackShipmentResponse($shipmentId, $currentDeliveryStatus);
        }

        return $this->authenticatedGet('/courier/track/shipment/' . $shipmentId);
    }

    public function trackByAwb(string $awbCode): array
    {
        $this->ensureConfigured();

        if ($this->isMockMode()) {
            $shipmentId = preg_replace('/\D+/', '', $awbCode);
            return $this->mockTrackShipmentResponse((int) ($shipmentId ?: 1), 'confirmed');
        }

        return $this->authenticatedGet('/courier/track/awb/' . $awbCode);
    }

    public function extractAwbCode(array $assignAwbResponse): ?string
    {
        return $assignAwbResponse['response']['data']['awb_code']
            ?? $assignAwbResponse['awb_code']
            ?? null;
    }

    protected function isPickupAlreadyQueuedMessage(string $message): bool
    {
        return str_contains(strtolower($message), 'already in pickup queue');
    }

    protected function authenticatedGet(string $uri, array $query = []): array
    {
        $token = $this->getToken();
        $response = Http::withToken($token)->get($this->baseUrl . $uri, $query);

        if ($response->status() === 401) {
            Cache::forget('shiprocket_token');
            $token = $this->getToken();
            $response = Http::withToken($token)->get($this->baseUrl . $uri, $query);
        }

        if (!$response->successful()) {
            throw new \Exception('Shiprocket API request failed: ' . $response->body());
        }

        return $response->json();
    }

    protected function authenticatedPost(string $uri, array $payload = []): array
    {
        $token = $this->getToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . $uri, $payload);

        if ($response->status() === 401) {
            Cache::forget('shiprocket_token');
            $token = $this->getToken();
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . $uri, $payload);
        }

        if (!$response->successful()) {
            throw new \Exception('Shiprocket API request failed: ' . $response->body());
        }

        return $response->json();
    }

    protected function mockPickupLocationsResponse(): array
    {
        return [
            'data' => [
                'shipping_address' => [
                    [
                        'id' => 1,
                        'pickup_location' => 'mock-warehouse',
                        'pin_code' => $this->mockPickupPincode,
                        'address' => 'Mock Pickup Address',
                        'city' => 'New Delhi',
                        'state' => 'Delhi',
                        'country' => 'India',
                        'is_primary_location' => 1,
                    ],
                ],
            ],
        ];
    }

    protected function mockCreateOrderResponse(array $orderData): array
    {
        $localOrderId = (string) ($orderData['order_id'] ?? 'MOCK-ORDER');
        $shipmentId = (int) abs(crc32($localOrderId));

        return [
            'order_id' => 900000000 + $shipmentId,
            'shipment_id' => $shipmentId,
            'status' => 'NEW',
            'status_code' => 1,
            'mock' => true,
        ];
    }

    protected function mockServiceabilityResponse(): array
    {
        return [
            'data' => [
                'available_courier_companies' => [
                    [
                        'courier_company_id' => 10,
                        'courier_name' => 'Mock Courier',
                    ],
                ],
            ],
            'mock' => true,
        ];
    }

    protected function mockAssignAwbResponse(int $shipmentId, int $courierId): array
    {
        $awbCode = 'MOCK' . str_pad((string) $shipmentId, 10, '0', STR_PAD_LEFT);

        return [
            'awb_assign_status' => 1,
            'response' => [
                'data' => [
                    'awb_code' => $awbCode,
                    'courier_company_id' => $courierId,
                    'shipment_id' => $shipmentId,
                ],
            ],
            'mock' => true,
        ];
    }

    protected function mockRequestPickupResponse(int $shipmentId): array
    {
        return [
            'pickup_status' => 1,
            'response' => [
                'pickup_scheduled_date' => now()->addDay()->format('Y-m-d'),
                'shipment_id' => $shipmentId,
            ],
            'mock' => true,
        ];
    }

    protected function mockTrackShipmentResponse(int $shipmentId, ?string $currentDeliveryStatus = null): array
    {
        $progression = [
            'confirmed' => [
                'shipment_status' => 42,
                'current_status' => 'PICKED UP',
                'delivery_status' => 'picked_up',
            ],
            'picked_up' => [
                'shipment_status' => 17,
                'current_status' => 'Out For Delivery',
                'delivery_status' => 'on_the_way',
            ],
            'on_the_way' => [
                'shipment_status' => 7,
                'current_status' => 'Delivered',
                'delivery_status' => 'delivered',
            ],
        ];

        $next = $progression[$currentDeliveryStatus] ?? [
            'shipment_status' => 42,
            'current_status' => 'PICKED UP',
            'delivery_status' => 'picked_up',
        ];

        return [
            'tracking_data' => [
                'track_status' => 1,
                'shipment_status' => $next['shipment_status'],
                'shipment_track' => [
                    [
                        'shipment_id' => $shipmentId,
                        'current_status' => $next['current_status'],
                    ],
                ],
            ],
            'mock' => true,
            'mapped_delivery_status' => $next['delivery_status'],
        ];
    }
}
