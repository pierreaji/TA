@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Items Data</h4>
                    <button class="btn btn-sm btn-primary btn-modal" data-bs-toggle="modal" data-bs-target="#Medium-modal"
                        type="button">
                        <i class="bi bi-plus"></i> Add Item
                    </button>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Distributor</th>
                                    <th>Distributor Price</th>
                                    <th>Stockist Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->name }}</td>
                                        <td>{{ $item->Category->name }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>{{ $item->Distributor->name }}</td>
                                        <td>Rp. {{ number_format($item->distributor_price, 0) }}</td>
                                        <td>Rp. {{ number_format($item->sale_price, 0) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-warning btn-modal"
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                    data-category="{{ $item->id_category }}"
                                                    data-distributor="{{ $item->id_distributor }}"
                                                    data-type="{{ $item->type }}"
                                                    data-distributor_price="{{ $item->distributor_price }}"
                                                    data-sale_price="{{ $item->sale_price }}" data-bs-toggle="modal"
                                                    data-bs-target="#Medium-modal">
                                                    Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $item->id }}">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Medium-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('items.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Item Form
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="select2-modal form-select form-select-lg" data-allow-clear="true"
                                required>
                                <option value="" selected disabled>Select Category</option>
                                @foreach ($category as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Distributor</label>
                            <select name="distributor" class="select2-modal form-select form-select-lg"
                                data-allow-clear="true" required>
                                <option value="" selected disabled>Select Distributor</option>
                                @foreach ($distributor as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="select2-modal form-select form-select-lg" data-allow-clear="true"
                                required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="Pack">Pack</option>
                                <option value="Carton">Carton</option>
                                <option value="Pless">Pless</option>
                                <option value="Refill">Refill</option>
                                <option value="Renceng">Renceng</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" name="name" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Distributor Price</label>
                            <input class="form-control form-rupiah" type="text" name="distributor_price"
                                placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Stockist Price</label>
                            <input class="form-control form-rupiah" type="text" name="sale_price" placeholder=""
                                required>
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
        $(document).on('click', '.btn-modal', function() {
            let val = $(this)
            $('input[name=id]').val(val.data('id'))
            $('input[name=name]').val(val.data('name'))
            $('input[name=distributor_price]').val(formatRupiah(val.data('distributor_price')))
            $('input[name=sale_price]').val(formatRupiah(val.data('sale_price')))

            $(`select[name=category]`).val(val.data('category')).trigger('change')
            $(`select[name=distributor]`).val(val.data('distributor')).trigger('change')
            // $(`select[name=type]`).val(val.data('type')).trigger('change')
        })

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id')
            let form = $('#form').attr('action', "{{ url('items') }}/" + id)
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
