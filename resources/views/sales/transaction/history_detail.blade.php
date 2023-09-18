@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Transacion Data at {{ Request::get('detailDate') }}</h4>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-light">Back</a>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-basic table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    {{-- <th>Shop Name</th> --}}
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
                                        {{-- <td class="table-plus">{{ $item->shop_name }}</td> --}}
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
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="1" class="text-end">Total Stockist Price:</td>
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
        })
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
