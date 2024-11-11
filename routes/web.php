<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LandingPage\AboutsController,
    LandingPage\PtgesController,
    LandingPage\ServicesController,
    LandingPage\SlideController,
    LandingPage\TrackingController,
    LandingPage\WhysController,
    Admin\AboutController,
    Admin\InformationsController,
    Admin\HeropageController,
    Admin\AdvertisementController,
    Admin\ServiceController,
    Admin\WhyController,
    Admin\ContactController,
    Admin\WhatsappController,
    Admin\PopupController,
    Admin\TrackingsController,
    Admin\PembagirateController,
    Admin\DashboardController,
    Admin\LoginController,
    Admin\DeliveryController,
    Admin\InvoiceController,
    Admin\CostumerController,
    Admin\DriverController,
    Admin\RekeningController,
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
};
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;


// Landing Page (Tidak Memerlukan Login)
Route::get('/PTGes', [PtgesController::class, 'index'])->name('PTGes');
Route::get('/About', [AboutsController::class, 'index'])->name('About');
Route::get('/Why', [WhysController::class, 'index'])->name('Why');
Route::get('/Services', [ServicesController::class, 'index'])->name('Services');
Route::get('/Slide', [SlideController::class, 'index'])->name('Slide');
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
    Route::get('/delivery/updateStatus', [DeliveryController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/delivery/detailBuktiPengantaran', [DeliveryController::class, 'detailBuktiPengantaran'])->name('detailBuktiPengantaran');
    Route::post('/delivery/confirmasiPengantaran', [DeliveryController::class, 'confirmasiPengantaran'])->name('confirmasiPengantaran');

    // Invoice
    Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice');
    Route::get('/invoice/getlistInvoice', [InvoiceController::class, 'getlistInvoice'])->name('getlistInvoice');
    Route::get('/invoice/getlistCicilan', [InvoiceController::class, 'getlistCicilan'])->name('getlistCicilan');
    Route::get('/invoice/getlistHeadCicilan', [InvoiceController::class, 'getlistHeadCicilan'])->name('getlistHeadCicilan');
    Route::get('/invoice/addinvoice', [InvoiceController::class, 'addinvoice'])->name('addinvoice');
    Route::get('/invoice/generateInvoice', [InvoiceController::class, 'generateInvoice'])->name('generateInvoice');
    Route::post('/invoice/addinvoice/tambainvoice', [InvoiceController::class, 'tambainvoice'])->name('tambainvoice');
    Route::post('/invoice/completePayment', [InvoiceController::class, 'completePayment'])->name('completePayment');
    Route::get('/invoice/deleteInvoice', [InvoiceController::class, 'deleteInvoice'])->name('deleteInvoice');
    Route::get('/invoice/detailBuktiPembayaran', [InvoiceController::class, 'detailBuktiPembayaran'])->name('detailBuktiPembayaran');
    Route::get('/invoice/editinvoice/{id}', [InvoiceController::class, 'editinvoice'])->name('editinvoice');
    Route::get('/invoice/cicilanInvoice/{id}', [InvoiceController::class, 'cicilanInvoice'])->name('cicilanInvoice');
    Route::post('/invoice/bayarTagihan', [InvoiceController::class, 'bayarTagihan'])->name('bayarTagihan');
    Route::get('/invoice/exportPdf', [InvoiceController::class, 'exportPdf'])->name('exportPdf');
    Route::get('/invoice/kirimPesanWaPembeli', [InvoiceController::class, 'kirimPesanWaPembeli'])->name('kirimPesanWaPembeli');
    Route::get('/invoice/changeMethod', [InvoiceController::class, 'changeMethod'])->name('changeMethod');
    Route::get('/invoice/cekResiInvoice', [InvoiceController::class, 'cekResiInvoice'])->name('cekResiInvoice');

    // Pickup
    Route::get('/pickup', [PickupController::class, 'index'])->name('pickup');
    Route::get('/pickup/jumlahresipickup', [PickupController::class, 'jumlahresipickup'])->name('jumlahresipickup');
    
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

    // Advertisement
    Route::get('/content/Advertisement', [AdvertisementController::class, 'index'])->name('advertisement');
    Route::get('/content/Advertisement/getlistAdvertisement', [AdvertisementController::class, 'getlistAdvertisement'])->name('getlistAdvertisement');
    Route::delete('/content/Advertisement/destroy/{id}', [AdvertisementController::class, 'destroyAdvertisement'])->name('destroyAdvertisement');
    Route::post('/content/Advertisement/store', [AdvertisementController::class, 'addAdvertisement'])->name('addAdvertisement');
    Route::post('/content/Advertisement/update/{id}', [AdvertisementController::class, 'updateAdvertisement'])->name('updateAdvertisement');
    Route::get('/content/Advertisement/{id}', [AdvertisementController::class, 'show']);

    // Costumer
    Route::get('/masterdata/costumer', [CostumerController::class, 'index'])->name('costumer');
    Route::get('/masterdata/costumer/list', [CostumerController::class, 'getlistCostumer'])->name('getlistCostumer');
    Route::post('/masterdata/costumer/store', [CostumerController::class, 'addCostumer'])->name('addCostumer');
    Route::post('/masterdata/costumer/update', [CostumerController::class, 'updateCostumer'])->name('updateCostumer');
    Route::get('/masterdata/costumer/destroy', [CostumerController::class, 'destroyCostumer'])->name('destroyCostumer');
    Route::get('/masterdata/costumer/generateMarking', [CostumerController::class, 'generateMarking'])->name('generateMarking');
    // Route::get('/masterdata/costumer', [CostumerController::class, 'show']);
    // Driver
    Route::get('/masterdata/driver', [DriverController::class, 'index'])->name('driver');
    Route::get('/masterdata/driver/list', [DriverController::class, 'getlistDriver'])->name('getlistDriver');
    Route::post('/masterdata/driver/store', [DriverController::class, 'addDriver'])->name('addDriver');
    Route::post('/masterdata/driver/update/{id}', [DriverController::class, 'updateDriver'])->name('updateDriver');
    Route::delete('/masterdata/driver/destroy/{id}', [DriverController::class, 'destroyDriver'])->name('destroyDriver');
    Route::get('/masterdata/driver/{id}', [DriverController::class, 'show']);

    // Category
    Route::get('/masterdata/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/masterdata/category/getlistCategory', [CategoryController::class, 'getlistCategory'])->name('getlistCategory');
    Route::post('/masterdata/category/store', [CategoryController::class, 'addCategory'])->name('addCategory');
    Route::put('/masterdata/category/update/{id}', [CategoryController::class, 'updateCategory'])->name('updateCategory');
    Route::delete('/masterdata/category/destroy/{id}', [CategoryController::class, 'destroyCategory'])->name('destroyCategory');
    Route::get('/masterdata/category/{id}', [CategoryController::class, 'show']);

    // Rekening
    Route::get('/masterdata/rekening', [RekeningController::class, 'index'])->name('rekening');
    Route::get('/masterdata/rekening/list', [RekeningController::class, 'getlistRekening'])->name('getlistRekening');
    Route::post('/masterdata/rekening/store', [RekeningController::class, 'addRekening'])->name('addRekening');
    Route::put('/masterdata/rekening/update/{id}', [RekeningController::class, 'updateRekening'])->name('updateRekening');
    Route::delete('/masterdata/rekening/destroy/{id}', [RekeningController::class, 'destroyRekening'])->name('destroyRekening');
    Route::get('/masterdata/rekening/{id}', [RekeningController::class, 'show']);

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

    //role
    Route::get('/masterdata/role', [RoleController::class, 'index'])->name('role');
    Route::get('/masterdata/role/list', [RoleController::class, 'getlistRole'])->name('getlistRole');
    Route::post('/masterdata/role/store', [RoleController::class, 'addRole'])->name('addRole');
    Route::put('/masterdata/role/update/{id}', [RoleController::class, 'updateRole'])->name('updateRole');
    Route::delete('/masterdata/role/destroy/{id}', [RoleController::class, 'destroyRole'])->name('destroyRole');
    Route::get('/masterdata/role/{id}', [RoleController::class, 'show']);

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
    Route::get('/vendor/purchasePayment/addPurchasePayment', [PurchasePaymentController::class, 'addPurchasePayment'])->name('addPurchasePayment');
    Route::get('/vendor/purchasePayment/getSupInvoiceAmount', [PurchasePaymentController::class, 'getSupInvoiceAmount'])->name('getSupInvoiceAmount');
    Route::get('/vendor/purchasePayment/export', [PurchasePaymentController::class, 'export'])->name('getSupInvoiceExport');
    Route::post('/vendor/purchasePayment/payment', [PurchasePaymentController::class, 'store'])->name('paymentSup');

    //Debit Note
    Route::get('/vendor/debitnote', [DebitNoteController::class, 'index'])->name('debitnote');
    Route::get('/vendor/debitnote/addDebitNote', [DebitNoteController::class, 'addDebitNote'])->name('addDebitNote');
    Route::get('/vendor/debitnote/getDebitNotes', [DebitNoteController::class, 'getDebitNotes'])->name('getDebitNotes');
    Route::post('/vendor/debitnote/store', [DebitNoteController::class, 'store'])->name('debit-note.store');
    Route::get('/vendor/debitnote/updatepage/{id}', [DebitNoteController::class, 'updatepage'])->name('debitnote.updatepage');
    Route::put('/vendor/debitnote/update/{id}', [DebitNoteController::class, 'update'])->name('debitnote.update');


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
    Route::get('/coa', [CoaController::class, 'index'])->name('coa');
    Route::get('/coa/getlistcoa', [CoaController::class, 'getlistcoa'])->name('getlistcoa');
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
    Route::post('/journal/updatejournal/update', [JournalController::class, 'updatejurnal'])->name('updatejurnal');
    Route::put('/journal/updatejournal/update/{id}', [JournalController::class, 'update'])->name('buatupdate');
    Route::delete('/jurnal/delete/{id}', [JournalController::class, 'destroy'])->name('destroyJurnal');

    //Acoounting Setting
    Route::get('/accountingSetting', [AccountingSettingController::class, 'index'])->name('accountingSetting');
    Route::post('/account-settings/store', [AccountingSettingController::class, 'store'])->name('account-settings.store');
    Route::post('/account-settings/update/{id}', [AccountingSettingController::class, 'update'])->name('account-settings.update');

    //Top Up
    Route::get('/topup', [TopupController::class, 'index'])->name('topuppage');
    Route::get('/topup/getPricePoints', [TopupController::class, 'getPricePoints'])->name('get-price-points');
    Route::get('/topup/getCustomers', [TopupController::class, 'getCustomers'])->name('get-customers');
    Route::post('/topup-points', [TopupController::class, 'storeTopup'])->name('topup-points');
    Route::get('/topup/data', [TopupController::class, 'getData'])->name('topup.data');
    Route::post('/topup/cancel', [TopupController::class, 'cancleTopup'])->name('cancleTopup');
    Route::get('/generateCodeVoucher', [TopupController::class, 'generateCodeVoucher'])->name('generateCodeVoucher');

    //Report
    //ProfitLoss
    Route::get('/report/profitloss',  [ProfitLossController::class, 'index'])->name('profitloss');
    Route::get('/report/getProfitOrLoss', [ProfitLossController::class, 'getProfitOrLoss'])->name('getProfitOrLoss');
    Route::get('/report/getProfitOrLoss/pdf', [ProfitLossController::class, 'generatePdf'])->name('profitLoss.pdf');

    //Equity
    Route::get('/report/equity', [EquityController::class, 'index'])->name('equity');
    Route::get('/report/getEquity', [EquityController::class, 'getEquity'])->name('getEquity');
    Route::get('/report/getEquity/pdf', [EquityController::class, 'generatePdf'])->name('equity.pdf');

     //Cashflow
    Route::get('/report/cashflow',  [CashFlowController::class, 'index'])->name('cashflow');
    Route::get('/report/getCashFlow',  [CashFlowController::class, 'getCashFlow'])->name('getCashFlow');
    Route::get('/report/getCashFlow/pdf', [CashFlowController::class, 'generatePdf'])->name('cashflow.pdf');

    //Ledger
    Route::get('/report/ledger',  [LedgerController::class, 'index'])->name('ledger');
    Route::get('/report/getLedger',  [LedgerController::class, 'getLedger'])->name('getLedger');
    Route::get('/report/getLedger/pdf', [LedgerController::class, 'generatePdf'])->name('ledger.pdf');

    //Balance
    Route::get('/report/balance',  [BalanceController::class, 'index'])->name('balance');
    Route::get('/report/getBalance',  [BalanceController::class, 'getBalance'])->name('getBalance');
    Route::get('/report/getBalance/pdf', [BalanceController::class, 'generatePdf'])->name('balance.pdf');

    //Balance
    Route::get('/report/soa',  [SoaController::class, 'index'])->name('soa');
    Route::get('/report/getSoa',  [SoaController::class, 'getSoa'])->name('getSoa');
    Route::get('/report/getSoa/soaWA', [SoaController::class, 'soaWA'])->name('soaWA');
});


