@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Item In Data</h4>
                </div>
                <div class="form-group mt-3">
                    <label>Distributor</label>
                    <select class="select2 form-distributor form-select form-select-lg" data-allow-clear="true" required>
                        {{-- <option value="" selected>All Distributor</option> --}}
                        @foreach ($distributor as $item)
                            <option value="{{ $item->id }}" {{ $firstDist->id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
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
                                @foreach ($itemData as $index => $item)
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
                                            <a href="{{ route('report.in.index', ['detailDate' => $index, 'distributor' => $firstDist->id]) }}"
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
        $('.datatables-basic').DataTable({
            "lengthMenu": [50, 75, 100],
            "pageLength": 50,
        });
        $('.form-distributor').on('change', function() {
            window.location.replace(
                `{{ route('report.in.index') }}?distributor=${$('.form-distributor').val()}`
            )
        })
        $('.flatpickr-input').on('change', function() {
            let val = $(this).val()
            if (val.includes('to')) {
                window.location.replace(
                    `{{ route('report.in.index') }}?distributor=${$('.form-distributor').val() ?? ''}&date=${val}`
                )
            }
        })
    </script>
@endsection
