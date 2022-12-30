<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    
    private $data = [];

    // loguin del empleado
    public function login(Request $request){

		$jwtAuth = new JwtAuth();

		// recoger los datos que llegan por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);
		
			// validar los datos
		$validate = Validator::make($params, [
			'email' => 'required|email',
			'password' => 'required'
		]);

		if($validate->fails()){

			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'Complete los campos requeridos';

			return response()->json($this->data, $this->data['code']);
		}

		// cifrar la contraseña
		$pwd = hash('sha256', $params['password']);

		// devolver token o datos
		$singup = $jwtAuth->singupEmployee($params['email'], $pwd);
		if($params['gettoken'] != "null"){
			$singup = $jwtAuth->singupEmployee($params['email'], $pwd, true);
		}

		// devolver token o datos
		$this->data['code'] = 200;
		$this->data['singup'] = $singup;
		
		return response()->json($this->data, $this->data['code']);
    }
    
    // registrar empleado
    public function register(Request $request){

		// validando token
		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);
		
		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}
		
		// recoger datos que llegan por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		// validar los datos
		$validate = Validator($params, [
			'name' => 'required',
			'lastname' =>'required',
			'type_document' => 'required',
			'document' => 'required',
			'phone' => 'required',
			'email' => 'required|email',
			'password' => 'required'
		]);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos requeridos';
			return response()->json($this->data, $this->data['code']);
		}

		// validar si ha empleado duplicado
		$employee = Employee::where([
			'document' => $params['document'],
			'email' => $params['email']
		])->first();

		if(is_object($employee)){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'El empleado se encuentra registrado';
			return response()->json($this->data, $this->data['code']);
		}	

		// cifrar contraseña
		$pwd = hash('sha256', $params['password']);
		
		// registrando datos del empleado	
		$employee = new Employee();
		$employee->name = $params['name'];
		$employee->lastname = $params['lastname'];
		$employee->type_document = $params['type_document'];
		$employee->document = $params['document'];
		$employee->phone = $params['phone'];
		$employee->email = $params['email'];
		$employee->password = $pwd;
		$employee->id_rol = 2;
		
		// guardando el empleado
		$employee->save();

		// devolver respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'El empleado ha sido registrado con exito';
		$this->data['admin'] = $employee;

		return response()->json($this->data, $this->data['code']);
    }

    // listar todos los empleados
    public function employees(Request $request){

		// validando token
		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);
		
		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		// listar todos los empleados
		$employees = Employee::whereIn("id_rol", [2])->get();

		// devolviendo respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'usuairos';
		$this->data['employess'] = $employees;
		return response()->json($this->data, $this->data['code']);
    }

    // detallar un empleado
    public function employee(Request $request, $id){

		// validando token
		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);
		
		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo empleado por id
		$employee = Employee::where(['id' => $id, 'id_rol' => 2])->first();
		
		if(!is_object($employee)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'Empleado no existe';
			return response()->json($this->data, $this->data['code']);
		}

		// devolver respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'Listando empleado';
		$this->data['employee'] = $employee;
		return response()->json($this->data, $this->data['code']);
    }

    // actualizacion de datos del empleado
    public function update(Request $request, $id){

		// validando token
		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);
		
		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo datos del empleado
		$employee = Employee::where(['id' => $id, 'id_rol' => 2])->first();
		if(!is_object($employee)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'El empleado no existe';
			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo los datos por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		// validando los datos
		$validate = Validator::make($params, [
			'name' => 'required',
			'lastname' => 'required',
			'phone' => 'required',
			'email' => 'required' 
		]);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos requeridos';
			return response()->json($this->data, $this->data['code']);
		}

		// quitando los datos que no se actualizaran
		unset($params['id']);
		unset($params['type_document']);
		unset($params['document']);
		unset($params['email']);
		unset($params['password']);
		unset($params['id_rol']);

		// actualizando empleado
		$employee_update = Employee::where(['id' => $id, 'id_rol' => $employee->id_rol])->update($params);

		// retornando respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'datos del empleado actualizados';
		$this->data['employee'] = $employee;
		$this->data['change'] = $employee_update;
		return response()->json($this->data, $this->data['code']);
    }

    public function delete(Request $request, $id){
    
		// validando token
		$JwtAuth = new JwtAuth();
		$admin = $JwtAuth->checkAdmin($request);
		
		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo datos del empleado
		$employee = Employee::where(['id' => $id, 'id_rol' => 2])->first();

		if (!is_object($employee)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'El usuario no existe';
			return response()->json($this->data, $this->data['code']);
		}

		// eliminando el empleado
		$employee->delete();

		$this->data['code']  = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'el empleado ha sido eliminando';
		$this->data['employee'] = $employee;
		return response()->json($this->data, $this->data['code']);
    }

    public function updateAdmin(Request $request, $id){
	
		//validar el token del admin
		$jwtAuth = new JwtAuth();
		$admin = $jwtAuth->checkAdmin($request);
		
		if(!$admin){
			$this->data['code'] = 200;
			$this->data['status'] = "success";
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}
		
		// validar la existencia del admin
		$admin = Employee::where(['id' => $id, 'id_rol' => $admin->rol])->first();
		if(!is_object($admin)){
			$this->data['code'] = 200;
			$this->data['status'] = "Not Found";
			$this->data['message'] = "no se encontro resultados";
			return response()->json($this->data, $this->data['code']);
		}

		// recoger los datos que envia por put
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		// validar los datos de params
		$validate = Validator::make($params,[
			'name' => 'required',
			'lastname' => 'required',
			'phone' => 'required',
		]);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos del formulario';
			return response()->json($this->data, $this->data['code']);
		}

		// eliminar datos de params que no se actualizaran
		unset($params['id']);
		unset($params['type_document']);
		unset($params['document']);
		unset($params['email']);
		unset($params['password']);
		unset($params['id_rol']);
		unset($params['created_at']);

		// realizar la actualizacion de datos
		$adminChange = Employee::where(['id' => $id, 'id_rol' => $admin->id_rol])->update($params);

		// retornar respuesta
		$this->data['code'] = 200;
		$this->data['message'] = "datos del administrador actualizados";
		$this->data['admin'] = $admin;
		$this->data['update'] = $adminChange;
		return response()->json($this->data, $this->data['code']);
    }
    
    public function updateEmployee(Request $request, $id){
	
		//validar el token del admin
		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);
		
		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = "success";
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}
		
		// validar la existencia del admin
		$employee = Employee::where(['id' => $id, 'id_rol' => $employee->rol])->first();
		if(!is_object($employee)){
			$this->data['code'] = 200;
			$this->data['status'] = "Not Found";
			$this->data['message'] = "no se encontro resultados";
			return response()->json($this->data, $this->data['code']);
		}

		// recoger los datos que envia por put
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		// validar los datos de params
		$validate = Validator::make($params,[
			'name' => 'required',
			'lastname' => 'required',
			'phone' => 'required',
		]);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos del formulario';
			return response()->json($this->data, $this->data['code']);
		}

		// eliminar datos de params que no se actualizaran
		unset($params['id']);
		unset($params['type_document']);
		unset($params['document']);
		unset($params['email']);
		unset($params['password']);
		unset($params['id_rol']);
		unset($params['created_at']);

		// realizar la actualizacion de datos
		$employeeChange = Employee::where(['id' => $id, 'id_rol' => $employee->id_rol])->update($params);

		// retornar respuesta
		$this->data['code'] = 200;
		$this->data['message'] = "datos del perfil actualizados";
		$this->data['admin'] = $employee;
		$this->data['update'] = $employeeChange;
		return response()->json($this->data, $this->data['code']);
    }


}
