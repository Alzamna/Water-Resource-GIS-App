<!DOCTYPE html>
<html>
<head>
    <title>Peta Interaktif</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 500px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-6 mt-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-green-700">Peta Leaflet di CodeIgniter 4</h2>
        <div id="map" class="rounded shadow"></div>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-7.797068, 110.370529], 13); // Contoh: Yogyakarta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([-7.797068, 110.370529])
            .addTo(map)
            .bindPopup("<b>Yogyakarta</b><br>Kota Istimewa.")
            .openPopup();
    </script>
</body>
</html>
