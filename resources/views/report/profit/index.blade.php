@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Report Profit</h4>
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
                        <table class="datatables-basic table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Distributor Price</th>
                                    <th>Stockist Price</th>
                                    <th>Sold Items</th>
                                    <th>Capital Total</th>
                                    <th>Sale Total</th>
                                    <th>Profit Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-nowrap">
                                @php
                                    $total = 0;
                                    $total1 = 0;
                                    $total2 = 0;
                                    $profit = 0;
                                @endphp
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->name }}</td>
                                        <td class="table-plus">{{ $item->Category->name }}</td>
                                        @php
                                            $sold_stocks = $item->sold_stocks ?? 0;
                                        @endphp
                                        <td class="table-plus">Rp. {{ number_format($item->distributor_price, 0) }}</td>
                                        <td class="table-plus">Rp. {{ number_format($item->sale_price, 0) }}</td>
                                        <td>{{ $sold_stocks }}</td>
                                        <td class="table-plus">Rp.
                                            {{ number_format($item->distributor_price * $sold_stocks, 0) }}
                                        </td>
                                        <td class="table-plus">Rp. {{ number_format($item->sale_price * $sold_stocks, 0) }}
                                        </td>
                                        @php
                                            $temp = $item->sale_price * $sold_stocks - $item->distributor_price * $sold_stocks;
                                        @endphp
                                        <td class="table-plus text-nowrap"
                                            style="color: {{ $temp < 0 ? 'red' : 'green' }};">Rp.
                                            {{ number_format($temp, 0) }}</td>
                                        @php
                                            $total += $item->sale_price;
                                            $total1 += $item->distributor_price * $sold_stocks;
                                            $total2 += $item->sale_price * $sold_stocks;
                                            $profit += $total2 - $total1;
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @php
                                    $profit = $total2 - $total1;
                                @endphp
                                <tr>
                                    <td colspan="4" class="text-end"></td>
                                    <td colspan="2">Total Stockist Price:</td>
                                    <td colspan="1">Rp. {{ number_format($total1, 0) }}</td>
                                    <td colspan="1">Rp. {{ number_format($total2, 0) }}</td>
                                    <td colspan="1" style="color: {{ $profit < 0 ? 'red' : 'green' }};">Rp.
                                        {{ number_format($profit, 0) }}</td>
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
                    footer: false,
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
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('report.profit.index') }}?date=${val}`)
            }
        })
    </script>
    {{-- <script src="{{ asset('assets') }}/assets/js/tables-datatables-basic.js"></script> --}}
@endsection
