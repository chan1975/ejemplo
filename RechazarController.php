<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InPeticion;
use App\Peticion;
use App\Repuesto;
use App\Formulario;
use Illuminate\Database\Eloquent\Collection;
use Laracasts\Flash\FlashServiceProvider;

class RechazarController extends Controller
{
    //
    public function rechazar($formulario_id){
        $formulario = Formulario::find($formulario_id);
    	$inPeticions = InPeticion::where('formulario_id', $formulario->id)->get();
    	foreach ($inPeticions as $in_peticion) {
    		# code...
    		$in_peticion->status = "Rechazado";
            $in_peticion->save();
    	}
    	
    	return redirect('/orders/'. $formulario_id, ['inPeticions' => $inPeticions]);
    }
}
