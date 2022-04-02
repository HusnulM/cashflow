@extends('templates/main')

@section('title', 'Laporan Deposit')

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
                <h3 class="card-title">Laporan Deposit</h3>
                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button> -->
                    
                    <!-- <a href="/master/bank/create" class="btn btn-primary btn-sm"> 
                        <i class="fa fa-plus"></i> Tambah Bank
                    </a> -->
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
                        <table id="tbl-users" class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <th>No.</th>
                                <th>ID Player</th>
                                <th>Nama Player</th>
                                <th>Jumlah Deposit</th>
                                <th>Bonus Deposit</th>
                                <th>Tanggal Deposit</th>
                                <th>dibayar Dari</th>
                                <th>dibayar Ke</th>
                                <th>Status Deposit</th>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ $d->idplayer }}</td>
                                    <td>{{ $d->playername }}</td>
                                    <td style="text-align:right;">{{ number_format($d->amount,0,'.',',') }}</td>
                                    <td style="text-align:right;">{{ number_format($d->topup_bonus,0,'.',',') }}</td>
                                    <td>{{ $d->topupdate }}</td>
                                    <td>{{ $d->bankname }} {{ $d->bankacc }}</td>
                                    <td>{{ $d->banknameto }} {{ $d->rekening_tujuan }}</td>
                                    <!-- <td>
                                        <a href="/efiles/topupfiles/{{ $d->efile }}" target="_blank">{{ $d->efile }}</a>
                                    </td> -->
                                    <td>{{ $d->topup_status }}</td>
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
        $('#tbl-users').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    })
</script>
@endsection