@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Return Data</h4>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary btn-confirm" data-status="1" type="button">
                            <i class="bi bi-plus"></i> Confirms Items
                        </button>
                    </div>
                    <div class="form-floating form-floating-outline mt-4">
                        <input type="text" class="form-control flatpickr-input" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                            value="{{ Request::get('date') ?? '' }}" id="flatpickr-range" readonly="readonly">
                        <label for="flatpickr-range">Date Range</label>
                    </div>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-basic2 table table-bordered">
                            <thead>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" value="all" id="defaultCheckAll">
                                    </td>
                                    <th>Shop Name</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Sold Item</th>
                                    <th>Stockist Price</th>
                                    <th>Sale Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                    $total = 0;
                                    $total2 = 0;
                                @endphp
                                @foreach ($transactions as $index => $item)
                                    <tr>
                                        <td>
                                            <input class="form-check-input form-check-id" type="checkbox" value="{{ $item->id_item }}"
                                                id="defaultCheck{{ $index }}">
                                        </td>
                                        <td class="table-plus">{{ $item->shop_name }}</td>
                                        <td class="table-plus">{{ $item->Item->name }}</td>
                                        <td class="table-plus">{{ $item->Item->Category->name }}</td>
                                        <td class="table-plus">{{ $item->items_sold }}</td>
                                        <td class="table-plus">Rp. {{ number_format($item->Item->sale_price, 0) }}</td>
                                        <td class="table-plus">Rp.
                                            {{ number_format($item->Item->sale_price * $item->items_sold, 0) }}
                                        </td>
                                        @php
                                            $total += $item->Item->sale_price;
                                            $total2 += $item->Item->sale_price * $item->items_sold;
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end">Total Stockist Price:</td>
                                    <td colspan="1">Rp. {{ number_format($total, 0) }}</td>
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
                                                    @if ($item['is_return'])
                                                        <button class="btn btn-sm btn-info">Already Return</button>
                                                    @else
                                                        <button class="btn btn-sm btn-info">Request</button>
                                                    @endif
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
    <form action="" id="form" method="post">
        @csrf
        <input type="hidden" name="id">
    </form>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.datatables-basic2').DataTable({});
        })
        $('#defaultCheckAll').on('change', function() {
            if($(this).prop('checked')) {
                $('.form-check-id').prop('checked', true)
            } else {
                $('.form-check-id').prop('checked', false)
            }
        })
        $('.form-sales').on('change', function() {
            window.location.replace(`{{ route('sales.transaction.history') }}?sales=${$('.form-sales').val()}`)
        })
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('sales.return.sales') }}?date=${val}`)
            }
        })
        $('.btn-modal').on('click', function() {
            let stock = $(this).data('stock')
            let id = $(this).data('id')

            $('input[name=stock_left]').val(stock)
            $('input[name=id_item]').val(id)
        })

        $(document).on('click', '.btn-confirm', function() {
            let form = $('#form').attr('action', "{{ route('sales.return.store') }}")
            let selected = []
            $('.form-check-input').map((index, row) => {
                if ($(row).prop('checked')) {
                    selected.push($(row).val())
                }
            })

            $('input[name=id]').val(selected.join(','))
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, confirm it!',
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
    {{-- <script src="{{ asset('assets') }}/assets/js/tables-datatables-basic.js"></script> --}}
@endsection
