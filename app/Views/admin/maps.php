<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-sky-700 to-sky-600 bg-clip-text text-transparent mb-2">
                    <i class="fas fa-map-marked-alt mr-2 text-sky-600"></i>
                    Peta Sumber Daya Air
                </h1>
                <p class="text-slate-600">Visualisasi lokasi infrastruktur sumber daya air</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('admin/maps/list') ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-list mr-2"></i>
                    Daftar Lokasi
                </a>
                <a href="<?= base_url('admin/maps/add') ?>" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-lg hover:from-sky-600 hover:to-sky-700 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Lokasi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-lg p-4 border border-white/50">
        <div class="flex items-center">
            <div class="resource-type-icon type-deep-well mr-3">
                <i class="fas fa-tint"></i>
            </div>
            <div>
                <p class="text-xs text-slate-600">Sumur Dalam</p>
                <p class="text-lg font-bold text-slate-800" id="count-deep-well">0</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-4 border border-white/50">
        <div class="flex items-center">
            <div class="resource-type-icon type-reservoir mr-3">
                <i class="fas fa-database"></i>
            </div>
            <div>
                <p class="text-xs text-slate-600">Reservoir</p>
                <p class="text-lg font-bold text-slate-800" id="count-reservoir">0</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-4 border border-white/50">
        <div class="flex items-center">
            <div class="resource-type-icon type-drainage mr-3">
                <i class="fas fa-stream"></i>
            </div>
            <div>
                <p class="text-xs text-slate-600">Saluran Pembuang</p>
                <p class="text-lg font-bold text-slate-800" id="count-drainage">0</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-4 border border-white/50">
        <div class="flex items-center">
            <div class="resource-type-icon type-irrigation mr-3">
                <i class="fas fa-seedling"></i>
            </div>
            <div>
                <p class="text-xs text-slate-600">Jaringan Irigasi</p>
                <p class="text-lg font-bold text-slate-800" id="count-irrigation">0</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-4 border border-white/50">
        <div class="flex items-center">
            <div class="resource-type-icon type-other mr-3">
                <i class="fas fa-ellipsis-h"></i>
            </div>
            <div>
                <p class="text-xs text-slate-600">Lainnya</p>
                <p class="text-lg font-bold text-slate-800" id="count-other">0</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-4 border border-white/50">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-check text-white text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-slate-600">Total Aktif</p>
                <p class="text-lg font-bold text-slate-800" id="count-active">0</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Panel -->
