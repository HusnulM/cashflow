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
<div class="row">
    <div class="col-lg-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah User</h3>
                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button> -->
                    
                    <a href="/user" class="btn btn-danger btn-sm"> 
                        <i class="fa fa-arrow-alt-circle-left"></i> Kembali
                    </a>
                    <a href="/user" class="btn btn-primary btn-sm"> 
                        <i class="fa fa-save"></i> Simpan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <label for="nama">Nama User</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
</div>    
@endsection