<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-sky-700 to-sky-600 bg-clip-text text-transparent mb-2">
                    <i class="fas fa-edit mr-2 text-sky-600"></i>
                    Edit Kategori
                </h1>
                <p class="text-slate-600">Perbarui informasi kategori</p>
            </div>
            <div>
                <a href="<?= base_url('admin/categories') ?>" 
                   class="bg-slate-100 text-slate-700 px-6 py-3 rounded-lg hover:bg-slate-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Loading State -->
<div id="loading-state" class="bg-white rounded-2xl shadow-xl p-12 text-center border border-white/50">
    <i class="fas fa-spinner fa-spin text-4xl text-sky-600 mb-4"></i>
    <p class="text-slate-600">Memuat data kategori...</p>
</div>

<!-- Form -->
<div id="form-container" class="bg-white rounded-2xl shadow-xl border border-white/50 hidden">
    <div class="p-6 border-b border-slate-200">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-edit mr-2 text-sky-600"></i>
            Informasi Kategori
        </h2>
    </div>
    
    <form id="category-form" class="p-6">
        <input type="hidden" id="category-id" name="id">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           required
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors"
                           placeholder="Masukkan nama kategori">
                    <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors resize-none"
                              placeholder="Masukkan deskripsi kategori (opsional)"></textarea>
                    <div id="description-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Status -->
                <div>
                    <label for="is_active" class="block text-sm font-medium text-slate-700 mb-2">
                        Status
                    </label>
                    <select id="is_active" 
                            name="is_active"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Color Picker -->
                <div>
                    <label for="color" class="block text-sm font-medium text-slate-700 mb-2">
                        Warna Kategori
                    </label>
                    <div class="flex items-center space-x-4">
                        <input type="color" 
                               id="color" 
                               name="color" 
                               value="#3B82F6"
                               class="w-16 h-12 border border-slate-300 rounded-lg cursor-pointer">
                        <input type="text" 
                               id="color-hex" 
                               value="#3B82F6"
                               class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors"
                               placeholder="#000000">
                    </div>
                    <div id="color-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Icon Selector -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-slate-700 mb-2">
                        Icon Kategori
                    </label>
                    <div class="grid grid-cols-6 gap-3 mb-4" id="icon-grid">
                        <!-- Icons will be populated by JavaScript -->
                    </div>
                    <input type="text" 
                           id="icon" 
                           name="icon" 
                           value="fas fa-tag"
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors"
                           placeholder="fas fa-tag">
                    <div id="icon-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Preview -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Preview
                    </label>
                    <div class="bg-slate-50 rounded-lg p-6 border border-slate-200">
                        <div class="flex items-center">
                            <div id="preview-icon" class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" style="background-color: #3B82F620; color: #3B82F6;">
                                <i class="fas fa-tag text-xl"></i>
                            </div>
                            <div>
                                <h3 id="preview-name" class="font-bold text-slate-800 text-lg">Nama Kategori</h3>
                                <p id="preview-description" class="text-slate-600 text-sm">Deskripsi kategori akan muncul di sini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-slate-200">
            <a href="<?= base_url('admin/categories') ?>" 
               class="px-6 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
            <button type="submit" 
                    id="submit-btn"
                    class="px-6 py-3 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 hover:scale-105">
                <i class="fas fa-save mr-2"></i>
                Perbarui Kategori
            </button>
        </div>
    </form>
</div>

<!-- Error State -->
<div id="error-state" class="bg-white rounded-2xl shadow-xl p-12 text-center border border-white/50 hidden">
    <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
    <h3 class="text-xl font-semibold text-red-600 mb-2">Gagal memuat data</h3>
    <p class="text-red-500 mb-6">Kategori tidak ditemukan atau terjadi kesalahan.</p>
    <a href="<?= base_url('admin/categories') ?>" 
       class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar
    </a>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Available icons
const availableIcons = [
    'fas fa-tag', 'fas fa-tint', 'fas fa-water', 'fas fa-road', 'fas fa-seedling',
    'fas fa-circle', 'fas fa-square', 'fas fa-star', 'fas fa-heart', 'fas fa-home',
    'fas fa-building', 'fas fa-tree', 'fas fa-leaf', 'fas fa-mountain', 'fas fa-sun',
    'fas fa-cloud', 'fas fa-bolt', 'fas fa-fire', 'fas fa-snowflake', 'fas fa-umbrella',
    'fas fa-map-marker-alt', 'fas fa-compass', 'fas fa-globe', 'fas fa-location-arrow'
];

let categoryId = null;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Get category ID from URL
    const pathParts = window.location.pathname.split('/');
    categoryId = pathParts[pathParts.length - 1];
    
    if (categoryId) {
        loadCategory();
    } else {
        showError();
    }
});

// Load category data
async function loadCategory() {
    try {
        const response = await fetch(`<?= base_url('admin/api/categories') ?>/${categoryId}`);
        const data = await response.json();
        
        if (data.success) {
            populateForm(data.category);
            initializeComponents();
            showForm();
        } else {
            showError();
        }
    } catch (error) {
        console.error('Error loading category:', error);
        showError();
    }
}

