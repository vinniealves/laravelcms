@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('title', 'Painel')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h1>Dashboard</h1>
        </div>
        <div class="col-md-6">
            <form action="{{ route('admin') }}" method="GET" class="form-inline float-md-right">
                <select onChange="this.form.submit()"  name="d" class="form-control form-control-sm mr-1">
                    <option {{$dateInterval==30?'selected':''}} value="30">Últimos 30 dias</option>
                    <option {{$dateInterval==60?'selected':''}} value="60">Últimos 60 dias</option>
                    <option {{$dateInterval==90?'selected':''}} value="90">Últimos 90 dias</option>
                    <option {{$dateInterval==120?'selected':''}} value="120">Últimos 120 dias</option>
                    <option {{$dateInterval==150?'selected':''}} value="150">Últimos 150 dias</option>
                    <option {{$dateInterval==180?'selected':''}} value="180">Últimos 180 dias</option>
                    <option {{$dateInterval==210?'selected':''}} value="210">Últimos 210 dias</option>
                    <option {{$dateInterval==240?'selected':''}} value="240">Últimos 240 dias</option>
                    <option {{$dateInterval==270?'selected':''}} value="270">Últimos 270 dias</option>
                    <option {{$dateInterval==300?'selected':''}} value="300">Últimos 300 dias</option>
                    <option {{$dateInterval==330?'selected':''}} value="330">Últimos 330 dias</option>
                    <option {{$dateInterval==360?'selected':''}} value="360">Últimos 360 dias</option>
                </select>
            </form>
        </div>    
    </div>  
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$visitsCount}}</h3>
                    <p>Acessos</p>
                </div>
                <div class="icon">
                    <i class="far fa-fw fa-eye"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$onlineCount}}</h3>
                    <p>Usuários Online</p>
                </div>
                <div class="icon">
                    <i class="far fa-fw fa-heart"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$pageCount}}</h3>
                    <p>Páginas</p>
                </div>
                <div class="icon">
                    <i class="far fa-fw fa-sticky-note"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{$userCount}}</h3>
                    <p>Usuários</p>
                </div>
                <div class="icon">
                    <i class="far fa-fw fa-user"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Páginas mais visitadas</h3>
                </div>
                <div class="card-body">
                    <canvas id="pagePie"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sobre o sistema</h3>
                </div>
                <div class="card-body">
                    ....
                </div>
            </div>
        </div>
    </div>

<script>
    window.onload = function(){
        let ctx = document.getElementById('pagePie').getContext('2d');
        window.pagePie = new Chart(ctx, {
            type:'pie',
            data:{
                datasets:[{
                    data:{{$pageValues}},
                    backgroundColor:'#0000ff'
                }],
                labels:{!! $pageLabels !!}
            },
            options:{
                responsive: true,
                legend: {
                    display: false
                }
            }
        });
    }
</script>

@endsection