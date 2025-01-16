<?php

use App\Http\Controllers\Admin\CompanyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Landingpage\AboutsController,
    Landingpage\PtgesController,
    Landingpage\ServicesController,
    Landingpage\TrackingController,
    Landingpage\WhysController,
    Admin\AboutController,
    Admin\InformationsController,
    Admin\HeropageController,
    Admin\AdvertisementController,
    Admin\ServiceController,
    Admin\WhyController,
    Admin\ContactController,
    Admin\WhatsappController,
    Admin\WhatsappBroadcastController,
    Admin\PopupController,
    Admin\TrackingsController,
    Admin\PembagirateController,
    Admin\DashboardController,
    Admin\LoginController,
    Admin\DeliveryController,
    Admin\InvoiceController,
    Admin\CostumerController,
    Admin\DriverController,
    Admin\ProfileController,
    Admin\PickupController,
    Admin\PaymentController,
    Admin\SupplierInvoiceController,
    Admin\UserController,
    Admin\RoleController,
    Admin\SupirController,
    Admin\CategoryController,
    Admin\CoaController,
    Admin\AccountingSettingController,
    Admin\JournalController,
    Admin\PurchasePaymentController,
    Admin\DebitNoteController,
    Admin\CreditNoteController,
    Admin\ProfitLossController,
    Admin\LedgerController,
    Admin\EquityController,
    Admin\BalanceController,
    Admin\CashFlowController,
    Admin\VendorController,
    Admin\TopupController,
    Admin\SoaController,
    Admin\SoaVendorController,
    Admin\AssetController,
    Admin\AssetReportController,
    Admin\PenerimaanKasController,
    Admin\TopUpReportController,
    Admin\PeriodeController,
    Admin\OngoingInvoiceController,
    Admin\PiutangController
};
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Middleware\SetActiveCompany;


// Landing Page (Tidak Memerlukan Login)
Route::get('/PTGes', [PtgesController::class, 'index'])->name('PTGes');
Route::get('/About', [AboutsController::class, 'index'])->name('About');
Route::get('/Why', [WhysController::class, 'index'])->name('Why');
Route::get('/Services', [ServicesController::class, 'index'])->name('Services');
Route::get('/Tracking', [TrackingController::class, 'index'])->name('Tracking');
Route::get('/Tracking/lacakResi', [TrackingController::class, 'lacakResi'])->name('lacakResi');

// Root URL diarahkan ke Landing Page
Route::get('/', function () {
    return redirect('/PTGes');
});

// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'ajaxLogin'])->name('login.ajax');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Password
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest')->name('password.update');