<div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-filter mr-2 text-sky-600"></i>
            Filter Peta
        </h2>
        <button onclick="clearAllFilters()" class="text-sm text-slate-500 hover:text-slate-700">
            <i class="fas fa-times mr-1"></i>
            Reset Filter
        </button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Cari Lokasi</label>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Nama lokasi..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
            </div>
        </div>

        <!-- Type Filter -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Jenis Infrastruktur</label>
            <select id="typeFilter" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="">Semua Jenis</option>
                <option value="deep-well">Sumur Pompa Dalam</option>
                <option value="reservoir">Sumur Reservoir</option>
                <option value="drainage">Saluran Pembuang</option>
                <option value="irrigation">Jaringan Irigasi</option>
                <option value="other">Lainnya</option>
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Status Lokasi</label>
            <select id="statusFilter" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="maintenance">Maintenance</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
    </div>

    <!-- Filter Chips -->
    <div>
        <p class="text-sm font-medium text-slate-700 mb-2">Filter Cepat:</p>
        <div class="flex flex-wrap gap-2">
            <button onclick="quickFilter('all')" class="filter-chip active px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm hover:bg-slate-200 transition-colors">
                Semua
            </button>
            <button onclick="quickFilter('deep-well')" class="filter-chip px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition-colors">
                <i class="fas fa-tint mr-1"></i>
                Sumur Dalam
            </button>
            <button onclick="quickFilter('reservoir')" class="filter-chip px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm hover:bg-green-200 transition-colors">
                <i class="fas fa-database mr-1"></i>
                Reservoir
            </button>
            <button onclick="quickFilter('drainage')" class="filter-chip px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm hover:bg-yellow-200 transition-colors">
                <i class="fas fa-stream mr-1"></i>
                Saluran Pembuang
            </button>
            <button onclick="quickFilter('irrigation')" class="filter-chip px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm hover:bg-purple-200 transition-colors">
                <i class="fas fa-seedling mr-1"></i>
                Irigasi
            </button>
            <button onclick="quickFilter('active')" class="filter-chip px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm hover:bg-green-200 transition-colors">
                <i class="fas fa-check mr-1"></i>
                Aktif
            </button>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-globe mr-2 text-sky-600"></i>
            Peta Interaktif
        </h2>
        <div class="text-sm text-slate-500">
            Total Lokasi: <span id="totalLocations" class="font-semibold">0</span>
        </div>
    </div>
    <div id="map" style="height: calc(100vh - 200px); border-radius: 12px;"></div>
    
    <!-- Map Controls -->
    <div class="mt-4 flex justify-between items-center">
        <div class="flex space-x-2">
            <button onclick="centerMap()" class="px-3 py-1 bg-slate-100 text-slate-700 rounded text-sm hover:bg-slate-200 transition-colors">
                <i class="fas fa-crosshairs mr-1"></i>
                Reset View
            </button>
            <button onclick="toggleFullscreen()" class="px-3 py-1 bg-slate-100 text-slate-700 rounded text-sm hover:bg-slate-200 transition-colors">
                <i class="fas fa-expand mr-1"></i>
                Fullscreen
            </button>
        </div>
        <div class="text-xs text-slate-500">
            <i class="fas fa-info-circle mr-1"></i>
            Klik marker untuk detail lokasi
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let map;
    let markers = {};
    let locations = [];

    // Initialize map
    function initMap() {
        map = L.map('map').setView([-6.2088, 106.8456], 10);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        loadLocations();
    }

    // Get icon for resource type
    function getResourceIcon(type) {
        const icons = {
            'deep-well': { icon: 'tint', color: '#3b82f6' },
            'reservoir': { icon: 'database', color: '#10b981' },
            'drainage': { icon: 'stream', color: '#f59e0b' },
            'irrigation': { icon: 'seedling', color: '#8b5cf6' },
            'other': { icon: 'ellipsis-h', color: '#6b7280' }
        };
        
        const iconData = icons[type] || icons['other'];
        
        return L.divIcon({
            html: `<div style="background-color: ${iconData.color}; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="fas fa-${iconData.icon}" style="color: white; font-size: 12px;"></i>
                   </div>`,
            className: 'custom-div-icon',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
    }

    // Load locations from server
    async function loadLocations() {
        try {
            const response = await fetch('<?= base_url('admin/maps/get-locations') ?>');
            locations = await response.json();
            
            displayLocations();
            updateStatistics();
        } catch (error) {
            console.error('Error loading locations:', error);
        }
    }

    // Display locations on map
    function displayLocations() {
        // Clear existing markers
        Object.values(markers).forEach(marker => map.removeLayer(marker));
        markers = {};

        // Filter locations based on current filters
        const filteredLocations = getFilteredLocations();

        // Add markers to map
        filteredLocations.forEach(location => {
            const marker = L.marker([location.latitude, location.longitude], {
                icon: getResourceIcon(location.type)
            }).addTo(map);

            const statusColor = location.status === 'active' ? 'green' : location.status === 'maintenance' ? 'yellow' : 'red';
            const statusText = location.status === 'active' ? 'Aktif' : location.status === 'maintenance' ? 'Maintenance' : 'Tidak Aktif';

            marker.bindPopup(`
                <div class="p-3 min-w-[280px]">
                    <h3 class="font-bold text-lg mb-2 text-slate-800">${location.name}</h3>
                    <p class="text-sm text-slate-600 mb-2">${location.description || 'Tidak ada deskripsi'}</p>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2 py-1 bg-${statusColor}-100 text-${statusColor}-800 rounded text-xs font-medium">
                            ${statusText}
                        </span>
                        <span class="text-xs text-slate-500">${getTypeLabel(location.type)}</span>
                    </div>
                    <div class="text-xs text-slate-500 mb-3">
                        <i class="fas fa-map-pin mr-1"></i>
                        ${location.latitude}, ${location.longitude}
                    </div>
                    <div class="flex space-x-2">
                        <a href="<?= base_url('admin/maps/edit') ?>/${location.id}" class="flex-1 px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors text-center">
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </a>
                        <button onclick="deleteLocation(${location.id})" class="flex-1 px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 transition-colors">
                            <i class="fas fa-trash mr-1"></i>
                            Hapus
                        </button>
                    </div>
                </div>
            `);

            markers[location.id] = marker;
        });

        // Update total locations counter
        document.getElementById('totalLocations').textContent = filteredLocations.length;
    }

    // Get filtered locations based on current filters
    function getFilteredLocations() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const typeFilter = document.getElementById('typeFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;

        return locations.filter(location => {
            const matchesSearch = location.name.toLowerCase().includes(searchTerm) || 
                                (location.description && location.description.toLowerCase().includes(searchTerm));
            const matchesType = !typeFilter || location.type === typeFilter;
            const matchesStatus = !statusFilter || location.status === statusFilter;
            return matchesSearch && matchesType && matchesStatus;
        });
    }

    // Update statistics
    function updateStatistics() {
        const counts = {
            'deep-well': 0,
            'reservoir': 0,
            'drainage': 0,
            'irrigation': 0,
            'other': 0,
            'active': 0
        };

        locations.forEach(location => {
            counts[location.type] = (counts[location.type] || 0) + 1;
            if (location.status === 'active') {
                counts['active']++;
            }
        });

        Object.keys(counts).forEach(type => {
            const element = document.getElementById(`count-${type}`);
            if (element) {
                element.textContent = counts[type];
            }
        });
    }

    // Helper functions
    function getTypeLabel(type) {
        const labels = {
            'deep-well': 'Sumur Pompa Dalam',
            'reservoir': 'Sumur Reservoir',
            'drainage': 'Saluran Pembuang',
            'irrigation': 'Jaringan Irigasi',
            'other': 'Lainnya'
        };
        return labels[type] || 'Lainnya';
    }

    // Delete location
    async function deleteLocation(id) {
        if (confirm('Apakah Anda yakin ingin menghapus lokasi ini?')) {
            try {
                const response = await fetch(`<?= base_url('admin/maps/delete-location') ?>/${id}`, {
                    method: 'DELETE'
                });
                
                if (response.ok) {
                    loadLocations();
                    showNotification('Lokasi berhasil dihapus', 'success');
                } else {
                    showNotification('Gagal menghapus lokasi', 'error');
                }
            } catch (error) {
                console.error('Error deleting location:', error);
                showNotification('Terjadi kesalahan saat menghapus lokasi', 'error');
            }
        }
    }

    // Filter functions
    function quickFilter(type) {
        // Update filter chips
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.classList.remove('active');
        });
        event.target.classList.add('active');

        // Apply filter
        if (type === 'all') {
            document.getElementById('typeFilter').value = '';
            document.getElementById('statusFilter').value = '';
        } else if (type === 'active') {
            document.getElementById('statusFilter').value = 'active';
            document.getElementById('typeFilter').value = '';
        } else {
            document.getElementById('typeFilter').value = type;
            document.getElementById('statusFilter').value = '';
        }

        applyFilters();
    }

    function clearAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('typeFilter').value = '';
        document.getElementById('statusFilter').value = '';
        
        // Reset filter chips
        document.querySelectorAll('.filter-chip').forEach(chip => {
            chip.classList.remove('active');
        });
        document.querySelector('.filter-chip').classList.add('active');

        applyFilters();
    }

    function applyFilters() {
        displayLocations();
    }

    // Map controls
    function centerMap() {
        map.setView([-6.2088, 106.8456], 10);
    }

    function toggleFullscreen() {
        const mapContainer = document.getElementById('map');
        if (!document.fullscreenElement) {
            mapContainer.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }

    // Search and filter events
    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('typeFilter').addEventListener('change', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });
</script>
<?= $this->endSection() ?>
