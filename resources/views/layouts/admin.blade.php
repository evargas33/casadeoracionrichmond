<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($title ?? 'Admin') . ' — Casa de Oración' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Sidebar transition */
        #sidebar {
            transition: width .25s ease;
        }

        #sidebar.collapsed {
            width: 56px;
        }

        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .nav-group-label,
        #sidebar.collapsed .sidebar-brand-text,
        #sidebar.collapsed .user-info {
            display: none;
        }

        #sidebar.collapsed .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        #sidebar.collapsed .nav-item svg {
            margin: 0;
        }

        #sidebar.collapsed .nav-group-label-wrap {
            justify-content: center;
        }

        #main {
            transition: margin-left .25s ease;
        }

        /* Active nav item */
        .nav-item.active {
            background: rgba(255, 255, 255, .1);
            border-left: 3px solid #c9a84c;
        }

        .nav-item.active svg {
            color: #c9a84c;
        }

        .nav-item.active .nav-label {
            color: #fff;
        }

        /* Oculta elementos Alpine hasta que inicialice (evita flash) */
        [x-cloak] { display: none !important; }

        /* Tooltip for collapsed state */
        #sidebar.collapsed .nav-item {
            position: relative;
        }

        #sidebar.collapsed .nav-item:hover::after {
            content: attr(data-label);
            position: absolute;
            left: 56px;
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: #fff;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 4px;
            white-space: nowrap;
            z-index: 99;
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex min-h-screen">

        {{-- ═══════════════════ SIDEBAR ═══════════════════ --}}
        <aside id="sidebar" class="w-56 bg-[#1a2e4a] flex flex-col flex-shrink-0 fixed top-0 left-0 h-full z-30">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-4 h-14 border-b border-white/10 flex-shrink-0">
                <img src="{{ asset('images/download.jpeg') }}" alt="Casa de Oración"
                    class="w-8 h-8 object-contain flex-shrink-0">
                <span class="sidebar-brand-text text-white font-semibold text-sm tracking-wide truncate">
                    Casa de Oración
                </span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 space-y-0.5 px-2">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" data-label="Dashboard"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    <span class="nav-label text-sm">Dashboard</span>
                </a>

                {{-- Content group --}}
                <div class="nav-group-label-wrap flex items-center pt-4 pb-1 px-3">
                    <span
                        class="nav-group-label text-[10px] uppercase tracking-widest text-white/30 font-medium">Content</span>
                </div>

                {{-- Servicios — visible for leaders and admins --}}
                @if(auth()->user()->hasAnyRole(['superadmin','admin','pastor','lider_alabanza','lider_ujieres','lider_tecnicos']))
                <a href="{{ route('admin.services') }}" data-label="Servicios"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.services') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span class="nav-label text-sm">Servicios</span>
                </a>
                @endif

                <a href="{{ route('admin.sermons') }}" data-label="Sermons"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.sermons') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <path
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="nav-label text-sm">Sermons</span>
                </a>

                <a href="{{ route('admin.series') }}" data-label="Series"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.series') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <path
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="nav-label text-sm">Series</span>
                </a>

                <a href="{{ route('admin.events') }}" data-label="Events"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.events') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span class="nav-label text-sm">Events</span>
                </a>

                <a href="{{ route('admin.inscriptions') }}" data-label="Inscripciones"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.inscriptions') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                        <path d="M9 12h6M9 16h4" />
                    </svg>
                    <span class="nav-label text-sm">Inscripciones</span>
                </a>

                <a href="{{ route('admin.pages') }}" data-label="Pages"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.pages') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="nav-label text-sm">Pages</span>
                </a>

                {{-- Media group --}}
                <div class="nav-group-label-wrap flex items-center pt-4 pb-1 px-3">
                    <span
                        class="nav-group-label text-[10px] uppercase tracking-widest text-white/30 font-medium">Media</span>
                </div>

                <a href="{{ route('admin.media') }}" data-label="Media Library"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.media') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    <span class="nav-label text-sm">Media library</span>
                </a>

                {{-- Settings group --}}
                <div class="nav-group-label-wrap flex items-center pt-4 pb-1 px-3">
                    <span
                        class="nav-group-label text-[10px] uppercase tracking-widest text-white/30 font-medium">Settings</span>
                </div>

                <a href="{{ route('admin.users') }}" data-label="Users"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87" />
                        <path d="M16 3.13a4 4 0 010 7.75" />
                    </svg>
                    <span class="nav-label text-sm">Users</span>
                </a>

                <a href="{{ route('admin.categories') }}" data-label="Categories"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <path
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="nav-label text-sm">Categories</span>
                </a>

                <a href="{{ route('admin.settings') }}" data-label="Settings"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-md text-white/60 hover:text-white hover:bg-white/10 transition-all cursor-pointer {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.75">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="nav-label text-sm">Settings</span>
                </a>

            </nav>

            {{-- User profile --}}
            <a href="{{ route('admin.profile') }}"
                class="border-t border-white/10 p-3 flex items-center gap-3 flex-shrink-0 hover:bg-white/5 transition-colors group">
                <div class="w-8 h-8 rounded-full bg-[#c9a84c]/20 border border-[#c9a84c]/40 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-[#c9a84c] text-xs font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div class="user-info min-w-0 flex-1">
                    <p class="text-white text-xs font-medium truncate group-hover:text-[#c9a84c] transition-colors">{{ auth()->user()->name }}</p>
                    <p class="text-white/30 text-[10px] truncate">Mi perfil</p>
                </div>
            </a>

        </aside>

        {{-- ═══════════════════ MAIN ═══════════════════ --}}
        <div id="main" class="flex-1 flex flex-col min-h-screen ml-56">

            {{-- Topbar --}}
            <header class="h-14 bg-white border-b border-gray-200 flex items-center px-4 gap-4 sticky top-0 z-20">

                {{-- Toggle sidebar --}}
                <button id="sidebar-toggle" onclick="toggleSidebar()"
                    class="w-8 h-8 flex items-center justify-center rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg id="icon-collapse" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span>Admin</span>
                    @isset($title)
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                        <span class="text-gray-700 font-medium">{{ $title }}</span>
                    @endisset
                </div>

                <div class="ml-auto flex items-center gap-3">
                    {{-- View site --}}
                    <a href="{{ url('/') }}" target="_blank"
                        class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 transition-colors">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                        View site
                    </a>

                    {{-- User info + logout --}}
                    <a href="{{ route('profile.edit') }}"
                        class="text-xs text-gray-500 hover:text-gray-700 transition-colors hidden sm:block truncate max-w-[100px]">
                        {{ auth()->user()->name }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-red-500 transition-colors">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span class="hidden sm:inline">Salir</span>
                        </button>
                    </form>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-6 overflow-auto">
                {{ $slot }}
            </main>

        </div>
    </div>



    <script>
        let collapsed = localStorage.getItem('sidebar-collapsed') === 'true';

        function applyState() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            if (collapsed) {
                sidebar.classList.add('collapsed');
                sidebar.style.width = '56px';
                main.style.marginLeft = '56px';
            } else {
                sidebar.classList.remove('collapsed');
                sidebar.style.width = '224px';
                main.style.marginLeft = '224px';
            }
        }

        function toggleSidebar() {
            collapsed = !collapsed;
            localStorage.setItem('sidebar-collapsed', collapsed);
            applyState();
        }

        applyState();
    </script>
    <livewire:admin.media-picker-modal />
    @stack('scripts')
    @livewireScripts
</body>

</html>
