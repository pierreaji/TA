@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sale Report</h4>
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
                    <div class="col-12 my-4">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control flatpickr-input"
                                value="{{ Request::get('date') ?? '' }}" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                                id="flatpickr-range" readonly="readonly">
                            <label for="flatpickr-range">Date Range</label>
                        </div>
                    </div>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-basic table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Stockist Price</th>
                                    <th>Sold Items</th>
                                    <th>Sale Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                    $total = 0;
                                    $total2 = 0;
                                    $total3 = 0;
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
                                            $total3 += $item->sale_price * ($stocks - $sold_stocks);
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"></td>
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

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.datatables-basic').DataTable({
            "lengthMenu": [50, 75, 100],
            "pageLength": 50,
            })
            $('.form-sales').on('change', function() {
                window.location.replace(
                    `{{ route('sales.transaction.historySale') }}?sales=${$('.form-sales').val()}`)
            })
            $('.flatpickr-input').on('change', function() {
                let val = $(this).val()
                if (val.includes('to')) {
                    window.location.replace(
                        `{{ route('sales.transaction.historySale') }}?sales=${$('.form-sales').val()}&date=${val}`
                        )
                }
            })
            $('.btn-modal').on('click', function() {
                let stock = $(this).data('stock')
                let id = $(this).data('id')

                $('input[name=stock_left]').val(stock)
                $('input[name=id_item]').val(id)
            })
        })
    </script>
    {{-- <script src="{{ asset('assets') }}/assets/js/tables-datatables-basic.js"></script> --}}
@endsection
