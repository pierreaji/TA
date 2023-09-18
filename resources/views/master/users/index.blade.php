@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Users Data</h4>
                    <button class="btn btn-sm btn-primary btn-modal" data-bs-toggle="modal" data-bs-target="#Medium-modal"
                        type="button">
                        <i class="bi bi-plus"></i> Add User
                    </button>
                </div>
                <div class="pb-20">
                    <div class="card-datatable table-responsive">
                        <table class="dt-row-grouping table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="table-plus">{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->role }}
                                            {{ $item->role == 'Sales' ? "- {$item?->Sales?->type}" : '' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @if ($item->role == 'Sales')
                                                    <a href="{{ route('users.sales', ['id' => $item->id]) }}"
                                                        class="btn btn-sm btn-primary">
                                                        Sales
                                                    </a>
                                                @endif
                                                <button class="btn btn-sm btn-warning btn-modal"
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                    data-email="{{ $item->email }}" data-role="{{ $item->role }}"
                                                    data-nik="{{ $item?->Sales?->nik }}" data-number="{{  $item?->Sales?->number }}"
                                                    data-address="{{  $item?->Sales?->address }}"
                                                    data-skck="{{  $item?->Sales?->skck }}"
                                                    data-ktp="{{  $item?->Sales?->ktp }}"
                                                    data-sim="{{  $item?->Sales?->sim }}"
                                                    data-stnk="{{  $item?->Sales?->stnk }}"
                                                    data-foto="{{  $item?->Sales?->pas_foto }}"
                                                    data-sertifikat="{{  $item?->Sales?->sertifikat }}"
                                                    data-agreement="{{  $item?->Sales?->agreement }}"
                                                    data-bs-toggle="modal" data-bs-target="#Medium-modal">
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
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content" style="overflow-y: auto">
                <form action="{{ route('users.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            User Form
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 main-form">
                                <input type="hidden" name="id">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control" type="text" name="name" placeholder="Johnny Brown" required>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email" placeholder="jonh@gmail.com" required>
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" id="role" class="select2-modal form-select" required>
                                        <option value="" selected disabled>Select Role</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="Sales">Sales</option>
                                    </select>
                                </div>
                                <div id="type_form">
                                    <div class="form-group" >
                                        <label>Type</label>
                                        <select name="type" id="type" class="select2-modal form-select">
                                            <option value="Car">Car</option>
                                            <option value="Motorcycle">Motorcycle</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input class="form-control" maxlength="16" minlength="16" type="text" name="nik" placeholder="" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" maxlength="13" minlength="9" type="text" id="number" name="number" placeholder="" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input class="form-control" type="text" name="address" placeholder="" required>
                                    </div>
                                </div>

                                <div class="mb-3 col-12 form-password-toggle fv-plugins-icon-container">
                                    <label for="password">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="password" name="password" id="password"
                                            placeholder="············">
                                        <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-outline"></i></span>
                                    </div>
                                    <small class="text-danger fillpass d-none">*Fill password if want to change it</small>
                                </div>
                            </div>
                            <div class="col-6 sales-form d-none">
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">SKCK</label>
                                    <input required class="form-control" type="file" accept="image/*" name="skck" id="skck"
                                        onchange="previewImage(event, 'skck')">
                                    <a href="" id="preview-skck" target="_blank"
                                        class="btn btn-info btn-sm mt-2 d-none">
                                        <i class="anticon anticon-camera"></i> Preview File
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">KTP</label>
                                    <input required class="form-control" type="file" accept="image/*" name="ktp" id="ktp"
                                        onchange="previewImage(event, 'ktp')">
                                    <a href="#" id="preview-ktp" target="_blank"
                                        class="btn btn-info btn-sm mt-2 d-none">
                                        <i class="anticon anticon-camera"></i> Preview File
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">SIM</label>
                                    <input required class="form-control" type="file" accept="image/*" name="sim" id="sim"
                                        onchange="previewImage(event, 'sim')">
                                    <a href="#" id="preview-sim" target="_blank"
                                        class="btn btn-info btn-sm mt-2 d-none">
                                        <i class="anticon anticon-camera"></i> Preview File
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">STNK</label>
                                    <input required class="form-control" type="file" accept="image/*" name="stnk" id="stnk"
                                        onchange="previewImage(event, 'stnk')">
                                    <a href="#" id="preview-stnk" target="_blank"
                                        class="btn btn-info btn-sm mt-2 d-none">
                                        <i class="anticon anticon-camera"></i> Preview File
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Pas Foto</label>
                                    <input required class="form-control" type="file" accept="image/*" name="pas_foto" id="pas_foto"
                                        onchange="previewImage(event, 'pas_foto')">
                                    <a href="#" id="preview-pas_foto" target="_blank"
                                        class="btn btn-info btn-sm mt-2 d-none">
                                        <i class="anticon anticon-camera"></i> Preview File
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Sertifikat</label>
                                    <input required class="form-control " type="file" accept="image/*" name="sertifikat" id="sertifikat"
                                        onchange="previewImage(event, 'sertifikat')">
                                    <a href="#" id="preview-sertifikat" target="_blank"
                                        class="btn btn-info btn-sm mt-2 d-none">
                                        <i class="anticon anticon-camera"></i> Preview File
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Agreement document</label>
                                    <input required class="form-control" type="file" accept="image/*" name="agreement" id="agreement"
                                        onchange="previewImage(event, 'agreement')">
                                    <a href="#" id="preview-agreement"
                                        target="_blank"
                                        class="btn btn-info btn-sm mt-2 d-none">
                                        <i class="anticon anticon-camera"></i> Preview File
                                    </a>
                                    {{-- <a href="{{ url('userssalesdoc') }}/{{ $user->id }}">Download Template</a> --}}
                                </div>
                            </div>
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
            $('.sales-form a').addClass('d-none')
            $('.sales-form input').prop('required', true)
            $('input[name=id]').val(val.data('id'))
            $('input[name=name]').val(val.data('name'))
            $('input[name=email]').val(val.data('email'))
            $('input[name=nik]').val(val.data('nik'))
            $('input[name=number]').val(val.data('number'))
            $('input[name=address]').val(val.data('address'))
            $(`select[name=role]`).val(val.data('role')).trigger('change')
            if(val.data('id') == undefined) {
                $('.fillpass').addClass('d-none')
            } else {
                $('.fillpass').removeClass('d-none')
                
            }

            let file = ['skck', 'ktp', 'sim', 'stnk', 'sertifikat', 'agreement']


            file.map((row, index) => {
                if(val.data(row) != '' && val.data(row) != undefined) {
                    $(`input[name=${row}]`).prop('required', false)
                    $('#preview-' + row).attr('href', "{{ url('') }}/" + val.data(row)).removeClass('d-none')
                }
            })

            if(val.data('foto') != '' && val.data('foto') != undefined) {
                $(`input[name=pas_foto]`).prop('required', false)
                $('#preview-pas_foto').attr('href', "{{ url('') }}/" + val.data('foto')).removeClass('d-none')
            }
            
        })
        const previewImage = (e, type) => {
            let file = e.target.files[0]
            $('.custom-file-label').html(file.name)
            $('#preview-' + type).removeClass('d-none')
            $('#preview-' + type).attr('href', URL.createObjectURL(file))
        }

        $('#number').keypress(function (e) {    
            $(this).val($(this).val().replace(/\D/g,''))
                      

        });    

        $(document).on('change', '#role', function() {
            let value = $(this).val()

            if (value == 'Sales') {
                $('#type_form').removeClass('d-none')
                $('.main-form').addClass('col-6').removeClass('col-12')
                $('.sales-form').removeClass('d-none')
                $('.sales-form input').prop('required', true)
                $('#type_form input').attr('required', true)
            } else {
                $('#type_form').addClass('d-none')
                $('.sales-form').addClass('d-none')
                $('.sales-form input').prop('required', false)
                $('.main-form').removeClass('col-6').addClass('col-12')
                $('#type_form input').attr('required', false)
            }
        })

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id')
            let form = $('#form').attr('action', "{{ url('users') }}/" + id)
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
