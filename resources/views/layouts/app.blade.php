<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link href="{{ asset('scripts/DataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @guest
                        @else
                            @if (Auth::user()->userReference->is_admin)
                                <li class="nav-item dropdown px-2">
                                    <a class="nav-link dropdown-toggle {{ !empty($nav) && in_array($nav, ['users', 'users_deleted']) ? 'active' : '' }}" href="#" id="navbarUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Users
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarUsers">
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'users' ? 'active' : '' }}" href="{{ route('admin.users') }}">List</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'users_deleted' ? 'active' : '' }}" href="{{ route('admin.users').'?deleted=1' }}">Deleted</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown px-2">
                                    <a class="nav-link dropdown-toggle {{ !empty($nav) && in_array($nav, ['properties', 'properties_disposed', 'property_categories']) ? 'active' : '' }}" href="#" id="navbarUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Properties
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarUsers">
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'properties' ? 'active' : '' }}" href="{{ route('admin.properties') }}">List</a></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'properties_disposed' ? 'active' : '' }}" href="{{ route('admin.properties').'?disposed=1' }}">Disposed</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'property_categories' ? 'active' : '' }}" href="{{ route('admin.property_categories') }}">Account Titles</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown px-2">
                                    <a class="nav-link dropdown-toggle {{ !empty($nav) && in_array($nav, ['borrow_pending', 'borrow_borrowed', 'borrow_history', 'borrow_rejected']) ? 'active' : '' }}" href="#" id="navbarUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Borrow
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarUsers">
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'borrow_pending' ? 'active' : '' }}" href="{{ route('admin.borrow.pending') }}">Pending</a></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'borrow_borrowed' ? 'active' : '' }}" href="{{ route('admin.borrow.borrowed') }}">To Return</a></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'borrow_history' ? 'active' : '' }}" href="{{ route('admin.borrow.history') }}">History</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'borrow_rejected' ? 'active' : '' }}" href="{{ route('admin.borrow.rejected') }}">Rejected</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown px-2">
                                    <a class="nav-link dropdown-toggle {{ !empty($nav) && in_array($nav, ['purchase_pending', 'purchase_history', 'purchase_rejected']) ? 'active' : '' }}" href="#" id="navbarUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Purchase
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarUsers">
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'purchase_pending' ? 'active' : '' }}" href="{{ route('admin.purchase.pending') }}">Pending</a></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'purchase_history' ? 'active' : '' }}" href="{{ route('admin.purchase.history') }}">History</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'purchase_rejected' ? 'active' : '' }}" href="{{ route('admin.purchase.rejected') }}">Rejected</a></li>
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item dropdown px-2">
                                    <a class="nav-link dropdown-toggle {{ !empty($nav) && in_array($nav, ['properties']) ? 'active' : '' }}" href="#" id="navbarUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Properties
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarUsers">
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'properties' ? 'active' : '' }}" href="{{ route('guest.properties') }}">List</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown px-2">
                                    <a class="nav-link dropdown-toggle {{ !empty($nav) && in_array($nav, ['borrow_pending', 'borrow_history']) ? 'active' : '' }}" href="#" id="navbarUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Borrow
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarUsers">
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'borrow_pending' ? 'active' : '' }}" href="{{ route('guest.borrow.pending') }}">Pending</a></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'borrow_history' ? 'active' : '' }}" href="{{ route('guest.borrow.history') }}">History</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown px-2">
                                    <a class="nav-link dropdown-toggle {{ !empty($nav) && in_array($nav, ['purchase_pending', 'purchase_history']) ? 'active' : '' }}" href="#" id="navbarUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Purchase
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarUsers">
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'purchase_pending' ? 'active' : '' }}" href="{{ route('guest.purchase.pending') }}">Pending</a></li>
                                        <li><a class="dropdown-item {{ !empty($nav) && $nav == 'purchase_history' ? 'active' : '' }}" href="{{ route('guest.purchase.history') }}">History</a></li>
                                    </ul>
                                </li>
                            @endif
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->userReference->first_name.' '.Auth::user()->userReference->last_name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <span class="dropdown-item-text">
                                        <p>
                                            <code>Role</code><br>
                                            {{ Auth::user()->userReference->is_super ? 'Super ' : '' }}{{ Auth::user()->userReference->is_admin ? 'Admin' : 'Guest' }}
                                        </p>
                                        <p>
                                            <code>School</code><br>
                                            {{ Auth::user()->userReference->school->tag }}
                                        </p>
                                    </span>
                                    <hr class="dropdown-divider">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @include('layouts.notif')
            </div>
            @yield('content')
        </main>
    </div>

    @include('layouts.scripts')
</body>
</html>
