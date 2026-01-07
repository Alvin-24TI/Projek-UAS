<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .collapse-inner .collapse-item {
            color: #fff !important;
            font-weight: 500;
        }

        .collapse-inner .collapse-item:hover {
            color: #e3e6f0 !important;
            background-color: #1e5ba8 !important;
            border-radius: 0.25rem;
        }

        .collapse-inner .collapse-item.active {
            color: #fff !important;
            background-color: #1e5ba8 !important;
            font-weight: 600;
        }
    </style>

    @stack('css')

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/dashboard') }}">
                <i class="fas fa-fw fa-shopping-cart" style="font-size: 1.5rem;"></i>
                <div class="sidebar-brand-text mx-3">JBON STORE</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Management
            </div>

            <!-- Nav Item - Products (Admin Only) -->
            @if(auth()->check() && auth()->user()->isAdmin())
            <li class="nav-item {{ request()->is('admin/products*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            @endif

            <!-- Nav Item - Orders (Admin & Staff) -->
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff()))
            <li class="nav-item {{ request()->is('admin/orders*', 'staff/orders*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ auth()->user()->isAdmin() ? route('admin.orders.index') : route('staff.orders.index') }}">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            @endif

            <!-- Nav Item - Customers (Admin & Staff) -->
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff()))
            <li class="nav-item {{ request()->is('admin/customers*', 'staff/customers*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ auth()->user()->isAdmin() ? route('admin.customers.index') : route('staff.customers.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            @endif

            <!-- Nav Item - Categories (Admin Only) -->
            @if(auth()->check() && auth()->user()->isAdmin())
            <li class="nav-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-fw fa-list"></i>
                    <span>Categories</span>
                </a>
            </li>
            @endif

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? 'User' }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                @auth
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                @else
                                    <a class="dropdown-item" href="{{ url('/login') }}">
                                        <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Login
                                    </a>
                                @endauth
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>made by Alvin & HajidGantenk</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    @stack('js')

</body>

</html>
