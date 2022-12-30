<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Helpers\JwtAuth;

class UserController extends Controller {
    
    private $data = []; 

    // metodo de registro de usuarios
    public function register(Request $request){

		
		$json = $request->input('json', null); // recoger los datos del usuario por post 
		$params = json_decode($json, true); // decodificar el json

		if(empty($params)){
			$this->data['code'] = 400;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos del formulario';
			$this->data['params'] = $params;
			return response()->json($this->data, $this->data['code']);
		}
	
		$params = array_map('trim', $params); // limpiar datos
		
		// validar datos
		$validate = Validator::make($params, [
			'name' => 'required',
			'lastname' => 'required',
			'type_document' => 'required',
			'document' => 'required|unique:users',
			'phone' => 'required',
			'email' => 'required|email|unique:users',
			'password' => 'required' 
		]);

		// validando la existencia del usuario
		$user_email = User::where('email', $params['email'])->first();
		$user_document = User::where('document', $params['document'])->first();
		if(!empty($user_email) || !empty($user_document)){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el Usuario ya se encuentra registrado';
			return response()->json($this->data, $this->data['code']);
		}

		// validando que no hayan errors
		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'Complete los campos';
			$this->data['fails'] = $validate->errors();
			return response()->json($this->data, $this->data['code']);
		}

		$pwd = hash('sha256', $params['password']); // cifrar contraseña

		// crear usuario
		$user = new User();	
		$user->name = $params['name'];
		$user->lastname = $params['lastname'];
		$user->type_document = $params['type_document'];
		$user->document = $params['document'];
		$user->phone = $params['phone'];
		$user->email = $params['email'];
		$user->password = $pwd;
		$user->id_rol = 3;
		$user->save(); // guardar usuario

		//devolviendo respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'Bienvenido/a, se realizo el registro correctamente';
		$this->data['user'] = $user;	
		return response()->json($this->data, $this->data['code']);
    }

    // funcion de login
    public function login(Request $request){

		$JwtAuth = new JwtAuth();

		// recibir los datos por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		// validar datos
		$validate = Validator::make($params, [
			'email' => 'required|email',
			'password' => 'required|required'
		]);

		if($validate->fails()){
			$this->data['code'] = 400;
			$this->data['status'] = 'error';
			$this->data['message'] = 'Complete los campos';
			return response()->json($this->data, $this->data['code']);
		}

		// cifrar la contraseña
		$pwd = hash('sha256', $params['password']);

		

		// devolver token o datos
		$singup = $JwtAuth->singup($params['email'], $pwd);

		if(!empty($params['gettoken'])){
			$singup = $JwtAuth->singup($params['email'], $pwd, true);
		}
		// devolver token o datos
		return response()->json($singup, 200);
    }

    // function de update_user
    public function update(Request $request){

		// recibir el token del usuario y validar el token que sea verdadero
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		// recoger los datos enviados por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);	

		// validar el token y el params
		if(!$checktoken || empty($params)){
			$this->data['code'] = 400;
			$this->data['status'] = 'error';
			$this->data['message'] = 'El usuairo no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		// sacar datos del usuairo indentificado
		$user = $jwtAuth->checktoken($token, true);
		
		// validar datos de params
		$validate = Validator::make($params,[
			'name' => 'required',
			'lastname' => 'required',
			'type_document' => 'required',
			'document' => 'required',
			'phone' => 'required',
			'email' => 'required'
		]);

		if($validate->fails()){
			$this->data['code'] = 400;
			$this->data['status'] = 'error';
			$this->data['message'] = 'Complete los campos';
			$this->data['errors'] = $validate->errors();
			return response()->json($this->data, $this->data['code']);
		}

		// eliminando los campos que no quiero actualizar
		unset($params['id']);
		unset($params['type_document']);
		unset($params['document']);
		unset($params['email']);
		unset($params['password']);
		unset($params['created_at']);
		unset($params['remember_token']);

		// actualizar los datos del usuario en la bd
		$user_update = User::where('id', $user->sub)->update($params);
		$user = User::where(['id' => $user->sub, 'document'=> $user->document])->first();

		// devolver respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'la actualizacion de los datos ha sido correcta';
		$this->data['user'] = $user;
		$this->data['change'] = $user;
		return response()->json($this->data, $this->data['code']);
    }

    public function changePassword(Request $request){
	
		//validamos el token
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		//obtenemos los datos del usuario
		$user = $jwtAuth->checktoken($token, true);
		
		//obtenemos los datos que llegan por put
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		$validate = Validator::make($params, [
			'email' => 'required|email',
			'password' => 'required',
			'newpass' => 'required',
			'equalpass' => 'required'
		]);

		$pwd = hash('sha256', $params['password']);
		$newpwd = hash('sha256', $params['equalpass']);

		// conseguir los datos del usuario
		$user = User::where(['email' => $user->email, 'password' => $pwd ])->first();
		if(!is_object($user)){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el correo y/o la contraseña son incorrectos';
			return response()->json($this->data, $this->data['code']);
		}

		$user->password = $newpwd;
		$user->update();
		
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['user'] = $user;
		return response()->json($this->data, $this->data['code']);
    } 
}