<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard CMS - GIS Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
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
        .animate-fade-in { animation: fade-in 0.8s ease-out; }
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
</head>
<body class="animated-bg min-h-screen">
    
    <!-- Floating Elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full animate-float" style="animation-delay: 0s;"></div>
        <div class="absolute top-40 right-20 w-24 h-24 bg-white/5 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-20 h-20 bg-white/10 rounded-full animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute bottom-32 right-1/3 w-16 h-16 bg-white/5 rounded-full animate-float" style="animation-delay: 1s;"></div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed left-0 top-0 h-full w-70 ocean-gradient shadow-2xl z-50 animate-slide-in-left">
        <div class="p-6">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4 animate-float">
                    <i class="fas fa-tachometer-alt text-2xl text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Dashboard CMS</h1>
                <p class="text-sky-100 text-sm">GIS Admin Portal</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-y-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-item active flex items-center px-4 py-3 text-white rounded-lg">
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
                    <span>Manajemen Peta</span>
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
                        <a href="<?= base_url('auth/logout') ?>" class="flex-1 bg-red-500/80 hover:bg-red-600 text-white py-2 px-3 rounded text-center text-xs transition-all duration-200">
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
    <div class="main-content min-h-screen p-6 animate-slide-in-right">
        <div class="max-w-6xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8 animate-fade-in">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-sky-700 to-sky-600 bg-clip-text text-transparent mb-2">
                                <i class="fas fa-user-shield mr-2 text-sky-600"></i>
                                Selamat Datang, Admin
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
                
                <!-- Total Content -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm mb-1">Total Konten</p>
                            <p class="text-2xl font-bold text-slate-800">245</p>
                        </div>
                        <div class="bg-sky-100 p-3 rounded-lg">
                            <i class="fas fa-file-text text-sky-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+12%</span>
                        <span class="text-slate-600 ml-1">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm mb-1">Total User</p>
                            <p class="text-2xl font-bold text-slate-800">1,234</p>
                        </div>
                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <i class="fas fa-users text-emerald-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+8%</span>
                        <span class="text-slate-600 ml-1">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Total Maps -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm mb-1">Total Peta</p>
                            <p class="text-2xl font-bold text-slate-800">89</p>
                        </div>
                        <div class="bg-violet-100 p-3 rounded-lg">
                            <i class="fas fa-map-marked-alt text-violet-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+5%</span>
                        <span class="text-slate-600 ml-1">dari bulan lalu</span>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-white/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm mb-1">Status Sistem</p>
                            <p class="text-2xl font-bold text-green-600">Online</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-server text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">99.9%</span>
                        <span class="text-slate-600 ml-1">uptime</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-white/50 animate-fade-in" style="animation-delay: 0.4s;">
                <h2 class="text-xl font-bold text-slate-800 mb-6">
                    <i class="fas fa-clock mr-2 text-sky-600"></i>
                    Aktivitas Terbaru
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-sky-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-plus text-sky-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Konten baru ditambahkan</p>
                            <p class="text-sm text-slate-600">2 menit yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-emerald-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-user-plus text-emerald-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">User baru terdaftar</p>
                            <p class="text-sm text-slate-600">15 menit yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                        <div class="bg-violet-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-map text-violet-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800">Peta diperbarui</p>
                            <p class="text-sm text-slate-600">1 jam yang lalu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 animate-fade-in" style="animation-delay: 0.6s;">
                <p class="text-slate-600 text-sm">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Secure Geographic Information System Dashboard &copy; <?= date('Y') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

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
    </script>
</body>
</html>
