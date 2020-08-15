@section('title','Master Data User')
@extends('template')
@section('contents')   
<header class="gray accent-3 relative nav-sticky">
    <div class="container-fluid text-black">
        <div class="row">
            <div class="col">
                <h3 class="my-3">
                    <i class="icon icon-notifications_active"></i>
                    Home <span class="s-14"> <a class="btn btn-outline-primary btn-xs" href="#" target="_blank"> @yield('title') </a> </span>
                </h3>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid animatedParent animateOnce my-3">
    <div class="animated fadeInUpShort">    
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
        
        
        <div class="container-fluid my-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="card no-b">
                        <div class="card-header">
                            <button class="add btn btn-primary btn-xs"><i class="icon icon-add"></i>Tambah data</button>   
                             
                        </div>
                        <div class="card-body"> 
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>USername</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Active</th> 
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div> 
            </div>
        </div> 
        <script>  
            $(function(){
                $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };
                $('#example2').DataTable({
                    initComplete: function() {
                        var api = this.api();
                        $('#datatables input')
                        .off('.DT')
                        .on('keyup.DT', function(e) {
                            if (e.keyCode == 13) {
                                api.search(this.value).draw();
                            }
                        });
                    },
                    oLanguage: {
                        sProcessing: '<i class="icon icon-spin"></i><b>Sedang Meload Data...</b>'
                        
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ Url('userdata/table') }}",
                    },
                    columns: [
                    {
                        data: 'DT_RowIndex',
                    }, 
                    {
                        data : 'username',
                    },
                    {
                        data : 'name',
                    },
                    {
                        data : 'email',
                    }, 
                    {
                        data : 'active', 
                    },
                    {
                        data : 'action',
                        orderable :false,
                    }
                    ],
                    'responsive' : true,
                    
                });
                
                $('#example2').on('click','.print',function(){
                    var id = $(this).attr('id');
                    var url_excel = "{{ Url('notulen/download/') }}/"+id+'/excel';
                    var url_word = "{{ Url('notulen/download/') }}/"+id+'/word';
                    var url_raw = "{{ Url('notulen/download/') }}/"+id+'/raw';
                    $('#judul').html('Jenis Export File Rapat ?');
                    $('#cetak_data').html('<a href="'+url_excel+'" class="btn btn-success btn-sm"><i class="fa fa-print"></i> Cetak Excel</a>  <a href="'+url_word+'" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>Cetak Word</a> <a href="'+url_raw+'" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i>Cetak Web</a>');
                    $('#confirm').modal('show');
                });
                
                //if add 
                $('.add').on('click',function(){
                    $('#show_form').modal('show'); 
                    $('.judul_form').html('<h4>Tambah data user</h4>');
                    $('#action').val('{{ route("userlogin.store") }}');
                    $('#method').val('post');
                    
                });

                $('#example2').on('click','.edit',function(){ 
                    var id = $(this).attr('data');
                    $.ajax({
                        type : 'post',
                        url  : "{{ route('userlogin.api') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                            },
                        dataType : 'json',
                        chace :false,
                        success:function(data){
                            $('.judul_form').html('<h4>Edit data barang <b>'+data.barangnm+'</b></h4>');
                            $('#action').val(data.action);
                            $('#username').val(data.method);
                            $('#name').val(data.barangnm);
                            $('#foto').val();
                            $('#email').val(data.hargabeli);
                            $('#hjual').val(data.hargajual);
                            $('#stok').val(data.stok); 
                            $('#hidden_id').val(data.id); 
                            
                        },error:function(data,jqxhr,error){
                            alert('alert'+status);
                        }   
                      });
  
                    $('#show_form').modal('show'); 
                    $('#action').val('{{ route("userlogin.store") }}')
                });
                
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#foto').attr('src', e.target.result);
                            //$('#image_2').attr('src', e.target.result); 
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }
                
    
            $('#example2').on('click','#delete',function(){ 
                var id = $(this).attr('data');
                swal({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda Yakin Untuk Menghapus Data Ini?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: 'Ya',
                    closeOnConfirm: false
                },
                function() {
                    swal('Hapus Data', 'Sedang Memproses permintaan ...', 'info');
                    $.ajax({  
                        type : 'post',  
                        url  : "{{ Url('userlogin/destroy') }}", 
                        data : {
                            id: id,
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        chace: false, 
                        success:function(result) { 
                            $('#example2').DataTable().ajax.reload(); 
                            swal('success', 'Data berhasil di hapus', 'success'); 
                        },
                        error: function(result) {
                            swal('Error', 'Maaf data tidak dapat di proses', 'error');
                            $('#example2').DataTable().ajax.reload();
                        }
                    });


                });
        
            });
        }); 
        </script> 
        
        <script>
            $(function(){
                $('#simpan').submit(function(e) {
                    e.preventDefault();
                    
                    var action     = $('#action').val();
                    var username   = $('#username').val();
                    var password   = $('#password').val();
                    var name       = $('#name').val();
                    var foto       = $('#foto').val();
                    var email      = $('#email').val();
                    var method     = $('#method').val(); 
                    
             
                    if (username == '') {
                        swal('Keterangan', 'username tidak boleh kosong', 'error');
                    } else if (password == '') {
                        swal('Keterangan', 'password boleh kosong', 'error');
                    } else if (name == '') {
                        swal('Keterangan', 'nama user tidak boleh kosong', 'error');
                    } else if (foto == '') {
                        swal('Keterangan', 'foto user tidak boleh kosong', 'error');
                    } else if (email == '') {
                        swal('Keterangan', 'email tidak boleh kosong', 'error');
                    } else { 
                        var datastring = new FormData(this);
                        $.ajax({
                            url: action,
                            type: method,
                            data: datastring,
                            headers: {
                                '_csrf': '{{ csrf_token() }}'
                            }, 
                            mimeType: "multipart/form-data",
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('#simpan').attr("disabled", "disabled");
                                $('#simpan').css("opacity", ".5");
                            },
                            success: function(data) {
                                swal('Keterangan', 'Data username dan password berhasil di update', 'success');
                                $('#simpan').css("opacity", "");
                                $("#simpan").removeAttr("disabled");
                            },
                            error: function(data) {
                                swal('Keterangan', 'Data username dan password gagal di update', 'warning');
                            }
                        });
                    }
                });
            });  
        </script>     
        
    </div>
