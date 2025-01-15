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
            @if (in_array(Auth::user()->role, ['superadmin']))
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endif

            <!-- Menu Tracking -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor', 'customer']))
                <li class="nav-item {{ request()->routeIs('tracking') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('tracking') }}">
                        <i class="fas fa-globe-asia"></i>
                        <span>Tracking</span>
                    </a>
                </li>
            @endif

            <!-- Menu Driver -->
            @if (in_array(Auth::user()->role, ['driver']))
                <li class="nav-item {{ request()->routeIs('supir') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('supir') }}">
                        <i class="fas fa-truck-loading"></i>
                        <span>Driver</span>
                    </a>
                </li>
            @endif

            <!-- Menu Top Up -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
                <li class="nav-item {{ request()->routeIs('topuppage') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('topuppage') }}">
                        <i class="fas fa-wallet"></i>
                        <span>Top Up</span>
                    </a>
                </li>
            @endif

            @if (in_array(Auth::user()->role, ['pickup']))
                <li class="nav-item {{ request()->routeIs('pickup') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pickup') }}">
                        <i class="fas fa-people-carry"></i>
                        <span>Pickup</span>
                    </a>
                </li>
            @endif


            <!-- Menu Customer -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
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

            <!-- Menu Accounting -->
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
                            <a class="collapse-item {{ request()->routeIs('asset') ? 'active' : '' }}"
                                href="{{ route('asset') }}">Asset</a>
                            <a class="collapse-item {{ request()->routeIs('accountingSetting') ? 'active' : '' }}"
                                href="{{ route('accountingSetting') }}">Accounting Setting</a>
                        </div>
                    </div>
                </li>
            @endif
            <!-- Menu Report -->
            @if (Auth::user()->role === 'superadmin')
                <li
                    class="nav-item {{ request()->routeIs('assetReport') || request()->routeIs('soa') || request()->routeIs('topUpReport') || request()->routeIs('penerimaanKas') || request()->routeIs('ongoingInvoice') || request()->routeIs('soaVendor') || request()->routeIs('piutang') ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#listreport"
                        aria-expanded="true" aria-controls="listreport">
                        <i class="fas fa-file-alt fa-lg"></i>
                        <span>Report</span>
                    </a>
                    <div id="listreport" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Report</h6>
                            <a class="collapse-item {{ request()->routeIs('assetReport') ? 'active' : '' }}"
                                href="{{ route('assetReport') }}">Asset Report</a>
                            <a class="collapse-item {{ request()->routeIs('topUpReport') ? 'active' : '' }}"
                                href="{{ route('topUpReport') }}">Top Up Report</a>
                            <a class="collapse-item {{ request()->routeIs('penerimaanKas') ? 'active' : '' }}"
                                href="{{ route('penerimaanKas') }}">Penerimaan Kas Report</a>
                            <a class="collapse-item {{ request()->routeIs('soa') ? 'active' : '' }}"
                                href="{{ route('soa') }}">SOA Customer</a>
                            <a class="collapse-item {{ request()->routeIs('soaVendor') ? 'active' : '' }}"
                                href="{{ route('soaVendor') }}">SOA Vendor</a>
                            <a class="collapse-item {{ request()->routeIs('ongoingInvoice') ? 'active' : '' }}"
                                href="{{ route('ongoingInvoice') }}">Ongoing Invoice</a>
                            <a class="collapse-item {{ request()->routeIs('piutang') ? 'active' : '' }}"
                                href="{{ route('piutang') }}">Piutang</a>
                        </div>
                    </div>
                </li>
            @endif
            <!-- Menu Report Customer-->
            @if (Auth::user()->role === 'customer')
                <li
                    class="nav-item {{ request()->routeIs('assetReport') || request()->routeIs('soa') || request()->routeIs('topUpReport') || request()->routeIs('penerimaanKas') || request()->routeIs('ongoingInvoice') || request()->routeIs('soaVendor') || request()->routeIs('piutang') ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#listreport"
                        aria-expanded="true" aria-controls="listreport">
                        <i class="fas fa-file-alt fa-lg"></i>
                        <span>Report</span>
                    </a>
                    <div id="listreport" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Report</h6>

                            <a class="collapse-item {{ request()->routeIs('topUpReport') ? 'active' : '' }}"
                                href="{{ route('topUpReport') }}">Top Up Report</a>

                        </div>
                    </div>
                </li>
            @endif

            <!-- Menu Accounting Report -->
            @if (Auth::user()->role === 'superadmin')
                <li
                    class="nav-item {{ request()->routeIs('profitloss') || request()->routeIs('ledger') || request()->routeIs('equity') || request()->routeIs('balance') || request()->routeIs('cashflow') ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                        data-target="#listAccountingReport" aria-expanded="true" aria-controls="listAccountingReport">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Accounting Report</span>
                    </a>
                    <div id="listAccountingReport" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Accounting Report</h6>
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
            @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
                <li
                    class="nav-item {{ request()->routeIs('supplierInvoice') || request()->routeIs('purchasePayment') || request()->routeIs('debitnote') ? 'active' : '' }}">
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

            <!-- Master Data -->
            @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
                <li
                    class="nav-item {{ request()->routeIs('costumer') || request()->routeIs('driver') || request()->routeIs('rekening') || request()->routeIs('pembagirate') || request()->routeIs('category') || request()->routeIs('user') || request()->routeIs('vendor') || request()->routeIs('periode') ? 'active' : '' }}">
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
                            {{-- <a class="collapse-item {{ request()->routeIs('rekening') ? 'active' : '' }}"
                            href="{{ route('rekening') }}">Rekening</a> --}}
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
                            @if (Auth::user()->role === 'superadmin')
                                <!-- Hanya Superadmin yang dapat melihat Periode -->
                                <a class="collapse-item {{ request()->routeIs('periode') ? 'active' : '' }}"
                                    href="{{ route('periode') }}">Periode</a>
                                <a class="collapse-item" href="#" id="triggerModal">Company</a>
                            @endif
                        </div>
                    </div>
                </li>
            @endif


            {{-- Menu Content --}}
            @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
                <li
                    class="nav-item {{ request()->routeIs('abouts') || request()->routeIs('whys') || request()->routeIs('services') || request()->routeIs('informations') || request()->routeIs('heropage') || request()->routeIs('Advertisement') || request()->routeIs('popup') || request()->routeIs('contact') ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                        data-target="#collapseBootstraps" aria-expanded="true" aria-controls="collapseBootstraps">
                        <i class="fas fa-tasks"></i>
                        <span>Content</span>
                    </a>
                    <div id="collapseBootstraps" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Menu</h6>
                            <a class="collapse-item {{ request()->routeIs('heropage') ? 'active' : '' }}"
                                href="{{ route('heropage') }}">Hero Page</a>
                            <a class="collapse-item {{ request()->routeIs('informations') ? 'active' : '' }}"
                                href="{{ route('informations') }}">Information</a>
                            <a class="collapse-item {{ request()->routeIs('abouts') ? 'active' : '' }}"
                                href="{{ route('abouts') }}">About</a>
                            <a class="collapse-item {{ request()->routeIs('whys') ? 'active' : '' }}"
                                href="{{ route('whys') }}">Why</a>
                            <a class="collapse-item {{ request()->routeIs('services') ? 'active' : '' }}"
                                href="{{ route('services') }}">Service</a>
                            <a class="collapse-item {{ request()->routeIs('advertisement') ? 'active' : '' }}"
                                href="{{ route('advertisement') }}">Advertisement</a>
                            <a class="collapse-item {{ request()->routeIs('popup') ? 'active' : '' }}"
                                href="{{ route('popup') }}">Popup</a>
                            <a class="collapse-item {{ request()->routeIs('contact') ? 'active' : '' }}"
                                href="{{ route('contact') }}">Contact</a>
                            <a class="collapse-item {{ request()->routeIs('whatsapp') ? 'active' : '' }}"
                                href="{{ route('whatsapp') }}">Whatsapp</a>
                            <a class="collapse-item {{ request()->routeIs('wa.broadcast') ? 'active' : '' }}"
                                href="{{ route('wa.broadcast') }}">WA Broadcast</a>
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
                        @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="invoiceDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    title="Unpaid Invoices">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <span class="badge badge-danger badge-counter" id="unpaid-invoice-count"
                                        style="display: none;">0</span>
                                </a>
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="invoiceDropdown">
                                    <h6 class="dropdown-header">Unpaid Invoices</h6>
                                    <div id="invoice-notifications" style="max-height: 300px; overflow-y: auto;">
                                        <p class="dropdown-item text-center small text-gray-500">No unpaid invoices</p>
                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500"
                                        href="{{ route('invoice') }}">View All Invoices</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="kuotaDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    title="Kuota Notifications">
                                    <i class="fas fa-comment-dollar"></i>
                                    <span class="badge badge-danger badge-counter" id="unpaid-kuota-count"
                                        style="display: none;">0</span>
                                </a>
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="kuotaDropdown">
                                    <h6 class="dropdown-header">Kuota</h6>
                                    <div id="kuota-notifications" style="max-height: 300px; overflow-y: auto;">
                                        <p class="dropdown-item text-center small text-gray-500">No data kuota</p>
                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500"
                                        href="{{ route('topuppage') }}">View all
                                        Kuota</a>
                                </div>
                            </li>
                        @endif


                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile rounded-circle" src="{{ asset('RuangAdmin/img/boy.png') }}"
                                    style="max-width: 60px">

                                @if (Auth::check())
                                    <span
                                        class="ml-2 d-none d-lg-inline text-white small">{{ Auth::user()->name }}</span>
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
                                <a class="dropdown-item" href="{{ route('indexcompany')}}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Company
                                </a>
                                {{-- <a class="dropdown-item" href="{{ route('verification.notice') }}">
                                <i class="fas fa-envelope fa-sm fa-fw mr-2 text-gray-400"></i>
                                Verify Account
                            </a> --}}
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

            <!-- Modal -->
            <div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="companyModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="companyModalLabel">Select Company</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <select id="selectCompany" class="form-control">
                                <option value="" disabled>Select a Company</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="saveCompanyBtn">Save</button>
                        </div>
                    </div>
                </div>
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
