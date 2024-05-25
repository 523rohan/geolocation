<!DOCTYPE html>
<html>
<head>
    <title>Geolocation Data</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Geolocation Data</h1>
    <form method="POST" action="/geolocation">
        @csrf
        {{-- <label for="ip_address">Enter IP Address:</label> --}}
        <label for="ip_address">Enter IP Address (or leave blank to use default IP(Belgaum)):</label>

        <input type="text" id="ip_address" name="ip_address" required>
        <button type="submit">Get Location</button>
    </form>

    @if(isset($ip_address))
    <p><strong>IP Address:</strong> {{ $ip_address }}</p>
    <p><strong>Country:</strong> {{ $country }}</p>
    <p><strong>Region:</strong> {{ $region }}</p>
    <p><strong>City:</strong> {{ $city }}</p>
    <p><strong>Latitude:</strong> {{ $latitude }}</p>
    <p><strong>Longitude:</strong> {{ $longitude }}</p>

    <div id="map"></div>

    <script>
        var map = L.map('map').setView([{{ $latitude }}, {{ $longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        L.marker([{{ $latitude }}, {{ $longitude }}]).addTo(map)
            .bindPopup('<b>Location:</b> {{ $city }}, {{ $region }}, {{ $country }}')
            .openPopup();
    </script>
    @endif
</body>
</html>
