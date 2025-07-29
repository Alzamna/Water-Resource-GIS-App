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
                <p class="text-slate-600">Kelola dan ekspor data lokasi infrastruktur sumber daya air</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('admin/maps') ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-map mr-2"></i>
                    Lihat Peta
                </a>
                <a href="<?= base_url('admin/maps/add') ?>" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Lokasi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari lokasi..." 
                       class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            </div>
            
            <!-- Type Filter -->
            <select id="typeFilter" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="">Semua Jenis</option>
                <option value="deep-well">Sumur Pompa Dalam</option>
                <option value="reservoir">Sumur Reservoir</option>
                <option value="drainage">Saluran Pembuang</option>
                <option value="irrigation">Jaringan Irigasi</option>
                <option value="other">Lainnya</option>
            </select>
            
            <!-- Status Filter -->
            <select id="statusFilter" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="maintenance">Maintenance</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
            
            <button onclick="resetFilters()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors">
                <i class="fas fa-undo mr-2"></i>
                Reset
            </button>
        </div>
        
        <!-- Export Buttons -->
        <div class="flex items-center space-x-2">
            <button onclick="exportData('excel')" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                <i class="fas fa-file-excel mr-2"></i>
                Export Excel
            </button>
            <button onclick="exportData('pdf')" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                <i class="fas fa-file-pdf mr-2"></i>
                Export PDF
            </button>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-2xl shadow-xl border border-white/50 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-table mr-2 text-sky-600"></i>
                Data Lokasi
            </h2>
            <div class="text-sm text-slate-500">
                Total: <span id="totalCount">0</span> lokasi
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Lokasi</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Koordinat</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal Dibuat</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="locationTableBody" class="bg-white divide-y divide-slate-200">
                <!-- Data will be loaded here -->
            </tbody>
        </table>
    </div>
    
    <!-- Loading State -->
    <div id="tableLoading" class="p-12 text-center">
        <div class="inline-flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div>
            <span class="text-slate-600">Memuat data...</span>
        </div>
    </div>
    
    <!-- Empty State -->
    <div id="emptyState" class="p-12 text-center hidden">
        <div class="text-slate-400 mb-4">
            <i class="fas fa-inbox text-6xl"></i>
        </div>
        <h3 class="text-lg font-medium text-slate-900 mb-2">Tidak ada data</h3>
        <p class="text-slate-500 mb-4">Belum ada lokasi yang ditambahkan atau tidak ada yang sesuai dengan filter.</p>
        <a href="<?= base_url('admin/maps/add') ?>" class="inline-flex items-center px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Lokasi Pertama
        </a>
    </div>
</div>

<!-- Pagination -->
<div id="pagination" class="mt-6 flex items-center justify-between">
    <div class="text-sm text-slate-700">
        Menampilkan <span id="showingStart">0</span> sampai <span id="showingEnd">0</span> dari <span id="showingTotal">0</span> hasil
    </div>
    <div class="flex items-center space-x-2" id="paginationButtons">
        <!-- Pagination buttons will be generated here -->
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let locations = [];
let filteredLocations = [];
let currentPage = 1;
const itemsPerPage = 10;

// Type and status labels
const typeLabels = {
    'deep-well': 'Sumur Pompa Dalam',
    'reservoir': 'Sumur Reservoir',
    'drainage': 'Saluran Pembuang',
    'irrigation': 'Jaringan Irigasi',
    'other': 'Lainnya'
};

const statusLabels = {
    'active': 'Aktif',
    'maintenance': 'Maintenance',
    'inactive': 'Tidak Aktif'
};

