<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Peticion;
use App\User;
use App\InPeticion;
use App\RepuestosFormulario;
use App\Formulario;
use Carbon\Carbon;


class FormulariosController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
	    //dd($request);
	    $user = User::find(\Auth::id());
	    $repuestos = $user->repuestos()->get();

	    $in_peticions = InPeticion::where([['user_id' , \Auth::id()], ['status','Incompleto']])->get();
	    foreach ($in_peticions as $in_peticion) {
	    	# code...
	    	$in_peticion->status = 'Enviado';
	    	$in_peticion->save();
	    }
	   
	    $formulario = new Formulario;
	    $formulario->user_id = $user->id;
	    $formulario->cliente = $request->cliente;
	    $formulario->ciudad = $request->ciudad;
	    $formulario->telefono = $request->telefono;
	    $formulario->fecha = $request->fecha;
	    $formulario->ciclos = 14;
	    $formulario->hora_entrada = $request->hora_entrada;
	    $formulario->hora_salida = $request->hora_salida;
	    $formulario->categoria_id = $request->modelo_id;
	    $formulario->serie_id = $request->serie;
	    $formulario->motivo = $request->motivo;
	    $formulario->accion = $request->accion;
	    $formulario->observacion = $request->observacion;
	    $formulario->status = "Incompleto";

	    if($formulario->save()){
	    	foreach ($repuestos as $repuesto) {
	    		# code...
	    		$form_repuesto = new RepuestosFormulario;
	    		$form_repuesto->repuesto_id = $repuesto->id;
	    		$form_repuesto->formulario_id = $formulario->id;

	    		$form_repuesto->save();

	    	}
	    	foreach ($in_peticions as $peticion) {
	    		# code...
	    		$peticion->formulario_id = $formulario->id;
	    		$peticion->save();

	    	}
	    	flash('Formulario enviado con exito')->success();
	    	return \Redirect::route('peticions');
	    } else{
	    	flash('Error al enviar el formulario')->error();
	    	return \Redirect::route('peticions');
	    }
	    

    	
	    
	}
}
