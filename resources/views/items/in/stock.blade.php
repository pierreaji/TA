@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Item Stock Data</h4>
                </div>
                <div class="form-group mt-3">
                    <label>Distributor</label>
                    <select class="select2 form-distributor form-select form-select-lg" data-allow-clear="true" required>
                        <option value="" selected>All Distributor</option>
                        @foreach ($distributor as $item)
                            <option value="{{ $item->id }}" {{ $firstDist->id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label>Category</label>
                    <select class="select2 form-category form-select form-select-lg" data-allow-clear="true" required>
                        <option value="" selected>All Category</option>
                        @foreach ($category as $item)
                            <option value="{{ $item->id }}" {{ $firstCat->id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="form-group mt-3">
                    <label>Sort By</label>
                    <select class="select2 form-sort form-select form-select-lg" data-allow-clear="true" required>
                        <option value="most" selected>Most Stock</option>
                        <option value="least">Least Stock</option>
                    </select>
                </div> --}}
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Distributor</th>
                                    <th>Stock</th>
                                    {{-- <th>Distributor Price</th> --}}
                                    <th>Stockist Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr>
                                        @php
                                            $stocks = $item->store_stocks + $item->assign_stocks;
                                            // $sold_stocks = $item->sold_stocks ?? 0;
                                        @endphp
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->name }}</td>
                                        <td class="table-plus">{{ $item->Category->name }}</td>
                                        <td class="table-plus">{{ $item->Distributor->name }}</td>
                                        <td class="table-plus">{{ ($item->stocks ?? 0) - $stocks }} {{ $item->type }}</td>
                                        {{-- <td class="table-plus">Rp {{ number_format($item->distributor_price, 0) }}</td> --}}
                                        <td class="table-plus">Rp {{ number_format($item->sale_price, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card d-none">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Item Stock From Return</h4>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Item</th>
                                    {{-- <th>Category</th> --}}
                                    {{-- <th>Distributor</th> --}}
                                    <th>Stock</th>
                                    <th>Return Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemsDelete as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->Item->name }}</td>
                                        {{-- <td class="table-plus">{{ $item->Category->name }}</td> --}}
                                        {{-- <td class="table-plus">{{ $item->Distributor->name }}</td> --}}
                                        <td class="table-plus">{{ ($item->stock ?? 0) }}</td>
                                        <td class="table-plus">{{ $item->deleted_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.form-distributor').on('change', function() {
            window.location.replace(
                `{{ route('items.in.stock') }}?distributor=${$('.form-distributor').val()}&category=${$('.form-category').val()}&sort=${$('.form-sort').val()}`
                )
        })

        $('.form-category').on('change', function() {
            window.location.replace(
                `{{ route('items.in.stock') }}?distributor=${$('.form-distributor').val()}&category=${$('.form-category').val()}&sort=${$('.form-sort').val()}`
                )
        })
        $('.form-sort').on('change', function() {
            window.location.replace(
                `{{ route('items.in.stock') }}?distributor=${$('.form-distributor').val()}&category=${$('.form-category').val()}&sort=${$('.form-sort').val()}`
                )
        })
    </script>
@endsection