// Load locations from API
async function loadLocations() {
    try {
        showTableLoading(true);
        
        const response = await fetch('<?= base_url('admin/api/locations') ?>');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Locations response:', data);
        
        if (Array.isArray(data)) {
            locations = data;
            filteredLocations = [...locations];
            renderTable();
            showNotification('Data lokasi berhasil dimuat!', 'success');
        } else if (data.success && Array.isArray(data.locations)) {
            locations = data.locations;
            filteredLocations = [...locations];
            renderTable();
            showNotification('Data lokasi berhasil dimuat!', 'success');
        } else {
            throw new Error(data.message || 'Invalid response format');
        }
    } catch (error) {
        console.error('Error loading locations:', error);
        showNotification('Gagal memuat data lokasi: ' + error.message, 'error');
        showEmptyState(true);
    } finally {
        showTableLoading(false);
    }
}

// Filter locations
function filterLocations() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    filteredLocations = locations.filter(location => {
        const matchSearch = !search || 
            location.name.toLowerCase().includes(search) ||
            (location.description && location.description.toLowerCase().includes(search));
        
        const matchType = !typeFilter || location.type === typeFilter;
        const matchStatus = !statusFilter || location.status === statusFilter;
        
        return matchSearch && matchType && matchStatus;
    });
    
    currentPage = 1;
    renderTable();
}

// Reset filters
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('statusFilter').value = '';
    
    filteredLocations = [...locations];
    currentPage = 1;
    renderTable();
    
    showNotification('Filter berhasil direset!', 'info');
}

