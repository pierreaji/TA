@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Sales Return Data at {{ Request::get('detailDate') }}</h4>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-light">Back</a>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-basic table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Stockist Price</th>
                                    <th>Sold Items</th>
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
                                        <td class="table-plus">{{ $item->Item->name }}</td>
                                        <td class="table-plus">{{ $item->Item->Category->name }}</td>
                                        @php
                                            $stocks = $item->store_stocks + $item->assign_stocks;
                                            $sold_stocks = $item->items_sold ?? 0;
                                        @endphp
                                        <td class="table-plus">Rp. {{ number_format($item->Item->sale_price, 0) }}</td>
                                        <td>{{ $sold_stocks }}</td>
                                        <td class="table-plus">Rp.
                                            {{ number_format($item->Item->sale_price * $item->items_sold, 0) }}
                                        </td>
                                        @php
                                            $total += $sold_stocks;
                                            $total2 += $item->Item->sale_price * $item->items_sold;
                                        @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end">Total Stockist Price:</td>
                                    <td colspan="1">{{ $total }}</td>
                                    <td colspan="1">Rp. {{ number_format($total2, 0) }}</td>
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
    </script>
@endsection