</div>


<div class="modal fade" id="show_form" tabindex="-1" role="dialog">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content b-0">
<div class="modal-header r-0 bg-primary">
<h6 class="modal-title text-white" id="exampleModalLabel"><div class="judul_form"></div></h6>
<a href="#" data-dismiss="modal" aria-label="Close"
class="paper-nav-toggle paper-nav-white active"><i></i></a>
</div>
<div class="modal-body no-p no-b"> 
<form id="simpan" enctype="multipart/form-data" class='form-horizontal'>
@csrf()  
<input type="hidden" name="action" id="action" value=""> 
<input type="hidden" name="method" id="method" value="">
<input type="hidden" name="id" id="hidden_id" value="">

<div class='form-body'> 
<hr class='m-t-0 m-b-40'>
<div class='row'>
    <div class='col-md-6'>
        <div class='form-group'>
            <label class='control-label col-md-3'>Username</label>
            <div class='col-md-9'>
                <input type='text' name="username" id="username" class='form-control' placeholder='username' value=""></div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class='form-group'>
                <label class='control-label col-md-3'>Foto  </label>
                <div class='col-md-9'>
                    <div class="tampil_foto"></div>
                    <input type='file' name="foto" id="foto" class='form-control' placeholder='Foto' value=""></div>
                </div>
            </div>
            <div class='col-md-6'>
                <div class='form-group'>
                    <label class='control-label col-md-3'>Password </label>
                    <div class='col-md-9'> 
                        <input type='text' name="password" id="password" class='form-control' placeholder='Password' value=""></div>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label class='control-label col-md-3'>Nama</label>
                        <div class='col-md-9'> 
                            <input type='text' name="nama" id="nama" class='form-control' placeholder='Nama' value=""></div>
                        </div>
                    </div>
                    
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label class='control-label col-md-3'>Email</label>
                            <div class='col-md-9'> 
                                <input type='email' name="email" id="email" class='form-control' placeholder='email' value=""></div>
                            </div>
                        </div>  
                        <!--/span-->
                        
                        <hr class='m-t-0 m-b-40'>
                    </div>
                    <div class='form-actions'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class='row'>
                                    <div class='col-md-offset-3 col-md-9'>
                                        <button type='submit' id="simpan" class='btn btn-success'><i class="icon icon-save"></i>Simpan Data</button>
                                        <button type='button' class='btn btn-default'>Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-6'> </div>
                        </div>
                    </div>
                </form>
                
            
            </div>
            <div class="modal-footer">
                {{-- <button class="btn btn-primary l-s-1 s-12 text-uppercase">
                    Send Message
                </button> --}}
            </div>
        </div>
    </div>
</div>
@endsection