<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    
    private $data = [];

    public function getStatusById(Request $request, $id){

		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);

		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = "el usuario no se encuentra autenticado o autorizado";
			return response()->json($this->data, $this->data['code']);
		}

		// realizar busqueda del estado
		$status = Status::where('id', $id)->first();
		if(!is_object($status)){
			$this->data['code'] = 200;
			$this->data['status'] = "not fount";
			$this->data['message'] = "no se encontraro resultados";
			return response()->json($this->data, $this->data['code']);
		}

		$this->data['code'] = 200;
		$this->data['status'] = "success";
		$this->data['status'] = $status;
		return response()->json($this->data,$this->data['code']);
    }
}
