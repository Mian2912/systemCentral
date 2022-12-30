<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FamilyController extends Controller
{
    
    private $data = [];

    
    public function register(Request $request){
	
		// validar token
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra autenticado";
			return response()->json($this->data, $this->data['code']);
		}	

		// datos del usuairo recuperados
		$user = $jwtAuth->checktoken($token, true);
		
		// recoger y validar los datos que llegan por post 
		$json = $request->input("json", null);
		$params = json_decode($json, true);

		$validate = Validator::make($params, [
			'name' => 'required',
			'lastname' => 'required',
			'type_document' => 'required',
			'document' => 'required',
			'phone' => 'required'
		]);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message']= "complete los campos del formulario";
			return response()->json($this->data, $this->data['code']);
		}

		// validando si ya existe el familiar
		$family = Family::where('document', $params['document'])->first();
		if(is_object($family)){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario ya se encuentra registrado";
			return response()->json($this->data, $this->data['code']);
		}
		
		// registrando los datos del familiar
		$family = new Family();
		$family->name = $params['name'];
		$family->lastname = $params['lastname'];
		$family->type_document = $params['type_document'];
		$family->document = $params['document'];
		$family->phone = $params['phone'];
		$family->id_user = $user->sub;
		$family->id_rol = 4;

		// guardando los datos del familiar
		$family->save();

		// retornar respuesta 
		$this->data['code'] = 200;
		$this->data['status'] = "success";
		$this->data['message'] = "Datos del familiar registrados";
		$this->data['family'] = $family;
		return response()->json($this->data, $this->data['code']);
    }

    public function familys(Request $request){

		$token = $request->header("Authorization");
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra autenticado";
			return response()->json($this->data, $this->data['code']);
		}
		
		$user = $jwtAuth->checktoken($token, true);

		$familys = Family::where(['id_user' => $user->sub, 'id_rol' => 4])->get();	
		if(count($familys) < 1){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = "no se encontraron resultados";
			return response()->json($this->data, $this->data['code']);
		}

		$this->data['code'] = 200;
		$this->data['success'] = "success";
		$this->data['message'] = "listado encontrado";
		$this->data['family'] = $familys;
		return response()->json($this->data, $this->data['code']);
    }

    public function family(Request $request, $id){

		// validando el token del usuario
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);
		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra autenticado";
			return response()->json($this->data, $this->data['code']);
		}

		$user = $jwtAuth->checktoken($token, true);

		// buscando datos del familar
		$family = Family::where(['id' => $id, 'id_user' => $user->sub])->first();
		if(!is_object($family)){
			$this->data['code'] = 200;
			$this->data['status'] = "not found";
			$this->data['message'] = "no se encontraron resultados";
			return response()->json($this->data, $this->data['code']);
		}

		$this->data['code'] = 200;
		$this->data['status'] = "success";
		$this->data['message'] = "familiar encontrado";
		$this->data['family'] = $family;
		return response()->json($this->data, $this->data['code']);
    }

    public function update(Request $request, $id){

		$token = $request->header("Authorization");
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);
		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra autenticado";
			return response()->json($this->data, $this->data['code']);
		}

		// recogemos datos del usuario
		$user = $jwtAuth->checktoken($token, true);

		// busqueda de datos del familiar
		$family = Family::where(['id' => $id, 'id_user' => $user->sub])->first();
		if(!is_object($family)){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "no se encontro resultado";
			return response()->json($this->data, $this->data['code']);
		}
		
		// recoger los datos y validarlos
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		$validate = Validator::make($params,[
			'name' => 'required',
			'lastname' => 'required',
			'type_document' => 'required',
			'document' => 'required',
			'phone' => 'required'
		]);
		
		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos requeridos';
			return response()->json($this->data, $this->data['code']);
		}

		// eliminar datos de params que no se actualizaran
		unset($params['id']);
		unset($params['type_document']);
		unset($params['document']);
		unset($params['user_id']);
		unset($params['id_role']);
		unset($params['created_at']);

		// actualizar datos
		$familyChange = Family::where(['id' => $id, 'id_user' => $user->sub])->update($params);

		$this->data['code'] = 200;
		$this->data['status'] = "success";
		$this->data['message'] = "los datos del familiar fueron actualizados";
		$this->data['family'] = $family;
		$this->data['change'] = $familyChange;
		return response()->json($this->data, $this->data['code']);
    }
}