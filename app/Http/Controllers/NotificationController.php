<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Cite;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    private $data = [];
    
    // obtener todas las notificaciones del usuario
    public function getNotifications(Request $request){

		// obtener la autenticacion del usuario y validando
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		// obtener los datos del usuario autenticado
		$user = $jwtAuth->checktoken($token, true);

		// obtener todas las citas
		$notifications = Notification::where(['id_user' => $user->sub, 'destination' => 'Usuario'])->get();

		// mejorar validacion(pendiente)	
		if(count($notifications) <= 0){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'No se encontraron notificaciones';
			return response()->json($this->data, $this->data['code']);
		}	

		// retornar respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = '';
		$this->data['notifications'] = $notifications;
		return response()->json($this->data, $this->data['code']);
    }

    // eliminar notificacion
    public function deleteNotification(Request $request, $id){

		// validar token del usuario
		$token = $request->header('Authorization');
		$jwtAuth = new JwtAuth();
		$checktoken = $jwtAuth->checktoken($token);

		if(!$checktoken){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo los datos del usuario identificado
		$user = $jwtAuth->checktoken($token, true);

		// obteniendo la notificacion por id
		$notificacion = Notification::where(['id' => $id, 'id_user' => $user->sub, 'destination' => 'Usuario'])->first();

		if(empty($notificacion)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'notificacion no encontrada';
			return response()->json($this->data, $this->data['code']);
		}

		// eliminando notificacion
		$notificacion->delete();

		// retornando respuesta	
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'notificacion eliminada correctamente';
		$this->data['notificacion'] = $notificacion;
		return response()->json($this->data, $this->data['code']);
    }

    public function getNotificationsEmployee(Request $request){

		// obtener la autenticacion del usuario y validando
		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);

		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		// obtener todas las citas
		$notifications = Notification::where('destination', 'Empleado')->get();

		// mejorar validacion(pendiente)	
		if(count($notifications) <= 0){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'No se encontraron notificaciones';
			return response()->json($this->data, $this->data['code']);
		}	

		// retornar respuesta
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = '';
		$this->data['notifications'] = $notifications;
		return response()->json($this->data, $this->data['code']);
    }

    public function deleteNotificationEmployee(Request $request, $id){

		// validar token del usuario
		$jwtAuth = new JwtAuth();
		$employee = $jwtAuth->checkEmployee($request);

		if(!$employee){
			$this->data['code'] = 200;
			$this->data['status'] = 'error';
			$this->data['message'] = 'el usuario no se encuentra autenticado';
			return response()->json($this->data, $this->data['code']);
		}

		// obteniendo la notificacion por id
		$notificacion = Notification::where(['id' => $id, 'destination' => 'Empleado'])->first();

		if(empty($notificacion)){
			$this->data['code'] = 200;
			$this->data['status'] = 'not found';
			$this->data['message'] = 'notificacion no encontrada';
			return response()->json($this->data, $this->data['code']);
		}

		// eliminando notificacion
		$notificacion->delete();

		// retornando respuesta	
		$this->data['code'] = 200;
		$this->data['status'] = 'success';
		$this->data['message'] = 'notificacion eliminada correctamente';
		$this->data['notificacion'] = $notificacion;
		return response()->json($this->data, $this->data['code']);
    }
}