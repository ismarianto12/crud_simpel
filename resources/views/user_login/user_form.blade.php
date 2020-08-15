@section('title','Edit Profil')
@extends('template')
@section('contents')

<header class="gray accent-3 relative nav-sticky">
    <div class="container-fluid text-black">
        <div class="row">
            <div class="col">
                <h3 class="my-3">
                    <i class="icon icon-notifications_active"></i>
                    Home <span class="s-14"> <a class="btn btn-outline-primary btn-xs" href="#" target="_blank">
                            @yield('title') </a> </span>
                </h3>
            </div>
        </div>
    </div>
</header>


<form class="needs-validation" id="cupdate" method="POST" action="{{ route('profil.update',Auth::user()->id) }}" enctype="multipart/form-data">
    @csrf()
    {{ method_field('POST') }}
  
    <input type="hidden" id="id" name="id" />
    <div class="card mt-2">
        <div class="card-body">
            <div class="form-row form-inline">
                <div class="col-md-12">
                    <div class="container" style="margin-left: 15px"> 
                    <table>
                        <tr>
                            <td>Username</td>
                            <td>
                                <input type='text' name="username" id="username" class='form-control'
                                    placeholder='Username' value="{{ $data->username }}"></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input type='password' name="password" id="password" class='form-control'
                                    placeholder='Password..'>
                            </td>
                        </tr>
                        <tr>
                            <td>Ulangi Password</td>
                            <td><input type='password' name="password_ul" id="password_ul" class='form-control'
                                    placeholder='Password..'></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td><input type='text' name="nama" id="nama" class='form-control' placeholder='Password..'
                                    value="{{ $data->nama }}"></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type='email' name="email" id="email" class='form-control' placeholder='Email..'
                                    value="{{ $data->email }}"></td>
                        </tr>
                        <tr>
                            <td>Foto</td>
                            <td> <img src="{{ asset('public/foto_user/'.$data->foto) }}"
                                    class="img-responsive" style="width:100px;height:100px">
                                <br />
                                <input type='file' id="foto" name="foto" class='form-control' placeholder='Foto..'>
                            </td>
                        </tr>
                        <tr>
                            <td> <button type='submit' id="cupdate" class='btn btn-success'>Simpan Data</button>
                                <button type='button' class='btn btn-default'>Cancel</button>
                            </td>
                        </tr>
                    </table> 
                  </div>
                </div>
            </div>
        </div>

    </div>
</form>
    <script type="text/javascript">
        $(function () {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#image_upload_preview').attr('src', e.target.result);
                        $('#image_2').attr('src', e.target.result);

                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#foto").change(function () {
                var ext = $('#foto').val().split('.').pop().toLowerCase();
                //Allowed file types
                if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                    swal('File Error', 'tidak bisa upload', 'warning');
                    $('#foto').val('');
                } else {
                    readURL(this);
                }
            });
 
            $('#cupdate').submit(function (e) {
                e.preventDefault();
                var username = $('#username').val();
                var password = $('#password').val();
                var password_ul = $('#password_ul').val();
                var nama = $('#nama').val();
                var email = $('#email').val();
                //   var foto = $('#foto').val();
                if (username == '') {
                    swal('Keterangan', 'Username tidak boleh kosong', 'error');
                } else if (password == '') {
                    swal('Keterangan', 'Password tidak boleh kosong', 'error');
                } else if (password_ul == '') {
                    swal('Keterangan', 'Ulangi Password tidak boleh kosong', 'error');
                } else if (password != password_ul) {
                    swal('Keterangan', 'password tidak sama silahkan ulangi', 'error');
                } else if (nama == '') {
                    swal('Keterangan', 'Nama tidak boleh kosong', 'error');
                } else if (email == '') {
                    swal('Keterangan', 'email tidak boleh kosong', 'error');
                } else {

                    var datastring = new FormData(this);
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'put',
                        data: datastring,
                        headers: {
                            '_csrf': '{{ csrf_token() }}'
                        },
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#cupdate').attr("disabled", "disabled");
                            $('#cupdate').css("opacity", ".5");
                        },
                        success: function (data) {
                            swal('Keterangan',
                                'Data username dan password berhasil di update',
                                'success');
                            $('#cupdate').css("opacity", "");
                            $("#cupdate").removeAttr("disabled");
                        },
                        error: function (data) {
                            swal('Keterangan', 'Data username dan password gagal di update',
                                'warning');
                        }
                    });
                }
            });
        });

    </script>
    @endsection