// Populate form with category data
function populateForm(category) {
    document.getElementById('category-id').value = category.id;
    document.getElementById('name').value = category.name;
    document.getElementById('description').value = category.description || '';
    document.getElementById('is_active').value = category.is_active;
    document.getElementById('color').value = category.color;
    document.getElementById('color-hex').value = category.color;
    document.getElementById('icon').value = category.icon;
}

// Initialize components
function initializeComponents() {
    initializeIconGrid();
    initializeColorPicker();
    initializePreview();
    initializeForm();
    updatePreview();
}

// Initialize icon grid
function initializeIconGrid() {
    const iconGrid = document.getElementById('icon-grid');
    const currentIcon = document.getElementById('icon').value;
    
    iconGrid.innerHTML = availableIcons.map(icon => `
        <button type="button" 
                onclick="selectIcon('${icon}')" 
                class="icon-option w-12 h-12 border border-slate-300 rounded-lg flex items-center justify-center hover:bg-sky-100 hover:border-sky-500 transition-colors ${icon === currentIcon ? 'bg-sky-100 border-sky-500' : ''}">
            <i class="${icon} text-slate-600"></i>
        </button>
    `).join('');
}

// Initialize color picker
function initializeColorPicker() {
    const colorPicker = document.getElementById('color');
    const colorHex = document.getElementById('color-hex');
    
    colorPicker.addEventListener('change', function() {
        colorHex.value = this.value;
        updatePreview();
    });
    
    colorHex.addEventListener('input', function() {
        if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
            colorPicker.value = this.value;
            updatePreview();
        }
    });
}

// Initialize preview
function initializePreview() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    
    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
}

// Select icon
function selectIcon(iconClass) {
    // Remove active class from all icons
    document.querySelectorAll('.icon-option').forEach(btn => {
        btn.classList.remove('bg-sky-100', 'border-sky-500');
    });
    
    // Add active class to selected icon
    event.target.closest('.icon-option').classList.add('bg-sky-100', 'border-sky-500');
    
    // Update input and preview
    document.getElementById('icon').value = iconClass;
    updatePreview();
}

// Update preview
function updatePreview() {
    const name = document.getElementById('name').value || 'Nama Kategori';
    const description = document.getElementById('description').value || 'Deskripsi kategori akan muncul di sini';
    const color = document.getElementById('color').value;
    const icon = document.getElementById('icon').value;
    
    document.getElementById('preview-name').textContent = name;
    document.getElementById('preview-description').textContent = description;
    
    const previewIcon = document.getElementById('preview-icon');
    previewIcon.style.backgroundColor = color + '20';
    previewIcon.style.color = color;
    previewIcon.innerHTML = `<i class="${icon} text-xl"></i>`;
}

// Initialize form
function initializeForm() {
    const form = document.getElementById('category-form');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Clear previous errors
        clearErrors();
        
        // Get form data
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Validate form
        if (!validateForm(data)) {
            return;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submit-btn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memperbarui...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(`<?= base_url('admin/api/categories') ?>/${categoryId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification(result.message, 'success');
                setTimeout(() => {
                    window.location.href = '<?= base_url('admin/categories') ?>';
                }, 1500);
            } else {
                if (result.errors) {
                    displayErrors(result.errors);
                } else {
                    showNotification(result.message || 'Gagal memperbarui kategori', 'error');
                }
            }
        } catch (error) {
            console.error('Error updating category:', error);
            showNotification('Terjadi kesalahan saat memperbarui kategori', 'error');
        } finally {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
}

// Show/hide states
function showForm() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('form-container').classList.remove('hidden');
    document.getElementById('error-state').classList.add('hidden');
}

function showError() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('form-container').classList.add('hidden');
    document.getElementById('error-state').classList.remove('hidden');
}

// Validate form
function validateForm(data) {
    let isValid = true;
    
    // Name validation
    if (!data.name || data.name.trim().length < 3) {
        showFieldError('name', 'Nama kategori minimal 3 karakter');
        isValid = false;
    }
    
    // Color validation
    if (!data.color.match(/^#[0-9A-Fa-f]{6}$/)) {
        showFieldError('color', 'Format warna harus berupa hex code (contoh: #FF0000)');
        isValid = false;
    }
    
    // Icon validation
    if (!data.icon || data.icon.trim().length === 0) {
        showFieldError('icon', 'Icon harus dipilih');
        isValid = false;
    }
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    const errorElement = document.getElementById(field + '-error');
    const inputElement = document.getElementById(field);
    
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
    
    if (inputElement) {
        inputElement.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    }
}

// Clear errors
function clearErrors() {
    document.querySelectorAll('[id$="-error"]').forEach(element => {
        element.classList.add('hidden');
    });
    
    document.querySelectorAll('input, textarea, select').forEach(element => {
        element.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
    });
}

// Display errors
function displayErrors(errors) {
    Object.keys(errors).forEach(field => {
        showFieldError(field, errors[field]);
    });
}

// Notification function (placeholder)
function showNotification(message, type) {
    console.log(`Notification (${type}): ${message}`);
}
</script>
<?= $this->endSection() ?>
