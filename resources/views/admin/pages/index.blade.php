@extends('adminlte::page')

@section('title', 'Páginas')

@section('content_header')
    <h1>
        Minhas Páginas
        <a href="{{ route('pages.create') }}" class="btn btn-sm bg-gradient-success">Nova Página</a>
    </h1>
@endsection

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i>Ocorreu um erro:</h5>
            <ul>
                <li>{{session('error')}}</li>
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Título</th>
                            <th width="200">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr>
                                <td>{{$page->id}}</td>
                                <td>{{$page->title}}</td>
                                <td>
                                    <a href="" target="_blank" class="btn btn-sm bg-gradient-success">Ver</a>

                                    <a href="{{ route('pages.edit', ['page'=>$page->id]) }}" class="btn btn-sm bg-gradient-info">Editar</a>
                                    
                                    <form class="d-inline" method="POST" action="{{ route('pages.destroy', ['page'=>$page->id]) }}" onsubmit="return confirm('Tem certeza que deseja excluir o usuário?')">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-sm bg-gradient-danger">Excluir</button>    
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{ $pages->links() }} 
    
@endsection