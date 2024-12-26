<?php $__env->startSection('title', 'Accounting | Accounting Setting'); ?>

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

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Accounting Setting</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Accounting</li>
                <li class="breadcrumb-item active" aria-current="page">Accounting Setting</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg">
                <div class="card mb-4">
                    <div class="card-body">
                        <input type="hidden" id="idData" value="<?php echo e($createdAtStatus ?? ''); ?>">
                        <div id="containerBooking" class="table-responsive px-3">
                            <div class="mt-3">
                                <label for="salesAccount" class="form-label fw-bold">Sales Account</label>
                                <select class="form-control select2singgle" id="Sales" required>
                                    <option value="">Pilih Akun</option>
                                    <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coa->id); ?>"
                                            <?php echo e($accountSettings && $accountSettings->sales_account_id == $coa->id ? 'selected' : ''); ?>>
                                            <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="receivableSalesAccount" class="form-label fw-bold">Receivable Sales
                                    Account</label>
                                <select class="form-control select2singgle" id="Receivable" required>
                                    <option value="">Pilih Akun</option>
                                    <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coa->id); ?>"
                                            <?php echo e($accountSettings && $accountSettings->receivable_sales_account_id == $coa->id ? 'selected' : ''); ?>>
                                            <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="returnAccount" class="form-label fw-bold">Customer Sales Return Account</label>
                                <select class="form-control select2singgle" id="Return" required>
                                    <option value="">Pilih Akun</option>
                                    <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coa->id); ?>"
                                            <?php echo e($accountSettings && $accountSettings->customer_sales_return_account_id == $coa->id ? 'selected' : ''); ?>>
                                            <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="discountAccount" class="form-label fw-bold">Customer Paymennt Kuota Margin</label>
                                <select class="form-control select2singgle" id="Discount" required>
                                    <option value="">Pilih Akun</option>
                                    <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coa->id); ?>"
                                            <?php echo e($accountSettings && $accountSettings->discount_sales_account_id == $coa->id ? 'selected' : ''); ?>>
                                            <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="mt-3">
                                <label for="purchaseProfitAccount" class="form-label fw-bold">Setting Kredit untuk Top Up</label>
                                <select class="form-control select2singgle" id="PurchaseRate" required>
                                    <option value="">Pilih Akun</option>
                                    <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coa->id); ?>"
                                            <?php echo e($accountSettings && $accountSettings->purchase_profit_rate_account_id == $coa->id ? 'selected' : ''); ?>>
                                            <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="mt-3">
                                <label for="supplierAccount" class="form-label fw-bold">Supplier Purchase Return
                                    Account</label>
                                <select class="form-control select2singgle" id="Supplier" required>
                                    <option value="">Pilih Akun</option>
                                    <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coa->id); ?>"
                                            <?php echo e($accountSettings && $accountSettings->supplier_purchase_return_account_id == $coa->id ? 'selected' : ''); ?>>
                                            <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="mt-3">
                                <label for="salesProfitAccount" class="form-label fw-bold">Discount Payment Account</label>
                                <select class="form-control select2singgle" id="ProfitRate" required>
                                    <option value="">Pilih Akun</option>
                                    <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($coa->id); ?>"
                                            <?php echo e($accountSettings && $accountSettings->sales_profit_rate_account_id == $coa->id ? 'selected' : ''); ?>>
                                            <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        <div class="mt-3">
                        <label for="PaymentAcoount" class="form-label fw-bold">Payment Account</label>
                            <select class="form-control" id="PaymentAccount" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedPaymentAccounts)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="OperatingRevenue" class="form-label fw-bold">Operating Revenue Account</label>
                            <select class="form-control" id="OperatingRevenue" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedOperatingRevenue)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="OperatingExpense" class="form-label fw-bold">Operating Expense Account</label>
                            <select class="form-control" id="OperatingExpense" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedOperatingExpense)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="NonOperatingRevenue" class="form-label fw-bold">Non Operating Revenue Account</label>
                            <select class="form-control" id="NonOperatingRevenue" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedNonOperatingRevenue)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="NonOperatingExpense" class="form-label fw-bold">Non Operating Expense Account</label>
                            <select class="form-control" id="NonOperatingExpense" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedNonOperatingExpense)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="CapitalAccount" class="form-label fw-bold">Capital Account</label>
                            <select class="form-control" id="CapitalAccount" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedCapitalAccount)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="AdditionalCapitalAccount" class="form-label fw-bold">Additional Capital Account</label>
                            <select class="form-control" id="AdditionalCapitalAccount" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedAdditionalCapitalAccount)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="ReturnedProfitAccount" class="form-label fw-bold">Returned Profit Account</label>
                            <select class="form-control" id="ReturnedProfitAccount" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedReturnedProfitAccount)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="CurrentProfitAccount" class="form-label fw-bold">Curren Year Profit Account</label>
                            <select class="form-control" id="CurrentProfitAccount" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedCurrentProfitAccount)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="DevidenAccount" class="form-label fw-bold">Deviden Account</label>
                            <select class="form-control" id="DevidenAccount" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedDevidenAccount)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="InvestingAccount" class="form-label fw-bold">Investing Account</label>
                            <select class="form-control" id="InvestingAccount" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedInvestingAccount)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mt-3">
                        <label for="FinancingAccount" class="form-label fw-bold">Financing Account</label>
                            <select class="form-control" id="FinancingAccount" style="width:100%;" multiple="multiple">
                                <option value="">Pilih Akun</option>
                                <?php $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>" 
                                        <?php if(in_array($coa->id, $savedFinancingAccount)): ?> selected <?php endif; ?>>
                                        <?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div id="containerBooking" class="table-responsive px-3">
                            <div class="d-flex justify-content-center mb-2 mr-3">
                                <button id="saveButton" class="btn btn-primary p-3 float-right mt-3" style="width: 30%;"
                                    disabled>Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!---Container Fluid-->

    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('script'); ?>

        <script>
            $('.select2singgle').select2();
            const $saveButton = $('#saveButton');
            const selectedValues = {};

            const accountSettingId = $('#idData').val();

            $('#PaymentAccount').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#OperatingRevenue').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#OperatingExpense').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#NonOperatingRevenue').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#NonOperatingExpense').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#CapitalAccount').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#AdditionalCapitalAccount').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#ReturnedProfitAccount').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#CurrentProfitAccount').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#DevidenAccount').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#InvestingAccount').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            $('#FinancingAccount').select2({
                placeholder: 'Pilih Akun',
                allowClear: true
            });
            
            $('select').on('change', function() {
                const dropdownName = $(this).attr('name');
                const selectedValue = $(this).val();
                selectedValues[dropdownName] = selectedValue;
                $saveButton.removeClass('btn-secondary').addClass('btn-primary').prop('disabled', false);
            });

            $('#saveButton').on('click', function(e) {
            e.preventDefault();

            const Data = {
                idData: $('#idData').val(),
                sales_account_id: $('#Sales').val(),
                receivable_sales_account_id: $('#Receivable').val(),
                customer_sales_return_account_id: $('#Return').val(),
                discount_sales_account_id: $('#Discount').val(),
                sales_profit_rate_account_id: $('#ProfitRate').val(),
                sales_loss_rate_account_id: $('#LossRate').val(),
                purchase_account_id: $('#Purchase').val(),
                debt_account_id: $('#Debt').val(),
                supplier_purchase_return_account_id: $('#Supplier').val(),
                discount_purchase_account_id: $('#DiscountPurchase').val(),
                purchase_profit_rate_account_id: $('#PurchaseRate').val(),
                purchase_loss_rate_account_id: $('#PurchaseLoss').val(),
                coa_id: $("#PaymentAccount").val(),
                operating_revenue : $("#OperatingRevenue").val(),
                operating_expense : $("#OperatingExpense").val(),
                non_operating_revenue : $("#NonOperatingRevenue").val(),
                non_operating_expense : $("#NonOperatingExpense").val(),
                capital : $("#CapitalAccount").val(),
                additional_capital : $("#AdditionalCapitalAccount").val(),
                returned_profit : $("#ReturnedProfitAccount").val(),
                current_profit : $("#CurrentProfitAccount").val(),
                deviden : $("#DevidenAccount").val(),
                investing : $("#InvestingAccount").val(),
                financing : $("#FinancingAccount").val()
                
            };

            Swal.fire({
                title: 'Memproses...',
                text: 'Harap tunggu, sedang memperbarui data.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/account-settings/store',  
                type: 'POST',
                data: Data,  
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.close();
                    showMessage("success", "Data berhasil disimpan").then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({
                        title: 'Terjadi Kesalahan!',
                        text: xhr.responseJSON.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

    </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\accounting\accountingSetting\indexaccountsetting.blade.php ENDPATH**/ ?>