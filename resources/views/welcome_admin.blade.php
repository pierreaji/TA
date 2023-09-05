@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
@endsection
@section('contents')
    @if (Auth::user()->role == 'Admin')
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Dashboard</h4>
                    <div class="card h-100">
                        <div class="card-body d-flex justify-content-between flex-wrap gap-3">
                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-account-outline mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ \App\Models\UserSales::count() }}</h4>
                                    <small class="text-muted">Sales</small>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-warning rounded">
                                        <i class="mdi mdi-poll mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ \App\Models\Item::count() }}</h4>
                                    <small class="text-muted">Items</small>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-info rounded">
                                        <i class="mdi mdi-trending-up mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ \App\Models\Transaction::count() }}</h4>
                                    <small class="text-muted">Transactions</small>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded">
                                        <i class="mdi mdi-truck mdi-24px"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <h4 class="mb-0">{{ \App\Models\Distributor::count() }}</h4>
                                    <small class="text-muted">Distributors</small>
                                </div>
                            </div>
                        </div>
                    </div>
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
                <div class="pb-20 my-3">
                    <h4 class="text-blue h4">Top Sales {{ date('Y F', strtotime($date)) ?? date('Y F') }}</h4>
                    <div class="card-datatable table-responsive">
                        @php
                            $top_sales = [];
                            $top_sale = \App\Models\User::where('role', 'Sales')
                                ->with([
                                    'Transactions' => function ($query) use ($week) {
                                        $query->with('Item');
                                        $query->whereMonth('created_at', $week[1]);
                                        $query->whereYear('created_at', $week[0]);
                                    },
                                ])
                                ->get();
                            
                            foreach ($top_sale as $index => $item2) {
                                $item_total = 0;
                                foreach ($item2->Transactions as $trx) {
                                    $item_total += $trx->items_sold * $trx->Item->sale_price;
                                }
                                $top_sales[] = [
                                    'sales' => $item2->name,
                                    'type' => $item2->Sales->type,
                                    'total' => $item_total,
                                ];
                            }
                            $top_sales = collect($top_sales)->sortByDesc('total');
                        @endphp
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Sales</th>
                                    <th>Type</th>
                                    <th>Total</th>
                                    <th>Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_sales as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item['sales'] }}</td>
                                        <td>{{ $item['type'] }}</td>
                                        <td>
                                            Rp. {{ number_format($item['total'], 0) }}
                                        </td>
                                        @php
                                            $target = $item['type'] == 'Car' ? 70000000 : 35000000;
                                            $persentase = ($item['total'] / $target) * 100;
                                            $persentase = round($persentase, 2);
                                        @endphp
                                        <td>
                                            Rp. {{ number_format($item['total'], 0) }} / Rp. {{ number_format($target, 0) }} ({{ $persentase }}%)
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                    //  {
                    //     name: 'Items available',
                    //     data: @json($transactions->pluck('items_sold'))
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
