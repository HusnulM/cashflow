@extends('templates/main')

@section('title', 'Pindah Dana')

@section('header-content')
@endsection

@section('content')
<form action="{{ url('transaksi/transfer/save') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pindah Dana</h3>
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
                                <label for="tglTransfer">Tanggal Pindah Dana</label>
                                <input type="date" name="tglTransfer" class="form-control" value="<?= date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="rekeningAsal">Rekening Asal</label>
                                <select name="rekAsal" id="rekAsal" class="form-control">
                                    <option value="">Pilih Rekening Asal</option>
                                    @foreach($bank as $b)
                                    <option value="{{ $b->bank_accountnumber }}">{{ $b->bank_accountname }} - {{ $b->bank_accountnumber }} | Saldo : {{ number_format($b->saldo,0,'.',',') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="rekeningTujuan">Rekening Tujuan</label>
                                <select name="rekTujuan" id="rekTujuan" class="form-control">
                                    <option value="">Pilih Rekening Tujuan</option>
                                    @foreach($bank as $b)
                                    <option value="{{ $b->bank_accountnumber }}">{{ $b->bank_accountname }} - {{ $b->bank_accountnumber }} | Saldo : {{ number_format($b->saldo,0,'.',',') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="jmlTransfer">Jumlah Pindah Dana</label>
                                <input type="text" name="jmlTransfer" class="form-control" required>
                            </div>
                        </div>     
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="biayaTransfer">Biaya Admin</label>
                                <input type="text" name="biayaTransfer" class="form-control" value="0" readonly>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="note">Keterangan</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="form-control"></textarea>
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

    $(function(){
        var fromBank = '';
        var toBank   = '';
        
        $('#rekAsal').on('change', function(){
            
            // alert(this.value)
            var bankid = this.value;
            (async () => {
                const rawResponse = await fetch(base_url+'/master/bank/detail/'+bankid, {
                    method: 'GET',
                    headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                    }
                });
                const content = await rawResponse.json();
                console.log(content)
                fromBank = content.bankid;
                // toBank   = $('#bankid').val();

                getBiayaAdmin(fromBank, toBank);
            })();
        });

        $('#rekTujuan').on('change', function(){
            
            // alert(this.value)
            var bankid = this.value;
            (async () => {
                const rawResponse = await fetch(base_url+'/master/bank/detail/'+bankid, {
                    method: 'GET',
                    headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                    }
                });
                const content = await rawResponse.json();
                console.log(content)
                toBank = content.bankid;
                // toBank   = $('#bankid').val();

                getBiayaAdmin(fromBank, toBank);
            })();
        });

        function getBiayaAdmin(_from, _to){
            // biayaadm
            (async () => {
                $('input[name=biayaTransfer]').val('0');
                const rawResponse = await fetch(base_url+'/master/bank/biayaadm/'+_from+'/'+_to, {
                    method: 'GET',
                    headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                    }
                });
                const content = await rawResponse.json();
                console.log(content)
                if(content.biaya_adm){
                    $('input[name=biayaTransfer]').val(content.biaya_adm);
                }
            })();
        }
    });
</script>
@endsection