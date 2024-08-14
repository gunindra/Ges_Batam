<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\IklanController;
use App\Http\Controllers\InformationsController;
use App\Http\Controllers\PembagirateController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\WhyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RekeningController;



// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');

// Dashboard
Route::get('/dashboardnew', [DashboardController::class, 'index'])->name('dashboard');

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
Route::get('/invoice/addinvoice', [InvoiceController::class, 'addinvoice'])->name('addinvoice');
Route::post('/invoice/addinvoice/tambainvoice', [InvoiceController::class, 'tambainvoice'])->name('tambainvoice');
Route::post('/invoice/completePayment', [InvoiceController::class, 'completePayment'])->name('completePayment');
Route::get('/invoice/deleteInvoice', [InvoiceController::class, 'deleteInvoice'])->name('deleteInvoice');
Route::get('/invoice/detailBuktiPembayaran', [InvoiceController::class, 'detailBuktiPembayaran'])->name('detailBuktiPembayaran');
Route::get('/invoice/exportPdf', [InvoiceController::class, 'exportPdf'])->name('exportPdf');

// About
Route::get('/information/abouts', [AboutController::class, 'index'])->name('abouts');
Route::get('/information/abouts/getlistAbout', [AboutController::class, 'getlistAbout'])->name('getlistAbout');
Route::get('/information/abouts/destroy', [AboutController::class, 'destroyAboutUs'])->name('destroyAboutUs');
Route::get('/information/abouts/update', [AboutController::class, 'updateAboutUs'])->name('updateAboutUs');
Route::post('/information/abouts/insert', [AboutController::class, 'insertAboutUs'])->name('insertAboutUs');

// Why
Route::get('/information/whys', [WhyController::class, 'index'])->name('whys');

// Services
Route::get('/information/services', [ServiceController::class, 'index'])->name('services');

// Carousel
Route::get('/information/carousel', [CarouselController::class, 'index'])->name('carousel');
Route::get('/information/carousel/getlistCarousel', [CarouselController::class, 'getlistCarousel'])->name('getlistCarousel');
Route::get('/information/carousel/destroy', [CarouselController::class, 'destroyCarousel'])->name('destroyCarousel');
Route::post('/information/carousel/tambah', [CarouselController::class, 'addCarousel'])->name('addCarousel');
Route::post('/information/carousel/update', [CarouselController::class, 'updateCarousel'])->name('updateCarousel');


// Informations
Route::get('/information/informations', [InformationsController::class, 'index'])->name('informations');
Route::get('/information/informations/getlistInformations', [InformationsController::class, 'getlistInformations'])->name('getlistInformations');
Route::get('/information/informations/destroy', [InformationsController::class, 'destroyInformations'])->name('destroyInformations');
Route::post('/information/informations/tambah', [InformationsController::class, 'addInformations'])->name('addInformations');
Route::post('/information/informations/update', [InformationsController::class, 'updateInformations'])->name('updateInformations');

//Iklan
Route::get('/information/iklan', [IklanController::class, 'index'])->name('iklan');
Route::get('/information/iklan/getlistIklan', [IklanController::class, 'getlistIklan'])->name('getlistIklan');
Route::get('/information/iklan/destroy', [IklanController::class, 'destroyIklan'])->name('destroyIklan');
Route::post('/information/iklan/tambah', [IklanController::class, 'addIklan'])->name('addIklan');
Route::post('/information/iklan/update', [IklanController::class, 'updateIklan'])->name('updateIklan');



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


Route::get('/', function () {
    return view('PTGes');
})->name('PTGes');

Route::get('/About', function () {
    return view('About');
})->name('About');

Route::get('/Why', function () {
    return view('Why');
})->name('Why');

Route::get('/Services', function () {
    return view('Services');
})->name('Services');

Route::get('/Services1', function () {
    return view('Services1');
});

Route::get('/Services2', function () {
    return view('Services2');
});

Route::get('/Slide', function () {
    return view('/Slide');
})->name('Slide');

Route::get('/Slide1', function () {
    return view('Slide1');
});

Route::get('/Slide2', function () {
    return view('Slide2');
});

Route::get('/Tracking', function () {
    return view('Tracking');
})->name('Tracking');
