@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Transacion Data</h4>
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
                                    <th>Date Transaction</th>
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
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->created_at }}</td>
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
                                    <td colspan="6" class="text-end">Total Stockist Price:</td>
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

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
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
                            exportOptions: {
                                columns: ':visible'
                            },
                            footer: true,
                            customize: function(doc) {
                                
                                doc.content.splice( 1, 0, {
                                    margin: [ 0, 0, 0, 12 ],
                                    alignment: 'center',
                                    height: 10,
                                    image: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAyAAAAMgBAMAAAApXhtbAAAAD1BMVEUAAAAAAAAAAAADAwP///+cMWYPAAAAA3RSTlMAHR7/FZWhAAAAAWJLR0QEj2jZUQAAAYFJREFUeNrt2zERADAMAzFTKIVSCH9uxdC7DB4kCj9/AgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABsOVTJUEUQQRBEEAQRBEEEQRBBEARBBEEQQRBEEAQRBEEQRBAEEQRBBEEQQRAEQQThN8ilioUMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANY8/jpGR25QBw0AAAAASUVORK5CYII='
                                } );
                                doc.content[2].table.widths =
                                    Array(doc.content[2].table.body[0].length + 1).join('*').split(
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
                    $('.datatables-basic2').DataTable()
                    $('.form-sales').on('change', function() {
                        window.location.replace(
                            `{{ Auth::user()->role == 'Admin' ? route('sales.transaction.historyAdmin') : route('sales.transaction.history') }}?sales=${$('.form-sales').val()}`
                        )
                    })
                    $('.flatpickr-input').on('change', function() {
                        let val = $(this).val()
                        if (val.includes('to')) {
                            window.location.replace(
                                `{{ Auth::user()->role == 'Admin' ? route('sales.transaction.historyAdmin') : route('sales.transaction.history') }}?sales=${$('.form-sales').val() ?? ''}&date=${val}`
                            )
                        }
                    })
                    $('.btn-modal').on('click', function() {
                        let stock = $(this).data('stock')
                        let id = $(this).data('id')

                        $('input[name=stock_left]').val(stock)
                        $('input[name=id_item]').val(id)
                    })
    </script>
    {{-- <script src="{{ asset('assets') }}/assets/js/tables-datatables-basic.js"></script> --}}
@endsection
