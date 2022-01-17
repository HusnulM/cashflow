@extends('templates/main')

@section('title', 'Home')

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
                <h3 class="card-title">Data User</h3>
                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button> -->
                    
                    <a href="/createuser" class="btn btn-primary btn-sm"> 
                        <i class="fa fa-plus"></i> Tambah User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th>No.</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Nama User</th>
                                <th></th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
</div>    
@endsection