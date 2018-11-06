<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InPeticion;
use App\Peticion;
use App\Repuesto;
use App\formulario;
use Illuminate\Database\Eloquent\Collection;
use Laracasts\Flash\FlashServiceProvider;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('admin');
        
    }
    public function index($formulario_id)
    {
        //
        $formulario = formulario::find($formulario_id);
        
        $inPeticions = InPeticion::where('formulario_id',$formulario_id)->get();
        
        
        foreach ($inPeticions as $inPeticion) {
            $inPeticion->repuesto;
            $inPeticion->user;
            
        }
        
        
        return view("orders.index", ["inPeticions"=> $inPeticions, 'formulario_id' => $formulario_id]);
    }

    
   
    public function edit($id)
    {
       
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formulario = formulario::find($request->formulario_id);
        $in_peticions = InPeticion::where('formulario_id',$formulario->id)->get();
        $repuesto_name;
        foreach ($in_peticions as $in_peticion) {
            # code...
            $repuesto = $in_peticion->repuesto;

            if($repuesto->stock >= $in_peticion->cantidad){
                $flag = true;
            }else {
                $flag = false;
                
            }
            if(!$flag){
                break;
            }
            
        }
        //dd($repuesto);
        if(!$flag){
            flash('No hoy suficiente stock para el repuesto '. $repuesto->title)->error();
            return redirect('/orders/'. $formulario->id);
        } else {
            foreach ($in_peticions as $in_peticion) {
                # code...
                $repuesto = $in_peticion->repuesto;

                if($repuesto->stock >= $in_peticion->cantidad){
                    $repuesto->stock = $repuesto->stock - $in_peticion->cantidad;
                    $in_peticion->status = "Aceptado";
                    $repuesto->save();
                    $in_peticion->save();
                }
            }
            flash('Pedido aceptado con exito ')->success();
            return redirect('/orders/'. $formulario->id);
        }
    }

    
}
