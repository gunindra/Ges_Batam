@extends('layout.app')

@section('content')
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <!-- Logo Sidebar -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <div class="bukuslogo py-2 px-3" style="background-color: white; border-radius: 10px;">
                        <img src="{{ asset('RuangAdmin/img/logo/logo4.png') }}">
                    </div>
                </div>
            </a>

            <hr class="sidebar-divider my-0">

            <!-- Menu Dashboard -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'customer', 'driver']))
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endif

            <!-- Menu Tracking -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                <li class="nav-item {{ request()->routeIs('tracking') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('tracking') }}">
                        <i class="fas fa-globe-asia"></i>
                        <span>Tracking</span>
                    </a>
                </li>
            @endif

            <!-- Menu Driver -->
            @if (in_array(Auth::user()->role, ['superadmin', 'driver']))
                <li class="nav-item {{ request()->routeIs('supir') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('supir') }}">
                        <i class="fas fa-truck-loading"></i>
                        <span>Driver</span>
                    </a>
                </li>
            @endif

            <!-- Menu Top Up -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                <li class="nav-item {{ request()->routeIs('topuppage') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('topuppage') }}">
                        <i class="fas fa-wallet"></i>
                        <span>Top Up</span>
                    </a>
                </li>
            @endif

            <!-- Menu Customer -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                <li
                    class="nav-item {{ request()->routeIs('invoice') || request()->routeIs('delivery') || request()->routeIs('payment') || request()->routeIs('creditnote') ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#customerMenu"
                        aria-expanded="true" aria-controls="collapseBootstrap">
                        <i class="fas fa-user"></i>
                        <span>Customer</span>
                    </a>
                    <div id="customerMenu" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Customer</h6>
                            <a class="collapse-item {{ request()->routeIs('invoice') ? 'active' : '' }}"
                                href="{{ route('invoice') }}">Invoice</a>
                            <a class="collapse-item {{ request()->routeIs('delivery') ? 'active' : '' }}"
                                href="{{ route('delivery') }}">Delivery / Pickup</a>
                            <a class="collapse-item {{ request()->routeIs('payment') ? 'active' : '' }}"
                                href="{{ route('payment') }}">Payment</a>
                            <a class="collapse-item {{ request()->routeIs('creditnote') ? 'active' : '' }}"
                                href="{{ route('creditnote') }}">Credit Note</a>
                        </div>
                    </div>
                </li>
            @endif

            <!-- Menu Accounting (hanya superadmin) -->
            @if (Auth::user()->role === 'superadmin')
                <li
                    class="nav-item {{ request()->routeIs('coa') || request()->routeIs('journal') || request()->routeIs('accountingSetting') ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#listMenuAccounting"
                        aria-expanded="true" aria-controls="listMenuAccounting">
                        <i class="fas fa-money-bill-wave-alt"></i>
                        <span>Accounting</span>
                    </a>
                    <div id="listMenuAccounting" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Accounting</h6>
                            <a class="collapse-item {{ request()->routeIs('coa') ? 'active' : '' }}"
                                href="{{ route('coa') }}">COA</a>
                            <a class="collapse-item {{ request()->routeIs('journal') ? 'active' : '' }}"
                                href="{{ route('journal') }}">Journal</a>
                            <a class="collapse-item {{ request()->routeIs('accountingSetting') ? 'active' : '' }}"
                                href="{{ route('accountingSetting') }}">Accounting Setting</a>
                        </div>
                    </div>
                </li>
            @endif

            <!-- Menu Report (hanya superadmin) -->
            @if (Auth::user()->role === 'superadmin')
                <li class="nav-item {{ request()->routeIs('profitloss') || request()->routeIs('ledger') || request()->routeIs('equity')  || request()->routeIs('balance') || request()->routeIs('cashflow') ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#listreport"
                        aria-expanded="true" aria-controls="listreport">
                        <i class="fas fa-file-alt fa-lg"></i>
                        <span>Report</span>
                    </a>
                    <div id="listreport" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Report</h6>
                            <a class="collapse-item {{ request()->routeIs('profitloss') ? 'active' : '' }}"
                                href="{{ route('profitloss') }}">Profit/Loss</a>
                            <a class="collapse-item {{ request()->routeIs('ledger') ? 'active' : '' }}"
                                href="{{ route('ledger') }}">Ledger</a>
                            <a class="collapse-item {{ request()->routeIs('equity') ? 'active' : '' }}"
                                href="{{ route('equity') }}">Equity</a>
                            <a class="collapse-item {{ request()->routeIs('balance') ? 'active' : '' }}"
                                href="{{ route('balance') }}">Balance</a>
                            <a class="collapse-item {{ request()->routeIs('cashflow') ? 'active' : '' }}"
                                href="{{ route('cashflow') }}">CashFlow</a>
                        </div>
                    </div>
                </li>
            @endif

            <!-- Menu Vendor -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                <li class="nav-item {{ request()->routeIs('supplierInvoice') || request()->routeIs('purchasePayment') || request()->routeIs('debitnote')? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                        data-target="#collapseBootstrapsVendor" aria-expanded="true"
                        aria-controls="collapseBootstrapsVendor">
                        <i class="fas fa-truck"></i>
                        <span>Vendor</span>
                    </a>
                    <div id="collapseBootstrapsVendor" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Vendor</h6>
                            <a class="collapse-item {{ request()->routeIs('supplierInvoice') ? 'active' : '' }}"
                                href="{{ route('supplierInvoice') }}">Invoice</a>
                            <a class="collapse-item {{ request()->routeIs('purchasePayment') ? 'active' : '' }}"
                                href="{{ route('purchasePayment') }}">Payment</a>
                            <a class="collapse-item {{ request()->routeIs('debitnote') ? 'active' : '' }}"
                                href="{{ route('debitnote') }}">Debit Note</a>
                        </div>
                    </div>
                </li>
            @endif

            <!-- Master Data (Hanya Superadmin) -->
            @if (Auth::user()->role === 'superadmin')
            <li class="nav-item {{ request()->routeIs('costumer') || request()->routeIs('driver') || request()->routeIs('rekening') || request()->routeIs('pembagirate') || request()->routeIs('category') || request()->routeIs('user') || request()->routeIs('vendor') ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
                        aria-expanded="true" aria-controls="collapseBootstrap">
                        <i class="far fa-fw fa-window-maximize"></i>
                        <span>Master Data</span>
                    </a>
                    <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Master Data</h6>
                            <a class="collapse-item {{ request()->routeIs('costumer') ? 'active' : '' }}"
                                href="{{ route('costumer') }}">Costumers</a>
                            <a class="collapse-item {{ request()->routeIs('driver') ? 'active' : '' }}"
                                href="{{ route('driver') }}">Driver</a>
                            <a class="collapse-item {{ request()->routeIs('rekening') ? 'active' : '' }}"
                                href="{{ route('rekening') }}">Rekening</a>
                            <a class="collapse-item {{ request()->routeIs('pembagirate') ? 'active' : '' }}"
                                href="{{ route('pembagirate') }}">Rate</a>
                            <a class="collapse-item {{ request()->routeIs('category') ? 'active' : '' }}"
                                href="{{ route('category') }}">Category</a>
                            {{-- <a class="collapse-item {{ request()->routeIs('role') ? 'active' : '' }}"
                                href="{{ route('role') }}">Role</a> --}}
                            <a class="collapse-item {{ request()->routeIs('user') ? 'active' : '' }}"
                                href="{{ route('user') }}">User</a>
                            <a class="collapse-item {{ request()->routeIs('vendor') ? 'active' : '' }}"
                                href="{{ route('vendor') }}">Vendor</a>
                        </div>
                    </div>
                </li>
            @endif
        </ul>


        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        {{-- <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                            aria-labelledby="searchDropdown">
                            <form class="navbar-search">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-1 small"
                                        placeholder="What do you want to look for?" aria-label="Search"
                                        aria-describedby="basic-addon2" style="border-color: #3f51b5;">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li> --}}
                        {{-- <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <span class="badge badge-danger badge-counter">3+</span>
                        </a>
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">
                                Alerts Center
                            </h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 12, 2019</div>
                                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-donate text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 7, 2019</div>
                                    $290.29 has been deposited into your account!
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-warning">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">December 2, 2019</div>
                                    Spending Alert: We've noticed unusually high spending for your account.
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Show All
                                Alerts</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-envelope fa-fw"></i>
                            <span class="badge badge-warning badge-counter">2</span>
                        </a>
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">
                                Message Center
                            </h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="{{ asset('RuangAdmin/img/man.png') }}"
                                        style="max-width: 60px" alt="">
                                    <div class="status-indicator bg-success"></div>
                                </div>
                                <div class="font-weight-bold">
                                    <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                        problem
                                        I've been
                                        having.</div>
                                    <div class="small text-gray-500">Udin Cilok · 58m</div>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="{{ asset('RuangAdmin/img/girl.png') }}"
                                        style="max-width: 60px" alt="">
                                    <div class="status-indicator bg-default"></div>
                                </div>
                                <div>
                                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                        told me
                                        that people
                                        say this to all dogs, even if they aren't good...</div>
                                    <div class="small text-gray-500">Jaenab · 2w</div>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Read More
                                Messages</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-tasks fa-fw"></i>
                            <span class="badge badge-success badge-counter">3</span>
                        </a>
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="messagesDropdown">
                            <h6 class="dropdown-header">
                                Task
                            </h6>
                            <a class="dropdown-item align-items-center" href="#">
                                <div class="mb-3">
                                    <div class="small text-gray-500">Design Button
                                        <div class="small float-right"><b>50%</b></div>
                                    </div>
                                    <div class="progress" style="height: 12px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%"
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item align-items-center" href="#">
                                <div class="mb-3">
                                    <div class="small text-gray-500">Make Beautiful Transitions
                                        <div class="small float-right"><b>30%</b></div>
                                    </div>
                                    <div class="progress" style="height: 12px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 30%"
                                            aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item align-items-center" href="#">
                                <div class="mb-3">
                                    <div class="small text-gray-500">Create Pie Chart
                                        <div class="small float-right"><b>75%</b></div>
                                    </div>
                                    <div class="progress" style="height: 12px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 75%"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">View All Taks</a>
                        </div>
                    </li> --}}
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile rounded-circle" src="{{ asset('RuangAdmin/img/boy.png') }}"
                                    style="max-width: 60px">

                                @if (Auth::check())
                                    <span class="ml-2 d-none d-lg-inline text-white small">{{ Auth::user()->name }}</span>
                                @else
                                    <span class="ml-2 d-none d-lg-inline text-white small">{{ $username }}</span>
                                @endif
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                {{-- <a class="dropdown-item" href="#">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a> --}}
                                <a class="dropdown-item" href="{{ route('verification.notice') }}">
                                    <i class="fas fa-envelope fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Verify Account
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- Topbar -->

                <!-- Modal Logout -->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabelLogout" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabelLogout">Ohh No!</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to logout?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary"
                                    data-dismiss="modal">Cancel</button>
                                <!-- Form untuk logout dengan metode POST -->
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                @yield('main')

            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script> - developed by
                            {{-- <b><a href="https://indrijunanda.gitlab.io/" target="_blank">indrijunanda</a></b> --}}
                            <b>SmartAppCare</b>
                        </span>
                    </div>
                </div>
            </footer>
            <!-- Footer -->
        </div>
    </div>
@endsection
