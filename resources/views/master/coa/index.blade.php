@extends('templates/main')

@section('title', 'Data Chart of Account')

@section('header-content')

@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Chart of Account</h3>
                <div class="card-tools">                    
                    <a href="/master/coa/create" class="btn btn-primary btn-sm"> 
                        <i class="fa fa-plus"></i> Tambah COA
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
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
                    <div class="table-responsive">
                        <table id="tbl-coa" class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <th>No.</th>
                                <th>Account</th>
                                <th>Nama Account</th>
                                <!-- <th>Tipe</th> -->
                                <th style="width:170px;"></th>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ $d->account }}</td>
                                    <td>{{ $d->account_name }}</td>
                                    <!-- <td>{{ $d->account_ind }}</td> -->
                                    <td style="text-align:center;">
                                        <a href="/master/coa/delete/{{$d->id}}" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> HAPUS
                                        </a> 
                                        <a href="/master/coa/edit/{{$d->id}}" class="btn btn-success btn-sm">
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
    $(function(){
        $('#tbl-coa').DataTable({
            // "paging": true,
            // "lengthChange": false,
            // "searching": true,
            // "ordering": true,
            // "info": true,
            // "autoWidth": false,
            // "responsive": true,
        });
    })
</script>
@endsection