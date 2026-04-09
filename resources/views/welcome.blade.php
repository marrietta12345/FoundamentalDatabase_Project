<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrightSphere • Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f8fafc;
            overflow-x: hidden;
        }

        /* Animated background mesh */
        .color-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.1) 0px, transparent 50%);
        }

        .soft-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .soft-card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.15);
        }

        .icon-box {
            position: relative;
            z-index: 1;
        }

        .icon-box::after {
            content: '';
            position: absolute;
            inset: -8px;
            background: currentColor;
            opacity: 0.1;
            border-radius: 1rem;
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <div class="color-bg"></div>

    <nav class="w-full max-w-5xl mx-auto px-6 py-12 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-purple-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-900 uppercase">BrightSphere</span>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="max-w-4xl w-full">
            
            <div class="mb-12 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                    Ready to <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-500">upgrade</span> your learning?
                </h1>
                <p class="text-slate-500 font-medium text-lg">Pick your path to enter the BrightSphere.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                
                <a href="{{ route('register.student') }}" class="soft-card p-10 rounded-[2.5rem] flex flex-col items-start group">
                    <div class="icon-box text-indigo-600 mb-8 transition-transform group-hover:scale-110 duration-300">
                        <i class="fa-solid fa-user-astronaut text-3xl"></i>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">New Student</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10">
                        Create a unique profile, unlock personalized AI tutoring, and join the global classroom.
                    </p>
                    
                    <div class="mt-auto px-6 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-indigo-200 group-hover:bg-indigo-700 transition-all">
                        Register Now
                    </div>
                </a>

                <a href="{{ route('login') }}" class="soft-card p-10 rounded-[2.5rem] flex flex-col items-start group">
                    <div class="icon-box text-purple-500 mb-8 transition-transform group-hover:scale-110 duration-300">
                        <i class="fa-solid fa-fingerprint text-3xl"></i>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Portal Login</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10">
                        Welcome back! Resume your courses, check your grades, and sync with your mentors.
                    </p>
                    
                    <div class="mt-auto px-6 py-3 border-2 border-slate-900 text-slate-900 rounded-2xl text-xs font-bold uppercase tracking-widest group-hover:bg-slate-900 group-hover:text-white transition-all">
                        Sign In
                    </div>
                </a>

            </div>

        </div>
    </main>

    <footer class="p-12">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">© 2026 BrightSphere. All rights reserved.</p>
            <div class="flex gap-4">
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-indigo-600 transition shadow-sm border border-slate-100"><i class="fa-brands fa-twitter"></i></div>
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-indigo-600 transition shadow-sm border border-slate-100"><i class="fa-brands fa-discord"></i></div>
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-indigo-600 transition shadow-sm border border-slate-100"><i class="fa-brands fa-github"></i></div>
            </div>
        </div>
    </footer>

</body>
</html>