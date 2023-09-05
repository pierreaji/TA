@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Item Request Data</h4>
                    @if (Auth::user()->role == 'Sales')
                        <button class="btn btn-sm btn-primary btn-modal" data-id="" data-bs-toggle="modal"
                            data-bs-target="#Medium-modal" type="button">
                            <i class="bi bi-plus"></i> Add Item Request
                        </button>
                    @endif
                    <div class="col-12 my-4">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control flatpickr-input" value="{{ Request::get('date') ?? '' }}"
                                placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" readonly="readonly">
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
                                    <th>Request Date</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    {{-- <th>Type</th> --}}
                                    <th>Request Stock</th>
                                    <th>Stockist Price</th>
                                    <th>Sale Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($ItemRequest as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->created_at }}</td>
                                        <td class="table-plus">{{ $item->Item->name }}</td>
                                        <td class="table-plus">{{ $item->Item->Category->name }}</td>
                                        {{-- <td>Sales {{ $item->ItemAssign->type }}</td> --}}
                                        <td>{{ $item->stock }}</td>
                                        <td class="table-plus">Rp. {{ number_format($item->Item->sale_price, 0) }}</td>
                                        <td class="table-plus">Rp.
                                            {{ number_format($item->Item->sale_price * $item->stock, 0) }}
                                        </td>
                                        @php
                                            $total += $item->Item->sale_price;
                                        @endphp
                                        <td>
                                            @if($item->status == 2) 
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                            @elseif($item->status == 1) 
                                            <button class="btn btn-sm btn-success">Approve</button>
                                            @elseif($item->status == 0) 
                                            <button class="btn btn-sm btn-success">Approve</button>
                                            @else 
                                            
                                            <button class="btn btn-sm btn-light">Request</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->approved_at == null)
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-warning btn-modal"
                                                        data-id="{{ $item->id }}" data-item="{{ $item->id_item }}"
                                                        data-stock="{{ $item->stock }}" data-bs-toggle="modal"
                                                        data-bs-target="#Medium-modal">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-delete"
                                                        data-id="{{ $item->id }}">
                                                        Delete
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Medium-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('items.request.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Item Form
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label>Item</label>
                            <select name="item" class="select2-modal form-item form-select form-select-lg"
                                data-allow-clear="true" required>
                                <option value="" selected disabled>Select Item</option>
                                @foreach ($items as $item)
                                    @php
                                        $stocks = ($item->in_stocks ?? 0) - ($item->assign_stocks ?? 0) - ($item->store_stocks ?? 0);
                                    @endphp
                                    <option value="{{ $item->id }}" data-stock="{{ $stocks }}">
                                        {{ $item->name }} - Stocks: {{ $stocks }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="stock_left">
                        </div>
                        <div class="form-group">
                            <label>Request Stock</label>
                            <input class="form-control" type="number" name="stock" placeholder="10" required>
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
    <form action="" id="form" method="post">
        @csrf
        @method('delete')
    </form>
    <form action="" id="form-approve" method="post">
        @csrf
        <input type="hidden" name="status" id="status-approve">
    </form>
@endsection
@section('scripts')
    <script>
        $(document).on('click', '.btn-modal', function() {
            let val = $(this)

            $(`select[name=item]`).val(val.data('item')).trigger('change')
            $(`input[name=stock]`).val(val.data('stock'))
            $(`input[name=id]`).val(val.data('id'))
        })


        $('.form-sale').on('change', function() {
            window.location.replace(`{{ route('items.request.index') }}?type=${$('.form-sale').val()}`)
        })

        $('.form-item').on('change', function() {
            let stock = $(`option[value=${$(this).val()}]`).data('stock')

            $('input[name=stock_left]').val(stock)
        })

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id')
            let form = $('#form').attr('action', "{{ url('items/request/delete') }}/" + id)
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
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
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('items.request.index') }}?date=${val}`
                )
            }
        })

        $(document).on('click', '.btn-approve', function() {
            let id = $(this).data('id')
            let status = $(this).data('status')
            let form = $('#form-approve').attr('action', "{{ url('items/store/approve') }}/" + id)

            $('#status-approve').val(status)
            Swal.fire({
                title: 'Are you sure, want to approve it?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
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
    </script>
@endsection
