@extends('templates/main')

@section('title', 'Verifikasi Withdraw')

@section('header-content')
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Verifikasi Withdraw</h3>
                <div class="card-tools">
                    
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
                                <th>Tanggal Withdraw</th>
                                <th>Rekening Sumber Dana</th>
                                <th>Rekening Tujuan</th>
                                <th>Jumlah Withdraw</th>
                                <th>Biaya Admin</th>
                                <th style="width:100px;"></th>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ $d->idplayer }}</td>
                                    <td>{{ $d->playername }}</td>
                                    
                                    <td>{{ $d->wdpdate }}</td>
                                    <td>{{ $d->bank_sumbername }} | {{ $d->rekening_sumber }}</td>
                                    <td>{{ $d->bankname }} | {{ $d->bankacc }}</td>
                                    <td style="text-align:right;">{{ number_format($d->amount,0,'.',',') }}</td>
                                    <td style="text-align:right;">{{ number_format($d->biaya_adm,0,'.',',') }}</td>
                                    <!-- <td>
                                        <a href="/efiles/topupfiles/{{ $d->efile }}" target="_blank">{{ $d->efile }}</a>
                                    </td> -->
                                    <td style="text-align:center;">
                                        <a href="/transaksi/withdraw/close/{{$d->id}}" class="btn btn-success btn-sm">
                                            <i class="fa fa-ok"></i> DONE
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