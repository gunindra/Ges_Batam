<?php


use App\Http\Controllers\PopupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AboutController,
    AboutsController,
    CarouselController,
    IklanController,
    InformationsController,
    PembagirateController,
    PtgesController,
    ServiceController,
    ServicesController,
    SlideController,
    TrackingController,
    WhyController,
    WhysController,
    DashboardController,
    LoginController,
    BookingController,
    DeliveryController,
    InvoiceController,
    CostumerController,
    DriverController,
    RekeningController,
    ProfileController,
    PickupController,
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


// Booking Confirmation
Route::get('/booking', [BookingController::class, 'index'])->name('booking');
Route::get('/booking/list', [BookingController::class, 'getlistBooking'])->name('getlistBooking');
Route::get('/booking/dataBookingForm', [BookingController::class, 'dataBookingForm'])->name('dataBookingForm');

// Delivery
Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery');
Route::get('/delivery/getlistDelivery', [DeliveryController::class, 'getlistDelivery'])->name('getlistDelivery');
Route::get('/delivery/acceptPengantaran', [DeliveryController::class, 'acceptPengantaran'])->name('acceptPengantaran');
Route::get('/delivery/detailBuktiPengantaran', [DeliveryController::class, 'detailBuktiPengantaran'])->name('detailBuktiPengantaran');
Route::post('/delivery/confirmasiPengantaran', [DeliveryController::class, 'confirmasiPengantaran'])->name('confirmasiPengantaran');

// Invoice
Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice');
Route::get('/invoice/getlistInvoice', [InvoiceController::class, 'getlistInvoice'])->name('getlistInvoice');
Route::get('/invoice/getlistCicilan', [InvoiceController::class, 'getlistCicilan'])->name('getlistCicilan');
Route::get('/invoice/getlistHeadCicilan', [InvoiceController::class, 'getlistHeadCicilan'])->name('getlistHeadCicilan');
Route::get('/invoice/addinvoice', [InvoiceController::class, 'addinvoice'])->name('addinvoice');
Route::post('/invoice/addinvoice/tambainvoice', [InvoiceController::class, 'tambainvoice'])->name('tambainvoice');
Route::post('/invoice/completePayment', [InvoiceController::class, 'completePayment'])->name('completePayment');
Route::get('/invoice/deleteInvoice', [InvoiceController::class, 'deleteInvoice'])->name('deleteInvoice');
Route::get('/invoice/detailBuktiPembayaran', [InvoiceController::class, 'detailBuktiPembayaran'])->name('detailBuktiPembayaran');
Route::get('/invoice/editinvoice/{id}', [InvoiceController::class, 'editinvoice'])->name('editinvoice');
Route::get('/invoice/cicilanInvoice/{id}', [InvoiceController::class, 'cicilanInvoice'])->name('cicilanInvoice');
Route::post('/invoice/bayarTagihan', [InvoiceController::class, 'bayarTagihan'])->name('bayarTagihan');
Route::get('/invoice/exportPdf', [InvoiceController::class, 'exportPdf'])->name('exportPdf');

// Pickup
Route::get('/pickup', [PickupController::class, 'index'])->name('pickup');
Route::get('/pickup/getlistPickup', [PickupController::class, 'getlistPickup'])->name('getlistPickup');

// Popup
Route::get('/content/popup', [PopupController::class, 'index'])->name('popup');
Route::post('/content/popup/tambah', [PopupController::class, 'addPopup'])->name('addPopup');
Route::delete('/content/popup/destroy', [PopupController::class, 'destroyPopup'])->name('destroyPopup');


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
Route::get('/content/services/destroy', [ServiceController::class, 'destroyService'])->name('destroyService');

// Carousel
Route::get('/content/carousel', [CarouselController::class, 'index'])->name('carousel');
Route::get('/content/carousel/getlistCarousel', [CarouselController::class, 'getlistCarousel'])->name('getlistCarousel');
Route::get('/content/carousel/destroy', [CarouselController::class, 'destroyCarousel'])->name('destroyCarousel');
Route::post('/content/carousel/tambah', [CarouselController::class, 'addCarousel'])->name('addCarousel');
Route::post('/content/carousel/update', [CarouselController::class, 'updateCarousel'])->name('updateCarousel');

// Informations
Route::get('/content/informations', [InformationsController::class, 'index'])->name('informations');
Route::get('/content/informations/getlistInformations', [InformationsController::class, 'getlistInformations'])->name('getlistInformations');
Route::get('/content/informations/destroy', [InformationsController::class, 'destroyInformations'])->name('destroyInformations');
Route::post('/content/informations/tambah', [InformationsController::class, 'addInformations'])->name('addInformations');
Route::post('/content/informations/update', [InformationsController::class, 'updateInformations'])->name('updateInformations');

// Iklan
Route::get('/content/iklan', [IklanController::class, 'index'])->name('iklan');
Route::get('/content/iklan/getlistIklan', [IklanController::class, 'getlistIklan'])->name('getlistIklan');
Route::get('/content/iklan/destroy', [IklanController::class, 'destroyIklan'])->name('destroyIklan');
Route::post('/content/iklan/tambah', [IklanController::class, 'addIklan'])->name('addIklan');
Route::post('/content/iklan/update', [IklanController::class, 'updateIklan'])->name('updateIklan');

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
Route::get('/masterdata/rate/destroyrate', [PembagirateController::class, 'destroyRate'])->name('destroyRate');

});
