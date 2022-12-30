<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cite;
use App\Models\Notification;
use App\Models\Speciality;
use Symfony\Component\HttpFoundation\Response;

class CiteController extends Controller
{

    private $data = [];
    
    public function addCite(Request $request){

		// recoger el token
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		// recoger los datos enviados por post
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		// validar token y datos
		if(!$checktoken || empty($params)){
		
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'El Usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}
		
		$user = $jwtAuth->checktoken($token, true);

		// validar los datos de params
		$validate = Validator::make($params, [
			'name' => 'required',
			'lastname' => 'required',
			'type_document' => 'required',
			'document' => 'required',
			'phone' => 'required',
			'eps' => 'required',
			'id_speciality' => 'required'
		]);	
		
		if($validate->fails()){

			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'Error: por favor complete los campos';
			$this->data['fails'] = $validate->errors(); 

			return response()->json($this->data, $this->data['code']);
		}

		// guardar los datos 
		$cite = new Cite();
		$cite->name = $params['name'];
		$cite->lastname = $params['lastname'];
		$cite->type_document = $params['type_document'];
		$cite->document = $params['document'];
		$cite->phone = $params['phone'];
		$cite->eps = $params['eps'];
		$cite->id_speciality = $params['id_speciality'];
		$cite->id_status = 5;
		$cite->id_user = $user->sub;
		$cite->save();

		// enviando notificacion
		$notification = new Notification();
		$notification->notification = 'El Sr/Sra '.$params['name']. ' '. $params['lastname'].' ha solicitado cita';
		$notification->id_user = $user->sub;
		$notification->destination = 'Empleado';
		$notification->save();

		// devolver respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'La cita ha sido solicitada correctamente';
		$this->data['cite'] = $cite;
		$this->data['notification'] = $notification;

		return response()->json($this->data, $this->data['code']);	

    }

    // mostrar todas las citas del usuario
    public function showCites(Request $request){
	
		// identificar al usuario
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){

			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se ha autenticado';
			
			return response()->json($this->data, $this->data['code']);
		}

		//obteniendo datos del usuario autenticado
		$user = $jwtAuth->checktoken($token, true);

		// buscar todas las citas del usuario
		$cites = Cite::all()->where('id_user', $user->sub)->where('id_status', '!=', 3);
		if(count($cites) <= 0){
			
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontraron resultados';

			return response()->json($this->data, $this->data['code']);
		}
		
		// devolver respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['cites'] = $cites;

		return response()->json($this->data, $this->data['code']);	
    }
    
    // detallar la cita del usuario por id
    public function showCite(Request $request, $id){
	
		// validar la autenticacion del usuario
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'El usuario no se ha autenticado';
		}

		// obteniendo datos del usuario autenticado
		$user = $jwtAuth->checktoken($token, true);

		// obtenido la cita del usuario y validando que exista
		$cite = Cite::where(['id' => $id, 'id_user' => $user->sub])->first();

		if(empty($cite)){

			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontro resultado';

			return response()->json($this->data, $this->data['code']);
		}

		// retornando respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'cita obtenida';
		$this->data['cite'] = $cite;

