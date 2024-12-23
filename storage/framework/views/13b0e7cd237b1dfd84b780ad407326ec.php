<?php $__env->startSection('title', 'Customer | Credit Note'); ?>

<?php $__env->startSection('main'); ?>


<style>
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
</style>

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Credit Note</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Customer</li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('creditnote')); ?>">Credit Note</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Credit Note</li>
        </ol>
    </div>
    <a class="btn btn-primary mb-3" href="<?php echo e(route('creditnote')); ?>">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-6">
                            
                            <div class="mt-3">
                                <label for="invoiceCredit" class="form-label fw-bold">Invoice</label>
                                <select class="form-control select2" name="" id="invoiceCredit">
                                    <option value="" selected disabled>Pilih Invoice</option>
                                    <?php $__currentLoopData = $listInvoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($invoice->id); ?>"><?php echo e($invoice->no_invoice); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="invoiceCreditError" class="text-danger mt-1 d-none">Silahkan Pilih Invoice
                                    terlebih dahulu</div>
                            </div>

                            <div class="mt-3">
                                <label for="currencyCredit" class="form-label fw-bold">Currency</label>
                                <select class="form-control col-8" name="" id="currencyCredit">
                                    <?php $__currentLoopData = $listCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($currency->id); ?>">
                                            <?php echo e($currency->nama_matauang); ?> (<?php echo e($currency->singkatan_matauang); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="currencyCreditError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
                                    terlebih dahulu</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="accountCredit" class="form-label fw-bold">Account</label>
                                <select class="form-control select2" name="" id="accountCredit" style="">
                                    <option value="" selected disabled>Pilih Account</option>
                                    <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coa->id); ?>"><?php echo e($coa->code_account_id); ?> -
                                            <?php echo e($coa->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="accountCreditError" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                    terlebih dahulu</div>
                            </div>
                            <div class="mt-3">
                                <div class="mt-3" id="rateCurrencySection" style="display: none;">
                                    <label for="rateCurrency" class="form-label fw-bold">Rate Currency</label>
                                    <input type="text" class="form-control col-8" id="rateCurrency" value=""
                                        placeholder="Masukkan rate">
                                    <div id="rateCurrencyError" class="text-danger mt-1 d-none">Rate tidak boleh kosong
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-label ml-3 mt-5 mb-3">Item List</div>
                    <div class="table-responsive col-12 ms-5">
                        <table class="table mb-0" style="border-bottom : 1px solid black;">
                            <thead>
                                <tr>
                                    <th>No Resi</th>
                                    <th>Deskripsi</th>
                                    <th>Nominal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="items-container">


                                <tr class="tr">
                                    <td>
                                        <input required="" type="text" class="form-control" name="noresi">
                                    </td>
                                    <td>
                                        <input required="" type="text" class="form-control" name="deskripsi">
                                    </td>
                                    <td>
                                        <input required="" type="number" class="form-control" name="harga">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1"
                                            style="display:none;">Remove</button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <br>
                        <button type="button" class="btn btn-primary" id="add-item-button">Add
                            Item</button>

                    </div>
                    <div class="row">
                        <div class="col-5 mr-5">
                            <div class="input-group pt-2 mt-3">
                                <label for="noteCredit" class="form-label fw-bold p-1">Note</label>
                            </div>
                            <textarea id="noteCredit" class="form-control" aria-label="With textarea"
                                placeholder="Masukkan note" rows="4"></textarea>
                        </div>
                        <div class="col-4 ms-5 mt-5 ml-5">
                            <div class="mb-3" style="border-bottom:1px solid black;">
                                <label class="inline-label">Total keseluruhan : </label>
                                <input disabled="" type="text" id="Total" class="form-control-flush inline-input"
                                    name="subtotal" placeholder="0" fdprocessedid="nox2">
                            </div>
                        </div>
                        <div id="noteCreditError" class="text-danger mt-1 d-none">Silahkan isi Note</div>
                        <div class="col-12 mt-4">
                            <div class="col-4 float-right">
                                <button id="buatCredit" class="btn btn-primary p-3 float-right mt-3"
                                    style="width: 100%;">Buat
                                    Credit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            $('#currencyCredit').change(function () {
                const selectedCurrency = $(this).val();

                if (selectedCurrency !== '1') {
                    $('#rateCurrencySection').show();
                } else {
                    $('#rateCurrencySection').hide();
                }
                updateTotals();
            });

            const updateTotals = () => {
                let totalKeseluruhan = 0;

                $('#items-container tr').each(function () {
                    const harga = parseFloat($(this).find('input[name="harga"]').val()) || 0;
                    totalKeseluruhan += harga; 
                });

                $('#Total').val(totalKeseluruhan.toFixed(2));  
            };

            $('#add-item-button').click(function () {
                const newRow = `
            <tr>
                <td><input required type="text" class="form-control" name="noresi"></td>
                <td><input required type="text" class="form-control" name="deskripsi"></td>
                <td><input required type="number" class="form-control" name="harga"></td>
                <td><button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button></td>
            </tr>
        `;

                $('#items-container').append(newRow);

                if ($('#items-container tr').length > 1) {
                    $('.removeItemButton').show();
                }

                updateTotals();
            });

            // Remove item from the table
            $(document).on('click', '.removeItemButton', function () {
                if ($('#items-container tr').length > 1) {
                    $(this).closest('tr').remove();
                }

                if ($('#items-container tr').length === 1) {
                    $('.removeItemButton').hide();
                }
 
                updateTotals();
            });

            $(document).on('input', 'input[name="harga"]', function () {
                updateTotals();  
            });

            $('#buatCredit').click(function () {
                const invoiceCredit = $('#invoiceCredit').val();
                const accountCredit = $('#accountCredit').val();
                const currencyCredit = $('#currencyCredit').val();
                const noteCredit = $('#noteCredit').val();
                const rateCurrency = $('#rateCurrency').val();
                const items = [];

                $('#items-container tr').each(function () {
                    const noresi = $(this).find('input[name="noresi"]').val();
                    const deskripsi = $(this).find('input[name="deskripsi"]').val();
                    const harga = $(this).find('input[name="harga"]').val();

                    items.push({
                        noresi: noresi,
                        deskripsi: deskripsi,
                        harga: harga
                    });
                });

                const creditNoteData = {
                    invoiceCredit: invoiceCredit,
                    accountCredit: accountCredit,
                    currencyCredit: currencyCredit,
                    rateCurrency: rateCurrency,
                    noteCredit: noteCredit,
                    items: items,
                    totalKeseluruhan: $('#Total').val()
                };

                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "<?php echo e(route('credit-note.store')); ?>",
                    type: 'POST',
                    data: creditNoteData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    dataType: 'json',
                    success: function (response) {
                        showMessage('success', 'Credit Note berhasil disimpan!')
                            .then(() => {
                                location.reload();
                            });
                    },
                    error: function (xhr) {
                        let errorMessage = "Gagal membuat invoice.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: errorMessage,
                            icon: "error"
                        });
                    }
                });
            });
        });

    </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views\customer\creditnote\buatcreditnote.blade.php ENDPATH**/ ?>