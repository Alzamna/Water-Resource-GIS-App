<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Konten</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-lg w-full">
        <h1 class="text-2xl font-bold text-blue-700 mb-6 text-center">Manajemen Konten</h1>
        <form method="post" action="/cms/konten" class="space-y-4">
            <textarea name="konten" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required><?= esc($konten) ?></textarea>
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">Simpan Konten</button>
        </form>
        <a href="/" class="block mt-6 text-center bg-gray-200 text-gray-700 py-2 rounded hover:bg-gray-300 transition">Kembali ke Dashboard</a>
    </div>
</body>
</html> 