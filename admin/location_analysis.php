<div id="map" style="height: 500px;"></div>
<script>
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 13.0827, lng: 80.2707 }, // Default to Chennai
        zoom: 10
    });

    <?php
    $sql = "SELECT latitude, longitude FROM complaints WHERE status='Pending'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "new google.maps.Marker({
            position: { lat: {$row['latitude']}, lng: {$row['longitude']} },
            map: map
        });";
    }
    ?>
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>

