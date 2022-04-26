@extends('templates/main')

@section('title', 'Edit Player')

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
<form action="{{ url('master/player/update') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Player</h3>
                    <div class="card-tools">                        
                        <a href="/master/player" class="btn btn-danger btn-sm"> 
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
                        <div class="col-lg-6 col-md-12">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label for="idplayer">ID Player</label>
                                    <input type="text" name="idplayer" class="form-control" value="{{ $data->playerid }}" readonly autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label for="namaplayer">Nama Player</label>
                                    <input type="text" name="namaplayer" class="form-control" value="{{ $data->playername }}" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="kodebank">Kode Bank</label>
                                    <select name="kodebank" id="kodebank" class="form-control">
                                        <option value="{{ $oldbank->bankid}}">{{ $oldbank->bankid }} - {{ $oldbank->deskripsi }}</option>
                                        @foreach($banklist as $bank)
                                        <option value="{{ $bank->bankid }}">{{ $bank->bankid }} - {{ $bank->deskripsi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="namabank">Nama BANK</label>
                                    <input type="text" name="namabank" id="namabank" class="form-control" value="{{ $data->bankname }}" autocomplete="off" required>
                                </div>
                            </div>                     
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="norek">Nomor Rekening</label>
                                    <input type="text" name="norek" class="form-control" value="{{ $data->bankacc }}" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="afiliator">Referal</label>
                                    <input type="text" name="afiliator" class="form-control" value="{{ $data->afiliator }}" autocomplete="off" required>
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