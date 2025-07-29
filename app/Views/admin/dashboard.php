<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
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
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slide-in-left {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes slide-in-right {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-slide-in-left { animation: slide-in-left 0.6s ease-out; }
        .animate-slide-in-right { animation: slide-in-right 0.6s ease-out; }
        
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
    </style>
    
    <!-- Floating Elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full animate-float" style="animation-delay: 0s;"></div>
        <div class="absolute top-40 right-20 w-24 h-24 bg-white/5 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-20 h-20 bg-white/10 rounded-full animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute bottom-32 right-1/3 w-16 h-16 bg-white/5 rounded-full animate-float" style="animation-delay: 1s;"></div>
    </div>
            
            <!-- Header -->
            <div class="mb-8 animate-fade-in">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-sky-700 to-sky-600 bg-clip-text text-transparent mb-2">
                                <i class="fas fa-user-shield mr-2 text-sky-600"></i>
                                Selamat Datang, <?= esc(session()->get('admin_name') ?? 'Admin') ?>
                            </h1>
                            <p class="text-slate-600">Kelola sistem informasi geografis Anda dengan mudah</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-lg">
                                <div class="text-right">
                                    <p class="text-sm opacity-90">Tanggal Hari Ini</p>
                                    <p class="font-bold"><?= date('d F Y') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade-in" style="animation-delay: 0.2s;">
                
                <!-- Total Locations -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm mb-1">Total Lokasi</p>
                            <p class="text-2xl font-bold text-slate-800" id="total-locations"><?= $total_locations ?? 0 ?></p>
                        </div>
                        <div class="bg-sky-100 p-3 rounded-lg">
                            <i class="fas fa-map-marker-alt text-sky-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+12%</span>
                        <span class="text-slate-600 ml-1">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Active Locations -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm mb-1">Lokasi Aktif</p>
                            <p class="text-2xl font-bold text-green-600" id="active-locations"><?= $active_locations ?? 0 ?></p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+8%</span>
                        <span class="text-slate-600 ml-1">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Maintenance Locations -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm mb-1">Maintenance</p>
                            <p class="text-2xl font-bold text-yellow-600" id="maintenance-locations"><?= $maintenance_locations ?? 0 ?></p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <i class="fas fa-tools text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-yellow-600 font-medium">-2%</span>
                        <span class="text-slate-600 ml-1">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Total Categories -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50 hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm mb-1">Total Kategori</p>
                            <p class="text-2xl font-bold text-violet-600" id="total-categories"><?= $total_categories ?? 0 ?></p>
                        </div>
                        <div class="bg-violet-100 p-3 rounded-lg">
                            <i class="fas fa-tags text-violet-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+5%</span>
                        <span class="text-slate-600 ml-1">dari bulan lalu</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade-in" style="animation-delay: 0.4s;">
                <a href="<?= base_url('admin/maps/add') ?>" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors">
                            <i class="fas fa-plus text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Tambah Lokasi</h3>
                            <p class="text-sky-100 text-sm">Tambah lokasi baru</p>
                        </div>
                    </div>
                </a>

                <a href="<?= base_url('admin/categories/add') ?>" class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors">
                            <i class="fas fa-tag text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Tambah Kategori</h3>
                            <p class="text-emerald-100 text-sm">Buat kategori baru</p>
                        </div>
                    </div>
                </a>

                <a href="<?= base_url('admin/maps') ?>" class="bg-gradient-to-r from-violet-500 to-violet-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors">
                            <i class="fas fa-map text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Lihat Peta</h3>
                            <p class="text-violet-100 text-sm">Peta interaktif</p>
                        </div>
                    </div>
                </a>

                <a href="<?= base_url('admin/maps/list') ?>" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-lg mr-4 group-hover:bg-white/30 transition-colors">
                            <i class="fas fa-list text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Daftar Lokasi</h3>
                            <p class="text-orange-100 text-sm">Kelola semua lokasi</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50 animate-fade-in" style="animation-delay: 0.6s;">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-800">
                        <i class="fas fa-clock mr-2 text-sky-600"></i>
                        Aktivitas Terbaru
                    </h2>
                    <button onclick="refreshActivities()" class="text-sky-600 hover:text-sky-800 transition-colors">
                        <i class="fas fa-refresh mr-1"></i>
                        Refresh
                    </button>
                </div>
                
                <div class="space-y-4" id="recent-activities">
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-sky-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-plus text-sky-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Lokasi baru ditambahkan</p>
                            <p class="text-sm text-slate-600">5 menit yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-emerald-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-edit text-emerald-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Kategori diperbarui</p>
                            <p class="text-sm text-slate-600">15 menit yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-violet-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-map text-violet-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Peta diakses</p>
                            <p class="text-sm text-slate-600">1 jam yang lalu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 animate-fade-in" style="animation-delay: 0.8s;">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-slate-800">Status Server</h3>
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                    <p class="text-2xl font-bold text-green-600 mb-2">Online</p>
                    <p class="text-sm text-slate-600">Uptime: 99.9%</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-slate-800">Database</h3>
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                    <p class="text-2xl font-bold text-green-600 mb-2">Aktif</p>
                    <p class="text-sm text-slate-600">Response: 12ms</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-slate-800">Storage</h3>
                        <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                    </div>
                    <p class="text-2xl font-bold text-yellow-600 mb-2">75%</p>
                    <p class="text-sm text-slate-600">Used: 7.5GB / 10GB</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 animate-fade-in" style="animation-delay: 1s;">
                <p class="text-slate-600 text-sm">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Secure Geographic Information System Dashboard &copy; <?= date('Y') ?>
                </p>
            </div>
        </div>
    </div>


<?= $this->endSection() ?>
    
<?= $this->section('scripts') ?>


    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            mobileOverlay.classList.toggle('hidden');
        });

        mobileOverlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            mobileOverlay.classList.add('hidden');
        });

        // Close mobile menu when clicking on sidebar links
        const sidebarLinks = document.querySelectorAll('.sidebar-item');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('open');
                    mobileOverlay.classList.add('hidden');
                }
            });
        });

        // Dashboard functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard initialized');
            
            // Add hover effects to cards
            const cards = document.querySelectorAll('.hover\\:scale-105');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });

        // Auto-refresh dashboard data every 30 seconds
        setInterval(function() {
            refreshDashboardData();
        }, 30000);

        // Refresh dashboard data
        function refreshDashboardData() {
            // Update stats (you can implement API calls here)
            console.log('Refreshing dashboard data...');
        }

        // Refresh activities
        function refreshActivities() {
            const activitiesContainer = document.getElementById('recent-activities');
            
            // Add loading state
            activitiesContainer.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin text-sky-600"></i>
                    <p class="text-slate-600 mt-2">Memuat aktivitas terbaru...</p>
                </div>
            `;
            
            // Simulate API call
            setTimeout(() => {
                activitiesContainer.innerHTML = `
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-sky-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-plus text-sky-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Lokasi baru ditambahkan</p>
                            <p class="text-sm text-slate-600">Baru saja</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-emerald-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-edit text-emerald-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Kategori diperbarui</p>
                            <p class="text-sm text-slate-600">5 menit yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-violet-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-map text-violet-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Peta diakses</p>
                            <p class="text-sm text-slate-600">10 menit yang lalu</p>
                        </div>
                    </div>
                `;
                
                showNotification('Aktivitas berhasil diperbarui', 'success');
            }, 1000);
        }

        // Notification function (placeholder)
        function showNotification(message, type) {
            console.log(`Notification (${type}): ${message}`);
        }
    </script>
<?= $this->endSection() ?>

