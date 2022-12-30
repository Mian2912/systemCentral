<?php
    
namespace App\Helpers;

use App\Models\Employee;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use DomainException;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use UnexpectedValueException;

class JwtAuth{
    
    private $key;
    private $data = [];

    public function __construct() {
		$this->key = "esto_es_una_clave_super_secreta-9988776655";
    }

    public function singup($email, $password, $gettoken=null){

		// buscar si existe el usuairo con sus credenciales
		$user = User::where([
			'email'=> $email,
			'password' => $password
		])->first();
			
		// comprobar si son correctas (objeto)
		$singup = (is_object($user)) ? true : false;
		
		if(!$singup){
			$this->data['code'] = 400;
			$this->data['status'] = 'error';
			$this->data['message'] = 'Correo y/o contraseña son incorrectos, intentelo de nuevo';
			return response()->json($this->data, $this->data['code']);
		}

		// generar el token con los datos del usuairo identificado
		$token = Array(
			'sub' => $user->id,
			'email' => $user->email,
			'name' => $user->name,
			'lastname' => $user->lastname,
			'type_document' => $user->type_document,
			'document' => $user->document,
			'phone' => $user->phone,
			'rol' => $user->id_rol, 
			'iat' => time(),
			'exp' => time() + (7*24*60*60) 
		);

		$jwt = JWT::encode($token, $this->key, 'HS256');
		$decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));

		$this->data = (is_null($gettoken)) ? $jwt: $decoded;

		// devolver los datos decodificados o el token en funcion de un parametro
		return $this->data;
    }
    
    // singup del empleado
    public function singupEmployee($email, $password, $gettoken=null){
	
		// buscar si existe el usuairo con sus credenciales
		$employee = Employee::where([
			'email'=> $email,
			'password' => $password
		])->first();
			
		// comprobar si son correctas (objeto)
		$singup = (is_object($employee)) ? true : false;
		
		if(!$singup){
			$this->data['code'] = 400;
			$this->data['status'] = 'error';
			$this->data['message'] = 'Correo y/o contraseña son incorrectos, intentelo de nuevo';
			return response()->json($this->data, $this->data['code']);
		}	

		// generar el token con los datos del usuairo identificado
		$token = Array(
			'sub' => $employee->id,
			'email' => $employee->email,
			'name' => $employee->name,
			'lastname' => $employee->lastname,
			'type_document' => $employee->type_document,
			'document' => $employee->document,
			'phone' => $employee->phone,
			'rol' => $employee->id_rol,
			'iat' => time(),
			'exp' => time() + (7*24*60*60) 
		);

		$jwt = JWT::encode($token, $this->key, 'HS256');
		$decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));

		$this->data = ($gettoken == false) ? $jwt : $decoded;
		
		// devolver los datos decodificados o el token en funcion de un parametro
		return $this->data;
    }

    // validando token del usuario
    public function checktoken($jwt, $getIdentity=false){
	
		$auth = false;

		try{
			$jwt = str_replace('"','', $jwt);
			$decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
		}catch(UnexpectedValueException $e){
			$auth = false;
		}catch(DomainException $e){
			$auth = false;
		}

		$auth = (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) ? true : false; 

		if($getIdentity) return $decoded;

		return $auth;
    }
    
    public function checkAdmin($request){

		// validando token
		$token = $request->header('Authorization');
		$checktoken = $this->checktoken($token);

		if(!$checktoken) return false; 
		
		// obteniendo datos del admin
		$admin = $this->checktoken($token, true);

		if($admin->rol != 1) return false ;

		return $admin;
    }

    public function checkEmployee($request){

		// validando token
		$token = $request->header('Authorization');
		$checktoken = $this->checktoken($token);
		if(!$checktoken) return false; 
		
		// obteniendo datos del admin
		$employee = $this->checktoken($token, true);
		if($employee->rol != 2) return false ;

		return $employee;
    }

}