Route::middleware(['auth'])->group(function () {

    // Company
    Route::get('/company', [CompanyController::class, 'index'])->name('indexcompany');
    Route::get('/get-companies', [CompanyController::class, 'getCompanies']);
    Route::get('/company/getlistCompany', [CompanyController::class, 'getlistCompany'])->name('getlistCompany');
    Route::get('/company/getDataCompany', [CompanyController::class, 'getDataCompany'])->name('getDataCompany');
    Route::post('/set-active-company', [CompanyController::class, 'setActiveCompany']);
    Route::post('/company/tambahCompany', [CompanyController::class, 'tambahCompany'])->name('tambahCompany');
    Route::post('/company/updatecompany', [CompanyController::class, 'updateCompany'])->name('updateCompany');
    Route::post('/company/deleteCompany', [CompanyController::class, 'deleteCompany'])->name('deleteCompany');


    // Dashboard
    Route::get('/dashboardnew', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboardnew/mothly', [DashboardController::class, 'fetchMonthlyData'])->name('fetchMonthlyData');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/points-history', [ProfileController::class, 'getPointsHistory'])->name('getPointsHistory');
    // Verifikasi Email
    Route::get('/email/verify', [VerifyEmailController::class, '__invoke'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [VerifyEmailController::class, 'resendVerification'])->name('verification.send');

    // Delivery
    Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery');
    Route::get('/delivery/addDelivery', [DeliveryController::class, 'addDelivery'])->name('addDelivery');
    Route::get('/delivery/getlistTableBuatDelivery', [DeliveryController::class, 'getlistTableBuatDelivery'])->name('getlistTableBuatDelivery');
    Route::get('/delivery/getlistTableBuatPickup', [DeliveryController::class, 'getlistTableBuatPickup'])->name('getlistTableBuatPickup');
    Route::post('/delivery/cekResi', [DeliveryController::class, 'cekResi'])->name('cekResi');
    Route::post('/delivery/cekResiBulk', [DeliveryController::class, 'cekResiBulk'])->name('cekResiBulk');
    Route::post('/delivery/cekResiBulkPickup', [DeliveryController::class, 'cekResiBulkPickup'])->name('cekResiBulkPickup');
    Route::post('/delivery/cekResiPickup', [DeliveryController::class, 'cekResiPickup'])->name('cekResiPickup');
    Route::post('/delivery/buatDelivery', [DeliveryController::class, 'buatDelivery'])->name('buatDelivery');
    Route::post('/delivery/buatPickup', [DeliveryController::class, 'buatPickup'])->name('buatPickup');
    Route::get('/delivery/exportPDF', [DeliveryController::class, 'exportPDF'])->name('exportPDFDelivery');
    Route::get('/delivery/getlistDelivery', [DeliveryController::class, 'getlistDelivery'])->name('getlistDelivery');
    Route::get('/delivery/acceptPengantaran', [DeliveryController::class, 'acceptPengantaran'])->name('acceptPengantaran');
    Route::post('/delivery/updateStatus', [DeliveryController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/delivery/detailBuktiPengantaran', [DeliveryController::class, 'detailBuktiPengantaran'])->name('detailBuktiPengantaran');
    Route::post('/delivery/confirmasiPengantaran', [DeliveryController::class, 'confirmasiPengantaran'])->name('confirmasiPengantaran');

    // Invoice
    Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice');
    Route::get('/invoice/getlistInvoice', [InvoiceController::class, 'getlistInvoice'])->name('getlistInvoice');
    Route::get('/invoice/addinvoice', [InvoiceController::class, 'addinvoice'])->name('addinvoice');
    Route::get('/invoice/generateInvoice', [InvoiceController::class, 'generateInvoice'])->name('generateInvoice');
    Route::post('/invoice/addinvoice/tambainvoice', [InvoiceController::class, 'tambainvoice'])->name('tambainvoice');
    Route::post('/invoice/completePayment', [InvoiceController::class, 'completePayment'])->name('completePayment');
    Route::get('/invoice/deleteInvoice', [InvoiceController::class, 'deleteInvoice'])->name('deleteInvoice');
    Route::get('/invoice/detailBuktiPembayaran', [InvoiceController::class, 'detailBuktiPembayaran'])->name('detailBuktiPembayaran');
    Route::post('/invoice/editinvoice/{id}', [InvoiceController::class, 'editinvoice'])->name('editinvoice');
    Route::get('/invoice/cicilanInvoice/{id}', [InvoiceController::class, 'cicilanInvoice'])->name('cicilanInvoice');
    Route::post('/invoice/bayarTagihan', [InvoiceController::class, 'bayarTagihan'])->name('bayarTagihan');
    Route::get('/invoice/exportPdf', [InvoiceController::class, 'exportPdf'])->name('exportPdf');
    Route::get('/invoice/kirimPesanWaPembeli', [InvoiceController::class, 'kirimPesanWaPembeli'])->name('kirimPesanWaPembeli');
    Route::get('/invoice/changeMethod', [InvoiceController::class, 'changeMethod'])->name('changeMethod');
    Route::get('/invoice/cekResiInvoice', [InvoiceController::class, 'cekResiInvoice'])->name('cekResiInvoice');
    Route::get('/invoice/updatedeletepage/{id}', [InvoiceController::class, 'deleteoreditinvoice'])->name('deleteoreditinvoice');
    Route::get('/invoice/notifikasiInvoice', [InvoiceController::class, 'unpaidInvoices'])->name('unpaidInvoices');

    // Pickup
    Route::get('/pickup', [PickupController::class, 'index'])->name('pickup');
    Route::get('/pickup/jumlahresipickup', [PickupController::class, 'jumlahresipickup'])->name('jumlahresipickup');
    Route::get('/pickup/getDetailInvoice', [PickupController::class, 'getDetailInvoice'])->name('getDetailInvoice');
    Route::post('/pickup/cekPassPickup', [PickupController::class, 'checkPassword'])->name('cekPassPickup');

    // Pembayaran
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::get('/payment/addPayment', [PaymentController::class, 'addPayment'])->name('addPayment');
    Route::get('/payment/getInvoiceAmount', [PaymentController::class, 'getInvoiceAmount'])->name('getInvoiceAmount');
    Route::get('/payment/amountPoin', [PaymentController::class, 'amountPoin'])->name('amountPoin');
    Route::post('/payment/store', [PaymentController::class, 'store'])->name('buatpembayaran');
    Route::get('/payment-data', [PaymentController::class, 'getPaymentData'])->name('payment.data');
    Route::get('/paymentdata/export', [PaymentController::class, 'export'])->name('exportPayment');
    Route::get('/payment/generateKodePembayaran', [PaymentController::class, 'generateKodePembayaran'])->name('generateKodePembayaran');
    Route::get('/getInvoiceByMarking', [PaymentController::class, 'getInvoiceByMarking'])->name('getInvoiceByMarking');
    Route::get('/payment/getInvoiceDetail', [PaymentController::class, 'getInvoiceDetail'])->name('getInvoiceDetail');
    Route::get('/payment/getInvoiceByMarkingEdit', [PaymentController::class, 'getInvoiceByMarkingEdit'])->name('getInvoiceByMarkingEdit');
    Route::get('/payment/updatepayment/{id}', [PaymentController::class, 'editpayment'])->name('editpayment');
    Route::post('/payment/updatepayment/update', [PaymentController::class, 'update'])->name('editpayment.update');

    // Popup
    Route::get('/content/popup', [PopupController::class, 'index'])->name('popup');
    Route::post('/content/popup/tambah', [PopupController::class, 'addPopup'])->name('addPopup');
    Route::delete('/content/popup/destroy/{id}', [PopupController::class, 'destroyPopup'])->name('destroyPopup');

    // Contact
    Route::get('/content/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/content/contact/tambah', [ContactController::class, 'addContact'])->name('addContact');

    // About
    Route::get('/content/abouts', [AboutController::class, 'index'])->name('abouts');
    Route::post('/content/abouts/tambah', [AboutController::class, 'addAbout'])->name('addAbout');

    // Why
    Route::get('/content/whys', [WhyController::class, 'index'])->name('whys');
    Route::post('/content/whys/tambah', [WhyController::class, 'addWhy'])->name('addWhy');

    // Services
    Route::get('/content/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/content/services/getlistService', [ServiceController::class, 'getlistService'])->name('getlistService');
    Route::post('/content/services/store', [ServiceController::class, 'addService'])->name('addService');
    Route::post('/content/services/update/{id}', [ServiceController::class, 'updateService'])->name('updateService');
    Route::delete('/content/services/destroy/{id}', [ServiceController::class, 'destroyService'])->name('destroyService');
    Route::get('/content/services/{id}', [ServiceController::class, 'show']);

    // heropage
    Route::get('/content/heropage', [HeropageController::class, 'index'])->name('heropage');
    Route::get('/content/heropage/getlistHeroPage', [HeropageController::class, 'getlistHeroPage'])->name('getlistHeroPage');
    Route::delete('/content/heropage/destroy/{id}', [HeropageController::class, 'destroyHeroPage'])->name('destroyHeroPage');
    Route::post('/content/heropage/store', [HeropageController::class, 'addHeroPage'])->name('addHeroPage');
    Route::post('/content/heropage/update/{id}', [HeropageController::class, 'updateHeroPage'])->name('updateHeroPage');
    Route::get('/content/heropage/{id}', [HeropageController::class, 'show']);

    // Informations
    Route::get('/content/informations', [InformationsController::class, 'index'])->name('informations');
    Route::get('/content/informations/getlistInformations', [InformationsController::class, 'getlistInformations'])->name('getlistInformations');
    Route::delete('/content/informations/destroy/{id}', [InformationsController::class, 'destroyInformations'])->name('destroyInformations');
    Route::post('content/informations/store', [InformationsController::class, 'addInformations'])->name('addInformations');
    Route::post('/content/informations/update/{id}', [InformationsController::class, 'updateInformations'])->name('updateInformations');
    Route::get('/content/informations/{id}', [InformationsController::class, 'show']);

    // whatsapp
    Route::get('/content/whatsapp', [WhatsappController::class, 'index'])->name('whatsapp');
    Route::post('/content/whatsapp/tambah', [WhatsappController::class, 'addWa'])->name('addWa');
    Route::delete('/content/whatsapp/destroy/{id}', [WhatsappController::class, 'destroyWa'])->name('destroyWa');

    Route::prefix('content/whatsapp/broadcast')->group(function () {
        Route::get('/', [WhatsappBroadcastController::class, 'index'])->name('wa.broadcast'); // Broadcast listing
        Route::get('/new', [WhatsappBroadcastController::class, 'create'])->name('wa.broadcast.new'); // Create new broadcast
        Route::post('/store', [WhatsappBroadcastController::class, 'store'])->name('wa.broadcast.store'); // Store broadcast
        Route::post('/resend', [WhatsappBroadcastController::class, 'resend'])->name('wa.broadcast.resend'); // Resend broadcast

        // New routes for editing and updating a broadcast
        Route::get('{id}/edit', [WhatsappBroadcastController::class, 'edit'])->name('wa.broadcast.edit'); // Edit broadcast
        Route::put('{id}', [WhatsappBroadcastController::class, 'update'])->name('wa.broadcast.update'); // Update broadcast
    });

    // Advertisement
    Route::get('/content/Advertisement', [AdvertisementController::class, 'index'])->name('advertisement');
    Route::get('/content/Advertisement/getlistAdvertisement', [AdvertisementController::class, 'getlistAdvertisement'])->name('getlistAdvertisement');
    Route::delete('/content/Advertisement/destroy/{id}', [AdvertisementController::class, 'destroyAdvertisement'])->name('destroyAdvertisement');
    Route::post('/content/Advertisement/store', [AdvertisementController::class, 'addAdvertisement'])->name('addAdvertisement');
    Route::post('/content/Advertisement/update/{id}', [AdvertisementController::class, 'updateAdvertisement'])->name('updateAdvertisement');
    Route::get('/content/Advertisement/{id}', [AdvertisementController::class, 'show']);

    // Costumer
    Route::middleware([SetActiveCompany::class])->group(function () {
        Route::get('/masterdata/costumer', [CostumerController::class, 'index'])->name('costumer');
        Route::get('/masterdata/costumer/list', [CostumerController::class, 'getlistCostumer'])->name('getlistCostumer');
        Route::post('/masterdata/costumer/store', [CostumerController::class, 'addCostumer'])->name('addCostumer');
        Route::post('/masterdata/costumer/update', [CostumerController::class, 'updateCostumer'])->name('updateCostumer');
        Route::get('/masterdata/costumer/generateMarking', [CostumerController::class, 'generateMarking'])->name('generateMarking');
        Route::get('/masterdata/costumer/listbyname', [CostumerController::class, 'customerByName'])->name('customer.filter');
    });

    // Driver
    Route::get('/masterdata/driver', [DriverController::class, 'index'])->name('driver');
    Route::get('/masterdata/driver/list', [DriverController::class, 'getlistDriver'])->name('getlistDriver');
    Route::post('/masterdata/driver/store', [DriverController::class, 'addDriver'])->name('addDriver');
    Route::post('/masterdata/driver/update/{id}', [DriverController::class, 'updateDriver'])->name('updateDriver');
    Route::get('/masterdata/driver/{id}', [DriverController::class, 'show']);

    // Category
    Route::get('/masterdata/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/masterdata/category/getlistCategory', [CategoryController::class, 'getlistCategory'])->name('getlistCategory');
    Route::post('/masterdata/category/store', [CategoryController::class, 'addCategory'])->name('addCategory');
    Route::put('/masterdata/category/update/{id}', [CategoryController::class, 'updateCategory'])->name('updateCategory');
    Route::delete('/masterdata/category/destroy/{id}', [CategoryController::class, 'destroyCategory'])->name('destroyCategory');
    Route::get('/masterdata/category/{id}', [CategoryController::class, 'show']);

    // Pembagi dan Rate
    Route::get('/masterdata/pembagirate', [PembagirateController::class, 'index'])->name('pembagirate');
    Route::get('/masterdata/pembagirate/list', [PembagirateController::class, 'getlistPembagi'])->name('getlistPembagi');
    Route::post('/masterdata/pembagirate/store', [PembagirateController::class, 'addPembagi'])->name('addPembagi');
    Route::put('/masterdata/pembagirate/update/{id}', [PembagirateController::class, 'updatePembagi'])->name('updatePembagi');
    Route::delete('/masterdata/pembagirate/destroy/{id}', [PembagirateController::class, 'destroyPembagi'])->name('destroyPembagi');
    Route::get('/masterdata/pembagirate/{id}', [PembagirateController::class, 'show']);

    Route::get('/masterdata/rate/list', [PembagirateController::class, 'getlistRate'])->name('getlistRate');
    Route::post('/masterdata/rate/store', [PembagirateController::class, 'addRate'])->name('addRate');
    Route::put('/masterdata/rate/update/{id}', [PembagirateController::class, 'updateRate'])->name('updateRate');
    Route::delete('/masterdata/rate/destroyrate/{id}', [PembagirateController::class, 'destroyRate'])->name('destroyRate');
    Route::get('/masterdata/rate/{id}', [PembagirateController::class, 'showRate']);

    //user
    Route::get('/masterdata/user', [UserController::class, 'index'])->name('user');
    Route::get('/masterdata/user/list', [UserController::class, 'getlistUser'])->name('getlistUser');
    Route::post('/masterdata/user/store', [UserController::class, 'addUsers'])->name('addUsers');
    Route::put('/masterdata/user/update/{id}', [UserController::class, 'updateUsers'])->name('updateUsers');
    Route::delete('/masterdata/user/destroy/{id}', [UserController::class, 'destroyUsers'])->name('destroyUsers');
    Route::get('/masterdata/user/{id}', [UserController::class, 'show']);

    //periode
    Route::get('/masterdata/periode',  [PeriodeController::class, 'index'])->name('periode');
    Route::get('/masterdata/periode/list', [PeriodeController::class, 'getPeriode'])->name('getPeriode');
    Route::post('/masterdata/periode/store', [PeriodeController::class, 'addPeriode'])->name('addPeriode');
    Route::put('/masterdata/periode/updatePeriode/{id}', [PeriodeController::class, 'updatePeriode'])->name('updatePeriode');
    Route::delete('/masterdata/periode/deletePeriode/{id}', [PeriodeController::class, 'deletePeriode'])->name('deletePeriode');
    Route::get('/masterdata/periode/generatePeriode', [PeriodeController::class, 'generatePeriode'])->name('generatePeriode');
    Route::get('/periode/{id}', [PeriodeController::class, 'show']);

    //Menu
    Route::get('/masterdata/menu/list', [RoleController::class, 'getlistMenu'])->name('getlistMenu');

    // Vendor

    //Invoice
    Route::get('/vendor/supplierInvoice', [SupplierInvoiceController::class, 'index'])->name('supplierInvoice');
    Route::get('/vendor/supplierInvoice/addsupplierInvoice', [SupplierInvoiceController::class, 'addSupplierInvoice'])->name('addSupplierInvoice');
    Route::get('/vendor/supplierInvoice/generateSupInvoice', [SupplierInvoiceController::class, 'generateSupInvoice'])->name('generateSupInvoice');
    Route::get('/vendor/supplierInvoice/getlistSupplierInvoice', [SupplierInvoiceController::class, 'getlistSupplierInvoice'])->name('getlistSupplierInvoice');
    Route::get('/vendor/supplierInvoice/showDetail', [SupplierInvoiceController::class, 'showDetail'])->name('showDetail');
    Route::post('/vendor/supplierInvoice/store', [SupplierInvoiceController::class, 'store'])->name('supInvoice.store');

    //Purchase Payment
    Route::get('/vendor/purchasePayment', [PurchasePaymentController::class, 'index'])->name('purchasePayment');
    Route::get('/vendor/purchasePayment/getPaymentSupData', [PurchasePaymentController::class, 'getPaymentSupData'])->name('getPaymentSupData');
    Route::get('/vendor/purchasePayment/getInoviceByVendor', [PurchasePaymentController::class, 'getInoviceByVendor'])->name('getInoviceByVendor');
    Route::get('/vendor/purchasePayment/addPurchasePayment', [PurchasePaymentController::class, 'addPurchasePayment'])->name('addPurchasePayment');
    Route::get('/vendor/purchasePayment/getSupInvoiceAmount', [PurchasePaymentController::class, 'getSupInvoiceAmount'])->name('getSupInvoiceAmount');
    Route::get('/vendor/purchasePayment/export', [PurchasePaymentController::class, 'export'])->name('getSupInvoiceExport');
    Route::post('/vendor/purchasePayment/payment', [PurchasePaymentController::class, 'store'])->name('paymentSup');
    Route::get('/vendor/purchasePayment/getInvoiceSupDetail', [PurchasePaymentController::class, 'getInvoiceSupDetail'])->name('getInvoiceSupDetail');
    Route::get('/vendor/purchasePayment/{id}', [PurchasePaymentController::class, 'editpurchasepayment'])->name('editpurchasepayment');
    Route::get('/vendor/purchasePayment/getInoviceByVendorEdit', [PurchasePaymentController::class, 'getInoviceByVendorEdit'])->name('getInoviceByVendorEdit');
    Route::post('/vendor/purchasePayment/updatepayment', [PurchasePaymentController::class, 'update'])->name('editpaymentsup.update');


    //Debit Note
    Route::get('/vendor/debitnote', [DebitNoteController::class, 'index'])->name('debitnote');
    Route::get('/vendor/debitnote/addDebitNote', [DebitNoteController::class, 'addDebitNote'])->name('addDebitNote');
    Route::get('/vendor/debitnote/getDebitNotes', [DebitNoteController::class, 'getDebitNotes'])->name('getDebitNotes');
    Route::post('/vendor/debitnote/store', [DebitNoteController::class, 'store'])->name('debit-note.store');
    Route::get('/vendor/debitnote/updatepage/{id}', [DebitNoteController::class, 'updatepage'])->name('debitnote.updatepage');
    Route::get('/vendor/debitnote/GetInvoiceUpdate', [DebitNoteController::class, 'GetInvoiceUpdate'])->name('GetInvoiceUpdate');
    Route::get('/vendor/debitnote/getInvoiceByVendor', [DebitNoteController::class, 'getInvoiceByVendor'])->name('getInvoiceByVendor');

    //Credit Note
    Route::get('/customer/creditnote', [CreditNoteController::class, 'index'])->name('creditnote');
    Route::get('/customer/creditnote/addCreditNote', [CreditNoteController::class, 'addCreditNote'])->name('addCreditNote');
    Route::get('/customer/creditnote/getCreditNotes', [CreditNoteController::class, 'getCreditNotes'])->name('getCreditNotes');
    Route::post('/customer/creditnote/store', [CreditNoteController::class, 'store'])->name('credit-note.store');
    Route::get('/customer/creditnote/updatepage/{id}', [CreditNoteController::class, 'updatepage'])->name('updatepage');
    Route::put('/customer/creditnote/update/{id}', [CreditNoteController::class, 'update'])->name('update');

    //Tracking
    Route::get('/tracking', [TrackingsController::class, 'index'])->name('tracking');
    Route::get('/tracking-data', [TrackingsController::class, 'getTrackingData'])->name('tracking.data');
    Route::post('/tracking/store', [TrackingsController::class, 'addTracking'])->name('addTracking');
    Route::put('/tracking/updateTracking/{id}', [TrackingsController::class, 'updateTracking'])->name('updateTracking');
    Route::delete('/tracking/deleteTracking/{id}', [TrackingsController::class, 'deleteTracking'])->name('deleteTracking');
    Route::get('/tracking/{id}', [TrackingsController::class, 'show']);

    //Supir
    Route::get('/supir', [SupirController::class, 'index'])->name('supir');
    Route::get('/supir/jumlahresi', [SupirController::class, 'jumlahresi'])->name('jumlahresi');
    Route::post('/supir/tambahdata', [SupirController::class, 'tambahdata'])->name('tambahdata');
    Route::post('/supir/batalAntar', [SupirController::class, 'batalAntar'])->name('batalAntar');

    //Vendor
    Route::get('/vendors', [VendorController::class, 'index'])->name('vendor');
    Route::get('/get-vendors', [VendorController::class, 'getVendors'])->name('vendors.getVendors');
    Route::post('/vendors/store', [VendorController::class, 'store'])->name('vendors.store');
    Route::put('/vendors/edit/{id}', [VendorController::class, 'update'])->name('vendors.edit');
    Route::get('/vendors/getVendorById', [VendorController::class, 'getVendorById'])->name('vendors.getVendorById');


    //COA
    Route::get('/coa', [CoaController::class, 'index'])->name('coa');
    Route::get('/coa/getlistcoa', [CoaController::class, 'getlistcoa'])->name('getlistcoa');
    Route::get('/coa/getNextAccountCode', [CoaController::class, 'getNextAccountCode'])->name('getNextAccountCode');
    Route::post('/coa/store', [CoaController::class, 'store'])->name('coa.store');
    Route::delete('/coa/delete/{id}', [COAController::class, 'destroy'])->name('coa.destroy');
    Route::get('/coa/{id}', [COAController::class, 'show'])->name('coa.show');
    Route::put('/coa/update/{id}', [COAController::class, 'update'])->name('coa.update');

    //Journal
    Route::get('/journal', [JournalController::class, 'index'])->name('journal');
    Route::get('/journal-data', [JournalController::class, 'getjournalData'])->name('journal.data');
    Route::get('/journal/addjournal', [JournalController::class, 'addjournal'])->name('addjournal');
    Route::get('/journal/generateNoJurnal', [JournalController::class, 'generateNoJurnal'])->name('generateNoJurnal');
    Route::post('/journal/addjournal/store', [JournalController::class, 'store'])->name('storeJurnal');
    Route::get('/journal/updatejournal/{id}', [JournalController::class, 'updateJournal'])->name('updatejournal');
    Route::put('/journal/updatejournal/update/{id}', [JournalController::class, 'update'])->name('buatupdate');
    Route::delete('/jurnal/delete/{id}', [JournalController::class, 'destroy'])->name('destroyJurnal');
    Route::get('/journal/generateNoJournalBKK', [JournalController::class, 'generateNoJournalBKK'])->name('generateNoJournalBKK');
    Route::get('/journal/generateNoJournalBKM', [JournalController::class, 'generateNoJournalBKM'])->name('generateNoJournalBKM');

    //Acoounting Setting
    Route::get('/accountingSetting', [AccountingSettingController::class, 'index'])->name('accountingSetting');
    Route::post('/account-settings/store', [AccountingSettingController::class, 'store'])->name('account-settings.store');
    Route::post('/account-settings/update/{id}', [AccountingSettingController::class, 'update'])->name('account-settings.update');

    //Asset
    Route::get('/asset', [AssetController::class, 'index'])->name('asset');
    Route::get('/asset/add', [AssetController::class, 'create'])->name('asset.add');
    Route::post('/asset/store', [AssetController::class, 'store'])->name('asset.store');
    Route::get('/asset/show/{id}', [AssetController::class, 'show'])->name('asset.show');
    Route::delete('/asset/destroy/{id}', [AssetController::class, 'destroy'])->name('asset.destroy');

    //Top Up
    Route::get('/topup', [TopupController::class, 'index'])->name('topuppage');
    Route::get('/topup/getPricePoints', [TopupController::class, 'getPricePoints'])->name('get-price-points');
    Route::get('/topup/getCustomers', [TopupController::class, 'getCustomers'])->name('get-customers');
    Route::post('/topup-points', [TopupController::class, 'storeTopup'])->name('topup-points');
    Route::get('/topup/data', [TopupController::class, 'getData'])->name('topup.data');
    Route::post('/topup/cancel', [TopupController::class, 'cancleTopup'])->name('cancleTopup');
    Route::get('/generateCodeVoucher', [TopupController::class, 'generateCodeVoucher'])->name('generateCodeVoucher');
    Route::post('topup/expire/{id}', [TopupController::class, 'expireTopup'])->name('topup.expire');
    Route::get('/topup/topupNotification', [TopupController::class, 'topupNotification'])->name('topupNotification');

    //Report
    //OngoingInvoice
    Route::get('/report/ongoinginvoice', [OngoingInvoiceController::class, 'index'])->name('ongoingInvoice');
    Route::get('/report/ongoinginvoice/getlistOngoing', [OngoingInvoiceController::class, 'getlistOngoing'])->name('getlistOngoing');
    Route::get('/report/ongoinginvoice/export', [OngoingInvoiceController::class, 'export'])->name('ExportOngoingInvoice');
    Route::get('/report/ongoinginvoice/exportPdf', [OngoingInvoiceController::class, 'exportOngoingPdf'])->name('exportOngoingPdf');

    //ProfitLoss
    Route::get('/report/profitloss', [ProfitLossController::class, 'index'])->name('profitloss');
    Route::get('/report/getProfitOrLoss', [ProfitLossController::class, 'getProfitOrLoss'])->name('getProfitOrLoss');
    Route::get('/report/getProfitOrLoss/pdf', [ProfitLossController::class, 'generatePdf'])->name('profitLoss.pdf');

    //Equity
    Route::get('/report/equity', [EquityController::class, 'index'])->name('equity');
    Route::get('/report/getEquity', [EquityController::class, 'getEquity'])->name('getEquity');
    Route::get('/report/getEquity/pdf', [EquityController::class, 'generatePdf'])->name('equity.pdf');

    //Cashflow
    Route::get('/report/cashflow', [CashFlowController::class, 'index'])->name('cashflow');
    Route::get('/report/getCashFlow', [CashFlowController::class, 'getCashFlow'])->name('getCashFlow');
    Route::get('/report/getCashFlow/pdf', [CashFlowController::class, 'generatePdf'])->name('cashflow.pdf');

    //Ledger
    Route::get('/report/ledger', [LedgerController::class, 'index'])->name('ledger');
    Route::get('/report/getLedger', [LedgerController::class, 'getLedgerHtml'])->name('getLedger');
    Route::get('/report/getLedger/pdf', [LedgerController::class, 'generatePdf'])->name('ledger.pdf');
    Route::get('/report/getLedger/exportExcel', [LedgerController::class, 'exportExcel'])->name('ledger.exportExcel');

    //Balance
    Route::get('/report/balance', [BalanceController::class, 'index'])->name('balance');
    Route::get('/report/getBalance', [BalanceController::class, 'getBalance'])->name('getBalance');
    Route::get('/report/getBalance/pdf', [BalanceController::class, 'generatePdf'])->name('balance.pdf');

    //Soa Customer
    Route::get('/report/soa',  [SoaController::class, 'index'])->name('soa');
    Route::get('/report/getSoa',  [SoaController::class, 'getSoa'])->name('getSoa');
    Route::get('/report/getSoa/soaWA', [SoaController::class, 'soaWA'])->name('soaWA');
    Route::get('/report/getSoa/export', [SoaController::class, 'exportSoaCustomerReport'])->name('exportSoaCustomer');
    //Soa Vendor
    Route::get('/report/soaVendor',  [SoaVendorController::class, 'index'])->name('soaVendor');
    Route::get('/report/soaVendor/getSoaVendor',  [SoaVendorController::class, 'getSoaVendor'])->name('getSoaVendor');
    Route::get('/report/soaVendor/soaWA', [SoaVendorController::class, 'soaWA'])->name('soaWA.vendor');
    Route::get('/report/soaVendor/export', [SoaVendorController::class, 'exportSoaVendorReport'])->name('exportSoaVendor');
    //Asset
    Route::get('/report/assetReport',  [AssetReportController::class, 'index'])->name('assetReport');
    Route::get('/report/getAssetReport',  [AssetReportController::class, 'getAssetReport'])->name('getAssetReport');
    Route::get('/report/assetReport/pdf', [AssetReportController::class, 'generatePdf'])->name('assetReport.pdf');
    Route::get('/report/assetReport/export', [AssetReportController::class, 'exportAssetReport'])->name('exportReport');
    //PenerimaaKas
    Route::get('/report/penerimaanKas',  [PenerimaanKasController::class, 'index'])->name('penerimaanKas');
    Route::get('/report/getPenerimaanKas',  [PenerimaanKasController::class, 'getPenerimaanKas'])->name('getPenerimaanKas');
    Route::get('/report/penerimaanKas/pdf', [PenerimaanKasController::class, 'generatePdf'])->name('penerimaanKas.pdf');
    Route::get('/report/penerimaanKas/export', [PenerimaanKasController::class, 'exportKasReport'])->name('exportKasReport');

    //Top Up Report
    Route::get('/report/topUpReport',  [TopUpReportController::class, 'index'])->name('topUpReport');
    Route::get('/report/getTopUpReport',  [TopUpReportController::class, 'getTopUpReport'])->name('getTopUpReport');
    Route::get('/report/topUpReport/pdf', [TopUpReportController::class, 'generatePdf'])->name('topUpReport.pdf');
    Route::get('/report/topUpReport/export', [TopUpReportController::class, 'exportTopupReport'])->name('exportTopupReport');

    //piutang
    Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang');
    Route::get('/report/piutang', [PiutangController::class, 'getpiutang'])->name('getlistPiutang');
    Route::get('/report/piutang/export', [PiutangController::class, 'exportPiutangReport'])->name('exportPiutangReport');
    Route::get('/report/piutang/exportPdf', [PiutangController::class, 'exportPiutangPdf'])->name('exportPiutangPdf');
});


