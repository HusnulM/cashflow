@extends('templates/main')

@section('title', 'Input Permintaan Withdraw')

@section('addtional-css')
    <link href="{{ asset('/assets/select2/select2.min.css') }}" rel="stylesheet"/>
    <style type="text/css">
        .select2-container {
            display: block
        }

        .select2-container .select2-selection--single {
            height: 33px;
        }
    </style>
@endsection

@section('header-content')
@endsection

@section('content')
<form action="{{ url('transaksi/withdraw/save') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Input Permintaan Withdraw</h3>
                    <div class="card-tools">      
                        <a href="/transaksi/withdraw/upload" class="btn btn-success btn-sm"> 
                            <i class="fa fa-upload"></i> Upload Withdraw
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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="idplayer">ID Player</label>
                                <input type="hidden" name="idplayer" class="form-control" required>
                                <select name="player" id="find-player" class="form-control find-player"></select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="namaplayer">Nama Player</label>
                                <input type="text" name="namaplayer" class="form-control" readonly>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="jmlwd">Jumlah Withdraw *</label>
                                <input type="text" name="jmlwd" id="jmlwd" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="namabank">Nama Bank</label>
                                <input type="text" name="namabank" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nomor_rek">Nomor Rekening</label>
                                <input type="text" name="nomor_rek" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="tglwd">Tanggal Withdraw</label>
                                <input type="date" name="tglwd" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="rekening">Rekening Sumber Dana</label>
                                <select name="rekening" id="rekening" class="form-control" required>
                                    @foreach($bank as $b)
                                    <option value="{{ $b->bank_accountnumber }}">{{ $b->bankname }} - {{ $b->bank_accountnumber }} | Saldo : {{ number_format($b->saldo,0,'.',',') }}</option>
                                    @endforeach
                                </select>
                            </div>
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
    $(document).on('select2:open', (event) => {
        const searchField = document.querySelector(
            `.select2-search__field`,
        );
        if (searchField) {
            searchField.focus();
        }
    });

    $('#find-player').select2({
        placeholder: 'Type Player Name',
        minimumInputLength: 5,
        ajax: {
            url: base_url + '/master/player/searchbyname',
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.playername,
                            slug: item.playerid,
                            id: item.playerid,
                            ...item
                        }
                    })
                };
            },
        }
    });
        
    $('#find-player').on('select2:select', function (e) {
        var data = e.params.data;
        console.log(data);
        $('input[name=idplayer]').val(data.id);
        $('input[name=namaplayer]').val(data.text);
        $('input[name=namabank]').val(data.bankname);
        $('input[name=nomor_rek]').val(data.bankacc);

        document.getElementById("jmlwd").focus();
    });

    $(document).keypress(
        function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });

    $(function(){

    });
</script>
@endsection