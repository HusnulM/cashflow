@extends('templates/main')

@section('title', 'TOP Up Coin')

@section('header-content')
@endsection

@section('content')
<form action="{{ url('transaksi/topup/save') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">TOP Up Coin</h3>
                    <div class="card-tools">                        
                        <!-- <a href="/master/bank" class="btn btn-danger btn-sm"> 
                            <i class="fa fa-arrow-alt-circle-left"></i> Kembali
                        </a> -->
                        <a href="/transaksi/topup/upload" class="btn btn-success btn-sm"> 
                            <i class="fa fa-upload"></i> Upload
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm"> 
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            @if(count($errors) > 0)
                                @foreach( $errors->all() as $message )
                                <div class="alert alert-danger alert-block">
                                    <button type="button" class="close closeAlert" data-dismiss="alert"></button> 
                                    <strong>{{ $message }}</strong>
                                </div>
                                @endforeach            
                            @endif
                            @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block msgAlert">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger msgAlert">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead>
                                    <th>No</th>
                                    <th>ID Player</th>
                                    <th>Nama Player</th>
                                    <th>Jumlah Topup</th>
                                    <th>Bonus</th>
                                    <th>Tgl Topup</th>
                                    <th style="width:100px;"></th>
                                </thead>
                                <tbody class="mainbodynpo" id="item-topup">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5"></td>
                                        <td colspan="2" style="text-align:right;">
                                            <input type="number" id="new-row-add" style="text-align:right; width:60px;" placeholder="Jumlah Tambah Data" value="1">
                                            <button type="button" class="btn btn-primary btn-sm" id="btn-add-player">
                                                <i class="fa fa-plus"></i> <span>Tambah Player</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    
                </div>
            </div>
        </div>
    </div>    
</form>
@endsection

@section('additional-js')
<script>
    $(function(){
        var count = 0;
        $('#btn-add-player').on('click', function(){
            var now = new Date();
            var month = (now.getMonth() + 1);               
            var day = now.getDate();
            if (month < 10) 
                month = "0" + month;
            if (day < 10) 
                day = "0" + day;
            var today = day + '/' + month + '/' + now.getFullYear();
            
            // alert(today)
            var totalNewRow = $('#new-row-add').val();
            if(totalNewRow === ''){
                totalNewRow = 1;
            }
            if(totalNewRow < 1){
                totalNewRow = 1;
            }
            
            for(var i = 0; i < totalNewRow; i++){
                count = count + 1;
                $('#item-topup').append(`
                    <tr>
                        <td class="nurut"> 
                            `+ count +`
                            <input type="hidden" name="itm_no[]" value="`+ count +`" />
                        </td>
                        <td> 
                            <input type="text" name="itm_idplayer[]" counter="`+count+`" id="idplayer`+count+`" class="form-control" required/>
                        </td>
                        <td> 
                            <input type="text" name="itm_nmplayer[]" counter="`+count+`" id="nmplayer`+count+`" class="form-control" required/>
                        </td>
                        <td> 
                            <input type="text" name="itm_jmltopup[]" counter="`+count+`" id="jmltopup`+count+`" class="form-control" required/>
                        </td>
                        <td> 
                            <input type="text" name="itm_jmlbonus[]" counter="`+count+`" id="jmlbonus`+count+`" class="form-control" required/>
                        </td>
                        <td> 
                            <input type="date" name="itm_tgltopup[]" counter="`+count+`" id="tgltopup`+count+`" class="form-control" value="`+ today +`" required/>
                        </td>
                        <td style="text-align:center;width:100px;">
                            <button type="button" class="btn btn-danger btn-sm removeItem" counter="`+count+`">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                `);

                renumberRows();

                $('.removeItem').on('click', function(e){
                    e.preventDefault();
                    $(this).closest("tr").remove();
                    renumberRows();
                });
            }

            $('#new-row-add').val('1')
        });

        function renumberRows() {
            $(".mainbodynpo > tr").each(function(i, v) {
                $(this).find(".nurut").text(i + 1);
            });
        }
    });
</script>
@endsection