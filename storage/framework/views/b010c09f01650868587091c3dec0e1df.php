<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('main'); ?>


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            
        </div>

        <div class="row mb-3">

            

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-80">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Count Invoice</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($countInvoice); ?></div>
                                
                            </div>
                            <div class="col-auto">
                                
                                <i class="fas fa-file-invoice fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-80">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">User</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($countUser); ?></div>
                                
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            


            <div class="col-xl-12 ">
                
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Monthly Recap Chart</h6>
                        <div class="dropdown no-arrow d-flex">
                            

                            <button id="monthEvent" class="btn btn-light form-control" style="border: 1px solid #e9ecef;">
                                <span id="calendarTitle" class="fs-4"></span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="chartDashboard"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        function getCurrentMonth() {
            const months = [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ];

            const currentDate = new Date();
            const currentMonth = months[currentDate.getMonth()];
            const currentYear = currentDate.getFullYear();

            return `${currentMonth} ${currentYear}`;
        }

        let selectedMonth = '';

        $(document).ready(function() {
            $('#calendarTitle').text(getCurrentMonth());

            const monthFilterInput = $('#monthEvent');

            const flatpickrInstance = flatpickr(monthFilterInput[0], {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "M Y",
                        altFormat: "M Y",
                        theme: "light"
                    })
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    const selectedDate = selectedDates[0];
                    selectedMonth = instance.formatDate(selectedDate, "M Y");
                    $('#calendarTitle').text(selectedMonth);
                    getDataDashboard();
                }
            });

            function triggerChange() {
                const today = new Date();
                flatpickrInstance.setDate(today, true);
            }

            triggerChange();
        });

        function getDataDashboard() {
            $.ajax({
                url: "<?php echo e(route('fetchMonthlyData')); ?>",
                type: 'GET',
                data: {
                    month: selectedMonth
                },
                success: function(response) {
                    updateChart(response.data);
                },
                error: function(error) {
                    showMessage("error", "Gagal Mengambil Data");
                }
            });
        }

        function updateChart(data) {
            const selectedDate = new Date(selectedMonth);
            const year = selectedDate.getFullYear();
            const month = selectedDate.getMonth();

            const labels = [];
            const revenues = [];
            const daysInMonth = new Date(year, month + 1, 0).getDate();


            console.log("Ini label dan revenue",labels, revenues);

            const monthName = selectedDate.toLocaleString('default', {
                month: 'short'
            });

            for (let day = 1; day <= daysInMonth; day++) {
                labels.push(`${String(day).padStart(2, '0')} ${monthName}`);
                revenues.push(0);
            }

            data.forEach(item => {
                const day = parseInt(item.date.split('-')[2], 10);
                const index = day - 1;
                revenues[index] = item.daily_revenue;
            });
            myLineChart.data.labels = labels;
            myLineChart.data.datasets[0].data = revenues;
            myLineChart.update();
        }

        Chart.defaults.global.defaultFontFamily = 'Nunito',
            '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Area Chart Example
        var ctx = document.getElementById("chartDashboard");
        var myLineChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: "Invoice Total Harga",
                    backgroundColor: (context) => {
                        const chart = context.chart;
                        const {
                            ctx,
                            chartArea
                        } = chart;

                        if (!chartArea) {
                            return null;
                        }

                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea
                        .top);
                        gradient.addColorStop(0, "rgba(78, 115, 223, 0.5)");
                        gradient.addColorStop(1, "rgba(78, 115, 223, 1)");
                        return gradient;
                    },
                    borderColor: "rgba(78, 115, 223, 1)",
                    borderRadius: 5,
                    barPercentage: 0.8,
                    categoryPercentage: 0.8,
                    data: [],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleColor: '#6e707e',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': Rp.' + number_format(tooltipItem.raw);
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        formatter: (value) => 'Rp.' + number_format(value),
                        color: '#4e73df',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        ticks: {
                            callback: function(value) {
                                return 'Rp.' + number_format(value);
                            }
                        }
                    }
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': Rp.' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views\dashboard\indexdashboard.blade.php ENDPATH**/ ?>