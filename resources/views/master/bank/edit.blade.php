@extends('templates/main')

@section('title', 'Edit Master Bank')

@section('header-content')
<!-- <div class="content-header">
    <div class="container-fluid" style="background-color:#fff">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Data User</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Blank Page</li>
            </ol>
            </div>
        </div>
    </div>
</div>   -->
@endsection

@section('content')
<form action="{{ url('master/bank/update') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Master Bank</h3>
                    <div class="card-tools">                        
                        <a href="/master/bank" class="btn btn-danger btn-sm"> 
                            <i class="fa fa-arrow-alt-circle-left"></i> Kembali
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
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="kodebank">Kode Bank</label>
                                <input type="hidden" name="idbank" value="{{ $data->id }}">
                                <select name="kodebank" id="kodebank" class="form-control">
                                    <option value="{{ $currenctBank->bankid }}">{{ $currenctBank->bankid }} - {{ $currenctBank->deskripsi }}</option>
                                    @foreach($banklist as $bank)
                                    <option value="{{ $bank->bankid }}">{{ $bank->bankid }} - {{ $bank->deskripsi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="namabank">Nama Bank</label>
                                <input type="text" name="namabank" id="namabank" class="form-control" value="{{ $data->bankname }}" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="saldoawal">Saldo Awal</label>
                                <input type="text" name="saldoawal" class="form-control" value="{{ number_format($data->opening_balance,0,',','') }}" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="norek">Nomor Rekening</label>
                                <input type="text" name="norek" class="form-control" value="{{ $data->bank_accountnumber }}" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="atasnama">Atas Nama</label>
                                <input type="text" name="atasnama" class="form-control" value="{{ $data->bank_accountname }}" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="tipebank">Tipe Bank</label>
                                <!-- <select name="tipebank" id="tipebank" class="form-control">
                                    <option value="{{ $data->bank_type }}">{{ $data->bank_type }}</option>
                                    <option value="Depo">Depo</option>
                                    <option value="WD">WD</option>
                                    <option value="Penampung">Penampung</option>
                                </select> -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline" style="margin-right:25px;">
                                                @if( $data->bank_wd == 'Y' )
                                                <input type="checkbox" checked name="cbWD" id="cbWD">
                                                @else
                                                <input type="checkbox" name="cbWD" id="cbWD">
                                                @endif
                                                <label for="cbWD">WD
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline" style="margin-right:15px;">
                                                @if( $data->bank_depo == 'Y' )
                                                <input type="checkbox" checked name="cbDepo" id="cbDepo">
                                                @else
                                                <input type="checkbox" name="cbDepo" id="cbDepo">
                                                @endif
                                                
                                                <label for="cbDepo">Depo
                                                </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <!-- <input type="checkbox" name="cbPenampung" id="cbPenampung"> -->
                                                @if( $data->bank_penampung == 'Y' )
                                                <input type="checkbox" checked name="cbPenampung" id="cbPenampung">
                                                @else
                                                <input type="checkbox" name="cbPenampung" id="cbPenampung">
                                                @endif
                                                <label for="cbPenampung">
                                                Penampung
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    $(function(){
        $('#kodebank').on('change', function(){
            var namaBank = document.getElementById("kodebank").options[document.getElementById("kodebank").selectedIndex].text;
            const myArray = namaBank.split("-");
            console.log(myArray[1]);
            $('#namabank').val(myArray[1]);
        })
    });
</script>
@endsection