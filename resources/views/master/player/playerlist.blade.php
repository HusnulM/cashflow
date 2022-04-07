@extends('templates/main')

@section('title', 'Data Master Player')

@section('additional-css')
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
    $(function(){
        $('#tbl-users').DataTable({
            serverSide: true,
                ajax: {
                    url: base_url+'/master/player/playerlist',
                    data: function (data) {
                        data.params = {
                            sac: "sac"
                        }
                    }
            },
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            columns: [
                { "data": null,"sortable": false, 
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                {data: "playerid"},
                {data: "playername"},
                {data: "bankname"},
                {data: "bankacc"},
                {data: "afiliator"},
                {"defaultContent": 
                    "<button class='btn btn-danger btn-sm button-delete'> <i class='fa fa-trash'></i> HAPUS</button> <button class='btn btn-primary btn-sm button-edit'> <i class='fa fa-edit'></i> EDIT</button>"
                }
            ]  
        });

        $('#tbl-users tbody').on( 'click', '.button-delete', function () {
            
            var table = $('#tbl-users').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            // alert(selected_data.playerid);
            window.location = base_url+"/master/player/delete/"+selected_data.playerid;
        });

        $('#tbl-users tbody').on( 'click', '.button-edit', function () {
            // alert('edit')
            var table = $('#tbl-users').DataTable();
            selected_data = [];
            selected_data = table.row($(this).closest('tr')).data();
            window.location = base_url+"/master/player/edit/"+selected_data.playerid;
            // alert(selected_data.playerid);
        });
    })

    // $(document).ready(function() {
 
    //     $("#tbl-users").DataTable({
    //             serverSide: true,
    //             ajax: {
    //                 url: base_url+'/master/player/playerlist',
    //                 data: function (data) {
    //                     data.params = {
    //                         sac: "sac"
    //                     }
    //                 }
    //             },
    //             buttons: false,
    //             searching: true,
    //             scrollY: 500,
    //             scrollX: true,
    //             scrollCollapse: true,
    //             columns: [
    //                 {data: "playerid"},
    //                 {data: "playername"},
    //                 {data: "bankname"},
    //                 {data: "bankacc"},
    //                 {data: "afiliator"}
    //             ]  
    //     });

    // });
</script>
@endsection