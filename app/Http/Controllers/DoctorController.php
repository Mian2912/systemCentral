<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{

    private $data = [];

    public function doctors(Request $request){

		// consiguiendo listado de doctores
		$doctors = Doctor::all();
		if(count($doctors) <= 0){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontraron resultados';
			return response()->json($this->data, $this->data['code']);
		}

		// devolver respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'listado de doctores';	
		$this->data['doctors'] = $doctors;

		return response()->json($this->data, $this->data['code']);
    }

    public function doctor(Request $request, $id){
    
		// obteniendo datos del doctor
		$doctor = Doctor::where('id', $id)->first();
		if(!is_object($doctor)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontro resultado';
			return response()->json($this->data, $this->data['code']);
		}

		// devolviendo respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'detalle del doctor';
		$this->data['doctor'] = $doctor;

		return response()->json($this->data, $this->data['code']);
    }

    public function register(Request $request){
	
		// validando token
		$jwtAuth = new JwtAuth();
		$admin = $jwtAuth->checkAdmin($request);

		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		// recogiendo datos por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		$validate = Validator::make($params, [
			'name' => 'required',
			'lastname' => 'required',
			'phone' => 'required',
			'id_speciality' => 'required'
		]);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos requeridos';
			return response()->json($this->data, $this->data['code']);
		}

		// validando duplicidad
		$doctor = Doctor::where('document', $params['document'])->first();
		if(is_object($doctor)){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el doctor se encuentra registrado';
			return response()->json($this->data, $this->data['code']);
		}

		// creando instacia de medico
		$doctor = new Doctor();
		$doctor->name = $params['name'];
		$doctor->lastname = $params['lastname'];
		$doctor->type_document = $params['type_document'];
		$doctor->document = $params['document'];
		$doctor->phone = $params['phone'];
		$doctor->id_speciality = $params['id_speciality'];

		// guardando doctor
		$doctor->save();

		// devolviendo respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'medico ha sido registrado correctamente';
		$this->data['doctor'] = $doctor;

		return response()->json($this->data, $this->data['code']);
    }

    public function update(Request $request, $id){

		// validando token
		$jwtAuth = new JwtAuth();
		$admin = $jwtAuth->checkAdmin($request);

		if(!$admin) {
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuairo no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}
		
		// recogiendo datos por post  y validando
		$json = $request->input('json', null);
		$params = json_decode($json, true);
		
		$validate = Validator::make($params, [
			'name' => 'required',
			'lastname' => 'required',
			'type_document' => 'required',
			'document' => 'required',
			'phone' => 'required',
			'id_speciality' => 'required'
		]);


		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos requeridos';
			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo datos del medico
		$doctor = Doctor::where('id', $id)->first();
		if(!is_object($doctor)){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el medico no existe';
			return response()->json($this->data, $this->data['code']); 
		}

		// eliminando lo que no se acturizara
		unset($params['id']);
		unset($params['created_at']);

		// actualizando medico
		$doctor_change = Doctor::where('id', $id)->update($params);

		// retornando respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'datos del medico actualizados';
		$this->data['doctor'] = $doctor;
		$this->data['change'] = $doctor_change;

		return response()->json($this->data, $this->data['code']);
    }

    public function delete(Request $request, $id){

		// validar token
		$jwtAuth = new JwtAuth();
		$admin = $jwtAuth->checkAdmin($request);

		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}
		
		// obteniendo datos del medico
		$doctor = Doctor::where('id', $id)->first();
		if(!is_object($doctor)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontro resultado'; 
			return response()->json($this->data, $this->data['code']); 
		}

		// eliminado medico
		$doctor->delete();

		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'medico eliminado correctamente';
		$this->data['doctor'] = $doctor;
		return response()->json($this->data, $this->data['code']);
    }

    public function getDoctorByParams(Request $request, $params){

		// buscar medico por nombre
		$doctor = Doctor::where('name', 'like', '%'.$params.'%')->first();
		if(!is_object($doctor)){
			$this->data['code'] = 200;
			$this->data['status'] = "not found";
			$this->data['message'] = "no se encontro resultado";
			return response()->json($this->data, $this->data['code']);
		}

		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['doctor'] = $doctor;
		return response()->json($this->data, $this->data['code']);

    }

    public function getDoctorBySpeciality(Request $request, $id){
	
		// busqueda de medicos por id de specialidad	
		$doctors = Doctor::where('id_speciality', $id)->get();
		if(!is_object($doctors)){
			$this->data['code'] = 200;
			$this->data['status'] = "not found";
			$this->data['message'] = "no se encontraron resultados";
			return response()->json($this->data, $this->data['code']);
		}

		$this->data['code'] = 200;
		$this->data['status'] = "success";
		$this->data['doctors'] = $doctors;
		return response()->json($this->data, $this->data['code']);
    }

}