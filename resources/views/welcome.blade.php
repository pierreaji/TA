@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
@endsection
@section('contents')
    @if (Auth::user()->role == 'Sales')
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Dashboard</h4>
                    <div class="col-12 my-4">
                        <div class="form-floating form-floating-outline">
                            <input type="month" value="{{ $date ?? date('Y-m') }}" class="form-control form-month">
                            <label>Month</label>
                        </div>
                    </div>
                </div>
                @php
                    $stocks = 0;
                    $sold_stocks = 0;
                    $total = 0;
                    $target_total = Auth::user()?->Sales?->type == 'Car' ? 70000000 : 35000000;
                    $charts = [];
                @endphp
                @foreach ($items as $item)
                    @php
                        $stocks += $item->store_stocks + $item->assign_stocks;
                        $sold_stocks += $item->sold_stocks ?? 0;
                        $total += $item->sale_price * $sold_stocks;
                    @endphp
                @endforeach
                <div class="pb-20 rounded bg-secondary p-4 text-white">
                    <div class="form-group">
                        @php
                            $persentase = ($total / $target_total) * 100;
                            $persentase = round($persentase, 2);
                        @endphp
                        <label for="">Sales Transactions Target
                            <b>{{ date('Y F', strtotime($date)) ?? date('Y F') }}</b></label>
                        <p>Rp. {{ number_format($total, 0) }} / Rp. {{ number_format($target_total, 0) }} ({{ $persentase }}%)</p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $persentase }}%;"
                                aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">{{ $persentase }}%</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 my-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">Transactions Chart</h5>
                                <small class="text-muted">{{ date('Y F', strtotime($date)) ?? date('Y F') }}</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="lineAreaChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
@section('scripts')
    <script src="{{ asset('assets') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    {{-- <script src="{{ asset('assets') }}/assets/js/charts-apex.js"></script> --}}
    <script>
        $('.form-month').on('change', function() {
            window.location.replace(`{{ url('') }}?date=${$('.form-month').val()}`)
        })
        let cardColor, headingColor, labelColor, borderColor, legendColor;

        if (isDarkStyle) {
            cardColor = config.colors_dark.cardColor;
            headingColor = config.colors_dark.headingColor;
            labelColor = config.colors_dark.textMuted;
            legendColor = config.colors_dark.bodyColor;
            borderColor = config.colors_dark.borderColor;
        } else {
            cardColor = config.colors.cardColor;
            headingColor = config.colors.headingColor;
            labelColor = config.colors.textMuted;
            legendColor = config.colors.bodyColor;
            borderColor = config.colors.borderColor;
        }

        // Color constant
        const chartColors = {
            column: {
                series1: '#826af9',
                series2: '#d2b0ff',
                bg: '#f8d3ff'
            },
            donut: {
                series1: '#fdd835',
                series2: '#32baff',
                series3: '#ffa1a1',
                series4: '#7367f0',
                series5: '#29dac7'
            },
            area: {
                series1: '#ab7efd',
                series2: '#b992fe',
                series3: '#e0cffe'
            }
        };

        var tanggalCharts = [];
        var trx = @json($transactions);
        trx.map((row, index) => {
            tanggalCharts.push(`${row.day}/${row.month}`)
        })

        const areaChartEl = document.querySelector('#lineAreaChart'),
            areaChartConfig = {
                chart: {
                    height: 400,
                    fontFamily: 'Inter',
                    type: 'area',
                    parentHeightOffset: 0,
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: false,
                    curve: 'straight'
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'start',
                    labels: {
                        colors: legendColor,
                        useSeriesColors: false
                    }
                },
                grid: {
                    borderColor: borderColor,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                colors: [chartColors.area.series3, chartColors.area.series2, chartColors.area.series1],
                series: [{
                        name: 'Items Sold',
                        data: @json($transactions->pluck('items_sold'))
                    },
                    // {
                    //     name: 'Clicks',
                    //     data: [60, 80, 70, 110, 80, 100, 90, 180, 160, 140, 200, 220, 275]
                    // },
                ],
                xaxis: {
                    categories: tanggalCharts,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '11px'
                        }
                    }
                },
                fill: {
                    opacity: 1,
                    type: 'solid'
                },
                tooltip: {
                    shared: false
                }
            };
        if (typeof areaChartEl !== undefined && areaChartEl !== null) {
            const areaChart = new ApexCharts(areaChartEl, areaChartConfig);
            areaChart.render();
        }
    </script>
@endsection
