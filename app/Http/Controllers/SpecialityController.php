<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecialityController extends Controller
{
    //
    private $data = [];

    public function specialitys(Request $request){

		//listar todas las especialidades	
		$specialitys = Speciality::all();
		if(!is_object($specialitys)){
			$this->data['code'] = 200;
			$this->data['status'] = 'success';
			$this->data['message'] = 'no hay especialidades';
			return response()->json($this->data, $this->data['code']);
		}

		// devolver respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'listado de especialidades';
		$this->data['specialitys'] = $specialitys;
		return response()->json($this->data, $this->data['code']);
    }

    public function speciality(Request $request, $id){

		// obteniendo la especialidad
		$speciality = Speciality::where('id', $id)->first();

		if(!is_object($speciality)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontro resultado';
			return response()->json($this->data, $this->data['code']);
		}

		// devolviendo respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'detalle de especialidad';
		$this->data['speciality'] = $speciality;
		return response()->json($this->data, $this->data['code']);
	}

	public function specialityByParam(Request $request, $param){
		
		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);

		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'success';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		$speciality = Speciality::where('specialitys','like', '%'.$param.'%')->first();
		if(!is_object($speciality)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no hay resultados';
			return response()->json($this->data, $this->data['code']);
		}

		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'especialidad encontrada';
		$this->data['speciality'] = $speciality;
		return response()->json($this->data, $this->data['code']);	
    }

    public function register(Request $request){

		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);
		
		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		// recogemos los datos por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		$validate = Validator::make($params, [
			'specialitys' => 'required'
		]);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = "complete los campos requeridos"; 
			return response()->json($this->data, $this->data['code']);
		}
		
		// validando si se encuentra duplicidada
		$speciality = Speciality::where('specialitys', $params['specialitys'])->first();
		if(is_object($speciality)){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'la especialidad ya se encuentra registrada';
			return response()->json($this->data, $this->data['code']);
		}

		// creando instacia de speciality
		$speciality = new Speciality();
		$speciality->specialitys = $params['specialitys'];

		// guardando especialidad
		$speciality->save();
		
		//retornando respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'especialidad ha sido registrada correctamente';
		$this->data['speciality'] = $speciality;
		return response()->json($this->data, $this->data['code']);
    }

    public function update(Request $request, $id){
	
		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);
		
		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}	

		// obteniendo la especialidad
		$speciality = Speciality::where('id', $id)->first();
		if(!is_object($speciality)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'especialidad no existe';
			return response()->json($this->data, $this->data['code']);
		}
		
		//obteniendo los datos por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		//validando los datos
		$validate = Validator::make($params, ['specialitys' => 'required']);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos requeridos';
			$this->data['errors'] = $validate->errors();
			return response()->json($this->data, $this->data['code']);
		}

		// quitando los parametros que no se actualizaran
		unset($params['id']);
		unset($params['created_at']);

		// actualizando la especialidad
		$speciality_change = Speciality::where('id', $id)->update($params);

		// devolviendo respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'actualizando datos';
		$this->data['speciality'] = $speciality;
		$this->data['change'] = $speciality_change;
		return response()->json($this->data, $this->data['code']);
    }

    public function delete(Request $request, $id){

		// validando token del administrador
		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);

		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		// consiguiendo la specialidad
		$speciality = Speciality::where('id', $id)->first();
		if(!is_object($speciality)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'especialidad no encontrada';
			return response()->json($this->data, $this->data['code']);
		}    
		
		// eliminando especialidad
		$speciality->delete();

		// devolviendo respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'la especialidad ha sido eliminada';
		$this->data['speciality'] = $speciality;
		return response()->json($this->data, $this->data['code']);
    }

}