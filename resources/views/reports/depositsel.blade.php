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
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="strdate">Dari Tanggal</label>
                            <input type="date" name="strdate" id="strdate" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="enddate">Sampai Tanggal</label>
                            <input type="date" name="enddate" id="enddate" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" class="btn btn-primary pull-right" id="btn-search">
                            <i class="fa fa-search"></i> Tampilkan Data
                        </button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-js')
<script>
    $(document).keypress(
        function(event){
        if (event.which == '13') {
            event.preventDefault();
        }

    });
    
    $(function(){
        $('#btn-search').on('click', function(){
            var _Bankid = $('#rekening').val();
            var _Strdate = $('#strdate').val();
            var _Enddate = $('#enddate').val();
            if(_Strdate == ''){
                _Strdate = 'null';
                // alert('Tanggal tidak boleh kosong');
            }
            
            if(_Enddate == ''){
                _Enddate = 'null';
            }
            window.location.href = base_url+'/laporan/depositview/'+_Strdate+'/'+_Enddate
            // alert(_Bankid + ' - ' + _Strdate + ' - ' + _Enddate);
        });
    })
</script>
@endsection