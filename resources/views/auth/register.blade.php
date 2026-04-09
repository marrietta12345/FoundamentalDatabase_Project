<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrightSphere • Student Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #fdfdff;
            overflow-x: hidden;
        }

        .mesh-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background-color: #fdfdff;
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(129, 140, 248, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(199, 210, 254, 0.1) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.05);
        }

        .input-group:focus-within label {
            color: #4f46e5;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.3);
            transform: translateY(-2px);
        }

        /* Error animation */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }
        
        .error-shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Custom select styling */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <div class="mesh-bg"></div>

    <nav class="w-full max-w-7xl mx-auto px-8 py-8 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-purple-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <span class="text-xl font-extrabold tracking-tight text-slate-900 uppercase">BrightSphere</span>
        </div>
        <a href="{{ route('home') }}" class="text-[11px] font-bold text-slate-500 hover:text-indigo-600 transition-colors uppercase tracking-widest flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Home
        </a>
    </nav>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="max-w-6xl w-full">
            
            <!-- Display Error Messages -->
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 error-shake">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                            <i class="fa-solid fa-exclamation-mark text-sm"></i>
                        </div>
                        <h4 class="text-red-800 font-bold text-sm uppercase tracking-wider">Please fix the following errors:</h4>
                    </div>
                    <ul class="space-y-1.5">
                        @foreach($errors->all() as $error)
                            <li class="text-red-600 text-xs flex items-center gap-2 ml-4">
                                <i class="fa-solid fa-circle text-[4px] text-red-400"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
                    <p class="text-red-600 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ session('error') }}
                    </p>
                </div>
            @endif

            <div class="glass-card rounded-[3rem] overflow-hidden flex flex-col lg:flex-row min-h-[750px]">
                
                <div class="lg:w-5/12 bg-gradient-to-br from-indigo-600 to-purple-600 p-12 lg:p-16 flex flex-col justify-between relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center text-white text-xl mb-8">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <h1 class="text-5xl font-extrabold text-white leading-tight mb-6">
                            Create your <br><span class="text-indigo-200">future here.</span>
                        </h1>
                        <p class="text-indigo-100 text-lg opacity-80 leading-relaxed max-w-xs">
                            Join thousands of students and instructors in the most powerful educational ecosystem ever built.
                        </p>
                    </div>

                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 pt-10">
                        <div class="flex -space-x-3">
                            <div class="w-10 h-10 rounded-full border-2 border-indigo-600 bg-slate-200 shadow-sm"></div>
                            <div class="w-10 h-10 rounded-full border-2 border-indigo-600 bg-slate-300 shadow-sm"></div>
                            <div class="w-10 h-10 rounded-full border-2 border-indigo-600 bg-slate-400 shadow-sm"></div>
                        </div>
                        <p class="text-indigo-200 text-[10px] mt-4 font-bold uppercase tracking-[0.2em]">Joined by 2k+ students & instructors</p>
                    </div>
                </div>

                <div class="lg:w-7/12 p-12 lg:p-20 flex flex-col justify-center">
                    <div class="mb-10">
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Registration</h3>
                        <p class="text-slate-500 text-sm">Fill in your information to set up your account.</p>
                    </div>

                    <form method="POST" action="{{ route('register.student') }}" class="space-y-5">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="input-group space-y-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">First Name</label>
                                <input type="text" name="first_name" required value="{{ old('first_name') }}"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none @error('first_name') border-red-400 bg-red-50 @enderror"
                                    placeholder="Jane">
                                @error('first_name')
                                    <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="input-group space-y-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none"
                                    placeholder="D.">
                            </div>
                            
                            <div class="input-group space-y-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">Last Name</label>
                                <input type="text" name="last_name" required value="{{ old('last_name') }}"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none @error('last_name') border-red-400 bg-red-50 @enderror"
                                    placeholder="Smith">
                                @error('last_name')
                                    <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Selection Field -->
                        <div class="input-group space-y-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">Register as</label>
                            <select name="role" required 
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none @error('role') border-red-400 bg-red-50 @enderror">
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="instructor" {{ old('role') == 'instructor' ? 'selected' : '' }}>Instructor</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-[10px] text-slate-400 mt-1">Choose your role to get started</p>
                        </div>

                        <div class="input-group space-y-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">Email Address</label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none @error('email') border-red-400 bg-red-50 @enderror"
                                placeholder="jane@example.com">
                            @error('email')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="input-group space-y-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">Password</label>
                                <input type="password" name="password" required 
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none @error('password') border-red-400 bg-red-50 @enderror" 
                                    placeholder="••••••••">
                                @error('password')
                                    <p class="text-red-500 text-[10px] mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <!-- Live password length indicator -->
                                <div class="mt-2 text-[10px] {{ old('password') && strlen(old('password')) < 8 ? 'text-red-500' : 'text-green-500' }}" id="password-length-hint">
                                    @if(old('password'))
                                        {{ strlen(old('password')) < 8 ? '❌ Password must be at least 8 characters' : '✓ Password length OK' }}
                                    @endif
                                </div>
                            </div>
                            
                            <div class="input-group space-y-1.5">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">Confirm Password</label>
                                <input type="password" name="password_confirmation" required 
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none @error('password') border-red-400 bg-red-50 @enderror" 
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Password strength indicator -->
                        <div class="space-y-2">
                            <div class="flex gap-1 h-1.5">
                                <div class="flex-1 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full {{ old('password') && strlen(old('password')) >= 8 ? 'w-full bg-green-500' : 'w-0' }} transition-all duration-300"></div>
                                </div>
                                <div class="flex-1 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full {{ old('password') && preg_match('/[0-9]/', old('password')) ? 'w-full bg-green-500' : 'w-0' }} transition-all duration-300"></div>
                                </div>
                                <div class="flex-1 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full {{ old('password') && preg_match('/[^a-zA-Z0-9]/', old('password')) ? 'w-full bg-green-500' : 'w-0' }} transition-all duration-300"></div>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-500 text-center">
                                Password must be at least 8 characters with numbers and special characters
                            </p>
                        </div>

                        <div class="pt-6">
                            <button type="submit" 
                                class="btn-primary w-full py-5 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 group">
                                <i class="fa-solid fa-paper-plane text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                Create My Account
                            </button>
                        </div>
                    </form>

                    <p class="mt-8 text-center text-sm text-slate-500">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline ml-1">Log In here</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-10 text-center">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.4em]">
            &copy; 2026 BrightSphere Inc. • All Rights Reserved
        </p>
    </footer>

    <!-- JavaScript for live password validation -->
    <script>
        document.querySelector('input[name="password"]')?.addEventListener('input', function(e) {
            const password = e.target.value;
            const lengthHint = document.getElementById('password-length-hint');
            const strengthBars = document.querySelectorAll('.flex-1 .h-full');
            
            if (lengthHint) {
                if (password.length < 8 && password.length > 0) {
                    lengthHint.innerHTML = '❌ Password must be at least 8 characters';
                    lengthHint.className = 'mt-2 text-[10px] text-red-500';
                } else if (password.length >= 8) {
                    lengthHint.innerHTML = '✓ Password length OK';
                    lengthHint.className = 'mt-2 text-[10px] text-green-500';
                } else {
                    lengthHint.innerHTML = '';
                }
            }
            
            // Update strength bars
            if (strengthBars.length >= 3) {
                // Length bar
                strengthBars[0].style.width = password.length >= 8 ? '100%' : '0%';
                strengthBars[0].className = password.length >= 8 ? 'h-full bg-green-500' : 'h-full w-0';
                
                // Number bar
                const hasNumber = /\d/.test(password);
                strengthBars[1].style.width = hasNumber ? '100%' : '0%';
                strengthBars[1].className = hasNumber ? 'h-full bg-green-500' : 'h-full w-0';
                
                // Special char bar
                const hasSpecial = /[^a-zA-Z0-9]/.test(password);
                strengthBars[2].style.width = hasSpecial ? '100%' : '0%';
                strengthBars[2].className = hasSpecial ? 'h-full bg-green-500' : 'h-full w-0';
            }
        });
    </script>

</body>
</html>