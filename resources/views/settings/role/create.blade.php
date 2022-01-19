@extends('templates/main')

@section('title', 'Tambah User')

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
<form action="{{ url('setting/roles/save') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Role</h3>
                    <div class="card-tools">                        
                        <a href="/setting/roles" class="btn btn-danger btn-sm"> 
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
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label for="rolename">Nama Role</label>
                                <input type="text" name="rolename" class="form-control" autocomplete="off" required>
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