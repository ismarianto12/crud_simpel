@section('title','Data Master barang')
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
                                        <th>Nama Barang</th>
                                        <th>Foto</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Stok</th>
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
            $(function () {
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
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
                    initComplete: function () {
                        var api = this.api();
                        $('#datatables input')
                            .off('.DT')
                            .on('keyup.DT', function (e) {
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
                        url: "{{ Url('tmbarang/table') }}",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'barangnm',
                        },
                        {
                            data: 'foto_barang',
                        },
                        {
                            data: 'hbeli',
                        },
                        {
                            data: 'hjual',
                        },
                        {
                            data: 'stok',
                        },
                        {
                            data: 'action',
                            orderable: false,
                        }
                    ],
                    'responsive': true,

                });

                $('#example2').on('click', '.print', function () {
                    var id = $(this).attr('id');
                    var url_excel = "{{ Url('notulen/download/') }}/" + id +
                        '/excel';
                    var url_word = "{{ Url('notulen/download/') }}/" + id +
                        '/word';
                    var url_raw = "{{ Url('notulen/download/') }}/" + id + '/raw';
                    $('#judul').html('Jenis Export File Rapat ?');
                    $('#cetak_data').html('<a href="' + url_excel +
                        '" class="btn btn-success btn-sm"><i class="fa fa-print"></i> Cetak Excel</a>  <a href="' +
                        url_word +
                        '" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>Cetak Word</a> <a href="' +
                        url_raw +
                        '" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i>Cetak Web</a>'
                        );
                    $('#confirm').modal('show');
                });

                //if add 
                $('.add').on('click', function () {
                    $('#show_form').modal('show');
                    $('.judul_form').html('<h4>Tambah data barang</h4>');
                    $('#action').val('{{ route("barang.store") }}');
                    $('#method').val('post');
                    $('#example2').DataTable().ajax.reload();
                    $('#barangnm').val('');
                    $('#foto').val('');
                    $('#hbeli').val('');
                    $('#hjual').val('');
                    $('#stok').val('');
                    $('.tampil_foto').html('');
                });

                $('#example2').on('click', '#edit', function () {
                    var id = $(this).attr('data');
                    $.ajax({
                        type: 'post',
                        url: "{{ route('barang.api') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        dataType: 'json',
                        chace: false,
                        beforeSend: function (data) {
                            $('#simpan').attr("disabled", "disabled");
                            $('#simpan').css("opacity", ".5");
                            $('.judul_form').html(
                                '<h4>Harap Bersabar sedang meload data ....</b></h4>');

                        },
                        success: function (data) {
                            $('#simpan').css("opacity", "");
                            $("#simpan").removeAttr("disabled");

                            $('.judul_form').html('<h4>Edit data barang <b>' + data
                                .barangnm + '</b></h4>');
                            $('#action').val(data.action);
                            $('#method').val(data.method);
                            $('#barangnm').val(data.barangnm);
                            $('#foto').val();
                            $('#hbeli').val(data.hargabeli);
                            $('#hjual').val(data.hargajual);
                            $('#stok').val(data.stok);
                            $('#hidden_id').val(data.id);
                            $('.tampil_foto').html('<img src="' + data.foto +
                                '" class="img-responsive" style="height:100px;width:100px">'
                                )

                        },
                        error: function (data, jqxhr, error) {
                            alert('alert' + status);
                        }
                    });

                    $('#show_form').modal('show');
                    $('#action').val('{{ route("barang.store") }}')
                });



                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('.tampil_foto').html('<img src="'+e.target.result+'" class="img-responsive">');
                            //$('#image_2').attr('src', e.target.result); 
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }


                $('#example2').on('click', '#delete', function () {
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
                        function () {
                            swal('Hapus Data', 'Sedang Memproses permintaan ...');
                            $.ajax({
                                type: 'post',
                                url: "{{ Url('barang/destroy') }}",
                                data: {
                                    id: id,
                                    _token: '{{ csrf_token() }}',
                                    _method: 'DELETE'
                                },
                                chace: false,
                                success: function (result) {
                                    $('#example2').DataTable().ajax.reload();
                                    swal('success', 'Data berhasil di hapus',
                                    'success');
                                },
                                error: function (result) {
                                    swal('Error', 'Maaf data tidak dapat di proses',
                                        'error');
                                    $('#example2').DataTable().ajax.reload();
                                }
                            });


                        });

                });
            });

        </script>


        <script>
            $(function () {
                $('#simpan').submit(function (e) {
                    e.preventDefault();
                    var action = $('#action').val();
                    var method = $('#method').val();
                    var barangnm = $('#barangnm').val();
                    var foto = $('#foto').val();
                    var hbeli = $('#hbeli').val();
                    var hjual = $('#hjual').val();
                    var stok = $('#stok').val();

                    //   var foto = $('#foto').val();
                    if (barangnm == '') {
                        swal('Keterangan', 'Nama Barang tidak boleh kosong', 'error');
                    } else if (hbeli == '') {
                        swal('Keterangan', 'Harga Beli tidak boleh kosong', 'error');
                    } else if (hjual == '') {
                        swal('Keterangan', 'Harga jual tidak boleh kosong', 'error');
                    } else if (stok == '') {
                        swal('Keterangan', 'Stok barang tidak boleh kosong', 'error');
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
                            dataType: 'json',
                            beforeSend: function () {
                                $('#simpan').attr("disabled", "disabled");
                                $('#simpan').css("opacity", ".5");
                                swal('Keterangan',
                                'Sedang Menyimpan data harap tunggu ...');

                            },
                            success: function (data) {
                                if (data.success) {
                                    $('#show_form').modal('show');
                                    swal('Keterangan', 'Data Barang Berhasil di tambahkan',
                                        'success');
                                    $('#example2').DataTable().ajax.reload();
                                    $('#simpan').css("opacity", "");
                                    $("#simpan").removeAttr("disabled");

                                    $('#show_form').modal('hide');
                                    $('#barangnm').val('');
                                    $('#foto').val('');
                                    $('#hbeli').val('');
                                    $('#hjual').val('');
                                    $('#stok').val('');
                                } else if (data.errors) {
                                    $('#simpan').css("opacity", "");
                                    $("#simpan").removeAttr("disabled");

                                    swal('keterangan', data.errors, 'error');

                                }
                            },
                            error: function (data) {
                                swal('Keterangan',
                                    'Data username dan password gagal di update',
                                    'warning');
                            }
                        });
                    }
                });
            });

        </script>

    </div>
