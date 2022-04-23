@extends('templates/main')

@section('title', 'TOP Up')

@section('header-content')
@endsection

@section('content')
<form action="{{ url('transaksi/topup/save') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">TOP Up</h3>
                    <div class="card-tools">    
                        <!-- <a href="/transaksi/deposit/upload" class="btn btn-success btn-sm"> 
                            <i class="fa fa-upload"></i> Upload
                        </a> -->
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
                                <label for="tglDepo">Tanggal Top up</label>
                                <input type="date" name="tglDepo" class="form-control" value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="jmlDepo">Jumlah Top up</label>
                                <input type="text" name="jmlDepo" class="form-control">
                            </div>
                        </div>                        
                    </div>
                    <!-- <div class="row">
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
                    </div> -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="note">Keterangan</label>
                                <textarea name="note" id="note" cols="30" rows="4" class="form-control"></textarea>
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