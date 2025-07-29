<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-sky-700 to-sky-600 bg-clip-text text-transparent mb-2">
                    <i class="fas fa-tags mr-2 text-sky-600"></i>
                    Manajemen Kategori
                </h1>
                <p class="text-slate-600">Kelola kategori untuk mengorganisir data lokasi</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('admin/categories/add') ?>" 
                   class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Kategori
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600 text-sm mb-1">Total Kategori</p>
                <p class="text-2xl font-bold text-slate-800" id="total-categories">0</p>
            </div>
            <div class="bg-sky-100 p-3 rounded-lg">
                <i class="fas fa-tags text-sky-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600 text-sm mb-1">Kategori Aktif</p>
                <p class="text-2xl font-bold text-green-600" id="active-categories">0</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600 text-sm mb-1">Kategori Tidak Aktif</p>
                <p class="text-2xl font-bold text-red-600" id="inactive-categories">0</p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-600 text-sm mb-1">Kategori Terbaru</p>
                <p class="text-2xl font-bold text-violet-600" id="new-categories">0</p>
            </div>
            <div class="bg-violet-100 p-3 rounded-lg">
                <i class="fas fa-plus-circle text-violet-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       id="search-input" 
                       placeholder="Cari kategori..." 
                       class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 w-full md:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            </div>
            
            <!-- Status Filter -->
            <select id="status-filter" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">Semua Status</option>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>
        
        <div class="flex items-center gap-2">
            <button onclick="refreshData()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <i class="fas fa-refresh mr-2"></i>
                Refresh
            </button>
            <button onclick="exportData()" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-colors">
                <i class="fas fa-download mr-2"></i>
                Export
            </button>
        </div>
    </div>
</div>

<!-- Categories Grid -->
<div class="bg-white rounded-2xl shadow-xl border border-white/50">
    <div class="p-6 border-b border-slate-200">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-list mr-2 text-sky-600"></i>
            Daftar Kategori
        </h2>
    </div>
    
    <div class="p-6">
        <!-- Loading State -->
        <div id="loading-state" class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-4xl text-sky-600 mb-4"></i>
            <p class="text-slate-600">Memuat data kategori...</p>
        </div>
        
        <!-- Categories Grid -->
        <div id="categories-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
            <!-- Categories will be loaded here -->
        </div>
        
        <!-- Empty State -->
        <div id="empty-state" class="text-center py-12 hidden">
            <i class="fas fa-inbox text-6xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-600 mb-2">Tidak ada kategori</h3>
            <p class="text-slate-500 mb-6">Belum ada kategori yang dibuat. Mulai dengan menambahkan kategori pertama.</p>
            <a href="<?= base_url('admin/categories/add') ?>" 
               class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300 inline-block">
                <i class="fas fa-plus mr-2"></i>
                Tambah Kategori Pertama
            </a>
        </div>
        
        <!-- Error State -->
        <div id="error-state" class="text-center py-12 hidden">
            <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-red-600 mb-2">Gagal memuat data</h3>
            <p class="text-red-500 mb-6">Terjadi kesalahan saat memuat data kategori.</p>
            <button onclick="loadCategories()" 
                    class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors">
                <i class="fas fa-refresh mr-2"></i>
                Coba Lagi
            </button>
        </div>
    </div>
    
    <!-- Pagination -->
    <div id="pagination-container" class="px-6 py-4 border-t border-slate-200 hidden">
        <div class="flex items-center justify-between">
            <div class="text-sm text-slate-600">
                Menampilkan <span id="showing-start">0</span> - <span id="showing-end">0</span> dari <span id="total-items">0</span> kategori
            </div>
            <div class="flex space-x-2" id="pagination-buttons">
                <!-- Pagination buttons will be generated here -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <i class="fas fa-trash text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Konfirmasi Hapus</h3>
                    <p class="text-slate-600 text-sm">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            
            <p class="text-slate-700 mb-6">
                Apakah Anda yakin ingin menghapus kategori "<span id="delete-category-name" class="font-semibold"></span>"?
            </p>
            
            <div class="flex space-x-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <button onclick="confirmDelete()" 
                        class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentPage = 1;
