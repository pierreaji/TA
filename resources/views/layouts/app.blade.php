<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/assets/" data-template="vertical-menu-template-no-customizer">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>SyncMas - Stockist Management</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets') }}/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/fonts/fontawesome.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/css/rtl/theme-default.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
    {{-- <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/swiper/swiper.css" /> --}}
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/jquery-timepicker/jquery-timepicker.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/pickr/pickr-themes.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/css/pages/cards-statistics.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/css/pages/cards-analytics.css" />
    <!-- Helpers -->
    <script src="{{ asset('assets') }}/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets') }}/assets/js/config.js"></script>

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span style="color: var(--bs-primary)">
                                <svg width="268" height="150" viewBox="0 0 38 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z"
                                        fill="currentColor" />
                                    <path
                                        d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z"
                                        fill="url(#paint0_linear_2989_100980)" fill-opacity="0.4" />
                                    <path
                                        d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z"
                                        fill="currentColor" />
                                    <path
                                        d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                                        fill="currentColor" />
                                    <path
                                        d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                                        fill="url(#paint1_linear_2989_100980)" fill-opacity="0.4" />
                                    <path
                                        d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z"
                                        fill="currentColor" />
                                    <defs>
                                        <linearGradient id="paint0_linear_2989_100980" x1="5.36642" y1="0.849138"
                                            x2="10.532" y2="24.104" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-opacity="1" />
                                            <stop offset="1" stop-opacity="0" />
                                        </linearGradient>
                                        <linearGradient id="paint1_linear_2989_100980" x1="5.19475" y1="0.849139"
                                            x2="10.3357" y2="24.1155" gradientUnits="userSpaceOnUse">
                                            <stop offset="0" stop-opacity="1" />
                                            <stop offset="1" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-2">SyncMas</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                                fill="currentColor" fill-opacity="0.6" />
                            <path
                                d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                                fill="currentColor" fill-opacity="0.38" />
                        </svg>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                @php
                    $enable = Session::has('sales');
                @endphp
                <ul class="menu-inner py-1">
                    <li class="menu-item {{ url()->current() == url('') ? 'active' : '' }}">
                        <a href="{{ url('') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-home text-nowrap"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>
                    @if (Auth::user()->role == 'Admin')
                        <li class="menu-header fw-light mt-4">
                            <span class="menu-header-text">Master Data</span>
                        </li>
                        <li class="menu-item {{ Str::contains(url()->current(), 'users') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-account-multiple text-nowrap"></i>
                                <div data-i18n="Users">Users</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Str::contains(url()->current(), 'distributor') ? 'active' : '' }}">
                            <a href="{{ route('distributor.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-truck text-nowrap"></i>
                                <div data-i18n="Distributor">Distributor</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Str::contains(url()->current(), 'category') ? 'active' : '' }}">
                            <a href="{{ route('category.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-hexagon-multiple text-nowrap"></i>
                                <div data-i18n="Category">Category</div>
                            </a>
                        </li>
                        <li class="menu-item {{ url()->current() == route('items.index') ? 'active' : '' }}">
                            <a href="{{ route('items.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-food-variant text-nowrap"></i>
                                <div data-i18n="Items">Items</div>
                            </a>
                        </li>
                    @endif
                    <li class="menu-header fw-light mt-4">
                        <span class="menu-header-text">Stock Management</span>
                    </li>
                    <li class="menu-item {{ Str::contains(url()->current(), 'items/in/stock') ? 'active' : '' }}">
                        <a href="{{ route('items.in.stock') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-inbox-full text-nowrap"></i>
                            <div data-i18n="Stock Items">Stock Items</div>
                        </a>
                    </li>
                    @if (Auth::user()->role == 'Admin')
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'items/in/index') ? 'active' : '' }}">
                            <a href="{{ route('items.in.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-inbox-arrow-down text-nowrap"></i>
                                <div data-i18n="Stock In">Stock In</div>
                            </a>
                        </li>
                    @endif
                    <li class="menu-header fw-light mt-4">
                        <span class="menu-header-text">Transaction</span>
                    </li>
                    @if (Auth::user()->role == 'Admin')
                        <li class="menu-item {{ Str::contains(url()->current(), 'items/assign') ? 'active' : '' }}">
                            <a href="{{ route('items.assign.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-clipboard-text text-nowrap"></i>
                                <div data-i18n="Item Assign">Item Assign</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'items/warehouse') ? 'active' : '' }}">
                            <a href="{{ route('items.warehouse.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-view-dashboard text-nowrap"></i>
                                <div data-i18n="Item Request">Item Request</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/return/index') ? 'active' : '' }}">
                            <a href="{{ route('sales.return.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-cash-refund text-nowrap"></i>
                                <div data-i18n="Sales Return">Sales Return</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/transaction/history') ? 'active' : '' }}">
                            <a href="{{ route('sales.transaction.history') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-database text-nowrap"></i>
                                <div data-i18n="Data Transaction">Data Transaction</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->role == 'Sales')
                        <li class="menu-item {{ Str::contains(url()->current(), 'items/request') ? 'active' : '' }}">
                            <a href="{{ route('items.request.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-view-dashboard text-nowrap"></i>
                                <div data-i18n="Item Request">Item Request</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/transaction/index') ? 'active' : '' }}">
                            <a href="{{ route('sales.transaction.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-ballot-outline text-nowrap"></i>
                                <div data-i18n="Sales Transaction">Sales Transaction</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/return/sales') ? 'active' : '' }}">
                            <a href="{{ route('sales.return.sales') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-cash-refund text-nowrap"></i>
                                <div data-i18n="Sales Return">Sales Return</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->role == 'Warehouse')
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'items/warehouse') ? 'active' : '' }}">
                            <a href="{{ route('items.warehouse.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-view-dashboard text-nowrap"></i>
                                <div data-i18n="Item Warehouse">Item Warehouse</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->role == 'Sales')
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'items/warehouse') ? 'active' : '' }}">
                            <a href="{{ route('items.warehouse.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-view-dashboard text-nowrap"></i>
                                <div data-i18n="Item Warehouse">Item Warehouse</div>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role == 'Admin')
                        <li class="menu-header fw-light mt-4">
                            <span class="menu-header-text">History</span>
                        </li>
                        <li class="menu-item {{ Str::contains(url()->current(), 'sales/tracking') ? 'active' : '' }}">
                            <a href="{{ route('sales.tracking.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-crosshairs-gps text-nowrap"></i>
                                <div data-i18n="Sales Tracking">Sales Tracking</div>
                            </a>
                        </li>
                        {{-- <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/return/history') ? 'active' : '' }}">
                            <a href="{{ route('sales.return.history') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-file-document text-nowrap"></i>
                                <div data-i18n="Sales Return">Sales Return</div>
                            </a>
                        </li> --}}
                        {{-- <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/transaction/admin-history') ? 'active' : '' }}">
                            <a href="{{ route('sales.transaction.historyAdmin') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-ballot-outline text-nowrap"></i>
                                <div data-i18n="Sales Transaction">Sales Transaction</div>
                            </a>
                        </li> --}}
                    @endif
                    @if (Auth::user()->role == 'Sales')
                        {{-- <li class="menu-header fw-light mt-4">
                            <span class="menu-header-text">History</span>
                        </li> --}}
                        {{-- <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/transaction/history') ? 'active' : '' }}">
                            <a href="{{ route('sales.transaction.history') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-ballot-outline text-nowrap"></i>
                                <div data-i18n="Sales Transaction">Sales Transaction</div>
                            </a>
                        </li> --}}
                        {{-- <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/return/history') ? 'active' : '' }}">
                            <a href="{{ route('sales.return.history') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-ballot-outline text-nowrap"></i>
                                <div data-i18n="Sales Return">Sales Return</div>
                            </a>
                        </li> --}}
                    @endif


                    {{-- <li
                        class="menu-item {{ Str::contains(url()->current(), 'sales/return/history') ? 'active' : '' }}">
                        <a href="{{ route('sales.return.history') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-cash-refund text-nowrap"></i>
                            <div data-i18n="Sales Return">Sales Return</div>
                        </a>
                    </li> --}}
                    @if (Auth::user()->role != 'Warehouse')
                    <li class="menu-header fw-light mt-4">
                        <span class="menu-header-text">Report</span>
                    </li>
                    @endif
                    @if (Auth::user()->role == 'Admin')
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'report/profit/index') ? 'active' : '' }}">
                            <a href="{{ route('report.profit.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-currency-usd text-nowrap"></i>
                                <div data-i18n="Profit">Profit</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'report/sales/received') ? 'active' : '' }}">
                            <a href="{{ route('report.sales.received') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-view-headline text-nowrap"></i>
                                <div data-i18n="Received Items">Received Items</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'report/in/index') ? 'active' : '' }}">
                            <a href="{{ route('report.in.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-clipboard-arrow-down text-nowrap"></i>
                                <div data-i18n="In Items">In Items</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/transaction/sale-history') ? 'active' : '' }}">
                            <a href="{{ route('sales.transaction.historySale') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-shopping text-nowrap"></i>
                                <div data-i18n="Sale">Sale</div>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->role == 'Sales')
                        {{-- <li
                            class="menu-item {{ Str::contains(url()->current(), 'report/sales/index') ? 'active' : '' }}">
                            <a href="{{ route('report.sales.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-cash text-nowrap"></i>
                                <div data-i18n="Sale">Sale</div>
                            </a>
                        </li> --}}
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'sales/transaction/sale-history') ? 'active' : '' }}">
                            <a href="{{ route('sales.transaction.historySale') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-shopping text-nowrap"></i>
                                <div data-i18n="Sale">Sale</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Str::contains(url()->current(), 'report/sales/received') ? 'active' : '' }}">
                            <a href="{{ route('report.sales.received') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-cash text-nowrap"></i>
                                <div data-i18n="Received Items">Received Items</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="mdi mdi-menu mdi-24px"></i>
                        </a>
                    </div>
                    <div class="w-100">
                        <h6 class="my-auto">{{ Auth::user()->name }}</h6>
                        <h6 class="my-auto text-muted">{{ Auth::user()->role }}
                            {{ Auth::user()->Sales ? '- ' . Auth::user()->Sales->type : '' }}</h6>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- Style Switcher -->
                            <li class="nav-item me-1 me-xl-0">
                                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon style-switcher-toggle hide-arrow"
                                    href="javascript:void(0);">
                                    <i class="mdi mdi-24px"></i>
                                </a>
                            </li>
                            <!--/ Style Switcher -->

                            <!-- Notification -->
                            @php
                                if(Auth::user()->role == 'Warehouse') {
                                    $notifications = \App\Models\Notification::where('is_read', 0)
                                        ->where('title', 'LIKE', '%Assign Item from Admin%')
                                        ->where('text', 'LIKE', '%review%')
                                        ->get();
                                } else if (Auth::user()->role == 'Admin') {
                                    $notifications = \App\Models\Notification::where('is_read', 0)
                                        ->where('id_user', 0)
                                        ->where('title', 'NOT LIKE', '%Admin%')
                                        ->get();
                                    
                                } else {
                                    $notifications = \App\Models\Notification::where('is_read', 0)->where('id_user', Auth::user()->id)->get();
                                }
                            @endphp
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                    href="javascript:void(0);" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="mdi mdi-bell-outline mdi-24px"></i>
                                    @if (count($notifications) > 0)
                                        <span
                                        class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Notification</h6>
                                        </div>
                                    </li>
                                    @foreach ($notifications as $item)
                                        @php
                                            if(Auth::user()->role == 'Warehouse') {
                                                $title = 'items/warehouse/index';
                                            } else if (Auth::user()->role == 'Admin') {
                                                if(Str::contains($item->title, 'assign')) {
                                                    $title = 'items/assign/index';
                                                } else if(Str::contains($item->title, 'Request')) {
                                                    $title = 'items/warehouse/index';
                                                } else if(Str::contains($item->title, 'Return Transaction')) {
                                                    $title = 'sales/return/index';
                                                } else {
                                                    $title = 'users/sales/' .$item->target;
                                                }
                                            } else {
                                                $title = 'users/sales/' .$item->id_user;
                                                if(Str::contains($item->title, 'assign')) {
                                                    $title = 'items/warehouse/index';
                                                }
                                            }
                                        @endphp
                                        <li class="dropdown-notifications-list scrollable-container">
                                            <ul class="list-group list-group-flush">
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <a
                                                        href="{{ url($title) }}?notif={{ $item->id }}">
                                                        <div class="d-flex gap-2">
                                                            <div
                                                                class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                                <h6 class="mb-1 text-truncate">{{ $item->title }}
                                                                </h6>
                                                                <small
                                                                    class="text-truncate text-body">{{ $item->text }}</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endforeach
                                    {{-- <li class="dropdown-menu-footer border-top p-2">
                                    <a href="javascript:void(0);"
                                        class="btn btn-primary d-flex justify-content-center">
                                        View all notifications
                                    </a>
                                </li> --}}
                                </ul>
                            </li>
                            <!--/ Notification -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets') }}/assets/img/avatars/1.png" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('assets') }}/assets/img/avatars/1.png" alt
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                                    <small class="text-muted">{{ Auth::user()->role }}
                                                        {{ Auth::user()->role == 'Sales' ? '- ' . Auth::user()?->Sales?->type : '' }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    @if (in_array(Auth::user()->role, ['Sales']))
                                        
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ url('users/sales') }}/{{ Auth::user()->id }}">
                                                <i class="mdi mdi-user me-2"></i>
                                                <span class="align-middle">Sales Documents</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ url('profile') }}">
                                            <i class="mdi mdi-user me-2"></i>
                                            <span class="align-middle">Change Password</span>
                                        </a>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" href="#" form="form-logout">
                                            <i class="mdi mdi-logout me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <form id="form-logout" action="{{ route('logout') }}" method="post" class="d-none">
                    @csrf
                </form>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row gy-4">
                            {{-- <div class="card">
                                <div class="card-body"> --}}

                            @yield('contents')
                            {{-- </div>
                            </div> --}}
                            <!-- Activity Timeline -->
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , SyncMas <span class="text-danger">-</span>
                                    <a href="https://pixinvent.com" target="_blank"
                                        class="footer-link fw-medium">PD Senyuman Ikan Mas</a>
                                </div>
                                {{-- <div>
                                    <a href="https://themeforest.net/licenses/standard" class="footer-link me-4"
                                        target="_blank">License</a>
                                    <a href="https://1.envato.market/pixinvent_portfolio" target="_blank"
                                        class="footer-link me-4">More Themes</a>

                                    <a href="https://demos.pixinvent.com/materialize-html-admin-template/documentation/"
                                        target="_blank" class="footer-link me-4">Documentation</a>

                                    <a href="https://pixinvent.ticksy.com/" target="_blank"
                                        class="footer-link d-none d-sm-inline-block">Support</a>
                                </div> --}}
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{ asset('assets') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="{{ asset('assets') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
    {{-- <script src="{{ asset('assets') }}/assets/vendor/libs/swiper/swiper.js"></script> --}}
    <script src="{{ asset('assets') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets') }}/assets/js/main.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js">
    </script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js"></script>
    <script src="{{ asset('assets') }}/assets/vendor/libs/pickr/pickr.js"></script>
    <script src="{{ asset('assets') }}/assets/js/forms-pickers.js"></script>

    <!-- Page JS -->
    {{-- <script src="{{ asset('assets') }}/assets/js/dashboards-analytics.js"></script> --}}
    {{-- <script src="{{ asset('assets') }}/assets/js/tables-datatables-basic.js"></script> --}}
    <script>
        function formatRupiah(angka, prefix) {
            if (angka == null) return angka;
            var angka = angka.toString();
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                separator = '',
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        $(document).on('keyup', '.form-rupiah', function() {
            $(this).val(formatRupiah($(this).val()))
        })
    </script>
    @yield('scripts')
    <script>
        $('.dt-row-grouping').DataTable({
            "lengthMenu": [50, 75, 100],
            "pageLength": 50
        })

        $('.select2').select2()
        $('.select2-modal').select2({
            dropdownParent: $("#Medium-modal")
        })
        // setTimeout(() => {
        //   $('.alert').remove()
        // }, 5000);

        @if (Session::get('alert') != null)
            Swal.fire({
                icon: "{{ Session::get('alert') }}",
                title: "{{ Session::get('title') }}",
                text: "{{ Session::get('message') }}",
                customClass: {
                    confirmButton: 'btn btn-success waves-effect'
                }
            });
        @endif

        $('.flatpickr-input').flatpickr({
            "mode": 'range',
            "maxDate": "{{ date('Y-m-d', strtotime('+7 day')) }}"
        });
    </script>

    <script>
        function storeMap(position) {
            $.ajax({
                url: "{{ route('sales.tracking.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    lat: position.coords.latitude,
                    long: position.coords.longitude
                }
            })
        }

        function getLocation() {
            if (navigator.geolocation) {
                // setInterval(() => {
                navigator.geolocation.getCurrentPosition(storeMap, function() {

                    Swal.fire({
                        icon: "error",
                        title: "Warnnig",
                        text: "Please allow your location!",
                        customClass: {
                            confirmButton: 'btn btn-success waves-effect'
                        }
                    });
                });
                // }, 1000);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        if ("{{ Auth::user()->role }}" == "Sales") {
            getLocation()
            setInterval(() => {
                getLocation()
            }, 10000);
        }
        if("{{ $enable }}" != '') {
            $('.menu-inner a').attr('href', '#')
        }
    </script>
</body>

</html>
