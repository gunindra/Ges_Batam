@extends('layout.main')

@section('title', 'Edit Invoice')

@section('main')

<style>
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #cccccc;
    }

    .divider::before {
        margin-right: .25em;
    }

    .divider::after {
        margin-left: .25em;
    }

    .divider span {
        padding: 0 10px;
        font-weight: bold;
        color: #555555;
    }

    .select2-container--default .select2-selection--single {
        height: 40px;
        border: 1px solid #d1d3e2;
        border-radius: 0.25rem;
        padding: 6px 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 27px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }


    #pickupDelivery h2 {
        font-size: 2rem;
        color: #298be2;
        font-weight: bold;
        text-align: center;
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }
</style>


<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Invoice</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Costumer</li>
            <li class="breadcrumb-item"><a href="{{ route('invoice') }}">Invoice</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Invoice</li>
        </ol>
    </div>

    <div class="d-flex justify-content-between">
        <a class="btn btn-primary mb-3" href="{{ route('invoice') }}">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

        <a class="btn btn-danger mb-3">
            <i class="fas fa-trash"></i>
            Delete
        </a>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="noResi" class="form-label fw-bold">No Invoice : </label>
                                <div class="d-flex">
                                    <h2 class="fw-bold" id="noInvoice">{{ $invoice->no_invoice }}</h2>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                                <input type="text" class="form-control col-8" id="tanggal" value=""
                                    placeholder="Pilih tanggal">
                                <div id="tanggalError" class="text-danger mt-1 d-none">Tanggal tidak boleh kosong</div>
                            </div>
                            <div class="mt-3">
                                <label for="tanggal" class="form-label fw-bold">Tanggal Buat</label>
                                <input type="text" class="form-control col-8" id="tanggalBuat" value=""
                                    placeholder="Pilih tanggal" disabled>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="currencyInvoice" class="form-label fw-bold">Currency</label>
                                <select class="form-control col-8" name="" id="currencyInvoice">
                                    <option value="" selected disabled>Pilih Currency</option>
                                    @foreach ($listCurrency as $currency)
                                        <option value="{{ $currency->id }}">
                                            {{ $currency->nama_matauang }} ({{ $currency->singkatan_matauang }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="currencyInvoiceError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
                                    terlebih dahulu</div>
                            </div>
                            <div class="mt-3" id="rateCurrencySection" style="display: none;">
                                <label for="rateCurrency" class="form-label fw-bold">Rate Currency</label>
                                <input type="text" class="form-control col-8" id="rateCurrency" value=""
                                    placeholder="Masukkan rate">
                                <div id="rateCurrencyError" class="text-danger mt-1 d-none">Rate tidak boleh kosong
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider mt-4">
                        <span>Costumer</span>
                    </div>
                    <div class="d-flex">
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="customer" class="form-label fw-bold col-12">Customer</label>
                                <select class="form-control select2singgle" id="selectCostumer" style="width: 67%">
                                    <option value="" selected disabled>Pilih Customer</option>
                                    @foreach ($listPembeli as $pembeli)
                                        <option value="{{ $pembeli->id }}" data-metode="{{ $pembeli->metode_pengiriman }}"
                                            data-minrate="{{ $pembeli->minimum_rate }}"
                                            data-maxrate="{{ $pembeli->maximum_rate }}" data-alamat="{{ $pembeli->alamat }}"
                                            data-jumlahalamat="{{ $pembeli->jumlah_alamat }}">
                                            {{ $pembeli->marking }} - {{ $pembeli->nama_pembeli }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="customerError" class="text-danger mt-1 d-none">Silahkan Pilih Customer</div>
                            </div>
                            <div class="mt-3" id="alamatContainer">
                            </div>
                            <div id="alamatError" class="text-danger mt-1 d-none">Silahkan Pilih Alamat</div>

                        </div>
                        <div class="col-6">
                            <div class="mt-5" id="pickupDelivery" style="display: none;">
                                <h2></h2>
                            </div>
                        </div>
                    </div>
                    <div class="divider mt-4">
                        <span>Detail Barang</span>
                    </div>
                    <!-- Pilihan Rate Berat dan Volume -->
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="rateSelect" class="form-label fw-bold">Rate (Berat)</label>
                            <select class="form-control" id="rateBerat">
                                <option value="" selected disabled>Pilih Rate</option>
                                @foreach ($listRateVolume as $rate)
                                    @if ($rate->rate_for == 'Berat')
                                        <option value="{{ $rate->id }}">
                                            {{ number_format($rate->nilai_rate, 0, ',', '.') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="rateBeratError" class="text-danger mt-1 d-none">Silahkan Pilih Rate</div>
                        </div>
                        <div class="col-sm-6">
                            <label for="pembagiVolume" class="form-label fw-bold">Pembagi</label>
                            <select class="form-control" id="pembagiVolume">
                                <option value="" selected disabled>Pilih Pembagi</option>
                                @foreach ($listPembagi as $pembagi)
                                    <option value="{{ $pembagi->id }}">
                                        {{ number_format($pembagi->nilai_pembagi, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="pembagiVolumeError" class="text-danger mt-1 d-none">Silahkan Pilih Pembagi</div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-sm-6">
                            <label for="rate" class="form-label fw-bold">Rate (Volume)</label>
                            <select class="form-control" id="rateVolume">
                                <option value="" selected disabled>Pilih Rate</option>
                                @foreach ($listRateVolume as $rate)
                                    @if ($rate->rate_for == 'Volume')
                                        <option value="{{ $rate->id }}">
                                            {{ number_format($rate->nilai_rate, 0, ',', '.') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="rateVolumeError" class="text-danger mt-1 d-none">Silahkan Pilih Rate</div>
                        </div>
                    </div>

                    <div class="form-grup row mt-3">
                        <div class="col-sm-6">
                            <label for="scanResi" class="form-label fw-bold">Scan No Resi</label>
                            <input type="text" class="form-control" id="scanNoresi"
                                placeholder="Silahkan scan resi disini">
                        </div>
                    </div>

                    <!-- Tabel Input Berat dan Dimensi -->
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Resi</th>
                                <th>Berat/Dimensi</th>
                                <th>Hitungan</th>
                                <th>Harga</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="barang-list">
                        </tbody>
                    </table>
                    <!-- Tombol Tambah Barang -->
                    {{-- <button type="button" class="btn btn-primary" id="addItemBtn"><span class="pr-2"><i
                                class="fas fa-plus"></i></span>Tambah Barang</button> --}}
                    <div class="row">
                        <div class="col-12 mt-4" id="totalIdr" style="display: none;">
                            <div class="col-2 offset-8 me-1">
                                <p class="mb-0">Total Idr</p>
                                <div class="box bg-light text-dark p-3 mt-2"
                                    style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                    <span id="idrCurrentCy" style="font-weight: bold; color: #555;">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="col-4 float-right">
                            <p class="mb-0">Total Harga</p>
                            <div class="box bg-light text-dark p-3 mt-2"
                                style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                <span id="total-harga" style="font-weight: bold; color: #555;">Rp.
                                    {{ number_format($invoice->total_harga, 2, ',', '.') }}</span>
                            </div>
                            <input type="hidden" name="" id="totalHargaValue">
                            <button id="buatInvoice" class="btn btn-primary p-3 float-right mt-3"
                                style="width: 100%;">Buat
                                Invoice</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!---Container Fluid-->

@endsection
@section('script')

<script>
    $(document).ready(function () {
        var invoice = @json($invoice);

        $('.select2').select2();
        $('#tanggal, #tanggalBuat').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        });

        if (invoice) {
            $('#currencyInvoice').val(invoice.matauang_id).trigger('change');
            $('#selectCostumer').val(invoice.pembeli_id).trigger('change');
            if (invoice.tanggal_invoice) {
                const rawDate = new Date(invoice.tanggal_invoice);
                $('#tanggal').datepicker('setDate', rawDate);
            }
            if (invoice.tanggal_buat) {
                const rawDate = new Date(invoice.tanggal_buat);
                $('#tanggalBuat').datepicker('setDate', rawDate);
            }
            $('#rateBerat').val(invoice.nilai_rate).trigger('change');

        }
        invoice.forEach(function(invoice) {
            const newRow = `
               <tr data-index="${invoice.id}">
                    <td class="item-number">${invoice}</td>
                    <td name="noResi[]" >${noResi}</td> 
                    <td>
                        <select class="form-control selectBeratDimensi" data-index="${invoice}">
                            <option value="berat">Berat</option>
                            <option value="dimensi">Dimensi</option>
                        </select>
                    </td>
                    <td class="hitungan" data-index="${itemIndex}">
                        <input type="number" class="form-control beratBarang" data-index="${itemIndex}" name="beratBarang[]" placeholder="Masukkan Berat (Kg)" min="0" step="0.01">
                    </td>
                    <td><span class="hargaBarang">Rp. 0</span></td>
                    <td><button type="button" class="btn btn-danger remove-item"><span class="pr-2"><i class="fas fa-trash"></i></span>Hapus</button></td>
                </tr>`;
            $('#barang-list').append(newRow);
        });

        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();
        });

    });

</script>

@endsection