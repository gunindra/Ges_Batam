<?php $__env->startSection('content'); ?>
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <!-- Logo Sidebar -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <div class="bukuslogo py-2 px-3" style="background-color: white; border-radius: 10px;">
                        <img src="<?php echo e(asset('RuangAdmin/img/logo/logo4.png')); ?>">
                    </div>
                </div>
            </a>

            <hr class="sidebar-divider my-0">

            <!-- Menu Dashboard -->
            <?php if(in_array(Auth::user()->role, ['superadmin'])): ?>
                <li class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Menu Tracking -->
            <?php if(in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor', 'customer'])): ?>
                <li class="nav-item <?php echo e(request()->routeIs('tracking') ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('tracking')); ?>">
                        <i class="fas fa-globe-asia"></i>
                        <span>Tracking</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Menu Driver -->
            <?php if(in_array(Auth::user()->role, ['driver'])): ?>
                <li class="nav-item <?php echo e(request()->routeIs('supir') ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('supir')); ?>">
                        <i class="fas fa-truck-loading"></i>
                        <span>Driver</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Menu Top Up -->
            <?php if(in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor'])): ?>
                <li class="nav-item <?php echo e(request()->routeIs('topuppage') ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('topuppage')); ?>">
                        <i class="fas fa-wallet"></i>
                        <span>Top Up</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(in_array(Auth::user()->role, ['pickup'])): ?>
                <li class="nav-item <?php echo e(request()->routeIs('pickup') ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(route('pickup')); ?>">
                        <i class="fas fa-people-carry"></i>
                        <span>Pickup</span>
                    </a>
                </li>
            <?php endif; ?>


            <!-- Menu Customer -->
            <?php if(in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor'])): ?>
                <li
                    class="nav-item <?php echo e(request()->routeIs('invoice') || request()->routeIs('delivery') || request()->routeIs('payment') || request()->routeIs('creditnote') ? 'active' : ''); ?>">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#customerMenu"
                        aria-expanded="true" aria-controls="collapseBootstrap">
                        <i class="fas fa-user"></i>
                        <span>Customer</span>
                    </a>
                    <div id="customerMenu" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Customer</h6>
                            <a class="collapse-item <?php echo e(request()->routeIs('invoice') ? 'active' : ''); ?>"
                                href="<?php echo e(route('invoice')); ?>">Invoice</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('delivery') ? 'active' : ''); ?>"
                                href="<?php echo e(route('delivery')); ?>">Delivery / Pickup</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('payment') ? 'active' : ''); ?>"
                                href="<?php echo e(route('payment')); ?>">Payment</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('creditnote') ? 'active' : ''); ?>"
                                href="<?php echo e(route('creditnote')); ?>">Credit Note</a>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

        <!-- Menu Accounting -->
        <?php if(Auth::user()->role === 'superadmin'): ?>
            <li
                class="nav-item <?php echo e(request()->routeIs('coa') || request()->routeIs('journal') || request()->routeIs('accountingSetting') ? 'active' : ''); ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#listMenuAccounting"
                    aria-expanded="true" aria-controls="listMenuAccounting">
                    <i class="fas fa-money-bill-wave-alt"></i>
                    <span>Accounting</span>
                </a>
                <div id="listMenuAccounting" class="collapse" aria-labelledby="headingBootstrap"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Accounting</h6>
                        <a class="collapse-item <?php echo e(request()->routeIs('coa') ? 'active' : ''); ?>"
                            href="<?php echo e(route('coa')); ?>">COA</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('journal') ? 'active' : ''); ?>"
                            href="<?php echo e(route('journal')); ?>">Journal</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('asset') ? 'active' : ''); ?>"
                            href="<?php echo e(route('asset')); ?>">Asset</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('accountingSetting') ? 'active' : ''); ?>"
                            href="<?php echo e(route('accountingSetting')); ?>">Accounting Setting</a>
                    </div>
                </div>
            </li>
        <?php endif; ?>
        <!-- Menu Report -->
        <?php if(Auth::user()->role === 'superadmin'): ?>
            <li
                class="nav-item <?php echo e(request()->routeIs('assetReport') || request()->routeIs('soa') || request()->routeIs('topUpReport') || request()->routeIs('penerimaanKas') || request()->routeIs('ongoingInvoice') || request()->routeIs('soaVendor')|| request()->routeIs('piutang') ? 'active' : ''); ?>">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#listreport" aria-expanded="true"
                    aria-controls="listreport">
                    <i class="fas fa-file-alt fa-lg"></i>
                    <span>Report</span>
                </a>
                <div id="listreport" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Report</h6>
                        <a class="collapse-item <?php echo e(request()->routeIs('assetReport') ? 'active' : ''); ?>"
                            href="<?php echo e(route('assetReport')); ?>">Asset Report</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('topUpReport') ? 'active' : ''); ?>"
                            href="<?php echo e(route('topUpReport')); ?>">Top Up Report</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('penerimaanKas') ? 'active' : ''); ?>"
                            href="<?php echo e(route('penerimaanKas')); ?>">Penerimaan Kas Report</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('soa') ? 'active' : ''); ?>"
                            href="<?php echo e(route('soa')); ?>">SOA Customer</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('soaVendor') ? 'active' : ''); ?>"
                            href="<?php echo e(route('soaVendor')); ?>">SOA Vendor</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('ongoingInvoice') ? 'active' : ''); ?>"
                            href="<?php echo e(route('ongoingInvoice')); ?>">Ongoing Invoice</a>
                        <a class="collapse-item <?php echo e(request()->routeIs('piutang') ? 'active' : ''); ?>"
                            href="<?php echo e(route('piutang')); ?>">Piutang</a>
                    </div>
                </div>
            </li>
        <?php endif; ?>
         <!-- Menu Report Customer-->
         <?php if(Auth::user()->role === 'customer'): ?>
         <li
             class="nav-item <?php echo e(request()->routeIs('assetReport') || request()->routeIs('soa') || request()->routeIs('topUpReport') || request()->routeIs('penerimaanKas') || request()->routeIs('ongoingInvoice') || request()->routeIs('soaVendor')|| request()->routeIs('piutang') ? 'active' : ''); ?>">
             <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#listreport" aria-expanded="true"
                 aria-controls="listreport">
                 <i class="fas fa-file-alt fa-lg"></i>
                 <span>Report</span>
             </a>
             <div id="listreport" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
                 <div class="bg-white py-2 collapse-inner rounded">
                     <h6 class="collapse-header">Report</h6>

                     <a class="collapse-item <?php echo e(request()->routeIs('topUpReport') ? 'active' : ''); ?>"
                         href="<?php echo e(route('topUpReport')); ?>">Top Up Report</a>

                 </div>
             </div>
         </li>
     <?php endif; ?>

            <!-- Menu Accounting Report -->
            <?php if(Auth::user()->role === 'superadmin'): ?>
                <li
                    class="nav-item <?php echo e(request()->routeIs('profitloss') || request()->routeIs('ledger') || request()->routeIs('equity') || request()->routeIs('balance') || request()->routeIs('cashflow') ? 'active' : ''); ?>">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                        data-target="#listAccountingReport" aria-expanded="true" aria-controls="listAccountingReport">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Accounting Report</span>
                    </a>
                    <div id="listAccountingReport" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Accounting Report</h6>
                            <a class="collapse-item <?php echo e(request()->routeIs('profitloss') ? 'active' : ''); ?>"
                                href="<?php echo e(route('profitloss')); ?>">Profit/Loss</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('ledger') ? 'active' : ''); ?>"
                                href="<?php echo e(route('ledger')); ?>">Ledger</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('equity') ? 'active' : ''); ?>"
                                href="<?php echo e(route('equity')); ?>">Equity</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('balance') ? 'active' : ''); ?>"
                                href="<?php echo e(route('balance')); ?>">Balance</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('cashflow') ? 'active' : ''); ?>"
                                href="<?php echo e(route('cashflow')); ?>">CashFlow</a>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Menu Vendor -->
            <?php if(in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor'])): ?>
                <li
                    class="nav-item <?php echo e(request()->routeIs('supplierInvoice') || request()->routeIs('purchasePayment') || request()->routeIs('debitnote') ? 'active' : ''); ?>">
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
                            <a class="collapse-item <?php echo e(request()->routeIs('supplierInvoice') ? 'active' : ''); ?>"
                                href="<?php echo e(route('supplierInvoice')); ?>">Invoice</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('purchasePayment') ? 'active' : ''); ?>"
                                href="<?php echo e(route('purchasePayment')); ?>">Payment</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('debitnote') ? 'active' : ''); ?>"
                                href="<?php echo e(route('debitnote')); ?>">Debit Note</a>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Master Data -->
            <?php if(in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor'])): ?>
                <li
                    class="nav-item <?php echo e(request()->routeIs('costumer') || request()->routeIs('driver') || request()->routeIs('rekening') || request()->routeIs('pembagirate') || request()->routeIs('category') || request()->routeIs('user') || request()->routeIs('vendor') || request()->routeIs('periode') ? 'active' : ''); ?>">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
                        aria-expanded="true" aria-controls="collapseBootstrap">
                        <i class="far fa-fw fa-window-maximize"></i>
                        <span>Master Data</span>
                    </a>
                    <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Master Data</h6>
                            <a class="collapse-item <?php echo e(request()->routeIs('costumer') ? 'active' : ''); ?>"
                                href="<?php echo e(route('costumer')); ?>">Costumers</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('driver') ? 'active' : ''); ?>"
                                href="<?php echo e(route('driver')); ?>">Driver</a>
                            
                            <a class="collapse-item <?php echo e(request()->routeIs('pembagirate') ? 'active' : ''); ?>"
                                href="<?php echo e(route('pembagirate')); ?>">Rate</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('category') ? 'active' : ''); ?>"
                                href="<?php echo e(route('category')); ?>">Category</a>
                            
                            <a class="collapse-item <?php echo e(request()->routeIs('user') ? 'active' : ''); ?>"
                                href="<?php echo e(route('user')); ?>">User</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('vendor') ? 'active' : ''); ?>"
                                href="<?php echo e(route('vendor')); ?>">Vendor</a>
                            <?php if(Auth::user()->role === 'superadmin'): ?>
                                <!-- Hanya Superadmin yang dapat melihat Periode -->
                                <a class="collapse-item <?php echo e(request()->routeIs('periode') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('periode')); ?>">Periode</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endif; ?>


            
            <?php if(in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor'])): ?>
                <li
                    class="nav-item <?php echo e(request()->routeIs('abouts') || request()->routeIs('whys') || request()->routeIs('services') || request()->routeIs('informations') || request()->routeIs('heropage') || request()->routeIs('Advertisement') || request()->routeIs('popup') || request()->routeIs('contact') ? 'active' : ''); ?>">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse"
                        data-target="#collapseBootstraps" aria-expanded="true" aria-controls="collapseBootstraps">
                        <i class="fas fa-tasks"></i>
                        <span>Content</span>
                    </a>
                    <div id="collapseBootstraps" class="collapse" aria-labelledby="headingBootstrap"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Menu</h6>
                            <a class="collapse-item <?php echo e(request()->routeIs('heropage') ? 'active' : ''); ?>"
                                href="<?php echo e(route('heropage')); ?>">Hero Page</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('informations') ? 'active' : ''); ?>"
                                href="<?php echo e(route('informations')); ?>">Information</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('abouts') ? 'active' : ''); ?>"
                                href="<?php echo e(route('abouts')); ?>">About</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('whys') ? 'active' : ''); ?>"
                                href="<?php echo e(route('whys')); ?>">Why</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('services') ? 'active' : ''); ?>"
                                href="<?php echo e(route('services')); ?>">Service</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('advertisement') ? 'active' : ''); ?>"
                                href="<?php echo e(route('advertisement')); ?>">Advertisement</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('popup') ? 'active' : ''); ?>"
                                href="<?php echo e(route('popup')); ?>">Popup</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('contact') ? 'active' : ''); ?>"
                                href="<?php echo e(route('contact')); ?>">Contact</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('whatsapp') ? 'active' : ''); ?>"
                                href="<?php echo e(route('whatsapp')); ?>">Whatsapp</a>
                            <a class="collapse-item <?php echo e(request()->routeIs('wa.broadcast') ? 'active' : ''); ?>"
                                href="<?php echo e(route('wa.broadcast')); ?>">WA Broadcast</a>
                        </div>
                    </div>
                </li>
            <?php endif; ?>
        </ul>


        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <?php if(in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor'])): ?>
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
                                        href="<?php echo e(route('invoice')); ?>">View All Invoices</a>
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
                                    <a class="dropdown-item text-center small text-gray-500" href="<?php echo e(route('topuppage')); ?>">View all
                                        Kuota</a>
                                </div>
                            </li>
                        <?php endif; ?>


                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img-profile rounded-circle" src="<?php echo e(asset('RuangAdmin/img/boy.png')); ?>"
                                    style="max-width: 60px">

                                <?php if(Auth::check()): ?>
                                    <span class="ml-2 d-none d-lg-inline text-white small"><?php echo e(Auth::user()->name); ?></span>
                                <?php else: ?>
                                    <span class="ml-2 d-none d-lg-inline text-white small"><?php echo e($username); ?></span>
                                <?php endif; ?>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
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
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                    style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo $__env->yieldContent('main'); ?>

            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script> - developed by
                            
                            <b>SmartAppCare</b>
                        </span>
                    </div>
                </div>
            </footer>
            <!-- Footer -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views/layout/main.blade.php ENDPATH**/ ?>