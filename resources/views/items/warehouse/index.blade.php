@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Item {{ Auth::user()->role == 'Admin' ? 'Request' : 'Warehouse' }} Data</h4>
                    @if (Auth::user()->role != 'Sales')
                        @if ($type == 'Request' ||  Auth::user()->role == 'Admin')
                            <div class="btn-group">
                                <button class="btn btn-sm btn-success btn-confirm"
                                    data-status="{{ Auth::user()->role == 'Admin' ? 0 : 1 }}" type="button">
                                    <i class="bi bi-plus"></i>  {{ Auth::user()->role == 'Admin' ? 'Approve' : 'Confirm Received Items' }}
                                </button>
                                @if (Auth::user()->role == 'Admin')
                                    <button class="btn btn-sm btn-danger btn-confirm" data-status="2" type="button">
                                        <i class="bi bi-plus"></i> Reject Items
                                    </button>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
                @if (Auth::user()->role != 'Sales')
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
                @if (Auth::user()->role == 'Warehouse')
                    <div class="form-group">
                        <label>{{ Auth::user()->role == 'Admin' ? 'Request' : 'Warehouse' }} Filter</label>
                        <select class="select2 form-warehouse form-select form-select-lg" data-allow-clear="true" required>
                            <option value="" selected disabled>Select
                                {{ Auth::user()->role == 'Admin' ? 'Request' : 'Warehouse' }}</option>
                            <option value="All" {{ $type == 'All' ? 'selected' : '' }}>All</option>
                            <option value="Confirmed" {{ $type == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="Request" {{ $type == 'Request' ? 'selected' : '' }}>Request</option>
                            @if (Auth::user()->role != 'Warehouse')
                                <option value="Rejected" {{ $type == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            @endif
                        </select>
                        <input type="hidden" name="stock_left">
                    </div>
                @endif
                
                <div class="col-12 my-4">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control flatpickr-input" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                            value="{{ Request::get('date') ?? '' }}" id="flatpickr-range" readonly="readonly">
                        <label for="flatpickr-range">Date Range</label>
                    </div>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" value="all" id="defaultCheckAll">
                                    </td>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Request Stock</th>
                                    <th>Stockist Price</th>
                                    <th>Sale Total</th>
                                    <!-- <th>Sales</th> -->
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                    $total = 0;
                                    $total2 = 0;
                                    $in = [];
                                @endphp
                                @foreach ($ItemRequest as $index => $item)
                                   @if (!in_array($item->identity, $in))
                                       @php
                                           $in[] = $item->identity;
                                       @endphp
                                        <tr>
                                            <td>
                                                @if (Auth::user()->role != 'Sales')
                                                    @if ($item->status == 0 || $item->status == -1)
                                                        <input class="form-check-input form-check-id" type="checkbox"
                                                            value="{{ $item->identity }}|{{ $item->type }}"
                                                            id="defaultCheck{{ $index }}">
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="table-plus">{{ $item->Item->name }}</td>
                                            <td class="table-plus">{{ $item->Item->Category->name }}</td>
                                            <td>{{ $item->stock }}</td>
                                            <td class="table-plus">Rp. {{ number_format($item->Item->sale_price, 0) }}</td>
                                            <td class="table-plus">Rp.
                                                {{ number_format($item->Item->sale_price * $item->stock, 0) }}
                                            </td>
                                            <!-- <td>{{ $item->User->name }} - {{ $item?->User?->Sales?->type }}</td> -->
                                            <td class="text-uppercase">{{ $item->type }}</td>
                                            @php
                                                $total += $item->Item->sale_price;
                                                $total2 += $item->Item->sale_price * $item->stock;
                                            @endphp
                                            <td>
                                                @if ($item->status == -1)
                                                    <span class="btn btn-sm btn-light">Requested</span>
                                                
                                                @elseif($item->status == 0)
                                                    @if (Auth::user()->role == 'Warehouse' || Auth::user()->role == 'Sales')
                                                        <span class="btn btn-sm btn-light">Requested</span>
                                                    @else
                                                        <span class="btn btn-sm btn-success">Approved</span>
                                                    @endif
                                                
                                                @elseif($item->status == 1)
                                                    <span class="btn btn-sm btn-success">Received</span>
                                                @else
                                                    <span class="btn btn-sm btn-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                   @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end">Total Stockist Price:</td>
                                    <td colspan="3">Rp. {{ number_format($total2, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (Auth::user()->role == 'Warehouse')
        <div class="card">
            <div class="card-body">
                <div class="card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Warehouse History</h4>
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
                                    @foreach ($warehouseHistory as $index => $item)
                                        <tr>
                                            <td>{{ ++$count }}</td>
                                            <td>{{ $item['date'] }}</td>
                                            <td class="table-plus">{{ $item['all_item'] }}</td>
                                            <td class="table-plus">Rp. {{ number_format($item['total'], 0) }}</td>
                                            @php
                                                $total += $item['all_item'];
                                                $total2 += $item['total'];
                                            @endphp
                                            <td>
                                                <a href="{{ route('items.warehouse.index', ['detailDate' => $index]) }}"
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
    @endif
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
        <input type="hidden" name="id_user" value="{{ $firstSales->id }}">
        <input type="hidden" name="status_approve" id="status_approve">
        <input type="hidden" name="selected_row">
    </form>
    <form action="" id="form-approve" method="post">
        @csrf
        <input type="hidden" name="status" id="status-approve">
    </form>
@endsection
@section('scripts')
    <script>
        $('#defaultCheckAll').on('change', function() {
            if($(this).prop('checked')) {
                $('.form-check-id').prop('checked', true)
            } else {
                $('.form-check-id').prop('checked', false)
            }
        })
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(`{{ route('items.warehouse.index') }}?date=${val}`)
            }
        })
        $('.form-sales').on('change', function() {
            window.location.replace(
                `{{ route('items.warehouse.index') }}?sales=${$('.form-sales').val()}&warehouse=${$('.form-warehouse').val()}&date=${$('.flatpickr-input').val()}`
            )
        })
        $('.form-warehouse').on('change', function() {
            window.location.replace(
                `{{ route('items.warehouse.index') }}?sales=${$('.form-sales').val()}&warehouse=${$('.form-warehouse').val()}&date=${$('.flatpickr-input').val()}`
            )
        })
        $(document).on('click', '.btn-confirm', function() {
            let form = $('#form').attr('action', "{{ url('items/warehouse/confirm') }}")
            $('#status_approve').val($(this).data('status'))
            let selected = []
            $('.form-check-input').map((index, row) => {
                if ($(row).prop('checked')) {
                    selected.push($(row).val())
                }
            })

            $('input[name=selected_row]').val(JSON.stringify(selected))
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
