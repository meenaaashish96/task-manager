<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TaskFlow') }} - Manage Projects</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#4F46E5', // Indigo-600
                        secondary: '#1E293B', // Slate-800
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 text-slate-800">

    <nav class="w-full bg-white/80 backdrop-blur-md border-b border-gray-100 fixed top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="bg-primary p-1.5 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-secondary">Task Manager</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-primary transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-primary transition">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">Get Started</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-secondary tracking-tight mb-6">
                Manage your work, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">master your time.</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto mb-10">
                The all-in-one workspace for your tasks, notes, and projects. Streamline your workflow and collaborate with your team effortlessly.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-primary hover:bg-indigo-700 md:text-lg md:px-10 transition shadow-lg shadow-indigo-500/30">
                        Start for free
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                @else
                     <a href="#" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-primary hover:bg-indigo-700 md:text-lg md:px-10 transition shadow-lg shadow-indigo-500/30">
                        Get Started
                    </a>
                @endif
                <a href="#features" class="inline-flex items-center justify-center px-8 py-3 border border-gray-200 text-base font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 md:text-lg md:px-10 transition">
                    Learn more
                </a>
            </div>

            <div class="mt-16 relative">
                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-t from-gray-50 to-transparent z-20 h-full"></div>
                <div class="relative bg-white rounded-xl shadow-2xl ring-1 ring-gray-900/10 p-4 max-w-5xl mx-auto transform rotate-1 hover:rotate-0 transition duration-500">
                    <div class="flex items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left opacity-90">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between mb-4"><span class="font-bold text-sm text-gray-500">TODO</span><span class="text-xs bg-gray-200 px-2 py-0.5 rounded">3</span></div>
                            <div class="bg-white p-3 rounded shadow-sm mb-3 border border-gray-100">
                                <div class="text-xs text-indigo-600 font-semibold mb-1">Design</div>
                                <div class="text-sm font-medium">Create Dashboard Mockup</div>
                            </div>
                            <div class="bg-white p-3 rounded shadow-sm border border-gray-100">
                                <div class="text-xs text-orange-600 font-semibold mb-1">Research</div>
                                <div class="text-sm font-medium">Competitor Analysis</div>
                            </div>
                        </div>
                         <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between mb-4"><span class="font-bold text-sm text-gray-500">IN PROGRESS</span><span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">2</span></div>
                            <div class="bg-white p-3 rounded shadow-sm mb-3 border border-gray-100 border-l-4 border-l-primary">
                                <div class="text-xs text-purple-600 font-semibold mb-1">Backend</div>
                                <div class="text-sm font-medium">Setup API Routes</div>
                                <div class="mt-2 flex -space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-300 border-2 border-white"></div>
                                    <div class="w-6 h-6 rounded-full bg-gray-400 border-2 border-white"></div>
                                </div>
                            </div>
                        </div>
                         <div class="bg-gray-50 p-4 rounded-lg hidden md:block">
                            <div class="flex justify-between mb-4"><span class="font-bold text-sm text-gray-500">DONE</span><span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">5</span></div>
                             <div class="bg-white p-3 rounded shadow-sm border border-gray-100 opacity-60">
                                <div class="text-xs text-gray-400 font-semibold mb-1">Marketing</div>
                                <div class="text-sm font-medium line-through text-gray-400">Social Media Plan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-secondary">Why choose TaskFlow?</h2>
                <p class="mt-4 text-gray-600">Everything you need to manage complex projects without the chaos.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4 text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-secondary mb-2">Track Progress</h3>
                    <p class="text-gray-600 leading-relaxed">Visualize your project's progress with Kanban boards and Gantt charts. Never miss a deadline again.</p>
                </div>

                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4 text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-secondary mb-2">Team Collaboration</h3>
                    <p class="text-gray-600 leading-relaxed">Assign tasks, share files, and comment in real-time. Keep everyone on the same page.</p>
                </div>

                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4 text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-secondary mb-2">Instant Efficiency</h3>
                    <p class="text-gray-600 leading-relaxed">Automate recurring tasks and integrate with your favorite tools to speed up your workflow.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 py-12 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-gray-400">© {{ date('Y') }} TaskFlow. All rights reserved.</p>
            <p class="text-gray-600 text-sm mt-2">Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
        </div>
    </footer>

</body>
</html>