let totalPages = 1;
let deleteId = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadStats();
    
    // Search functionality
    document.getElementById('search-input').addEventListener('input', debounce(function() {
        currentPage = 1;
        loadCategories();
    }, 500));
    
    // Status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        currentPage = 1;
        loadCategories();
    });
});

// Load categories
async function loadCategories() {
    showLoading();
    
    try {
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        
        const params = new URLSearchParams({
            page: currentPage,
            search: search,
            status: status
        });
        
        const response = await fetch(`<?= base_url('admin/api/categories') ?>?${params}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Categories response:', data);
        
        if (data.success) {
            displayCategories(data.categories);
            if (data.pagination) {
                updatePagination(data.pagination);
            }
            if (data.statistics) {
                updateStats(data.statistics);
            }
            hideLoading();
        } else {
            throw new Error(data.message || 'Failed to load categories');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        showError();
    }
}

// Load statistics
async function loadStats() {
    try {
        const response = await fetch('<?= base_url('admin/api/categories/stats') ?>');
        
        if (!response.ok) {
            console.warn('Stats endpoint not available, using default values');
            return;
        }
        
        const data = await response.json();
        
        if (data.success && data.stats) {
            updateStats(data.stats);
        }
    } catch (error) {
        console.error('Error loading stats:', error);
        // Use default values if stats fail to load
    }
}

// Update stats display
function updateStats(stats) {
    document.getElementById('total-categories').textContent = stats.total || 0;
    document.getElementById('active-categories').textContent = stats.active || 0;
    document.getElementById('inactive-categories').textContent = stats.inactive || 0;
    document.getElementById('new-categories').textContent = stats.new_this_month || 0;
}

// Display categories
function displayCategories(categories) {
    const grid = document.getElementById('categories-grid');
    
    if (categories.length === 0) {
        showEmpty();
        return;
    }
    
    grid.innerHTML = categories.map(category => `
        <div class="bg-slate-50 rounded-xl p-6 border border-slate-200 hover:shadow-lg transition-all duration-300 hover:scale-105">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" style="background-color: ${category.color}20; color: ${category.color};">
                        <i class="${category.icon} text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">${category.name}</h3>
                        <p class="text-slate-600 text-sm">${category.description || 'Tidak ada deskripsi'}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${category.is_active ? 'Aktif' : 'Tidak Aktif'}
                    </span>
                </div>
            </div>
            
            <div class="flex items-center justify-between text-sm text-slate-500 mb-4">
                <span>Dibuat: ${formatDate(category.created_at)}</span>
                <span>Diperbarui: ${formatDate(category.updated_at)}</span>
            </div>
            
            <div class="flex space-x-2">
                <a href="<?= base_url('admin/categories/edit') ?>/${category.id}" 
                   class="flex-1 bg-sky-100 text-sky-700 px-3 py-2 rounded-lg text-center hover:bg-sky-200 transition-colors text-sm">
                    <i class="fas fa-edit mr-1"></i>
                    Edit
                </a>
                <button onclick="toggleStatus(${category.id}, ${category.is_active})" 
                        class="flex-1 ${category.is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200'} px-3 py-2 rounded-lg transition-colors text-sm">
                    <i class="fas fa-${category.is_active ? 'pause' : 'play'} mr-1"></i>
                    ${category.is_active ? 'Nonaktifkan' : 'Aktifkan'}
                </button>
                <button onclick="showDeleteModal(${category.id}, '${category.name}')" 
                        class="bg-red-100 text-red-700 px-3 py-2 rounded-lg hover:bg-red-200 transition-colors text
                        class="bg-red-100 text-red-700 px-3 py-2 rounded-lg hover:bg-red-200 transition-colors text-sm">
                    <i class="fas fa-trash mr-1"></i>
                    Hapus
                </button>
            </div>
        </div>
    `).join('');
    
    grid.classList.remove('hidden');
}



// Show/hide states
function showLoading() {
    document.getElementById('loading-state').classList.remove('hidden');
    document.getElementById('categories-grid').classList.add('hidden');
    document.getElementById('empty-state').classList.add('hidden');
    document.getElementById('error-state').classList.add('hidden');
    document.getElementById('pagination-container').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('categories-grid').classList.remove('hidden');
    document.getElementById('pagination-container').classList.remove('hidden');
}

function showEmpty() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('categories-grid').classList.add('hidden');
    document.getElementById('empty-state').classList.remove('hidden');
    document.getElementById('error-state').classList.add('hidden');
    document.getElementById('pagination-container').classList.add('hidden');
}

function showError() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('categories-grid').classList.add('hidden');
    document.getElementById('empty-state').classList.add('hidden');
    document.getElementById('error-state').classList.remove('hidden');
    document.getElementById('pagination-container').classList.add('hidden');
}

// Update pagination
function updatePagination(pagination) {
    totalPages = pagination.total_pages;
    currentPage = pagination.current_page;
    
    document.getElementById('showing-start').textContent = pagination.showing_start;
    document.getElementById('showing-end').textContent = pagination.showing_end;
    document.getElementById('total-items').textContent = pagination.total_items;
    
    const buttonsContainer = document.getElementById('pagination-buttons');
    buttonsContainer.innerHTML = '';
    
    // Previous button
    if (currentPage > 1) {
        buttonsContainer.innerHTML += `
            <button onclick="changePage(${currentPage - 1})" 
                    class="px-3 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
    }
    
    // Page numbers
    for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
        buttonsContainer.innerHTML += `
            <button onclick="changePage(${i})" 
                    class="px-3 py-2 ${i === currentPage ? 'bg-sky-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'} rounded-lg transition-colors">
                ${i}
            </button>
        `;
    }
    
    // Next button
    if (currentPage < totalPages) {
        buttonsContainer.innerHTML += `
            <button onclick="changePage(${currentPage + 1})" 
                    class="px-3 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
    }
}

// Change page
function changePage(page) {
    currentPage = page;
    loadCategories();
}

// Toggle status
async function toggleStatus(id, currentStatus) {
    try {
        const response = await fetch(`<?= base_url('admin/api/categories') ?>/${id}/toggle`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            loadCategories();
            loadStats();
        } else {
            showNotification(data.message || 'Gagal mengubah status', 'error');
        }
    } catch (error) {
        console.error('Error toggling status:', error);
        showNotification('Terjadi kesalahan saat mengubah status', 'error');
    }
}

// Delete modal functions
function showDeleteModal(id, name) {
    deleteId = id;
    document.getElementById('delete-category-name').textContent = name;
    document.getElementById('delete-modal').classList.add('show');
}

function closeDeleteModal() {
    deleteId = null;
    document.getElementById('delete-modal').classList.remove('show');
}

// Confirm delete
async function confirmDelete() {
    if (!deleteId) return;
    
    try {
        const response = await fetch(`<?= base_url('admin/api/categories') ?>/${deleteId}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            closeDeleteModal();
            loadCategories();
            loadStats();
        } else {
            showNotification(data.message || 'Gagal menghapus kategori', 'error');
        }
    } catch (error) {
        console.error('Error deleting category:', error);
        showNotification('Terjadi kesalahan saat menghapus kategori', 'error');
    }
}

// Refresh data
function refreshData() {
    loadCategories();
    loadStats();
    showNotification('Data berhasil diperbarui', 'success');
}

// Export data
function exportData() {
    window.open('<?= base_url('admin/api/categories/export') ?>', '_blank');
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Close modal when clicking outside
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
<?= $this->endSection() ?>
