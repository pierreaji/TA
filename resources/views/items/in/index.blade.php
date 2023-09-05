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
                        <option value="All" selected>All</option>
                        @foreach ($distributor as $item)
                            <option value="{{ $item->id }}" {{ $firstDist->id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label>Category</label>
                    <select class="select2 form-category form-select form-select-lg" data-allow-clear="true" required>
                        <option value="All" selected>All</option>
                        @foreach ($category as $item)
                            <option value="{{ $item->id }}" {{ $firstCat->id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Distributor</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Incoming Date</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemsIn as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->Item->Distributor->name }}</td>
                                        <td class="table-plus">{{ $item->Item->name }}</td>
                                        <td class="table-plus">{{ $item->Item->Category->name }}</td>
                                        <td class="table-plus">{{ $item->stock }}</td>
                                        <td class="table-plus">{{ $item->incoming_item_date }}</td>
                                        <td class="table-plus">{{ $item->User->name }} - {{ $item->User->role }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-warning btn-modal"
                                                    data-id="{{ $item->id }}" data-item="{{ $item->id_item }}"
                                                    data-stock="{{ $item->stock }}"
                                                    data-date="{{ $item->incoming_item_date }}" data-bs-toggle="modal"
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
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30 mt-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Add Item In</h4>
                </div>
                <form action="{{ route('items.in.store') }}" method="post">
                    @csrf
                    <div class="card-add">
                        <div class="card mb-3">
                            <div class="card-header">
                                Item In 1
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Item</label>
                                    <select name="item[]" class="select2 form-select form-select-lg"
                                        data-allow-clear="true" required>
                                        <option value="" selected disabled>Select Item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input class="form-control" type="number" name="stock[]" placeholder="10" required>
                                </div>
                                <div class="form-group">
                                    <label>Incoming Item Date</label>
                                    <input class="form-control" type="date" name="incoming_item_date[]" max="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-success btn-add">
                            Add
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Medium-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('items.in.update') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Item Form
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">

                        <div class="form-group">
                            <label>Item</label>
                            <select name="edit_item" class="select2 form-select form-select-lg" data-allow-clear="true"
                                required>
                                <option value="" selected disabled>Select Item</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input class="form-control" type="number" name="edit_stock" placeholder="10" required>
                        </div>
                        <div class="form-group">
                            <label>Incoming Item Date</label>
                            <input class="form-control" type="date" name="edit_incoming_item_date" max="{{ date('Y-m-d') }}" required>
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
        $('.form-distributor').on('change', function() {
            window.location.replace(
                `{{ route('items.in.index') }}?distributor=${$('.form-distributor').val()}&category=${$('.form-category').val()}`
            )
        })

        $('.form-category').on('change', function() {
            window.location.replace(
                `{{ route('items.in.index') }}?distributor=${$('.form-distributor').val()}&category=${$('.form-category').val()}`
            )
        })


        $(document).on('click', '.btn-modal', function() {
            let val = $(this)
            $('input[name=id]').val(val.data('id'))
            $('input[name=edit_stock]').val(val.data('stock'))
            $('input[name=edit_incoming_item_date]').val(val.data('date'))

            $(`select[name=edit_item]`).val(val.data('id')).trigger('change')
        })

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id')
            let form = $('#form').attr('action', "{{ url('items/in/delete') }}/" + id)
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

        let index = 2

        $('.btn-add').on('click', function() {
            let form = `
            <div class="card mb-3 card-${index}">
                <div class="card-header">
                    Item In ${index}
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Item</label>
                        <select
                            name="item[]"
                            class="select2 form-select form-select-lg"
                            data-allow-clear="true" required>
                            <option value="" selected disabled>Select Item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input class="form-control" type="number" name="stock[]" placeholder="10" required>
                    </div>
                    <div class="form-group">
                        <label>Incoming Item Date</label>
                        <input class="form-control" type="date" name="incoming_item_date[]" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <button class="btn btn-sm btn-danger btn-cancel mt-2" data-index="${index}">Cancel</button>
                </div>
            </div>
        `

            index++

            $('.card-add').append(form)

            setTimeout(() => {
                $('.select2').select2()
            }, 100);
        })

        $(document).on('click', '.btn-cancel', function() {
            let index = $(this).data('index')
            $('.card-' + index).remove()
        })
    </script>
@endsection
