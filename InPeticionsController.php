<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Peticion;
use App\InPeticion;
use App\User;
use App\Repuesto;
use App\Http\Requests\InPeticionsRequest;

class InPeticionsController extends Controller
{
    
    public function store(InPeticionsRequest $request)
    {
        //
        
        $user = User::find(\Auth::id());
        $repuesto = Repuesto::find($request->repuesto_id);
        if($repuesto->stock < $request->cantidad){
            flash('Stock Insuficiente')->error();
            return back();
        }else {
            $response = InPeticion::create(['user_id' => $user->id, 'repuesto_id'=> $request->repuesto_id, 'cantidad' => $request->cantidad , 'status' => 'Incompleto', 'formulario_id' => '0']);
            return back();
            
        }

        
    }

    


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
