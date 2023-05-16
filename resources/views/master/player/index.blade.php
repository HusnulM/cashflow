@extends('templates/main')

@section('title', 'Data Master Player')

@section('additional-css')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection

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
                <h3 class="card-title">Data Player</h3>
                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button> -->
                    
                    <a href="/master/player/create" class="btn btn-primary btn-sm"> 
                        <i class="fa fa-plus"></i> Tambah Player
                    </a>

                    <a href="/master/player/upload" class="btn btn-primary btn-sm"> 
                        <i class="fa fa-upload"></i> Upload Player
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if(count($errors) > 0)
                            @foreach( $errors->all() as $message )
                            <div class="alert alert-danger alert-block msgAlert">
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
                    <div class="table-responsive">
                        <table id="tbl-users" class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <th>No.</th>
                                <th>ID Player</th>
                                <th>Nama Player</th>
                                <th>Nama Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Referal</th>
                                <th style="width:170px;"></th>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    @if($d->totalafiliator <= 10)
                                    <td style="background-color:red; color:white;">
                                        {{ $d->playerid }}
                                    </td>
                                    @elseif($d->totalafiliator > 10 && $d->totalafiliator <= 49)
                                    <td style="background-color:green; color:white;">
                                        {{ $d->playerid }}
                                    </td>
                                    @elseif($d->totalafiliator >= 50)
                                    <td style="background-color:blue; color:white;">
                                        {{ $d->playerid }}
                                    </td>
                                    @endif
                                    <td>{{ $d->playername }}</td>
                                    <td>{{ $d->bankname }}</td>
                                    <td>{{ $d->bankacc }}</td>
                                    <td>{{ $d->afiliator }}</td>
                                    <td style="text-align:center;">
                                        <a href="/master/player/delete/{{$d->playerid}}" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> HAPUS
                                        </a> 
                                        <a href="/master/player/edit/{{$d->playerid}}" class="btn btn-success btn-sm">
                                            <i class="fa fa-edit"></i> EDIT
                                        </a>                                         
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
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

@section('additional-js')
<script>
    // $(function(){
    //     $('#tbl-users').DataTable({
    //         "paging": true,
    //         "lengthChange": false,
    //         "searching": false,
    //         "ordering": true,
    //         "info": true,
    //         "autoWidth": false,
    //         "responsive": true,
    //     });
    // })

    $(document).ready(function() {
 
        $("#tbl-users").DataTable({
                serverSide: true,
                ajax: {
                    url: base_url+'/master/player/playerlist',
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
                },
                buttons: false,
                searching: true,
                scrollY: 500,
                scrollX: true,
                scrollCollapse: true,
                columns: [
                    {data: "user_id", className: 'uid'},
                    {data: "first_name", className: 'fname'},
                    {data: "username", className: 'uname'},
                    {data: "gender", className: 'gender'}
                
                ]  
        });

    });
</script>
@endsection