<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="mb-6">
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-sky-700 to-sky-600 bg-clip-text text-transparent mb-2">
                    <i class="fas fa-list mr-2 text-sky-600"></i>
                    Daftar Lokasi Sumber Daya Air
                </h1>
                <p class="text-slate-600">Kelola semua lokasi infrastruktur dalam bentuk tabel</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('admin/maps') ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-map mr-2"></i>
                    Lihat Peta
                </a>
                <button onclick="exportData('excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Excel
                </button>
                <button onclick="exportData('pdf')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export PDF
                </button>
                <a href="<?= base_url('admin/maps/add') ?>" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-lg hover:from-sky-600 hover:to-sky-700 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Lokasi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Cari Lokasi</label>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Nama lokasi..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Jenis</label>
            <select id="typeFilter" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="">Semua Jenis</option>
                <option value="deep-well">Sumur Pompa Dalam</option>
                <option value="reservoir">Sumur Reservoir</option>
                <option value="drainage">Saluran Pembuang</option>
                <option value="irrigation">Jaringan Irigasi</option>
                <option value="other">Lainnya</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
            <select id="statusFilter" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="maintenance">Maintenance</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Tampilkan</label>
            <select id="perPageSelect" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
                <option value="100">100 per halaman</option>
            </select>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-xl border border-white/50">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-table mr-2 text-sky-600"></i>
                Tabel Lokasi
            </h2>
            <div class="text-sm text-slate-500">
                Total: <span id="totalCount">0</span> lokasi
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-slate-200">
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">
                            <button onclick="sortTable('name')" class="flex items-center hover:text-sky-600">
                                Nama Lokasi
                                <i class="fas fa-sort ml-1 text-xs"></i>
                            </button>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">
                            <button onclick="sortTable('type')" class="flex items-center hover:text-sky-600">
                                Jenis
                                <i class="fas fa-sort ml-1 text-xs"></i>
                            </button>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Koordinat</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">
                            <button onclick="sortTable('status')" class="flex items-center hover:text-sky-600">
                                Status
                                <i class="fas fa-sort ml-1 text-xs"></i>
                            </button>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">
                            <button onclick="sortTable('created_at')" class="flex items-center hover:text-sky-600">
                                Dibuat
                                <i class="fas fa-sort ml-1 text-xs"></i>
                            </button>
                        </th>
                        <th class="text-center py-3 px-4 font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody id="locationTableBody">
                    <!-- Dynamic content will be loaded here -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-200">
            <div class="text-sm text-slate-600">
                Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="showingTotal">0</span> lokasi
            </div>
            <div class="flex space-x-2" id="paginationContainer">
                <!-- Pagination buttons will be generated here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Hapus</h3>
                <p class="text-slate-600 mb-6">Apakah Anda yakin ingin menghapus lokasi "<span id="deleteLocationName" class="font-semibold"></span>"?</p>
                
                <div class="flex space-x-3">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button onclick="confirmDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let locations = [];
    let filteredLocations = [];
    let currentPage = 1;
    let perPage = 10;
    let sortField = 'name';
    let sortDirection = 'asc';
    let deleteLocationId = null;

    // Load locations from server
    async function loadLocations() {
        try {
            const response = await fetch('<?= base_url('admin/maps/get-locations') ?>');
            locations = await response.json();
            applyFilters();
        } catch (error) {
            console.error('Error loading locations:', error);
        }
    }

    // Apply filters and search
    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const typeFilter = document.getElementById('typeFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;

        filteredLocations = locations.filter(location => {
            const matchesSearch = location.name.toLowerCase().includes(searchTerm) || 
                                (location.description && location.description.toLowerCase().includes(searchTerm));
            const matchesType = !typeFilter || location.type === typeFilter;
            const matchesStatus = !statusFilter || location.status === statusFilter;
            return matchesSearch && matchesType && matchesStatus;
        });

        sortLocations();
        updateTable();
        updatePagination();
    }

    // Sort locations
    function sortLocations() {
        filteredLocations.sort((a, b) => {
            let aVal = a[sortField];
            let bVal = b[sortField];
            
            if (sortField === 'created_at') {
                aVal = new Date(aVal);
                bVal = new Date(bVal);
            }
            
            if (aVal < bVal) return sortDirection === 'asc' ? -1 : 1;
            if (aVal > bVal) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
    }

    // Sort table
    function sortTable(field) {
        if (sortField === field) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortField = field;
            sortDirection = 'asc';
        }
        applyFilters();
    }

    // Update table
    function updateTable() {
        const tableBody = document.getElementById('locationTableBody');
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const pageLocations = filteredLocations.slice(start, end);

        if (pageLocations.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-500">
                        <i class="fas fa-map-marked-alt text-3xl mb-2"></i>
                        <p>Tidak ada lokasi yang ditemukan</p>
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = pageLocations.map(location => {
            const statusClass = location.status === 'active' ? 'status-active' : 
                              location.status === 'maintenance' ? 'status-maintenance' : 'status-inactive';
            const statusText = location.status === 'active' ? 'Aktif' : 
                             location.status === 'maintenance' ? 'Maintenance' : 'Tidak Aktif';
            
            return `
                <tr class="table-row border-b border-slate-100">
                    <td class="py-4 px-4">
                        <div class="flex items-center space-x-3">
                            <div class="resource-type-icon type-${location.type}">
                                <i class="fas fa-${getIconName(location.type)}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">${location.name}</p>
                                <p class="text-sm text-slate-500">${getTypeLabel(location.type)}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <span class="text-sm text-slate-700">${getTypeLabel(location.type)}</span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm text-slate-600">
                            <div>${location.latitude}</div>
                            <div>${location.longitude}</div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="text-sm text-slate-600">${formatDate(location.created_at)}</span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex space-x-2 justify-center">
                            <a href="<?= base_url('admin/maps') ?>?focus=${location.id}" class="p-2 text-blue-600 hover:bg-blue-100 rounded transition-colors" title="Lihat di Peta">
                                <i class="fas fa-map text-sm"></i>
                            </a>
                            <a href="<?= base_url('admin/maps/edit') ?>/${location.id}" class="p-2 text-green-600 hover:bg-green-100 rounded transition-colors" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="showDeleteModal(${location.id}, '${location.name}')" class="p-2 text-red-600 hover:bg-red-100 rounded transition-colors" title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        // Update counters
        document.getElementById('totalCount').textContent = filteredLocations.length;
        document.getElementById('showingStart').textContent = start + 1;
        document.getElementById('showingEnd').textContent = Math.min(end, filteredLocations.length);
        document.getElementById('showingTotal').textContent = filteredLocations.length;
    }

    // Update pagination
    function updatePagination() {
        const totalPages = Math.ceil(filteredLocations.length / perPage);
        const paginationContainer = document.getElementById('paginationContainer');
        
        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let paginationHTML = '';
        
        // Previous button
        if (currentPage > 1) {
            paginationHTML += `<button onclick="changePage(${currentPage - 1})" class="px-3 py-1 border border-slate-300 rounded hover:bg-slate-50">
                <i class="fas fa-chevron-left"></i>
            </button>`;
        }

        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        if (startPage > 1) {
            paginationHTML += `<button onclick="changePage(1)" class="px-3 py-1 border border-slate-300 rounded hover:bg-slate-50">1</button>`;
            if (startPage > 2) {
                paginationHTML += `<span class="px-3 py-1">...</span>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === currentPage;
            paginationHTML += `<button onclick="changePage(${i})" class="px-3 py-1 border ${isActive ? 'bg-sky-600 text-white border-sky-600' : 'border-slate-300 hover:bg-slate-50'} rounded">${i}</button>`;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHTML += `<span class="px-3 py-1">...</span>`;
            }
            paginationHTML += `<button onclick="changePage(${totalPages})" class="px-3 py-1 border border-slate-300 rounded hover:bg-slate-50">${totalPages}</button>`;
        }

        // Next button
        if (currentPage < totalPages) {
            paginationHTML += `<button onclick="changePage(${currentPage + 1})" class="px-3 py-1 border border-slate-300 rounded hover:bg-slate-50">
                <i class="fas fa-chevron-right"></i>
            </button>`;
        }

        paginationContainer.innerHTML = paginationHTML;
    }

    // Change page
    function changePage(page) {
        currentPage = page;
        updateTable();
        updatePagination();
    }

    // Helper functions
    function getIconName(type) {
        const icons = {
            'deep-well': 'tint',
            'reservoir': 'database',
            'drainage': 'stream',
            'irrigation': 'seedling',
            'other': 'ellipsis-h'
        };
        return icons[type] || 'ellipsis-h';
    }

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

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Delete functions
    function showDeleteModal(id, name) {
        deleteLocationId = id;
        document.getElementById('deleteLocationName').textContent = name;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
        deleteLocationId = null;
    }

    async function confirmDelete() {
        if (deleteLocationId) {
            try {
                const response = await fetch(`<?= base_url('admin/maps/delete-location') ?>/${deleteLocationId}`, {
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
            closeDeleteModal();
        }
    }

    // Export functions
    function exportData(format) {
        const params = new URLSearchParams({
            format: format,
            search: document.getElementById('searchInput').value,
            type: document.getElementById('typeFilter').value,
            status: document.getElementById('statusFilter').value
        });
        
        window.open(`<?= base_url('admin/maps/export') ?>?${params.toString()}`, '_blank');
    }

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('typeFilter').addEventListener('change', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);
    document.getElementById('perPageSelect').addEventListener('change', function() {
        perPage = parseInt(this.value);
        currentPage = 1;
        applyFilters();
    });

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadLocations();
    });
</script>
<?= $this->endSection() ?>
