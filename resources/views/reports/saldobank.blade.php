@extends('templates/main')

@section('title', 'Laporan Saldo Bank')

@section('header-content')

@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Saldo Bank</h3>
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
                    <div class="table-responsive">
                        <table id="tbl-users" class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <th>No.</th>
                                <th>Kode Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Atas Nama Rekening</th>
                                <th>Saldo Bank</th>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ $d->bankid }}</td>
                                    <td>{{ $d->bank_accountnumber }}</td>
                                    <td>{{ $d->bank_accountname }}</td>
                                    <td style="text-align:right;">{{ number_format($d->saldo,0,'.',',') }}</td>
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