@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Received Items at {{ Request::get('detailDate') }}</h4>
                    <a href="{{ route('report.sales.received') }}" class="btn btn-sm btn-light">Back</a>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-basic table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Items</th>
                                    <th>Stock</th>
                                    <th>Stockist Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                $no = 0;
                                    $total = 0;
                                    $total1 = 0;
                                    $total2 = 0;
                                    $total3 = 0;
                                @endphp
                                @foreach ($ItemRequest as $index => $item)
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>{{ $item['item']['name'] }}</td>
                                        <td class="table-plus">{{ $item['stock'] }}</td>
                                        <td class="table-plus">Rp. {{ $item['item']['sale_price'] }}</td>
                                        @php
                                            $total += $item['stock'];
                                            $total2 += $item['item']['sale_price'];
                                            $total3 += $item['item']['sale_price'] * $item['stock'];
                                        @endphp
                                        <td>
                                            Rp. {{ number_format($item['item']['sale_price'] * $item['stock'], 0) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end"></td>
                                    <td colspan="1">Total:</td>
                                    <td colspan="1">{{--  Rp. {{ number_format($total2, 0) }} --}}</td>
                                    <td colspan="1">Rp. {{ number_format($total3, 0) }}</td> 
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
                    messageTop: `
                    Type: Received Items Report
                    Sales Name: {{ $firstSales->name }}
                    Date: {{ Request::get('detailDate') }}

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
                        doc.content[3].table.widths = ['*', '*', '*', '*', '*'];
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
            window.location.replace(`{{ route('report.sales.received') }}?sales=${$('.form-sales').val()}`)
        })

        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('report.sales.received') }}?sales=${$('.form-sales').val()}&date=${val}`)
            }
        })
    </script>
    {{-- <script src="{{ asset('assets') }}/assets/js/tables-datatables-basic.js"></script> --}}
@endsection