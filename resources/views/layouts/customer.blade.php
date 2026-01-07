<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="JBON STORE - Your Shopping Destination">
    <meta name="author" content="">

    <title>@yield('title', 'JBON STORE')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        html, body {
            height: 100%;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.15);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        .search-form {
            margin: 0 auto;
            max-width: 400px;
        }

        .search-form input {
            border-radius: 20px;
            border: none;
            padding: 0.5rem 1rem;
        }

        .cart-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
        }

        /* Product Card Styles */
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0.15rem 0.75rem 0 rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.75rem 0 rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #f0f0f0;
        }

        .product-body {
            padding: 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            text-decoration: none;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-name:hover {
            color: var(--primary-color);
        }

        .product-category {
            font-size: 0.85rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .product-stock {
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .stock-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .stock-in {
            background-color: #d4edda;
            color: #155724;
        }

        .stock-low {
            background-color: #fff3cd;
            color: #856404;
        }

        .stock-out {
            background-color: #f8d7da;
            color: #721c24;
        }

        .product-actions {
            margin-top: auto;
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm-custom {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        /* Sidebar Styles */
        .sidebar-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 0.15rem 0.75rem 0 rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .sidebar-title {
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-item {
            padding: 0.25rem 0;
        }

        .category-link {
            color: #333;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
            display: block;
            padding: 0.35rem 0;
        }

        .category-link:hover,
        .category-link.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin: 2rem 0;
        }

        .page-link {
            color: var(--primary-color);
            border: 1px solid #dee2e6;
        }

        .page-link:hover {
            color: #fff;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            padding: 0.75rem 0;
            margin-top: 2rem;
            text-align: center;
        }

        .footer p {
            margin: 0;
            font-size: 0.85rem;
        }

        /* Container */
        .container-custom {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            background-color: white;
            padding: 1rem 0;
            box-shadow: 0 0.15rem 0.75rem 0 rgba(0, 0, 0, 0.05);
            border-radius: 5px;
        }

        .breadcrumb-item.active {
            color: var(--secondary-color);
        }

        /* Alert Styles */
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-form {
                margin-top: 1rem;
                max-width: 100%;
            }

            .product-card {
                margin-bottom: 1rem;
            }

            .sidebar-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    @stack('css')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-shopping-cart"></i> JBON STORE
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <form method="GET" action="{{ route('home') }}" class="search-form mx-auto">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                            value="{{ request('search') }}">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto">
                    @if (auth()->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('my-orders') }}">
                                <i class="fas fa-box"></i> Pesanan Saya
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Daftar
                            </a>
                        </li>
                    @endunless
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.view') }}">
                            <div class="cart-icon-wrapper">
                                <i class="fas fa-shopping-cart"></i>
                                @php $cartCount = count(session('cart', [])); @endphp
                                @if($cartCount > 0)
                                    <span class="cart-badge">{{ $cartCount }}</span>
                                @endif
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid container-custom py-4" style="flex: 1;">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi Kesalahan!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p><strong>JBON STORE</strong> - Belanja online terpercaya | made by Alvin & HajidGantenk</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

    @stack('js')
</body>

</html>
