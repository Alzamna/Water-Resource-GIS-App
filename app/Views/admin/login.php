<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIS Admin Portal - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ocean': {
                            50: '#f0f9ff',
                            100: '#e0f2fe', 
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e'
                        },
                        'earth': {
                            50: '#fefce8',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#f59e0b',
                            500: '#d97706'
                        },
                        'forest': {
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d'
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { 
                                opacity: '0',
                                transform: 'translateY(30px)' 
                            },
                            '100%': { 
                                opacity: '1',
                                transform: 'translateY(0)' 
                            }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-ocean-50 via-blue-50 to-earth-50 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Topographic circles -->
        <div class="absolute top-20 left-10 w-64 h-64 border border-ocean-200/30 rounded-full animate-pulse-slow"></div>
        <div class="absolute top-32 left-20 w-48 h-48 border border-ocean-300/20 rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 right-10 w-72 h-72 border border-forest-500/20 rounded-full animate-pulse-slow" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-32 right-20 w-56 h-56 border border-earth-300/30 rounded-full animate-pulse-slow" style="animation-delay: 0.5s;"></div>
        
        <!-- Floating geographic elements -->
        <div class="absolute top-1/4 right-1/4 text-ocean-200/40 animate-float">
            <i class="fas fa-globe text-6xl"></i>
        </div>
        <div class="absolute bottom-1/4 left-1/4 text-forest-500/30 animate-float" style="animation-delay: 1.5s;">
            <i class="fas fa-map-marked-alt text-5xl"></i>
        </div>
    </div>

    <!-- Main Login Container -->
    <div class="relative z-10 w-full max-w-md pb-10 flex flex-col justify-center min-h-screen">
        <!-- Header Section -->
        <div class="text-center mb-6 animate-slide-up">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-ocean-600 to-ocean-700 rounded-full mb-4 shadow-lg shadow-ocean-600/30 animate-float">
                <i class="fas fa-map-location-dot text-xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-ocean-700 to-ocean-600 bg-clip-text text-transparent mb-1">
                GIS Admin Portal
            </h1>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl shadow-ocean-600/10 p-6 border border-white/50 animate-slide-up flex flex-col justify-between" style="animation-delay: 0.2s; min-height: 400px;">
            
            <div class="flex-grow">
                <!-- Error Message -->
                <?php if (!empty($error)) : ?>
                    <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg animate-slide-up">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2 text-sm"></i>
                            <p class="text-red-700 font-medium text-sm"><?= esc($error) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="post" action="/login" class="space-y-4" id="loginForm">
                <?= csrf_field() ?>
                
                <!-- Username Field -->
                <div class="group">
                    <label for="username" class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-0 pl-3 flex items-center h-full pointer-events-none">
                            <i class="fas fa-user text-slate-400 group-focus-within:text-ocean-600 transition-colors duration-200"></i>
                        </span>
                        <input type="text" 
                               id="username"
                               name="username" 
                               required 
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-ocean-500 focus:border-transparent transition-all duration-200 bg-white placeholder-slate-400"
                               placeholder="Enter your username">
                    </div>
                </div>

                <!-- Password Field -->
                <div class="group">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-0 pl-3 flex items-center h-full pointer-events-none">
                            <i class="fas fa-lock text-slate-400 group-focus-within:text-ocean-600 transition-colors duration-200"></i>
                        </span>
                        <input type="password" 
                               id="password"
                               name="password" 
                               required 
                               class="w-full pl-10 pr-12 py-3 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-ocean-500 focus:border-transparent transition-all duration-200 bg-white placeholder-slate-400"
                               placeholder="Enter your password">
                        <button type="button" 
                                onclick="togglePassword()"
                                class="absolute right-0 pr-3 flex items-center h-full">
                            <i id="toggleIcon" class="fas fa-eye text-slate-400 hover:text-ocean-600 transition-colors duration-200"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-ocean-600 border-slate-300 rounded focus:ring-ocean-500 focus:ring-2">
                        <span class="ml-2 text-sm text-slate-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-ocean-600 hover:text-ocean-700 transition-colors duration-200">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-ocean-600 to-ocean-700 text-white py-3 px-4 rounded-lg font-medium shadow-lg shadow-ocean-600/30 hover:shadow-xl hover:shadow-ocean-600/40 hover:from-ocean-700 hover:to-ocean-800 focus:outline-none focus:ring-2 focus:ring-ocean-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center group">
                    <i class="fas fa-sign-in-alt mr-2 group-hover:translate-x-1 transition-transform duration-200"></i>
                    Access GIS Portal
                </button>
                </form>
            </div>

            <!-- Footer - Always at bottom -->
            <div class="pt-4 border-t border-slate-200 text-center mt-auto">
                <p class="text-sm text-slate-500">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Secure Geographic Information System
                </p>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash text-slate-400 hover:text-ocean-600 transition-colors duration-200';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'fas fa-eye text-slate-400 hover:text-ocean-600 transition-colors duration-200';
            }
        }

        // Add subtle hover effects to form inputs
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.closest('.group')?.classList.add('scale-[1.02]');
            });
            
            input.addEventListener('blur', function() {
                this.closest('.group')?.classList.remove('scale-[1.02]');
            });
        });

        // Prevent form submission on demo (remove this in production)
        // document.querySelector('form').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     alert('Demo form - remove this listener in production');
        // });
    </script>
</body>
</html>