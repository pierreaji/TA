@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <h4 class="text-blue h4">Item Assign Data</h4>
                <div class="form-group mt-3">
                    {{-- <label>Sales</label>
                    <select class="select2 form-sales form-select form-select-lg" name="oke" data-allow-clear="true"
                        required>
                        <option value="" selected disabled>Filter Sales</option>
                        @foreach ($sales as $item)
                            <option value="{{ $item->id }}" {{ $firstSales->id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select> --}}
                    @if (!in_array(date('l'), ['Saturday','Sunday', 'Monday']))
                        <div class="pd-20 mt-2">
                            <button class="btn btn-sm btn-primary btn-modal" data-sales="{{ $firstSales->id }}" data-bs-toggle="modal"
                                data-bs-target="#Medium-modal" type="button">
                                <i class="bi bi-plus"></i> Add Item Assign
                            </button>
                        </div>
                    @endif
                </div>
                {{-- <div class="form-group">
                    <label>Sales Type Filter</label>
                    <select
                        class="select2 form-sale form-select form-select-lg"
                        data-allow-clear="true" required>
                        <option value="" selected disabled>Select Sales Type</option>
                        <option value="Car" {{ $type == 'Car' ? 'selected' : '' }}>Car</option>
                        <option value="Motorcycle" {{ $type == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                    </select>
                    <input type="hidden" name="stock_left">
                </div> --}}
                <div class="col-12 my-4">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control flatpickr-input" value="{{ Request::get('date') ?? '' }}"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" readonly="readonly">
                        <label for="flatpickr-range">Date Range</label>
                    </div>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-basic table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    {{-- <th>Assign Date</th> --}}
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Assign Stock</th>
                                    <th>Sales Total</th>
                                    {{-- <th>Type</th> --}}
                                    <th>Stockist Price</th>
                                    <th>Sale Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $total2 = 0;
                                    $haveTemp = false;
                                    $in = [];
                                @endphp
                                @foreach ($itemAssign as $index => $item)
                                    @if (!in_array($item->identity, $in))
                                    @php
                                        $in[] = $item->identity;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        {{-- <td class="table-plus">{{ $item->created_at }}</td> --}}
                                        <td class="table-plus">{{ $item->Item->name }}</td>
                                        <td class="table-plus">{{ $item->Item->Category->name }}</td>
                                        <td>{{ $item->stock }}</td>
                                        <td class="table-plus">{{ $item->user_total }}</td>
                                        {{-- <td>Sales {{ $item->type }}</td> --}}
                                        <td class="table-plus">Rp. {{ number_format($item->Item->sale_price, 0) }}</td>
                                        <td class="table-plus">Rp.
                                            {{ number_format($item->Item->sale_price * $item->stock, 0) }}
                                        </td>
                                        @php
                                            $total += $item->Item->sale_price;
                                            $total2 += $item->Item->sale_price * $item->stock;
                                        @endphp
                                        <td>
                                            @if ($item->is_temp)
                                                @php
                                                    $haveTemp = $item->is_temp;
                                                @endphp
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-warning btn-modal"
                                                        data-id="{{ $item->identity }}" data-item="{{ $item->id_item }}"
                                                        data-stock="{{ $item->stock }}" data-sales="{{ $item->id_user }}"
                                                        data-bs-toggle="modal" data-bs-target="#Medium-modal">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-delete"
                                                        data-id="{{ $item->identity }}">
                                                        Delete
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>

                                <tr>
                                    <td colspan="5" class="text-end"></td>
                                    <td colspan="1">Total Stockist Price:</td>
                                    <td colspan="1">Rp. {{ number_format($total2, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @if ($haveTemp)
                        <form action="{{ route('items.assign.confirm') }}" class="text-center" method="post">
                            @csrf
                            <button class="btn btn-sm btn-primary mx-auto">
                                Confirm Assign
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Medium-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('items.assign.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Item Form
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <input type="hidden" name="stock_left">
                        {{-- <input type="hidden" name="sales" value="{{ $firstSales->id }}"> --}}
                        <div class="form-group">
                            <label>Item</label>
                            <select name="item" class="select2-modal form-item form-select form-select-lg"
                                data-allow-clear="true" required>
                                <option value="" selected disabled>Select Item</option>
                                @php
                                    $stockArray = [];
                                @endphp
                                @foreach ($items as $item)
                                    @php
                                        $stocks = ($item->in_stocks ?? 0) - ($item->assign_stocks ?? 0) - ($item->store_stocks ?? 0);
                                        $stockArray[$item->id] = $stocks;
                                    @endphp
                                    <option value="{{ $item->id }}" data-stock="{!! $stocks !!}">
                                        {{ $item->name }} - Stocks: {{ $stocks }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
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
@endsection
@section('scripts')
    <script>
        $('.datatables-basic').DataTable({
            "lengthMenu": [50, 75, 100],
            "pageLength": 50,
            dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [{
                extend: 'pdf',
                title: `
                    SyncMas - Stockist & Sales Management Report
                    PD. Senyuman Ikan Mas.
                    Jln. Kapten Hanafiah, Kel/Ds. Karanganyar Kec. Subang 

                `,
                    messageTop: `
                    Type: Items Assign
                    Date: {{ Request::get('detailDate') }}

                    `,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                },
                footer: false,
                customize: function(doc) {
                    // doc.content[0].syle
                    doc.content.splice( 1, 0, {
                        margin: [ 0, 0, 0, 12 ],
                        alignment: 'center',
                        height: 10,
                        image: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAyAAAAMgBAMAAAApXhtbAAAAD1BMVEUAAAAAAAAAAAADAwP///+cMWYPAAAAA3RSTlMAHR7/FZWhAAAAAWJLR0QEj2jZUQAAAYFJREFUeNrt2zERADAMAzFTKIVSCH9uxdC7DB4kCj9/AgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABsOVTJUEUQQRBEEAQRBEEEQRBBEARBBEEQQRBEEAQRBEEQRBAEEQRBBEEQQRAEQQThN8ilioUMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANY8/jpGR25QBw0AAAAASUVORK5CYII='
                    } );
                    doc.content[3].table.widths =
                        Array(doc.content[3].table.body[0].length + 1).join('*').split(
                            ''); // Remove spaces around page title
                    doc.pageMargins = [10, 10, 10, 10];
                    doc.defaultStyle.fontSize = 7;
                    doc.styles.tableHeader.fontSize = 7;
                    doc.styles.title.fontSize = 9;
                    doc.content[0].text = doc.content[0].text.trim();
                    // Create a footer
                    doc['footer'] = (function(page, pages) {
                        return {
                            columns: [
                                "{{ Request::get('date') ?? '' }}",
                                {
                                    // This is the right column
                                    alignment: 'right',
                                    text: ['page ', {
                                        text: page.toString()
                                    }, ' of ', {
                                        text: pages.toString()
                                    }]
                                }
                            ],
                            margin: [10, 0]
                        }
                    });
                    // Styling the table: create style object
                    var objLayout = {};
                    // Horizontal line thickness
                    objLayout['hLineWidth'] = function(i) {
                        return .5;
                    };
                    // Vertikal line thickness
                    objLayout['vLineWidth'] = function(i) {
                        return .5;
                    };
                    // Horizontal line color
                    objLayout['hLineColor'] = function(i) {
                        return '#aaa';
                    };
                    // Vertical line color
                    objLayout['vLineColor'] = function(i) {
                        return '#aaa';
                    };
                    // Left padding of the cell
                    objLayout['paddingLeft'] = function(i) {
                        return 4;
                    };
                    // Right padding of the cell
                    objLayout['paddingRight'] = function(i) {
                        return 4;
                    };
                    // Inject the object in the document
                    doc.content[1].layout = objLayout;
                }
            }]
        });
        $(document).on('click', '.btn-modal', function() {
            let val = $(this)

            $(`select[name=sales]`).val(val.data('sales')).trigger('change')
            $(`select[name=item]`).val(val.data('item')).trigger('change')
            $(`input[name=stock]`).val(val.data('stock'))
            $(`input[name=id]`).val(val.data('id'))
        })
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('items.assign.index') }}?date=${val}`
                )
            }
        })


        $('.form-sales').on('change', function() {
            window.location.replace(`{{ route('items.assign.index') }}?sales=${$('.form-sales').val()}`)
        })

        $(document).on('change', '.form-item', function() {
            let arrStock = @json($stockArray);
            let stock = $(`option[value=${$(this).val()}]`).data('stock')

            $('input[name=stock_left]').val(arrStock[$(this).val()])
        })

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id')
            let form = $('#form').attr('action', "{{ url('items/assign/delete') }}/" + id)
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
    </script>
@endsection
