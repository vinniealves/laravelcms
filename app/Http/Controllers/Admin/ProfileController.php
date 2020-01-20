<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $loggedId = intval(Auth::id());
        
        $user = User::find($loggedId);

        if($user) {
            return view('admin.profile.index',[
                'user' => $user
            ]);
        }

        return redirect()->route('admin');
        
    }

    public function save(Request $request)
    {   
        //Pega o id do usuário logado
        $loggedId = intval(Auth::id());
        
        // Seleciona o usuário no banco pelo ID
        $user = User::find($loggedId);

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
                return redirect()->route('profile',[
                    'user' => $loggedId
                ])->withErrors($validator);
            } 

            // Salva os dados alterados no banco
            $user->save();

            return redirect()->route('profile')
                ->with('warning', 'Informações alteradas com sucesso'
            );

        }

        return redirect()->route('profile');
    }
}
