@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="#">
                
                    <div class="row">
                        @foreach ($data as $karyawan)
                        <div class="col-md-6">
                            <a href="{{ route('admin.hasil-kerja.karyawan', $karyawan->id) }}">
                                <div class="wg-chart-default mb-20">
                                    <div class="d-flex align-items-center">
                                        <div class="image ic-bg me-3">
                                            <img src="{{ asset('assets/images/approval.png') }}" alt="Approval Icon">
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Hasil dari {{ $karyawan->karyawan_name }} menunggu
                                                Approval</div>
                                            <h4>{{ $karyawan->jumlah_pending_approval }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.hasil-kerja.karyawan', $karyawan->id) }}">
                                <div class="wg-chart-default mb-20">
                                    <div class="d-flex align-items-center">
                                        <div class="image ic-bg me-3">
                                            <img src="{{ asset('assets/images/pay.png') }}" alt="Pay Icon">
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Hasil dari {{ $karyawan->karyawan_name }} belum
                                                dibayar</div>
                                            <h4>{{ $karyawan->jumlah_belum_dibayar }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-2">
                            <a href="{{ route('admin.hasil-kerja.karyawan', $karyawan->id) }}">
                                <div class="wg-chart-default mb-20">
                                    <div class="d-flex align-items-center">
                                        <div class="image ic-bg me-3">
                                            <img src="{{ asset('assets/images/rejected.png') }}" alt="Rejected Icon">
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Hasil dari {{ $karyawan->karyawan_name }}
                                                ditolak</div>
                                            <h4>{{ $karyawan->jumlah_ditolak }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </div>
@endsection


{{-- @push('scripts')
<script>
    (function ($) {

        var tfLineChart = (function () {

            var chartBar = function () {

                var options = {
                    series: [{
                        name: 'Total',
                        data: [{{ $AmountM }}]
                    }, {
                        name: 'Pending',
                        data: [{{ $orderedAmountM }}]
                    },
                    {
                        name: 'Delivered',
                        data: [{{ $deliveredAmountM }}]
                    }, {
                        name: 'Canceled',
                        data: [{{ $canceledAmountM }}]
                    }],
                    chart: {
                        type: 'bar',
                        height: 325,
                        toolbar: {
                            show: false,
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '10px',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false,
                    },
                    colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                    stroke: {
                        show: false,
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: '#212529',
                            },
                        },
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    },
                    yaxis: {
                        show: false,
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "$ " + val + ""
                            }
                        }
                    }
                };

                chart = new ApexCharts(
                    document.querySelector("#line-chart-8"),
                    options
                );
                if ($("#line-chart-8").length > 0) {
                    chart.render();
                }
            };

            /* Function ============ */
            return {
                init: function () { },

                load: function () {
                    chartBar();
                },
                resize: function () { },
            };
        })();

        jQuery(document).ready(function () { });

        jQuery(window).on("load", function () {
            tfLineChart.load();
        });

        jQuery(window).on("resize", function () { });
    })(jQuery);
</script>
@endpush --}}
