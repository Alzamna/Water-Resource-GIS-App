<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lokasi - GIS Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        .ocean-gradient {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
        }
        
        .animated-bg {
            background: linear-gradient(-45deg, #e0f2fe, #b3e5fc, #81d4fa, #4fc3f7);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-item:hover {
            transform: translateX(8px);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-right: 4px solid #ffffff;
        }
        
        .main-content {
            margin-left: 280px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
        
        #map {
            height: 300px;
            border-radius: 12px;
            cursor: crosshair;
        }
        
        .drag-drop-area {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .drag-drop-area.dragover {
            border-color: #0ea5e9;
            background-color: #f0f9ff;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body class="animated-bg min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed left-0 top-0 h-full w-70 ocean-gradient shadow-2xl z-50">
        <div class="p-6">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <i class="fas fa-tachometer-alt text-2xl text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Dashboard CMS</h1>
                <p class="text-sky-100 text-sm">GIS Admin Portal</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-y-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg">
                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="<?= base_url('admin/konten') ?>" class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg">
                    <i class="fas fa-file-text w-5 h-5 mr-3"></i>
                    <span>Manajemen Konten</span>
                </a>
                
                <a href="<?= base_url('admin/users') ?>" class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span>Manajemen User</span>
                </a>
                
                <a href="<?= base_url('admin/maps') ?>" class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg">
                    <i class="fas fa-map-marked-alt w-5 h-5 mr-3"></i>
                    <span>Peta Lokasi</span>
                </a>
                
                <a href="<?= base_url('admin/maps/list') ?>" class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg">
                    <i class="fas fa-list w-5 h-5 mr-3"></i>
                    <span>Daftar Lokasi</span>
                </a>
                
                <a href="<?= base_url('admin/settings') ?>" class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg">
                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>

            <!-- User Section -->
            <div class="absolute bottom-6 left-6 right-6">
                <div class="bg-white/10 rounded-lg p-4 mb-4">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">Admin User</p>
                            <p class="text-sky-100 text-xs">Administrator</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="<?= base_url('admin/profile') ?>" class="flex-1 bg-white/10 hover:bg-white/20 text-white py-2 px-3 rounded text-center text-xs transition-all duration-200">
                            <i class="fas fa-user-circle mr-1"></i>
                            Profil
                        </a>
                        <a href="<?= base_url('logout') ?>" class="flex-1 bg-red-500/80 hover:bg-red-600 text-white py-2 px-3 rounded text-center text-xs transition-all duration-200">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="md:hidden fixed top-4 left-4 z-60 bg-sky-600 text-white p-3 rounded-lg shadow-lg">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content -->
    <div class="main-content min-h-screen p-6">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="mb-6">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-sky-700 to-sky-600 bg-clip-text text-transparent mb-2">
                                <i class="fas fa-edit mr-2 text-sky-600"></i>
                                Edit Lokasi: <?= esc($location['name']) ?>
                            </h1>
                            <p class="text-slate-600">Perbarui informasi lokasi infrastruktur sumber daya air</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="<?= base_url('admin/maps') ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Peta
                            </a>
                            <a href="<?= base_url('admin/maps/list') ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-list mr-2"></i>
                                Daftar Lokasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form and Map Container -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Form Section -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
                    <div class="flex items-center mb-6">
                        <h2 class="text-xl font-bold text-slate-800">
                            <i class="fas fa-edit mr-2 text-sky-600"></i>
                            Form Edit Lokasi
                        </h2>
                    </div>
                    
                    <form action="<?= base_url('admin/maps/edit/' . $location['id']) ?>" method="POST" enctype="multipart/form-data" id="locationForm">
                        <?= csrf_field() ?>
                        
                        <div class="space-y-4">
                            <!-- Nama Lokasi -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-tag mr-1 text-sky-600"></i>
                                    Nama Lokasi *
                                </label>
                                <input type="text" name="name" id="locationName" required 
                                       value="<?= old('name', $location['name']) ?>"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                       placeholder="Masukkan nama lokasi">
                            </div>
                            
                            <!-- Jenis Infrastruktur -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-layer-group mr-1 text-sky-600"></i>
                                    Jenis Infrastruktur *
                                </label>
                                <select name="type" id="locationType" required 
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                                    <option value="">Pilih Jenis</option>
                                    <option value="deep-well" <?= old('type', $location['type']) === 'deep-well' ? 'selected' : '' ?>>Sumur Pompa Dalam</option>
                                    <option value="reservoir" <?= old('type', $location['type']) === 'reservoir' ? 'selected' : '' ?>>Sumur Reservoir</option>
                                    <option value="drainage" <?= old('type', $location['type']) === 'drainage' ? 'selected' : '' ?>>Saluran Pembuang</option>
                                    <option value="irrigation" <?= old('type', $location['type']) === 'irrigation' ? 'selected' : '' ?>>Jaringan Irigasi</option>
                                    <option value="other" <?= old('type', $location['type']) === 'other' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>
                            
                            <!-- Koordinat -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                        <i class="fas fa-map-pin mr-1 text-sky-600"></i>
                                        Latitude *
                                    </label>
                                    <input type="number" name="latitude" id="locationLat" step="any" required 
                                           value="<?= old('latitude', $location['latitude']) ?>"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                           placeholder="-6.2088">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                        <i class="fas fa-map-pin mr-1 text-sky-600"></i>
                                        Longitude *
                                    </label>
                                    <input type="number" name="longitude" id="locationLng" step="any" required 
                                           value="<?= old('longitude', $location['longitude']) ?>"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                           placeholder="106.8456">
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-info-circle mr-1 text-sky-600"></i>
                                    Status *
                                </label>
                                <select name="status" id="locationStatus" required 
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                                    <option value="active" <?= old('status', $location['status']) === 'active' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="maintenance" <?= old('status', $location['status']) === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                                    <option value="inactive" <?= old('status', $location['status']) === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                            </div>
                            
                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-align-left mr-1 text-sky-600"></i>
                                    Deskripsi
                                </label>
                                <textarea name="description" id="locationDescription" rows="4" 
                                          class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent" 
                                          placeholder="Deskripsi detail lokasi..."><?= old('description', $location['description']) ?></textarea>
                            </div>
                            
                            <!-- Upload Foto -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    <i class="fas fa-image mr-1 text-sky-600"></i>
                                    Foto Lokasi (Opsional)
                                </label>
                                
                                <?php if (!empty($location['photo'])): ?>
                                <div class="mb-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-slate-700">Foto Saat Ini:</span>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="remove_photo" value="1" class="mr-2">
                                            <span class="text-sm text-red-600">Hapus foto</span>
                                        </label>
                                    </div>
                                    <img src="<?= base_url('admin/maps/photo/' . $location['photo']) ?>" 
                                         alt="Current photo" 
                                         class="w-full max-w-xs h-32 object-cover rounded-lg">
                                </div>
                                <?php endif; ?>
                                
                                <div class="drag-drop-area" id="dragDropArea">
                                    <input type="file" name="photo" id="locationPhoto" accept="image/*" class="hidden">
                                    <div id="dropText">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-2"></i>
                                        <p class="text-slate-600">Drag & drop foto atau <span class="text-sky-600 cursor-pointer" onclick="document.getElementById('locationPhoto').click()">klik untuk pilih</span></p>
                                        <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, GIF (Max: 2MB)</p>
                                    </div>
                                    <div id="imagePreview" class="hidden">
                                        <img id="previewImg" class="image-preview mx-auto mb-2">
                                        <button type="button" onclick="removeImage()" class="text-red-600 text-sm hover:text-red-800">
                                            <i class="fas fa-trash mr-1"></i>
                                            Hapus Foto Baru
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3 mt-6 pt-4 border-t border-slate-200">
                            <a href="<?= base_url('admin/maps') ?>" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors text-center">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-lg hover:from-sky-600 hover:to-sky-700 transition-all duration-200">
                                <i class="fas fa-save mr-2"></i>
                                Perbarui Lokasi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Map and Info Section -->
                <div class="space-y-6">
                    <!-- Map Section -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-slate-800">
                                <i class="fas fa-map mr-2 text-sky-600"></i>
                                Lokasi di Peta
                            </h2>
                            <div class="text-sm text-slate-500">
                                <i class="fas fa-mouse-pointer mr-1"></i>
                                Klik untuk mengubah koordinat
                            </div>
                        </div>
                        
                        <div id="map" style="height: 300px; border-radius: 12px;"></div>
                        
                        <!-- Map Tips -->
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h3 class="text-sm font-semibold text-blue-800 mb-2">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Tips Penggunaan Peta:
                            </h3>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• Klik pada peta untuk mengubah koordinat</li>
                                <li>• Marker merah menunjukkan lokasi saat ini</li>
                                <li>• Gunakan scroll mouse untuk zoom in/out</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Info Panel -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">
                            <i class="fas fa-info-circle mr-2 text-sky-600"></i>
                            Informasi Lokasi
                        </h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm font-medium text-slate-600">ID Lokasi:</span>
                                <span class="text-sm text-slate-800">#<?= $location['id'] ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm font-medium text-slate-600">Dibuat:</span>
                                <span class="text-sm text-slate-800"><?= date('d/m/Y H:i', strtotime($location['created_at'])) ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-sm font-medium text-slate-600">Terakhir Diperbarui:</span>
                                <span class="text-sm text-slate-800"><?= date('d/m/Y H:i', strtotime($location['updated_at'])) ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-slate-600">Status Saat Ini:</span>
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    <?php if ($location['status'] === 'active'): ?>
                                        bg-green-100 text-green-800
                                    <?php elseif ($location['status'] === 'maintenance'): ?>
                                        bg-yellow-100 text-yellow-800
                                    <?php else: ?>
                                        bg-red-100 text-red-800
                                    <?php endif; ?>">
                                    <?php
                                    $statusLabels = [
                                        'active' => 'Aktif',
                                        'maintenance' => 'Maintenance',
                                        'inactive' => 'Tidak Aktif'
                                    ];
                                    echo $statusLabels[$location['status']] ?? $location['status'];
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let map;
        let currentMarker = null;

        // Initialize map
        function initMap() {
            const lat = <?= $location['latitude'] ?>;
            const lng = <?= $location['longitude'] ?>;
            
            map = L.map('map').setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add initial marker
            currentMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: '<div style="background-color: #ef4444; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-map-pin" style="color: white; font-size: 12px;"></i></div>',
                    className: 'custom-div-icon',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map);

            // Add click event to map for updating location
            map.on('click', function(e) {
                const newLat = e.latlng.lat.toFixed(6);
                const newLng = e.latlng.lng.toFixed(6);
                
                // Update form inputs
                document.getElementById('locationLat').value = newLat;
                document.getElementById('locationLng').value = newLng;
                
                // Remove existing marker
                if (currentMarker) {
                    map.removeLayer(currentMarker);
                }
                
                // Add new marker
                currentMarker = L.marker([newLat, newLng], {
                    icon: L.divIcon({
                        html: '<div style="background-color: #ef4444; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-map-pin" style="color: white; font-size: 12px;"></i></div>',
                        className: 'custom-div-icon',
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    })
                }).addTo(map);
                
                showNotification('Koordinat berhasil diperbarui dari peta!', 'success');
            });
        }

        // Update marker when coordinates change
        function updateMarkerFromInput() {
            const lat = parseFloat(document.getElementById('locationLat').value);
            const lng = parseFloat(document.getElementById('locationLng').value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                // Remove existing marker
                if (currentMarker) {
                    map.removeLayer(currentMarker);
                }
                
                // Add new marker
                currentMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        html: '<div style="background-color: #ef4444; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-map-pin" style="color: white; font-size: 12px;"></i></div>',
                        className: 'custom-div-icon',
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    })
                }).addTo(map);
                
                map.setView([lat, lng], 15);
            }
        }

        // Image upload handling
        function setupImageUpload() {
            const dragDropArea = document.getElementById('dragDropArea');
            const fileInput = document.getElementById('locationPhoto');

            // Drag and drop events
            dragDropArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                dragDropArea.classList.add('dragover');
            });

            dragDropArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dragDropArea.classList.remove('dragover');
            });

            dragDropArea.addEventListener('drop', function(e) {
                e.preventDefault();
                dragDropArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleImagePreview(files[0]);
                }
            });

            // File input change
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    handleImagePreview(e.target.files[0]);
                }
            });
        }

        function handleImagePreview(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('dropText').classList.add('hidden');
                    document.getElementById('imagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('locationPhoto').value = '';
            document.getElementById('dropText').classList.remove('hidden');
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('previewImg').src = '';
        }

        // Add event listeners for coordinate inputs
        document.getElementById('locationLat').addEventListener('input', updateMarkerFromInput);
        document.getElementById('locationLng').addEventListener('input', updateMarkerFromInput);

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            setupImageUpload();
        });
    </script>
</body>
</html>
