<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Peticion;
use App\User;
use App\InPeticion;
use App\Formulario;
use App\Categoria;
use Illuminate\Support\Facades\DB;

class PeticionsController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
	    
    	$user = User::find(\Auth::id());
	    $repuestos = $user->repuestos;
	    $cantidads = $user->inPeticiones;
	    //dd($repuestos);
	    if($repuestos->isNotEmpty()){
	    	$repuesto = $repuestos->first();
	    	$modelos = DB::table('repuestos')
			->join('categoria_repuestos', 'categoria_repuestos.repuesto_id' , '=', 'repuestos.id')
			->join('categorias', 'categorias.id', '=', 'categoria_repuestos.categoria_id')
			->select('categorias.*')
			->where('categoria_repuestos.repuesto_id',$repuesto->id )->get();
			//dd($modelos[0]->id);
			$modelo = Categoria::find($modelos->first()->id);
	    	$maquinas = $modelo->maquinas;
	    } else{
	    	$modelo = new Categoria;
	    	$maquinas= $modelo->maquinas;
	    }
	    $formulario = new Formulario;
	    return view("peticions.index", ['maquinas' => $maquinas, 'modelo' => $modelo, "repuestos"=> $repuestos, 'cantidads' => $cantidads, 'formulario' => $formulario]);
	}
}
