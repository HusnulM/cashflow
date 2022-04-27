@extends('templates/main')

@section('title', 'Deposit')

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
<form action="{{ url('transaksi/deposit/save') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Deposit</h3>
                    <div class="card-tools">    
                        <a href="/transaksi/deposit/upload" class="btn btn-success btn-sm"> 
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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="idplayer">ID Player</label>
                                <input type="hidden" name="idplayer" class="form-control">
                                <select name="player" id="find-player" class="form-control find-player"></select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="jmltopup">Jumlah Deposit</label>
                                <input type="text" name="jmltopup" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="bonustopup">Bonus Deposit</label>
                                <input type="text" name="bonustopup" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="namaplayer">Nama Player</label>
                                <input type="text" name="namaplayer" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="namabank">Nama Bank</label>
                                <input type="text" name="namabank" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="tgltopup">Tanggal Deposit</label>
                                <input type="date" name="tgltopup" class="form-control" value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nomor_rek">Nomor Rekening</label>
                                <input type="text" name="nomor_rek" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="rekening">Rekening Tujuan Pembayaran</label>
                                <select name="rekening" id="rekening" class="form-control" required>
                                    @foreach($bank as $b)
                                    <option value="{{ $b->bank_accountnumber }}">{{ $b->bankid }} - {{ $b->bank_accountname }} - {{ $b->bank_accountnumber }} | Saldo : {{ number_format($b->saldo,0,'.',',') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="stock">Stock Coin Tersedia</label>
                                <input type="text" name="stock" class="form-control" value="{{ $coin->quantity ?? '0' }}" readonly>
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
        placeholder: 'Type Player ID',
        minimumInputLength: 0,
        ajax: {
            url: base_url + '/master/player/searchbyname',
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.playerid,
                            slug: item.playername,
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
        $('input[name=namaplayer]').val(data.slug);
        $('input[name=namabank]').val(data.bankname);
        $('input[name=nomor_rek]').val(data.bankacc);
    });

    $(document).keypress(
        function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });
</script>
@endsection