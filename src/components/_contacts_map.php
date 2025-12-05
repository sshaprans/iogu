<?php
// src/components/contacts_map.php
$apiKey = '';
$zoom = 15;
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT key_name, value_uk FROM settings WHERE key_name IN ('google_maps_key', 'map_zoom')");
    $sets = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    $apiKey = $sets['google_maps_key'] ?? '';
    $zoom = $sets['map_zoom'] ?? 15;
} catch (Exception $e) {}

if (!$apiKey) return;
?>

<div id="map" style="width: 100%; height: 400px; border-radius: 8px; margin-top: 30px;"></div>

<script>
    function initMap() {
        const center = { lat: 50.4501, lng: 30.5234 };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: <?= (int)$zoom ?>,
            center: center,
        });

        new google.maps.Marker({
            position: center,
            map: map,
            title: "Головний офіс"
        });
    }
</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=<?= htmlspecialchars($apiKey) ?>&callback=initMap&v=weekly"
    defer
></script>