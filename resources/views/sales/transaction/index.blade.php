@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Transacion Data</h4>
                    <div class="col-12 my-4">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control flatpickr-input" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                                value="{{ Request::get('date') ?? '' }}" id="flatpickr-range" readonly="readonly">
                            <label for="flatpickr-range">Date Range</label>
                        </div>
                    </div>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Ready Stock</th>
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
                                        <td>{{ $stocks }}</td>
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
                                                @if ($stocks == $sold_stocks)
                                                    SOLD
                                                @else
                                                    READY
                                                    {{-- <button class="btn btn-sm btn-primary btn-modal" data-bs-toggle="modal"
                                                        data-bs-target="#Medium-modal" data-stock="{{ $stocks - $sold_stocks }}"
                                                        data-id="{{ $item->id }}">
                                                        Sell
                                                    </button> --}}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"></td>
                                    <td colspan="2">Total Stockist Price:</td>
                                    <td colspan="1">Rp. {{ number_format($total2, 0) }}</td>
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
            <div class="card-box mb-30 mt-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Add Transaction</h4>
                </div>
                <form action="{{ route('sales.transaction.store') }}" method="post">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Shop Name</label>
                                <input class="form-control" type="text" name="shop_name" placeholder="Shop Name"
                                    required>
                            </div>
                            <div class="card-trx">
                                <div class="row row-cols-2 my-2">
                                    <div class="form-group col-6">
                                        <label>Item</label>
                                        <select name="item[]" class="select2 form-select form-select-lg"
                                            data-allow-clear="true" required>
                                            <option value="" selected disabled>Select Item</option>
                                            @foreach ($items as $item)
                                                @php
                                                    $stocks = $item->store_stocks + $item->assign_stocks;
                                                    $sold_stocks = $item->sold_stocks ?? 0;
                                                @endphp
                                                <option value="{{ $item->id }}|{{ $stocks - $sold_stocks }}"
                                                    data-stock="{{ $stocks - $sold_stocks }}"
                                                    data-id="{{ $item->id }}">
                                                    {{ $item->name }} | Stocks : {{ ($stocks - $sold_stocks) < 0 ? 0 : $stocks - $sold_stocks }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Stock</label>
                                        <input class="form-control" type="number" name="stock[]" placeholder="10" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-success btn-add">
                                Add
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Transaction History</h4>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-basic2 table table-bordered">

                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Date</th>
                                    <th>Shop</th>
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
                                        <td>{{ $item['shop'] }}</td>
                                        <td class="table-plus">{{ $item['all_stock'] }}</td>
                                        <td class="table-plus">Rp. {{ number_format($item['total'], 0) }}</td>
                                        @php
                                            $total += $item['all_stock'];
                                            $total2 += $item['total'];
                                        @endphp
                                        <td>
                                            <a href="{{ route('sales.transaction.history', ['detailDate' => explode(' ', $index)[0],  'shop' => $item['shop']]) }}"
                                                class="btn btn-light btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end">Total:</td>
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
    <form action="" id="form" method="post">
        @csrf
    </form>
    <div class="modal fade" id="Medium-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('sales.transaction.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Sell Item Form
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label>Shop Name</label>
                            <input class="form-control" type="text" name="shop_name" placeholder="Shop Name" required>
                        </div>
                        <div class="form-group">
                            <label>Items Sold</label>
                            <input class="form-control" type="number" name="items_sold" placeholder="10" required>
                            <input type="hidden" name="id_item">
                            <input type="hidden" name="stock_left">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(`{{ route('sales.transaction.index') }}?date=${val}`)
            }
        })
        $('.btn-modal').on('click', function() {
            let stock = $(this).data('stock')
            let id = $(this).data('id')

            $('input[name=stock_left]').val(stock)
            $('input[name=id_item]').val(id)
        })

        $('.datatables-basic2').DataTable();
        let index = 2

        $('.btn-add').on('click', function() {
            let form = `
            <div class="row row-cols-2 my-2 row-${index}">
                <div class="form-group col-6">
                    <label>Item</label>
                    <select name="item[]" class="select2 form-select form-select-lg" data-allow-clear="true"
                        required>
                        <option value="" selected disabled>Select Item</option>
                        @foreach ($items as $item)
                            @php
                                $stocks = $item->store_stocks + $item->assign_stocks;
                                $sold_stocks = $item->sold_stocks ?? 0;
                            @endphp
                            <option value="{{ $item->id }}|{{ $stocks - $sold_stocks }}" data-stock="{{ $stocks - $sold_stocks }}"
                                data-id="{{ $item->id }}">{{ $item->name }} | Stocks : {{ $stocks }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-6">
                    <label>Stock</label>
                    <input class="form-control" type="number" name="stock[]" placeholder="10" required>
                    <button type="button" class="btn btn-sm btn-danger btn-cancel mt-2" data-index="${index}">Cancel</button>
                </div>
            </div>
        `

            index++

            $('.card-trx').append(form)

            setTimeout(() => {
                $('.select2').select2()
            }, 100);
        })

        $(document).on('click', '.btn-cancel', function() {
            let index = $(this).data('index')
            $('.row-' + index).remove()
        })
    </script>
@endsection
