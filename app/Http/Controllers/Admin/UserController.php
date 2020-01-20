<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('can:edit-users');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);
        $loggedId = Auth::id();

        return view('admin.users.index', [
            'users' => $users,
            'loggedId' => $loggedId
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation'
        ]);

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if($validator->fails()){
            return redirect()->route('users.create')
            ->withErrors($validator)
            ->withInput();
        }

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->route('users.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if($user){
            return view('admin.users.edit', [
                'user' => $user
            ]);
        }

        return redirect()->route('users.index');
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        // Seleciona o usuário no banco pelo ID
        $user = User::find($id);

        // Verifique se encontrou o usuário
        if($user){

            // Pega os dados passados pelo formulário de edição
            $data = $request->only([
                'name',
                'email',
                'password',
                'password_confirmation'
            ]);
            
            // Cria a validação dos campos de nome e e-mail
            $validator = Validator::make([
                'name' => $data['name'],
                'email' => $data['email']
            ], [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:100']
            ]);
            
            // Caso validado, edita o nome do usuário
            $user->name = $data['name'];

            // Verifica se o email digitado é diferente do que já está cadastrado
            if($user->email != $data['email']){
                
                // Verifica se o e-mail digitado já não existe no banco de dados
                $hasEmail = User::where('email', $data['email'])->get();
                if(count($hasEmail) === 0){
                    
                    // Edita o e-mail do usuário no banco
                    $user->email = $data['email'];
                } else {
                    $validator->errors()->add('email', __('validation.unique', [
                        'attribute' => 'email',       
                    ]));
                }
            }

            // Verifica se foi digitado alguma senha nova
            if(!empty($data['password'])){

                // Verifica se a senha digitada tem ao menos 8 caracteres
                if(strlen($data['password']) >= 8){
                    
                    // Verifica se a senha é igual a confirmação de senha
                    if($data['password'] === $data['password_confirmation']){

                        // Altera a senha do usuário
                        $user->password = Hash::make($data['password']);
                    } else {
                        $validator->errors()->add('password', __('validation.confirmed', [
                            'attribute' => 'password'            
                        ]));
                    }

                } else {
                    $validator->errors()->add('password', __('validation.min.string', [
                        'attribute' => 'password',
                        'min' => 8                  
                    ]));
                }
            }

            // Verifica se contém algum erro e direciona o usuário com as msg dos mesmos
            if(count($validator->errors()) > 0) {
                return redirect()->route('users.edit',[
                    'user' => $id
                ])->withErrors($validator);
            } 

            // Salva os dados alterados no banco
            $user->save();
        }

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loggedId = Auth::id();

        if($loggedId != $id){
            $user = User::find($id);
            $user->delete();
        } else {
            return redirect()->route('users.index')
            ->with([
                'error' => 'Você não pode se excluir!'
            ]);
        }
        
        return redirect()->route('users.index');

    }
}
