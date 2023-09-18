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
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Stock Assign</th>
                                    <th>Stockist Price</th>
                                    <th>Sold Items</th>
                                    <th>Sale Total</th>
                                    <th>Remaining Items</th>
                                    <th>Remaining Sale Total</th>
                                    <th>Return Items</th>
                                    <th>Remaining Return Items</th>
                                    <th>Remaining Return Items Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                    $total = 0;
                                    $total2 = 0;
                                    $total3 = 0;
                                    $total4 = 0;
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
                                            $check = $stocks - $sold_stocks < 1 ? 0 : $stocks - $sold_stocks;
                                            $total += $item->sale_price;
                                            $total2 += $item->sale_price * $sold_stocks;
                                            $total3 += $item->sale_price * $check;
                                            $items_sold = 0;
                                            foreach ($item->Transactions as $trx) {
                                                $items_sold += $trx->items_sold;
                                            }
                                            $total4 += $item->sale_price * ($sold_stocks - $items_sold);
                                        @endphp
                                        <td>{{ $check }}</td>
                                        <td>Rp. {{ number_format($item->sale_price * $check, 0) }}</td>
                                        <td>{{ $items_sold }}</td>
                                        <td>{{ $sold_stocks - $items_sold }}</td>
                                        <td>Rp.
                                            {{ number_format($item->sale_price * ($sold_stocks - $items_sold)) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"></td>
                                    <td colspan="2">Total Stockist Price:</td>
                                    <td colspan="2">Rp. {{ number_format($total2, 0) }}</td>
                                    <td colspan="3">Rp. {{ number_format($total3, 0) }}</td>
                                    <td colspan="1">Rp. {{ number_format($total4, 0) }}</td>
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

                        doc.content.splice(1, 0, {
                            margin: [0, 0, 0, 12],
                            alignment: 'center',
                            height: 10,
                            image: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAyAAAAMgBAMAAAApXhtbAAAAD1BMVEUAAAAAAAAAAAADAwP///+cMWYPAAAAA3RSTlMAHR7/FZWhAAAAAWJLR0QEj2jZUQAAAYFJREFUeNrt2zERADAMAzFTKIVSCH9uxdC7DB4kCj9/AgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABsOVTJUEUQQRBEEAQRBEEEQRBBEARBBEEQQRBEEAQRBEEQRBAEEQRBBEEQQRAEQQThN8ilioUMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANY8/jpGR25QBw0AAAAASUVORK5CYII='
                        });
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
        })
        $('.form-sales').on('change', function() {
            window.location.replace(`{{ route('sales.transaction.history') }}?sales=${$('.form-sales').val()}`)
        })
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('sales.transaction.history') }}?sales=${$('.form-sales').val()}&date=${val}`)
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
