<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrightSphere • Sign In</title>
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
        <div class="max-w-md w-full">
            <div class="glass-card rounded-[3rem] p-10 lg:p-12">
                
                <div class="mb-10 text-center">
                    <div class="w-16 h-16 bg-gradient-to-tr from-indigo-50 to-purple-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-6 mx-auto shadow-sm">
                        <i class="fa-solid fa-fingerprint"></i>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Welcome Back</h3>
                    <p class="text-slate-500 text-sm">Please enter your details to sign in.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div class="input-group space-y-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none" 
                            placeholder="jane@example.com">
                        @error('email')
                            <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group space-y-1.5">
                        <div class="flex justify-between items-center">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1 transition-all">Password</label>
                            <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-indigo-600 hover:underline uppercase tracking-wide">Forgot?</a>
                        </div>
                        <input type="password" name="password" required 
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 focus:bg-white transition-all outline-none" 
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center ml-1">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-[11px] font-bold text-slate-500 uppercase tracking-wider group-hover:text-slate-700 transition-colors">Remember me</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                            class="btn-primary w-full py-5 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                            <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i>
                            Sign Into Account
                        </button>
                    </div>
                </form>

                <div class="mt-10 pt-10 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        New to the sphere? 
                        <a href="{{ route('register.student') }}" class="text-indigo-600 font-bold hover:underline ml-1">Create an account</a>
                    </p>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-center gap-6">
                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <i class="fa-solid fa-shield-halved text-indigo-300"></i>
                    <span>Secure SSL</span>
                </div>
                <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <i class="fa-solid fa-lock text-indigo-300"></i>
                    <span>Encrypted</span>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-10 text-center">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.4em]">
            &copy; 2026 BrightSphere Inc. • All Rights Reserved
        </p>
    </footer>

</body>
</html>