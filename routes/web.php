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
    Admin\CategoryController
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

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
    Route::get('/pickup/getlistPickup', [PickupController::class, 'getlistPickup'])->name('getlistPickup');
    Route::post('/pickup/acceptPickup', [PickupController::class, 'acceptPickup'])->name('acceptPickup');

    // Pembayaran
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::get('/payment/addPayment', [PaymentController::class, 'addPayment'])->name('addPayment');


    // Popup
    Route::get('/content/popup', [PopupController::class, 'index'])->name('popup');
    Route::post('/content/popup/tambah', [PopupController::class, 'addPopup'])->name('addPopup');
    Route::delete('/content/popup/destroy', [PopupController::class, 'destroyPopup'])->name('destroyPopup');

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
    Route::post('/content/services/tambah', [ServiceController::class, 'addService'])->name('addService');
    Route::post('/content/services/update', [ServiceController::class, 'updateService'])->name('updateService');
    Route::delete('/content/services/destroy', [ServiceController::class, 'destroyService'])->name('destroyService');

    // heropage
    Route::get('/content/heropage', [HeropageController::class, 'index'])->name('heropage');
    Route::get('/content/heropage/getlistHeroPage', [HeropageController::class, 'getlistHeroPage'])->name('getlistHeroPage');
    Route::delete('/content/heropage/destroy', [HeropageController::class, 'destroyHeroPage'])->name('destroyHeroPage');
    Route::post('/content/heropage/tambah', [HeropageController::class, 'addHeroPage'])->name('addHeroPage');
    Route::post('/content/heropage/update', [HeropageController::class, 'updateHeroPage'])->name('updateHeroPage');

    // Informations
    Route::get('/content/informations', [InformationsController::class, 'index'])->name('informations');
    Route::get('/content/informations/getlistInformations', [InformationsController::class, 'getlistInformations'])->name('getlistInformations');
    Route::delete('/content/informations/destroy', [InformationsController::class, 'destroyInformations'])->name('destroyInformations');
    Route::post('/content/informations/tambah', [InformationsController::class, 'addInformations'])->name('addInformations');
    Route::post('/content/informations/update', [InformationsController::class, 'updateInformations'])->name('updateInformations');

    // whatsapp
    Route::get('/content/whatsapp', [WhatsappController::class, 'index'])->name('whatsapp');
    Route::post('/content/whatsapp/tambah', [WhatsappController::class, 'addWa'])->name('addWa');
    Route::delete('/content/whatsapp/destroy', [WhatsappController::class, 'destroyWa'])->name('destroyWa');
    // Advertisement
    Route::get('/content/Advertisement', [AdvertisementController::class, 'index'])->name('advertisement');
    Route::get('/content/Advertisement/getlistAdvertisement', [AdvertisementController::class, 'getlistAdvertisement'])->name('getlistAdvertisement');
    Route::delete('/content/Advertisement/destroy', [AdvertisementController::class, 'destroyAdvertisement'])->name('destroyAdvertisement');
    Route::post('/content/Advertisement/tambah', [AdvertisementController::class, 'addAdvertisement'])->name('addAdvertisement');
    Route::post('/content/Advertisement/update', [AdvertisementController::class, 'updateAdvertisement'])->name('updateAdvertisement');

    // Costumer
    Route::get('/masterdata/costumer', [CostumerController::class, 'index'])->name('costumer');
    Route::get('/masterdata/costumer/list', [CostumerController::class, 'getlistCostumer'])->name('getlistCostumer');
    Route::post('/masterdata/costumer/tambah', [CostumerController::class, 'addCostumer'])->name('addCostumer');
    Route::post('/masterdata/costumer/update', [CostumerController::class, 'updateCostumer'])->name('updateCostumer');
    Route::get('/masterdata/costumer/destroy', [CostumerController::class, 'destroyCostumer'])->name('destroyCostumer');
    Route::get('/masterdata/costumer/generateMarking', [CostumerController::class, 'generateMarking'])->name('generateMarking');

    // Driver
    Route::get('/masterdata/driver', [DriverController::class, 'index'])->name('driver');
    Route::get('/masterdata/driver/list', [DriverController::class, 'getlistDriver'])->name('getlistDriver');
    Route::post('/masterdata/driver/tambah', [DriverController::class, 'addDriver'])->name('addDriver');
    Route::post('/masterdata/driver/update', [DriverController::class, 'updateDriver'])->name('updateDriver');
    Route::get('/masterdata/driver/destroy', [DriverController::class, 'destroyDriver'])->name('destroyDriver');

    // Category
    Route::get('/masterdata/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/masterdata/category/getlistCategory', [CategoryController::class, 'getlistCategory'])->name('getlistCategory');
    Route::post('/masterdata/category/tambah', [CategoryController::class, 'addCategory'])->name('addCategory');
    Route::post('/masterdata/category/update', [CategoryController::class, 'updateCategory'])->name('updateCategory');
    Route::get('/masterdata/category/destroy', [CategoryController::class, 'destroyCategory'])->name('destroyCategory');

    // Rekening
    Route::get('/masterdata/rekening', [RekeningController::class, 'index'])->name('rekening');
    Route::get('/masterdata/rekening/list', [RekeningController::class, 'getlistRekening'])->name('getlistRekening');
    Route::post('/masterdata/rekening/tambah', [RekeningController::class, 'addRekening'])->name('addRekening');
    Route::post('/masterdata/rekening/update', [RekeningController::class, 'updateRekening'])->name('updateRekening');
    Route::get('/masterdata/rekening/destroy', [RekeningController::class, 'destroyRekening'])->name('destroyRekening');

    // Pembagi dan Rate
    Route::get('/masterdata/pembagirate', [PembagirateController::class, 'index'])->name('pembagirate');
    Route::get('/masterdata/pembagirate/list', [PembagirateController::class, 'getlistPembagi'])->name('getlistPembagi');
    Route::post('/masterdata/pembagirate/tambah', [PembagirateController::class, 'addPembagi'])->name('addPembagi');
    Route::post('/masterdata/pembagirate/update', [PembagirateController::class, 'updatePembagi'])->name('updatePembagi');
    Route::get('/masterdata/pembagirate/destroy', [PembagirateController::class, 'destroyPembagi'])->name('destroyPembagi');

    Route::get('/masterdata/rate/list', [PembagirateController::class, 'getlistRate'])->name('getlistRate');
    Route::post('/masterdata/rate/tambah', [PembagirateController::class, 'addRate'])->name('addRate');
    Route::post('/masterdata/rate/update', [PembagirateController::class, 'updateRate'])->name('updateRate');
    Route::get('/masterdata/rate/destroyrate', action: [PembagirateController::class, 'destroyRate'])->name('destroyRate');

    //user
    Route::get('/masterdata/user', [UserController::class, 'index'])->name('user');
    Route::get('/masterdata/user/list', [UserController::class, 'getlistUser'])->name('getlistUser');
    Route::post('/masterdata/user/tambah', [UserController::class, 'addUsers'])->name('addUsers');
    Route::post('/masterdata/user/update', [UserController::class, 'updateUsers'])->name('updateUsers');
    Route::get('/masterdata/user/destroy', [UserController::class, 'destroyUsers'])->name('destroyUsers');

    //role
    Route::get('/masterdata/role', [RoleController::class, 'index'])->name('role');
    Route::get('/masterdata/role/list', [RoleController::class, 'getlistRole'])->name('getlistRole');
    Route::post('/masterdata/role/tambah', [RoleController::class, 'addRole'])->name('addRole');
    Route::post('/masterdata/role/update', [RoleController::class, 'updateRole'])->name('updateRole');
    Route::get('/masterdata/role/destroy', [RoleController::class, 'destroyRole'])->name('destroyRole');
    //Menu
    Route::get('/masterdata/menu/list', [RoleController::class, 'getlistMenu'])->name('getlistMenu');

    // Vendor
    Route::get('/vendor/supplierInvoice', [SupplierInvoiceController::class, 'index'])->name('supplierInvoice');
    Route::get('/vendor/supplierInvoice/getlistSupplierInvoice', [SupplierInvoiceController::class, 'getlistSupplierInvoice'])->name('getlistSupplierInvoice');


    //Tracking
    Route::get('/tracking', [TrackingsController::class, 'index'])->name('tracking');
    Route::get('/tracking/getlistTracking', [TrackingsController::class, 'getlistTracking'])->name('getlistTracking');
    Route::post('/tracking/addTracking', [TrackingsController::class, 'addTracking'])->name('addTracking');
    Route::post('/tracking/updateTracking', [TrackingsController::class, 'updateTracking'])->name('updateTracking');
    Route::delete('/tracking/deleteTracking', [TrackingsController::class, 'deleteTracking'])->name('deleteTracking');

    //Supir
    Route::get('/supir', [SupirController::class, 'index'])->name('supir');
    Route::get('/supir/jumlahresi', [SupirController::class, 'jumlahresi'])->name('jumlahresi');
    Route::post('/supir/tambahdata', [SupirController::class, 'tambahdata'])->name('tambahdata');
    });