</div>



<script>
    function format_num(id) {
        var number = document.getElementById(id).value;
        number += '';
        number = number.replace(",", "");
        x = number.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        document.getElementById(id).value = x1 + x2;
    }

</script>


<div class="modal fade" id="show_form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content b-0">
            <div class="modal-header r-0 bg-primary">
                <h6 class="modal-title text-white" id="exampleModalLabel">
                    <div class="judul_form"></div>
                </h6>
                <a href="#" data-dismiss="modal" aria-label="Close"
                    class="paper-nav-toggle paper-nav-white active"><i></i></a>
            </div>
            <div class="modal-body no-p no-b">
                <div id="notif"></div>

                <form id="simpan" enctype="multipart/form-data" method="POST" class='form-horizontal'>
                    @csrf()
                    <input type="hidden" name="action" id="action" value="">
                    <input type="hidden" name="method" id="method" value="">
                    <input type="hidden" name="id" id="hidden_id" value="">

                    <div class='form-body'>
                        <center>
                            <small>Ket : untuk entri data harga beli dan harga jual silahkan gunakan spasi jika format
                                tidak muncul .</small>
                        </center>
                        <hr class='m-t-0 m-b-40'>
                        <div class='row'>

                            <div class='col-md-6'>
                                <div class='form-group'>
                                    <label class='control-label col-md-3'>Nama Barang</label>
                                    <div class='col-md-9'>
                                        <input type='text' name="barangnm" id="barangnm" class='form-control'
                                            placeholder='Nama Barang' value=""></div>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class='form-group'>
                                    <label class='control-label col-md-3'>Foto </label>
                                    <div class='col-md-9'>
                                        <div class="tampil_foto"></div>
                                        <input type='file' name="foto" id="foto" class='form-control' placeholder='Foto'
                                            value="">
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class='form-group'>
                                    <label class='control-label col-md-3'>Harga Beli </label>
                                    <div class='col-md-9'>
                                        <input type='text' name="hbeli" id="hbeli" class='form-control'
                                            placeholder='Harga Beli' value="" onkeypress="format_num(id)"></div>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class='form-group'>
                                    <label class='control-label col-md-3'>Harga Jual</label>
                                    <div class='col-md-9'>
                                        <input type='text' name="hjual" id="hjual" class='form-control'
                                            placeholder='Harga Jual' value="" onkeypress="format_num(id)"></div>
                                </div>
                            </div>

                            <div class='col-md-6'>
                                <div class='form-group'>
                                    <label class='control-label col-md-3'>Stok Barang</label>
                                    <div class='col-md-9'>
                                        <input type='number' name="stok" id="stok" class='form-control'
                                            placeholder='Stok Barang' value=""></div>
                                </div>
                            </div>
                            <!--/span-->
                            <hr class='m-t-0 m-b-40'>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type='submit' id="simpan" class='btn btn-success'><i class="icon icon-save"></i>Simpan
                            Data</button>
                        <button type='button' data-dismiss="modal" class='btn btn-default'>Cancel</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
    <script>
        $(function () {
            $('#example2').on('click', '#detail', function () {
                var id = $(this).attr('data');
                $.ajax({
                    type: 'post',
                    url: "{{ route('barang.api') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    dataType: 'json',
                    chace: false,
                    beforeSend: function (data) {
                        $('#simpan').attr("disabled", "disabled");
                        $('#simpan').css("opacity", ".5");
                        $('.judul_form').html(
                            '<h4>Harap Bersabar sedang meload data ....</b></h4>');

                    },
                    success: function (data) {
                        $('#simpan').css("opacity", "");
                        $("#simpan").removeAttr("disabled");

                        $('.judul_form').html('<h4>Detail data barang <b>' + data.barangnm +
                            '</b></h4>');
                        $('.action').html(data.action);
                        $('.barangnm').html(data.barangnm);
                        $('.hbeli').html(data.hargabeli);
                        $('.hjual').html(data.hargajual);
                        $('.stok').html('<b>'+data.stok+'</b>');
                        $('.tampil_foto').html('<img src="' + data.foto +
                            '" class="img-responsive" style="height:100px;width:100px">'
                            );

                    },
                    error: function (data, jqxhr, error) {
                        alert('alert' + status);
                    }
                });

                $('#detail_data').modal('show'); 
            });
        })

    </script>

    <div class="modal fade" id="detail_data" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content b-0">
                <div class="modal-header r-0 bg-primary">
                    <h6 class="modal-title text-white" id="exampleModalLabel">
                        <div class="judul_form"></div>
                    </h6>
                    <a href="#" data-dismiss="modal" aria-label="Close"
                        class="paper-nav-toggle paper-nav-white active"><i></i></a>
                </div>
                <div class="modal-body no-p no-b"> 
                    <div class='form-body'>
                       
                        <hr class='m-t-0 m-b-40'>
                        <div class='container'>
                            <table class="table">
                                <tr>
                                    <td>Nama Barang</td>
                                    <td>
                                        <div class="barangnm"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Foto</td>
                                    <td>
                                        <div class="tampil_foto"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Harga Beli</td>
                                    <td>
                                        <div class="hbeli"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Harga Jual</td>
                                    <td>
                                        <div class="hjual"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Stok Barang</td>
                                    <td>
                                        <div class="stok"></div>
                                    </td>
                                </tr>

                            </table> 


                            <div class="modal-footer">

                            </div> 

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