		return response()->json($this->data, $this->data['code']);	
    }
    
    // eliminar cita pendiente sin motivo alguno
    public function deleteCite(Request $request, $id){
	
		// validar el token del usuario
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){

			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra autenticado";
			
			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo los datos del usuario autenticado
		$user = $jwtAuth->checktoken($token, true);

		// obteniendo la cita
		$cite = Cite::where(['id' => $id, 'id_user' => $user->sub])->first();	

		if(empty($cite)){
			
			$this->data['code'] = 200;
			$this->data['status'] = "not found";
			$this->data['message'] = "la cita no se encontro";
			$this->data['cite'] = $cite;
			return response()->json($this->data, $this->data['code']);
		}
		
		// validando cita si se encuentra en estado pendiente
		if($cite->id_status != 5){
			
			// eliminar cita en proceso, actualizar o confirmada con motivo alguno (pendiente)
			
			$this->data['code'] = 200;
			$this->data['status'] = 'success';
			$this->data['message'] = 'la cita necesita motivo de cancelacion';

			return response()->json($this->data, $this->data['code']);
		}

		// eliminando cita 
		$cite->delete();

		// devolviendo respuesta	
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'la cita ha sido eliminada existosamente';
		$this->data['cite'] = $cite;	

		return response()->json($this->data, $this->data['code']);

    }

    public function getCitesByParamas(Request $request, $params){

		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra auntenticado";
		}

		$user = $jwtAuth->checktoken($token,  true);

		$cites = Cite::where('id_user',$user->sub)->where('id_status', '!=', 3)->where(function($query) use ($params){
			$query->where('name', 'like', '%'.$params.'%');
			$query->orwhere('lastname', 'like', '%'.$params.'%');
			$query->orwhere('document', 'like', '%'.$params.'%');
		})->get();
		
		if(count($cites) < 1){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = "no se encontraron resultados";
			return response()->json($this->data, $this->data['code']);
		}
		
		$this->data['code'] = 200;
		$this->data['status'] = "success";
		$this->data['message'] = "citas encontradas";
		$this->data['cites'] = $cites;
    	return response()->json($this->data, $this->data['code']);
    }

    public function updateRecommendations(Request $request, $id){
		// recogemos el token y validos que se ha correcto
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		$user = $jwtAuth->checktoken($token, true);

		$cite = Cite::where(['id' => $id, 'id_user' => $user->sub])->first();
		if(!is_object($cite)){
			$this->data['code'] = 200;
			$this->data['status'] = "error"; 
			$this->data['message'] = 'no se encontro resultado de la cita';
			return response()->json($this->data, $this->data['code']);
		}
		
		//recogemos los datos que llegan por put
		$json = $request->input('json', null);
		$params = json_decode($json, true);


		// actualizacion de datos 
		$cite->id_status = 1;
		$cite->recommendations = $params['recommendations'];
		$cite->orden = $params['orden'];
		$cite->authorization = $params['authorization'];
		$cite->save();

		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'la cita ha sido actualizada';
		$this->data['cite'] = $cite;
		return response()->json($this->data, $this->data['code']);
    }

    public function updateStatusToDelete(Request $request, $id){

		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = 'el usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		$user = $jwtAuth->checktoken($token, true);

		$cite = Cite::where(['id' => $id, 'id_user' => $user->sub])->first();
		if(!is_object($cite)){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "no se logro eliminar la cita intentelo de nuevo mas tarde";
			return response()->json($this->data, $this->data['code']);
		}


		$json = $request->input('json', null);
		$params = json_decode($json, true);

		$cite->id_status = 3;
		$cite->motiveCanceld = $params['motiveCanceld'];
		$cite->save();
		
		$notification = new Notification();
		$notification->id_user = $user->sub;
		$notification->notification = "El Sr/Sra ".$cite->name." ".$cite->lastname.' ha solicitado la cancelacion de la cita';
		$notification->destination = 'Empleado';
		$notification->save();

		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'la cita ha sido eliminada correctamente';
		return response()->json($this->data, $this->data['code']);
    }
	
    // functions employess
    public function cites(Request $request){
	
		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);

		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuairo no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		// listando citas pendientes, en proceso o acutalizada
		$cites = Cite::where('id_status', 1)->orwhere('id_status', 3)->orwhere('id_status', 4)->orwhere('id_status',5)->get();

		if(count($cites) <= 0){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontraron resultados';
			return response()->json($this->data, $this->data['code']);
		}

		// retornar respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'listado de citas pendientes';
		$this->data['cites'] = $cites;
		return response()->json($this->data, $this->data['code']);
    }

    public function cite(Request $request, $id){

		// validando token
		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);

		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado o autorizado';

			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo cita 
		$cite = Cite::where('id', $id)->first();
		if(!is_object($cite)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontro resultado';
			return response()->json($this->data, $this->data['code']);
		}
		
		// retornar respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'detalle de cita';
		$this->data['cite'] = $cite;
		return response()->json($this->data, $this->data['code']);
    }

    public function update(Request $request, $id){
	
		/* validando token */ 
		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);
		
		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado 0 autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		/* buscando cita a actualizar */
		$cite = Cite::where('id', $id)->first();
		if(!is_object($cite)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'no se encontro resultado';
			return response()->json($this->data, $this->data['code']);
		}

		/* recibiendo parametros por post */
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		/* validando los datos */
		$validate = Validator::make($params, [
			'name' => 'required',
			'lastname' => 'required',
			'type_document' => 'required',
			'document' => 'required',
			'phone' => 'required',
			'eps' => 'required',
			'id_speciality' => 'required',
			'id_doctors' => 'required',
			'id_status' => 'required'
		]);

		if($validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'complete los campos requeridos';
			return response()->json($this->data, $this->data['code']);
		}

		/* obteniendo datos de la cita */
		$cite = Cite::where('id', $id)->first();
		if(!is_object($cite)){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'no hay resultado';
			return response()->json($this->data, $this->data['code']);
		}
		
		
		$speciality = Speciality::where('id', $params['id_speciality'])->first();

		/* eliminar datos que no se actualizaran */
		unset($params['id']);
		unset($params['name']);
		unset($params['lastname']);
		unset($params['type_document']);
		unset($params['document']);
		unset($params['phone']);
		unset($params['id_speciality']);
		unset($params['id_user']);
		unset($params['created_at']);

		/* actualizar cita */
		$cite_update = Cite::where('id', $id)->update($params);

		/* enviando notification al usuario */
		$notification = new Notification();
		$notification->id_user = $cite->id_user;
		$notification->notification = 'Sr/Sra su cita de '.$speciality->specialitys.' ha sido comfirmada';
		$notification->destination = 'Usuario';
		
		/* guardar notificacion */
		$notification->save();

		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'la cita ha sido confirmada';
		$this->data['cite'] = $cite_update;
		$this->data['notificacion'] = $notification;
		return response()->json($this->data, $this->data['code']);
    }

    public function getSearchByParameters(Request $request, $params){

		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);

		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = 'el usuairo no se encuentra autenticado o autorizado';
			return response()->json($this->data, $this->data['code']);
		}

		$cites = Cite::where('id_status', '!=', 2)->where(function($query) use ($params){
			$query->orwhere('name', 'like', '%'.$params.'%');
			$query->orwhere('lastname', 'like' ,'%'.$params.'%');
			$query->orwhere('document', 'like', '%'.$params.'%');
		})->get();

		if(count($cites) < 1){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = "no se encontraron resultados";
			return response()->json($this->data, $this->data['code']);
		}

		$this->data['code'] = 200;
		$this->data['status'] = "success";
		$this->data['message'] = 'citas encontradas';
		$this->data['cites'] = $cites;
		return response()->json($this->data, $this->data['code']);
    }

    public function requiredFiles(Request $request, $id){

		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);
		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra autenticado o autorizado";
			return response()->json($this->data, $this->data['code']);
		}

		// recibir los parametros enviados
		$json = $request->input('json', null);
		$params = json_decode($json, true);

		// buscar la cita referente
		$cite = Cite::where(['id' => $id, 'id_user' => $params['id_user']])->first();
		if(!is_object($cite)){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "no se encotro resultado";
			return response()->json($this->data, $this->data['code']);
		}

		$cite->required_files = $params['required_files'];
		$cite->id_status = 4;
		$cite->recommendations = $params['recommendations'];	
		$cite->save();

		$notificacion = new Notification();
		$notificacion->id_user = $cite->id_user;
		$notificacion->notification = "Sr/Sra ".$cite->name." ".$cite->lastname." se solicita adjuntar los archivos solicitados: ".$cite->required_files;
		$notificacion->destination = "Usuario";
		$notificacion->save();

		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'se realizo la solicitud de archivos ';
		return response()->json($this->data, $this->data['code']);
    }   

    public function uploadFile(Request $request){

		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = "el usuairo no se encuentra autenticado";
			return response()->json($this->data, $this->data['code']);
		}

		// recoger el archivo de la peticion
		$file = $request->file('file0');	

		// validar pdf
		$validate = Validator::make($request->all(),[
			'file0' => 'required|file|mimes:pdf'
		]);

		if(!$file || $validate->fails()){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = "seleccione el archivo solicitado";
			return response()->json($this->data, $this->data['code']);
		}

		$file_name = time().$file->getClientOriginalName();
		// guardar pdf
		\Storage::disk('files')->put($file_name, \File::get($file));	

		// devolver los datos
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'el archibo se envio correctamente';
		$this->data['file'] = $file_name;

		return response()->json($this->data, $this->data['code']);
    } 
    
    public function getFile(Request $request, $filename){
		
		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);
		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra auntenticado o autorizado";
			return response()->json($this->data, $this->data['code']);
		}
		
		// comprobar si existe el fichero
		$isset = \Storage::disk('files')->exists($filename);

		if(!$isset){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'la archivo no existe';
			return response()->json($this->data, $this->data['code']);
		}

		// delvolver url del pdf
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'obteniendo el archivo';
		$this->data['pdf'] = "http://api-rest-central.com/pdf/".$filename;
		return response()->json($this->data, $this->data['code']);; 
    }

    public function delete(Request $request, $id){
		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);
		if(!$employee){
			$this->data['code'] = '200';
			$this->data['status'] = "error";
			$this->data['message'] = "el usuario no se encuentra auntenticado o autorizado";
			return response()->json($this->data, $this->data['code']);
		}

		$cite = Cite::where(['id' => $id, 'id_status' => 3])->first();
		if(!is_object($cite)){
			$this->data['code'] = 200;
			$this->data['status'] = "error";
			$this->data['message'] = 'no se encotro resultado';
			return response()->json($this->data, $this->data['code']);
		}

		$cite->delete();

		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'la cita sa sido eliminada';
		return response()->json($this->data, $this->data['code']);
    }
}