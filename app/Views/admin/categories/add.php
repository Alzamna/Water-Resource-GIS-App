<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-sky-600 to-sky-700 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Tambah Kategori</h1>
                <p class="text-sky-100 mt-1">Buat kategori baru untuk lokasi sumber daya air</p>
            </div>
            <a href="<?= base_url('admin/categories') ?>" 
               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="p-6">
        <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada form:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/categories/add') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kategori -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="<?= old('name') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                           placeholder="Masukkan nama kategori"
                           required>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                            required>
                        <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                          placeholder="Masukkan deskripsi kategori (opsional)"><?= old('description') ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Warna -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                        Warna Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="color" 
                               id="color" 
                               name="color" 
                               value="<?= old('color', '#3b82f6') ?>"
                               class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer"
                               required>
                        <input type="text" 
                               id="color-text" 
                               value="<?= old('color', '#3b82f6') ?>"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                               placeholder="#3b82f6"
                               readonly>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Pilih warna yang akan digunakan untuk kategori ini</p>
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                        Icon Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <div id="icon-preview" 
                             class="w-10 h-10 rounded-lg flex items-center justify-center text-white"
                             style="background-color: <?= old('color', '#3b82f6') ?>">
                            <i class="<?= old('icon', 'fas fa-circle') ?>"></i>
                        </div>
                        <select id="icon" 
                                name="icon" 
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                required>
                            <option value="fas fa-tint" <?= old('icon') === 'fas fa-tint' ? 'selected' : '' ?>>💧 Tetesan Air</option>
                            <option value="fas fa-water" <?= old('icon') === 'fas fa-water' ? 'selected' : '' ?>>🌊 Air</option>
                            <option value="fas fa-stream" <?= old('icon') === 'fas fa-stream' ? 'selected' : '' ?>>🏞️ Aliran</option>
                            <option value="fas fa-seedling" <?= old('icon') === 'fas fa-seedling' ? 'selected' : '' ?>>🌱 Tanaman</option>
                            <option value="fas fa-circle" <?= old('icon') === 'fas fa-circle' ? 'selected' : '' ?>>⚫ Lingkaran</option>
                            <option value="fas fa-square" <?= old('icon') === 'fas fa-square' ? 'selected' : '' ?>>⬛ Kotak</option>
                            <option value="fas fa-star" <?= old('icon') === 'fas fa-star' ? 'selected' : '' ?>>⭐ Bintang</option>
                            <option value="fas fa-heart" <?= old('icon') === 'fas fa-heart' ? 'selected' : '' ?>>❤️ Hati</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Preview Kategori</h3>
                <div id="category-preview" class="inline-flex items-center space-x-3 bg-white border border-gray-200 rounded-lg p-3">
                    <div id="preview-icon" 
                         class="w-10 h-10 rounded-lg flex items-center justify-center text-white"
                         style="background-color: <?= old('color', '#3b82f6') ?>">
                        <i class="<?= old('icon', 'fas fa-circle') ?>"></i>
                    </div>
                    <div>
                        <div id="preview-name" class="font-medium text-gray-900"><?= old('name', 'Nama Kategori') ?></div>
                        <div id="preview-status" class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                            <?= old('status', 'active') === 'active' ? 'Aktif' : 'Tidak Aktif' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="<?= base_url('admin/categories') ?>" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('color-text');
    const iconSelect = document.getElementById('icon');
    const nameInput = document.getElementById('name');
    const statusSelect = document.getElementById('status');
    
    const iconPreview = document.getElementById('icon-preview');
    const previewIcon = document.getElementById('preview-icon');
    const previewName = document.getElementById('preview-name');
    const previewStatus = document.getElementById('preview-status');

    // Update color text when color picker changes
    colorInput.addEventListener('change', function() {
        colorText.value = this.value;
        updatePreview();
    });

    // Update icon preview when icon changes
    iconSelect.addEventListener('change', function() {
        updatePreview();
    });

    // Update name preview
    nameInput.addEventListener('input', function() {
        updatePreview();
    });

    // Update status preview
    statusSelect.addEventListener('change', function() {
        updatePreview();
    });

    function updatePreview() {
        const color = colorInput.value;
        const icon = iconSelect.value;
        const name = nameInput.value || 'Nama Kategori';
        const status = statusSelect.value;

        // Update icon previews
        iconPreview.style.backgroundColor = color;
        iconPreview.innerHTML = `<i class="${icon}"></i>`;
        
        previewIcon.style.backgroundColor = color;
        previewIcon.innerHTML = `<i class="${icon}"></i>`;

        // Update name
        previewName.textContent = name;

        // Update status
        if (status === 'active') {
            previewStatus.className = 'text-xs px-2 py-1 rounded-full bg-green-100 text-green-800';
            previewStatus.textContent = 'Aktif';
        } else {
            previewStatus.className = 'text-xs px-2 py-1 rounded-full bg-red-100 text-red-800';
            previewStatus.textContent = 'Tidak Aktif';
        }
    }

    // Initialize preview
    updatePreview();
});
</script>
<?= $this->endSection() ?>
