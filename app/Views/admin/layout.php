<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'GIS Admin Portal' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS (conditionally loaded) -->
    <?php if (isset($include_leaflet) && $include_leaflet): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <?php endif; ?>
    
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
            position: relative;
        }
        
        .sidebar-item:hover {
            transform: translateX(4px);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-right: 4px solid #ffffff;
            transform: translateX(0);
        }
        
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ffffff;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 0;
            background: rgba(255, 255, 255, 0.05);
            margin: 4px 0;
            border-radius: 8px;
        }
        
        .sidebar-dropdown.open {
            max-height: 500px;
            opacity: 1;
            overflow: hidden;
        }
        
        .sidebar-dropdown-item {
            padding: 8px 16px 8px 48px;
            margin: 2px 8px;
            font-size: 0.875rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 6px;
            position: relative;
        }
        
        .sidebar-dropdown-item:hover {
            transform: translateX(4px);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-dropdown-item.active {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(0);
        }
        
        .sidebar-dropdown-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 16px;
            background: #ffffff;
            border-radius: 0 2px 2px 0;
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
        
        .dropdown-arrow {
            transition: transform 0.3s ease;
            font-size: 0.75rem;
        }
        
        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }
        
        .sidebar-section {
            margin-bottom: 24px;
        }
        
        .sidebar-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
            padding: 0 16px;
        }
        
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
        
        .filter-chip {
            transition: all 0.2s ease;
        }
        
        .filter-chip.active {
            background: #0ea5e9;
            color: white;
        }
        
        .table-row:hover {
            background-color: #f8fafc;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active { background: #dcfce7; color: #166534; }
        .status-maintenance { background: #fef3c7; color: #92400e; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        
        .modal {
            display: none;
        }
        
        .modal.show {
            display: flex;
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
        
        .user-section {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px;
            background: linear-gradient(to top, rgba(0,0,0,0.1), transparent);
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Custom styles for specific pages */
        <?= $custom_styles ?? '' ?>
    </style>
    
    <!-- Additional head content -->
    <?= $additional_head ?? '' ?>
</head>
<body class="animated-bg min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed left-0 top-0 h-full w-70 ocean-gradient shadow-2xl z-50">
        <div class="flex flex-col h-full">
            <!-- Logo/Header -->
            <div class="text-center py-6 px-4 border-b border-white/10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <i class="fas fa-tachometer-alt text-2xl text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Dashboard CMS</h1>
                <p class="text-sky-100 text-sm">GIS Admin Portal</p>
            </div>

            <!-- User Section (Moved here) -->
            <div class="px-4 pt-4 pb-2">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium text-sm truncate"><?= session()->get('admin_name') ?? 'Admin User' ?></p>
                            <p class="text-sky-100 text-xs">Administrator</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="<?= base_url('admin/profile') ?>"
                           class="flex-1 bg-white/10 hover:bg-white/20 text-white py-2 px-3 rounded text-center text-xs transition-all duration-200">
                            <i class="fas fa-user-circle mr-1"></i>
                            Profil
                        </a>
                        <a href="<?= base_url('logout') ?>"
                           class="flex-1 bg-red-500/80 hover:bg-red-600 text-white py-2 px-3 rounded text-center text-xs transition-all duration-200">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="flex-1 overflow-y-auto py-4 hide-scrollbar">
                <nav class="px-4 space-y-1">
                    <!-- Main Navigation -->
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Menu Utama</div>
                        
                        <a href="<?= base_url('admin/dashboard') ?>"
                           class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg <?= ($current_page ?? '') === 'dashboard' ? 'active' : '' ?>">
                            <i class="fas fa-home w-5 h-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="<?= base_url('admin/konten') ?>"
                           class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg <?= ($current_page ?? '') === 'konten' ? 'active' : '' ?>">
                            <i class="fas fa-file-text w-5 h-5 mr-3"></i>
                            <span>Manajemen Konten</span>
                        </a>
                        
                        <a href="<?= base_url('admin/users') ?>"
                           class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg <?= ($current_page ?? '') === 'users' ? 'active' : '' ?>">
                            <i class="fas fa-users w-5 h-5 mr-3"></i>
                            <span>Manajemen User</span>
                        </a>
                    </div>
                    
                    <!-- Maps Section -->
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Sistem Peta</div>
                        
                        <!-- Maps Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('maps-dropdown')"
                                    class="sidebar-item w-full flex items-center justify-between px-4 py-3 text-white rounded-lg <?= in_array(($current_page ?? ''), ['maps', 'maps-list', 'maps-add', 'maps-edit']) ? 'active' : '' ?>">
                                <div class="flex items-center">
                                    <i class="fas fa-map-marked-alt w-5 h-5 mr-3"></i>
                                    <span>Manajemen Peta</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow" id="maps-dropdown-arrow"></i>
                            </button>
                            
                            <div id="maps-dropdown" class="sidebar-dropdown <?= in_array(($current_page ?? ''), ['maps', 'maps-list', 'maps-add', 'maps-edit']) ? 'open' : '' ?>">
                                <a href="<?= base_url('admin/maps') ?>"
                                   class="sidebar-dropdown-item flex items-center text-white rounded-lg <?= ($current_page ?? '') === 'maps' ? 'active' : '' ?>">
                                    <i class="fas fa-globe w-4 h-4 mr-3"></i>
                                    <span>Peta Interaktif</span>
                                </a>
                                
                                <a href="<?= base_url('admin/maps/list') ?>"
                                   class="sidebar-dropdown-item flex items-center text-white rounded-lg <?= ($current_page ?? '') === 'maps-list' ? 'active' : '' ?>">
                                    <i class="fas fa-list w-4 h-4 mr-3"></i>
                                    <span>Daftar Lokasi</span>
                                </a>
                                
                                <a href="<?= base_url('admin/maps/add') ?>"
                                   class="sidebar-dropdown-item flex items-center text-white rounded-lg <?= ($current_page ?? '') === 'maps-add' ? 'active' : '' ?>">
                                    <i class="fas fa-plus w-4 h-4 mr-3"></i>
                                    <span>Tambah Lokasi</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Categories Section -->
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Kategori</div>
                        
                        <!-- Categories Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('categories-dropdown')"
                                    class="sidebar-item w-full flex items-center justify-between px-4 py-3 text-white rounded-lg <?= in_array(($current_page ?? ''), ['categories', 'categories-add', 'categories-edit']) ? 'active' : '' ?>">
                                <div class="flex items-center">
                                    <i class="fas fa-tags w-5 h-5 mr-3"></i>
                                    <span>Manajemen Kategori</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow" id="categories-dropdown-arrow"></i>
                            </button>
                            
                            <div id="categories-dropdown" class="sidebar-dropdown <?= in_array(($current_page ?? ''), ['categories', 'categories-add', 'categories-edit']) ? 'open' : '' ?>">
                                <a href="<?= base_url('admin/categories') ?>"
                                   class="sidebar-dropdown-item flex items-center text-white rounded-lg <?= ($current_page ?? '') === 'categories' ? 'active' : '' ?>">
                                    <i class="fas fa-list w-4 h-4 mr-3"></i>
                                    <span>Daftar Kategori</span>
                                </a>
                                
                                <a href="<?= base_url('admin/categories/add') ?>"
                                   class="sidebar-dropdown-item flex items-center text-white rounded-lg <?= ($current_page ?? '') === 'categories-add' ? 'active' : '' ?>">
                                    <i class="fas fa-plus w-4 h-4 mr-3"></i>
                                    <span>Tambah Kategori</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Section -->
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Sistem</div>
                        
                        <a href="<?= base_url('admin/settings') ?>"
                           class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg <?= ($current_page ?? '') === 'settings' ? 'active' : '' ?>">
                            <i class="fas fa-cog w-5 h-5 mr-3"></i>
                            <span>Pengaturan</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="md:hidden fixed top-4 left-4 z-60 bg-sky-600 text-white p-3 rounded-lg shadow-lg">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content -->
    <div class="main-content min-h-screen p-6">
        <div class="max-w-7xl mx-auto">
            
            <!-- Breadcrumb (optional) -->
            <?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
            <nav class="mb-4">
                <ol class="flex items-center space-x-2 text-sm text-slate-600">
                    <?php foreach ($breadcrumb as $index => $item): ?>
                        <?php if ($index > 0): ?>
                            <li><i class="fas fa-chevron-right text-xs"></i></li>
                        <?php endif; ?>
                        <li>
                            <?php if (isset($item['url']) && $index < count($breadcrumb) - 1): ?>
                                <a href="<?= $item['url'] ?>" class="hover:text-sky-600"><?= $item['title'] ?></a>
                            <?php else: ?>
                                <span class="text-slate-800 font-medium"><?= $item['title'] ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <?php endif; ?>
            
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('warning')): ?>
            <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span><?= session()->getFlashdata('warning') ?></span>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Base JavaScript -->
    <script>
        // Dropdown functionality
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById(dropdownId + '-arrow');
            
            if (dropdown.classList.contains('open')) {
                dropdown.classList.remove('open');
                arrow.classList.remove('rotated');
            } else {
                // Close all other dropdowns first
                document.querySelectorAll('.sidebar-dropdown').forEach(dd => {
                    if (dd.id !== dropdownId) {
                        dd.classList.remove('open');
                    }
                });
                document.querySelectorAll('.dropdown-arrow').forEach(arr => {
                    if (arr.id !== dropdownId + '-arrow') {
                        arr.classList.remove('rotated');
                    }
                });
                
                dropdown.classList.add('open');
                arrow.classList.add('rotated');
            }
        }

        // Auto-open dropdown if current page is in dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = '<?= $current_page ?? '' ?>';
            const mapsPages = ['maps', 'maps-list', 'maps-add', 'maps-edit'];
            const categoriesPages = ['categories', 'categories-add', 'categories-edit'];
            
            if (mapsPages.includes(currentPage)) {
                const dropdown = document.getElementById('maps-dropdown');
                const arrow = document.getElementById('maps-dropdown-arrow');
                if (dropdown && arrow) {
                    dropdown.classList.add('open');
                    arrow.classList.add('rotated');
                }
            }
            
            if (categoriesPages.includes(currentPage)) {
                const dropdown = document.getElementById('categories-dropdown');
                const arrow = document.getElementById('categories-dropdown-arrow');
                if (dropdown && arrow) {
                    dropdown.classList.add('open');
                    arrow.classList.add('rotated');
                }
            }
        });

        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');

        if (mobileMenuBtn && sidebar) {
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!sidebar.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    sidebar.classList.remove('open');
                }
            });

            // Close mobile menu when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('open');
                }
            });
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

        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('[role="alert"]');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    if (message.parentElement) {
                        message.style.opacity = '0';
                        message.style.transform = 'translateY(-10px)';
                        setTimeout(function() {
                            if (message.parentElement) {
                                message.style.display = 'none';
                            }
                        }, 300);
                    }
                }, 5000);
            });
        });
    </script>
    
    <!-- Leaflet JS (conditionally loaded) -->
    <?php if (isset($include_leaflet) && $include_leaflet): ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <?php endif; ?>
    
    <!-- Additional scripts -->
    <?= $additional_scripts ?? '' ?>
    
    <!-- Page specific scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
