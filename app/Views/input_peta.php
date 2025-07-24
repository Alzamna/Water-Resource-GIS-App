<!DOCTYPE html>
<html>
<head>
    <title>Input Koordinat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 400px; margin-top: 20px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6 mt-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Masukkan Latitude dan Longitude</h2>
        <form method="post" action="<?= base_url('/peta') ?>" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Latitude:</label>
                <input type="text" name="latitude" value="<?= old('latitude', $latitude ?? '') ?>" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Longitude:</label>
                <input type="text" name="longitude" value="<?= old('longitude', $longitude ?? '') ?>" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Tampilkan di Peta</button>
        </form>
        <?php if (!empty($latitude) && !empty($longitude)) : ?>
            <div id="map" class="mt-6 rounded shadow"></div>
        <?php endif; ?>
    </div>
    <?php if (!empty($latitude) && !empty($longitude)) : ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([<?= $latitude ?>, <?= $longitude ?>], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([<?= $latitude ?>, <?= $longitude ?>])
            .addTo(map)
            .bindPopup("Lokasi yang Anda masukkan.")
            .openPopup();
    </script>
    <?php endif; ?>
</body>
</html>
