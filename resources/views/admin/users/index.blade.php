@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <h1>
        Meus usuários
        <a href="{{ route('users.create') }}" class="btn btn-sm bg-gradient-success">Novo Usuário</a>
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
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    <a href="{{ route('users.edit', ['user'=>$user->id]) }}" class="btn btn-sm bg-gradient-info">Editar</a>
                                    
                                    <form class="d-inline" method="POST" action="{{ route('users.destroy', ['user'=>$user->id]) }}" onsubmit="return confirm('Tem certeza que deseja excluir o usuário?')">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-sm bg-gradient-danger" @if($loggedId == $user->id) disabled @endif >Excluir</button>    
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{ $users->links() }} 
    
@endsection