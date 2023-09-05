@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Category Data</h4>
                    <button class="btn btn-sm btn-primary btn-modal" data-bs-toggle="modal" data-bs-target="#Medium-modal"
                        type="button">
                        <i class="bi bi-plus"></i> Add Category
                    </button>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->name }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-warning btn-modal"
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                    data-role="{{ $item->role }}" data-bs-toggle="modal"
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
    <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('category.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Category Form
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" name="name" placeholder="Johnny Brown" required>
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
        })

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id')
            let form = $('#form').attr('action', "{{ url('category') }}/" + id)
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
