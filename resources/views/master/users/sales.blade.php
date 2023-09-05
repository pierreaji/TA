@extends('layouts.app')
@section('contents')
    <!-- Simple Datatable start -->
    <div class="card">
        <div class="card-body">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">User Sales {{ $user->name }}</h4>
                </div>
                <div class="pb-20">
                    <form action="{{ route('users.sales.store', ['id' => $user->id]) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @if (Auth::user()->role == 'Sales' && $user->Sales->approved_status == 2)
                            <div class="alert alert-danger" role="alert">
                                Your document is rejected! Please reupload documents <br>
                                Reason : {{ $user->Sales->reason }}
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="formFile" class="form-label">SKCK</label>
                            <input class="form-control {{ $user->Sales->skck == null ? '' : 'd-none' }}" type="file" accept="image/*" name="skck" id="skck"
                                onchange="previewImage(event, 'skck')">
                                <br>
                            <a href="{{ url('') . "/{$user->Sales->skck}" }}" id="preview-skck" target="_blank"
                                class="btn btn-info btn-sm mt-2 {{ $user->Sales->skck != null ? '' : 'd-none' }}">
                                <i class="anticon anticon-camera"></i> Preview File
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">KTP</label>
                            <input class="form-control {{ $user->Sales->ktp == null ? '' : 'd-none' }}" type="file" accept="image/*" name="ktp" id="ktp"
                                onchange="previewImage(event, 'ktp')">
                                <br>
                            <a href="{{ url('') . "/{$user->Sales->ktp}" }}" id="preview-ktp" target="_blank"
                                class="btn btn-info btn-sm mt-2 {{ $user->Sales->ktp != null ? '' : 'd-none' }}">
                                <i class="anticon anticon-camera"></i> Preview File
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">SIM</label>
                            <input class="form-control {{ $user->Sales->sim == null ? '' : 'd-none' }}" type="file" accept="image/*" name="sim" id="sim"
                                onchange="previewImage(event, 'sim')">
                                <br>
                            <a href="{{ url('') . "/{$user->Sales->sim}" }}" id="preview-sim" target="_blank"
                                class="btn btn-info btn-sm mt-2 {{ $user->Sales->sim != null ? '' : 'd-none' }}">
                                <i class="anticon anticon-camera"></i> Preview File
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">STNK</label>
                            <input class="form-control {{ $user->Sales->stnk == null ? '' : 'd-none' }}" type="file" accept="image/*" name="stnk" id="stnk"
                                onchange="previewImage(event, 'stnk')">
                                <br>
                            <a href="{{ url('') . "/{$user->Sales->stnk}" }}" id="preview-stnk" target="_blank"
                                class="btn btn-info btn-sm mt-2 {{ $user->Sales->stnk != null ? '' : 'd-none' }}">
                                <i class="anticon anticon-camera"></i> Preview File
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Pas Foto</label>
                            <input class="form-control {{ $user->Sales->pas_foto == null ? '' : 'd-none' }}" type="file" accept="image/*" name="pas_foto" id="pas_foto"
                                onchange="previewImage(event, 'pas_foto')">
                                <br>
                            <a href="{{ url('') . "/{$user->Sales->pas_foto}" }}" id="preview-pas_foto" target="_blank"
                                class="btn btn-info btn-sm mt-2 {{ $user->Sales->pas_foto != null ? '' : 'd-none' }}">
                                <i class="anticon anticon-camera"></i> Preview File
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Sertifikat</label>
                            <input class="form-control {{ $user->Sales->sertifikat == null ? '' : 'd-none' }}" type="file" accept="image/*" name="sertifikat" id="sertifikat"
                                onchange="previewImage(event, 'sertifikat')">
                                <br>
                            <a href="{{ url('') . "/{$user->Sales->sertifikat}" }}" id="preview-sertifikat" target="_blank"
                                class="btn btn-info btn-sm mt-2 {{ $user->Sales->sertifikat != null ? '' : 'd-none' }}">
                                <i class="anticon anticon-camera"></i> Preview File
                            </a>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Agreement document</label>
                            <input class="form-control {{ $user->Sales->agreement == null ? '' : 'd-none' }}" type="file" accept="image/*" name="agreement" id="agreement"
                                onchange="previewImage(event, 'agreement')">
                                <br>
                            <a href="{{ url('') . "/{$user->Sales->agreement}" }}" id="preview-agreement"
                                target="_blank"
                                class="btn btn-info btn-sm mt-2 {{ $user->Sales->agreement != null ? '' : 'd-none' }}">
                                <i class="anticon anticon-camera"></i> Preview File
                            </a>
                            <br>
                            @if (Auth::user()->role != 'Admin')
                            <a href="{{ url('userssalesdoc') }}/{{ $user->id }}">Download Template</a>
                            @endif
                        </div>
                        <div class="mb-3">
                            <div class="btn-group">
                                @php
                                    $files = [
                                        'skck' => $user->Sales->skck,
                                        'ktp' => $user->Sales->ktp,
                                        'sim' => $user->Sales->sim,
                                        'stnk' => $user->Sales->stnk,
                                        'pas_foto' => $user->Sales->pas_foto,
                                        'sertifikat' => $user->Sales->sertifikat,
                                        'agreement' => $user->Sales->agreement,
                                    ];
                                @endphp
                                
                                @if (Auth::user()->role == 'Admin' && in_array(null, $files) && $user->Sales->approved_status != 0)
                                <button class="btn btn-primary">Save</button>
                                @elseif (Auth::user()->role == 'Sales') 
                                <button class="btn btn-primary">Save</button>
                                @endif
                                @if (Auth::user()->role == 'Admin' && !in_array(null, $files) && $user->Sales->approved_status != 0)
                                    <button class="btn btn-warning" name="renew" value="true">Renew</button>
                                @endif
                                @if(Auth::user()->role == 'Admin' && $user->Sales->approved_status == 0)
                                    <button type="button" class="btn btn-approve btn-success" data-status="1"
                                        data-bs-toggle="modal" data-bs-target="#Medium-modal">Approve</button>
                                    <button type="button" class="btn btn-approve btn-danger" data-status="2"
                                        data-bs-toggle="modal" data-bs-target="#Medium-modal">Reject</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Medium-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('users.sales.confirm', ['id' => $user->id]) }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Approval
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="status">
                        <div class="form-group" id="reason">
                            
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
@endsection
@section('scripts')
    <script>
        const previewImage = (e, type) => {
            let file = e.target.files[0]
            $('.custom-file-label').html(file.name)
            $('#preview-' + type).removeClass('d-none')
            $('#preview-' + type).attr('href', URL.createObjectURL(file))
        }

        $('.btn-approve').on('click', function() {
            let status = $(this).data('status')

            $('#reason').html('')
            $('input[name=status]').val(status)
            if (status == "1") {
                $('.modal-title').html('Approve')
            } else {
                $('#reason').html(`
                <label>Reason</label>
                <textarea name="reason" id="reason" cols="30" rows="10" class="form-control" required></textarea>
                `)
                $('.modal-title').html('Reject')
            }
        })
    </script>
@endsection
