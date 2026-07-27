<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Raangalay</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/ico.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo/ico.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}" class="block py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    Categories
                </a>
                <a href="{{ route('admin.banners.index') }}" class="block py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.banners.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    Banners
                </a>
                <a href="{{ route('admin.products.index') }}" class="block py-3 px-6 hover:bg-gray-800 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    Products
                </a>
                <hr class="border-gray-700 my-4">
                <a href="{{ route('home') }}" class="block py-3 px-6 hover:bg-gray-800">
                    View Site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left py-3 px-6 hover:bg-gray-800 text-red-400">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ auth()->user()->name }}</span>
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
