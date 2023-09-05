@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Return Data</h4>
                    @if (Auth::user()->role == 'Admin')
                        <div class="form-group mt-3">
                            <label>Sales</label>
                            <select class="select2 form-sales form-select form-select-lg" data-allow-clear="true" required>
                                <option value="" selected disabled>Filter Sales</option>
                                @foreach ($sales as $item)
                                    <option value="{{ $item->id }}" {{ $firstSales->id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="form-floating form-floating-outline my-2">
                        <input type="text" class="form-control flatpickr-input" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                            value="{{ Request::get('date') ?? '' }}" id="flatpickr-range" readonly="readonly">
                        <label for="flatpickr-range">Date Range</label>
                    </div>
                    <br>
                    @if (date('l') == env('RETURN_ALL_DATE', 'Saturday'))
                        <button class="btn btn-sm btn-secondary btn-modal-all" data-all="true">
                            Return All
                        </button>
                    @else
                        <button class="btn btn-sm btn-primary btn-modal-all" data-all="false">
                            Already Paid All
                        </button>
                    @endif
                    <form id="form-all" action="{{ route('sales.return.storeAll') }}" method="post">
                        @csrf
                        @foreach ($items as $item)
                            @php
                                $stocks = $item->store_stocks + $item->assign_stocks;
                                $sold_stocks = $item->sold_stocks ?? 0;
                            @endphp
                            <input type="hidden" name="id[]"
                                value="{{ json_encode($item->Transactions->pluck('id')) }}">
                            <input type="hidden" name="id_item[]" value="{{ $item->id }}">
                            <input type="hidden" name="stock_left[]" value="{{ $stocks - $sold_stocks }}">
                            <input type="hidden" name="all_return">
                        @endforeach
                    </form>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Stockist Price</th>
                                    <th>Sold Items</th>
                                    <th>Sale Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                    $total = 0;
                                    $total2 = 0;
                                @endphp
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->name }}</td>
                                        <td class="table-plus">{{ $item->Category->name }}</td>
                                        @php
                                            $stocks = $item->store_stocks + $item->assign_stocks;
                                            $sold_stocks = $item->sold_stocks ?? 0;
                                        @endphp
                                        <td class="table-plus">Rp. {{ number_format($item->sale_price, 0) }}</td>
                                        <td>{{ $sold_stocks }}</td>
                                        <td class="table-plus">Rp. {{ number_format($item->sale_price * $sold_stocks, 0) }}
                                        </td>
                                        @php
                                            $total += $item->sale_price;
                                            $total2 += $item->sale_price * $sold_stocks;
                                        @endphp
                                        <td>
                                            <div class="btn-group">
                                                @if (date('l') == env('RETURN_ALL_DATE', 'Saturday'))
                                                    <button class="btn btn-sm btn-secondary btn-modal"
                                                        data-stock="{{ $stocks - $sold_stocks }}"
                                                        data-id="{{ json_encode($item->Transactions->pluck('id')) }}"
                                                        data-item="{{ $item->id }}" data-all="true">
                                                        Return All
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-primary btn-modal"
                                                        data-stock="{{ $stocks - $sold_stocks }}"
                                                        data-id="{{ json_encode($item->Transactions->pluck('id')) }}"
                                                        data-item="{{ $item->id }}" data-all="false">
                                                        Already Paid
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end">Total Stockist Price:</td>
                                    <td colspan="2">Rp. {{ number_format($total2, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Return History</h4>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-basic table table-bordered">

                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Date</th>
                                    <th>Count Items</th>
                                    <th>Count Totals</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                    $total = 0;
                                    $total1 = 0;
                                    $total2 = 0;
                                    $count = 0;
                                @endphp
                                @foreach ($transactionsHistory as $index => $item)
                                    <tr>
                                        <td>{{ ++$count }}</td>
                                        <td>{{ $item['date'] }}</td>
                                        <td class="table-plus">{{ $item['all_stock'] }}</td>
                                        <td class="table-plus">Rp. {{ number_format($item['total'], 0) }}</td>
                                        @php
                                            $total += $item['all_stock'];
                                            $total2 += $item['total'];
                                        @endphp
                                        <td>
                                            <a href="{{ route('sales.return.history', ['detailDate' => $index]) }}"
                                                class="btn btn-light btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end">Total:</td>
                                    <td colspan="1">{{ $total }}</td>
                                    <td colspan="2">Rp. {{ number_format($total2, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="form" action="{{ route('sales.return.store') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <input type="hidden" name="id_item">
        <input type="hidden" name="stock_left">
        <input type="hidden" name="all_return">
    </form>
@endsection
@section('scripts')
    <script>
        $('.form-sales').on('change', function() {
            window.location.replace(`{{ route('sales.return.index') }}?sales=${$('.form-sales').val()}`)
        })

        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('sales.return.index') }}?sales=${$('.form-sales').val() ?? ''}&date=${val}`)
            }
        })
        $(document).on('click', '.btn-modal', function() {
            let stock = $(this).data('stock')
            let id = $(this).data('id')
            let item = $(this).data('item')
            let all = $(this).data('all')
            let form = $('#form')

            $('input[name=id]').val(id)
            $('input[name=stock_left]').val(stock)
            $('input[name=id_item]').val(item)
            $('input[name=all_return]').val(all)
            console.log(id, item)

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Paid it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    form.submit()
                }
            });
        })
        $('.btn-modal-all').on('click', function() {
            let all = $(this).data('all')
            let form = $('#form-all')
            $('input[name=all_return]').val(all)

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Paid it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    form.submit()
                }
            });
        })
        $('.datatables-basic').DataTable({
            "lengthMenu": [50, 75, 100],
            "pageLength": 50,
        });
    </script>
@endsection
