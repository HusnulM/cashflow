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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="diplayer">ID Player</label>
                                <input type="text" name="idplayer" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="jmltopup">Jumlah TOP Up</label>
                                <input type="text" name="jmltopup" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="bonustopup">Bonus TOP Up</label>
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
                                <label for="tgltopup">Tanggal Topup</label>
                                <input type="date" name="tgltopup" class="form-control">
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
                                    <option value="{{ $b->bank_accountnumber }}">{{ $b->bankname }} - {{ $b->bank_accountnumber }}</option>
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
    $(document).keypress(
        function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    });
</script>
@endsection