// Render table
function renderTable() {
    const tbody = document.getElementById('locationTableBody');
    const totalCount = document.getElementById('totalCount');
    
    // Update total count
    totalCount.textContent = filteredLocations.length;
    
    // Check if empty
    if (filteredLocations.length === 0) {
        showEmptyState(true);
        return;
    } else {
        showEmptyState(false);
    }
    
    // Calculate pagination
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, filteredLocations.length);
    const pageData = filteredLocations.slice(startIndex, endIndex);
    
    // Render rows
    tbody.innerHTML = pageData.map((location, index) => {
        const globalIndex = startIndex + index + 1;
        return `
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">${globalIndex}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="resource-type-icon type-${location.type} mr-3">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-900">${location.name}</div>
                            ${location.description ? `<div class="text-sm text-slate-500 truncate max-w-xs">${location.description}</div>` : ''}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                    ${typeLabels[location.type] || location.type}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                    <div class="flex items-center">
                        <i class="fas fa-map-pin mr-2 text-slate-400"></i>
                        <span>${parseFloat(location.latitude).toFixed(4)}, ${parseFloat(location.longitude).toFixed(4)}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="status-badge status-${location.status}">
                        ${statusLabels[location.status] || location.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                    ${formatDate(location.created_at)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <a href="<?= base_url('admin/maps/edit/') ?>${location.id}" 
                           class="text-sky-600 hover:text-sky-900 transition-colors">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteLocation(${location.id})" 
                                class="text-red-600 hover:text-red-900 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                        ${location.photo ? `
                        <button onclick="viewPhoto('${location.photo}')" 
                                class="text-green-600 hover:text-green-900 transition-colors">
                            <i class="fas fa-image"></i>
                        </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    // Update pagination
    renderPagination();
}

// Render pagination
function renderPagination() {
    const totalPages = Math.ceil(filteredLocations.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage + 1;
    const endIndex = Math.min(currentPage * itemsPerPage, filteredLocations.length);
    
    // Update showing info
    document.getElementById('showingStart').textContent = startIndex;
    document.getElementById('showingEnd').textContent = endIndex;
    document.getElementById('showingTotal').textContent = filteredLocations.length;
    
    // Generate pagination buttons
    const paginationButtons = document.getElementById('paginationButtons');
    let buttonsHTML = '';
    
    // Previous button
    if (currentPage > 1) {
        buttonsHTML += `
            <button onclick="changePage(${currentPage - 1})" 
                    class="px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
    }
    
    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPage;
        buttonsHTML += `
            <button onclick="changePage(${i})" 
                    class="px-3 py-2 text-sm ${isActive ? 'bg-sky-500 text-white' : 'bg-white text-slate-700 hover:bg-slate-50'} border border-slate-300 rounded-lg transition-colors">
                ${i}
            </button>
        `;
    }
    
    // Next button
    if (currentPage < totalPages) {
        buttonsHTML += `
            <button onclick="changePage(${currentPage + 1})" 
                    class="px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
    }
    
    paginationButtons.innerHTML = buttonsHTML;
}

// Change page
function changePage(page) {
    currentPage = page;
    renderTable();
}

// Delete location
async function deleteLocation(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus lokasi ini?')) {
        return;
    }
    
    try {
        const response = await fetch(`<?= base_url('admin/api/locations/') ?>${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            showNotification('Lokasi berhasil dihapus!', 'success');
            loadLocations(); // Reload data
        } else {
            throw new Error(data.message || 'Gagal menghapus lokasi');
        }
    } catch (error) {
        console.error('Error deleting location:', error);
        showNotification('Gagal menghapus lokasi: ' + error.message, 'error');
    }
}

// View photo
function viewPhoto(photoPath) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-4 max-w-2xl max-h-full overflow-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Foto Lokasi</h3>
                <button onclick="this.closest('.fixed').remove()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <img src="<?= base_url('admin/photo/') ?>${photoPath}" alt="Foto lokasi" class="max-w-full h-auto rounded-lg">
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Export data
function exportData(format) {
    const search = document.getElementById('searchInput').value;
    const type = document.getElementById('typeFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    const params = new URLSearchParams({
        format: format,
        search: search,
        type: type,
        status: status
    });
    
    const url = `<?= base_url('admin/maps/export') ?>?${params.toString()}`;
    window.open(url, '_blank');
    
    showNotification(`Export ${format.toUpperCase()} dimulai!`, 'info');
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Show/hide loading
function showTableLoading(show) {
    const loading = document.getElementById('tableLoading');
    const tbody = document.getElementById('locationTableBody');
    
    if (show) {
        loading.classList.remove('hidden');
        tbody.innerHTML = '';
    } else {
        loading.classList.add('hidden');
    }
}

// Show/hide empty state
function showEmptyState(show) {
    const emptyState = document.getElementById('emptyState');
    const tbody = document.getElementById('locationTableBody');
    const pagination = document.getElementById('pagination');
    
    if (show) {
        emptyState.classList.remove('hidden');
        tbody.innerHTML = '';
        pagination.classList.add('hidden');
    } else {
        emptyState.classList.add('hidden');
        pagination.classList.remove('hidden');
    }
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full`;
    
    if (type === 'success') {
        notification.classList.add('bg-green-500');
        notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
    } else if (type === 'error') {
        notification.classList.add('bg-red-500');
        notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
    } else if (type === 'warning') {
        notification.classList.add('bg-yellow-500');
        notification.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
    } else {
        notification.classList.add('bg-blue-500');
        notification.innerHTML = `<i class="fas fa-info-circle mr-2"></i>${message}`;
    }

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Animate out and remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadLocations();
    
    // Add event listeners for filters
    document.getElementById('searchInput').addEventListener('input', filterLocations);
    document.getElementById('typeFilter').addEventListener('change', filterLocations);
    document.getElementById('statusFilter').addEventListener('change', filterLocations);
    
    console.log('Location list initialized successfully');
});
</script>

<style>
.resource-type-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.type-deep-well { background: #3b82f6; }
.type-reservoir { background: #10b981; }
.type-drainage { background: #f59e0b; }
.type-irrigation { background: #8b5cf6; }
.type-other { background: #6b7280; }

.status-badge {
    padding: 4px 8px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 500;
}

.status-active { background: #dcfce7; color: #166534; }
.status-maintenance { background: #fef3c7; color: #92400e; }
.status-inactive { background: #fee2e2; color: #991b1b; }

#tableLoading.hidden,
#emptyState.hidden,
#pagination.hidden {
    display: none;
}
</style>
<?= $this->endSection() ?>
