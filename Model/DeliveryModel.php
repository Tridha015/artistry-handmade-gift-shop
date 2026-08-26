<?php
class DeliveryModel
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function allStatuses(): array
    {
        return ['Ready for Delivery', 'Picked Up', 'Out for Delivery', 'Delivered', 'Failed'];
    }


    public function getNextStatuses(string $currentStatus): array
    {
        $transitions = [
            'Ready for Delivery' => ['Picked Up'],
            'Picked Up'          => ['Out for Delivery', 'Failed'],
            'Out for Delivery'   => ['Delivered', 'Failed'],
            'Failed'             => ['Picked Up'],
            'Delivered'          => [], // final state, no further changes
        ];

        return $transitions[$currentStatus] ?? [];
    }

    public function allZones(): array
    {
        return ['Dhanmondi', 'Gulshan', 'Mirpur', 'Uttara', 'Mohammadpur', 'Motijheel', 'Banani'];
    }


    public function getAssignedParcels(int $riderId): array
    {
        $sql = "SELECT
                    d.delivery_id,
                    d.order_id,
                    u.name  AS customer_name,
                    u.phone AS customer_phone,
                    o.address,
                    d.scheduled_date,
                    d.status
                FROM deliveries d
                INNER JOIN orders o ON d.order_id = o.order_id
                INNER JOIN users  u ON o.customer_id = u.user_id
                WHERE d.rider_id = :rider_id
                  AND d.status != 'Delivered'
                ORDER BY d.scheduled_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['rider_id' => $riderId]);

        return $stmt->fetchAll();
    }
    public function getDeliveryById(int $deliveryId): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM deliveries WHERE delivery_id = :id");
        $stmt->execute(['id' => $deliveryId]);

        return $stmt->fetch();
    }
    public function updateDeliveryStatus(int $deliveryId, string $newStatus, int $riderId): bool
    {
        $delivery = $this->getDeliveryById($deliveryId);

        if (!$delivery) {
            return false; // delivery doesn't exist
        }

        if ((int) $delivery['rider_id'] !== $riderId) {
            return false;
        }

        $allowedNext = $this->getNextStatuses($delivery['status']);
        if (!in_array($newStatus, $allowedNext, true)) {
            return false; 
        }

        $stmt = $this->db->prepare(
            "UPDATE deliveries
             SET status = :status, updated_at = NOW()
             WHERE delivery_id = :id AND rider_id = :rider_id"
        );

        return $stmt->execute([
            'status'    => $newStatus,
            'id'        => $deliveryId,
            'rider_id'  => $riderId,
        ]);
    }

    public function getRiderProfile(int $riderId): array|false
    {
        $sql = "SELECT
                    r.rider_id,
                    u.name,
                    u.phone,
                    r.vehicle_type,
                    r.vehicle_number,
                    r.zone,
                    r.duty_status
                FROM riders r
                INNER JOIN users u ON r.user_id = u.user_id
                WHERE r.rider_id = :rider_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['rider_id' => $riderId]);

        return $stmt->fetch();
    }


    public function updateDutyStatus(int $riderId, string $dutyStatus): bool
    {
        if (!in_array($dutyStatus, ['On Duty', 'Off Duty'], true)) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE riders SET duty_status = :status WHERE rider_id = :id"
        );

        return $stmt->execute([
            'status' => $dutyStatus,
            'id'     => $riderId,
        ]);
    }


    public function updateZone(int $riderId, string $zone): bool
    {
        if (!in_array($zone, $this->allZones(), true)) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE riders SET zone = :zone WHERE rider_id = :id"
        );

        return $stmt->execute([
            'zone' => $zone,
            'id'   => $riderId,
        ]);
    }
}
