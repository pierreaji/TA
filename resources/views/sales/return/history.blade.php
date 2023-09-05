@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Return Data</h4>
                    <div class="col-12 my-4">
                        @if (Auth::user()->role == 'Admin')
                            <div class="form-group my-3">
                                <label>Choose Sales</label>
                                <select class="select2 form-sales form-select form-select-lg" data-allow-clear="true"
                                    required>
                                    <option value="" selected disabled>Filter Sales</option>
                                    @foreach ($sales as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $firstSales->id == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control flatpickr-input"
                                placeholder="YYYY-MM-DD to YYYY-MM-DD" value="{{ Request::get('date') ?? '' }}"
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
                                @foreach ($transactions as $index => $item)
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
            let val = $(this).val()
            window.location.replace(`{{ route('sales.return.history') }}?sales=${val}`)
        })
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('sales.return.history') }}?sales=${$('.form-sales').val() ?? ''}&date=${val}`)
            }
        })
        $('.btn-modal').on('click', function() {
            let stock = $(this).data('stock')
            let id = $(this).data('id')
            let item = $(this).data('item')
            let all = $(this).data('all')
            let form = $('#form')

            $('input[name=id]').val(id)
            $('input[name=stosck_left]').val(stock)
            $('input[name=id_item]').val(item)
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
