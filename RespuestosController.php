<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Repuesto;
use App\User;
use App\Categoria;
use App\Inpeticion;
use App\CategoriaRepuesto;
use App\RepuestosFormulario;
use App\Http\Requests\RepuestosRequest;
use Laracasts\Flash\FlashServiceProvider;

class RespuestosController extends Controller
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

    public function index(Request $request)
    {   
        
        $repuestos= Repuesto::search($request->title)->get();
        foreach ($repuestos as $repuesto) {
            # code...
            $repuesto->categorias;

        }
        if ($repuestos->isEmpty() && trim($request->title)!=""){
            flash('Este repuesto no se encuentra en la base de datos')->error();
        }
        
        //dd($repuestos);
        
        return view("repuestos.index", ["repuestos" => $repuestos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $repuesto = new Repuesto;
        $categorias = Categoria::all();
        return view("repuestos.create", ["repuesto" => $repuesto, 'categorias' => $categorias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RepuestosRequest $request){

        $hasFile = $request->hasFile('cover') && $request->cover->isValid();           
        $repuesto = new Repuesto;         
        $repuesto->title = $request->title;         
        $repuesto->description = $request->description;
        $repuesto->stock = $request->stock;   
        $repuesto->tipo = $request->tipo;
        if($hasFile){             
            $extension = $request->cover->extension();             
            $repuesto->extension= $extension;         
        }                  
        if ($repuesto->save()){
            $categoria_repuesto = CategoriaRepuesto::create(['categoria_id' => $request->modelo, 'repuesto_id' => $repuesto->id]);

            
            return redirect("/repuestos");         
        } else {             
             return view("repuestos.create");         
        }      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $repuesto = Repuesto::find($id);
        return view('repuestos.show', ['repuesto'=> $repuesto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categorias = Categoria::all();
        $repuesto = Repuesto::find($id);
        return view("repuestos.edit", ["repuesto" => $repuesto, 'categorias' => $categorias]);
        
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
        $repuesto = Repuesto::find($id);
        $repuesto->title = $request->title;
        $repuesto->description = $request->description;
        $repuesto->stock = $request->stock;
        $repuesto->tipo = $request->tipo;
        
        if ($repuesto->save()){
            flash('Repuesto Modificado con Exito')->warning();
            return redirect("/repuestos");
        } else {
            return view("repuestos.edit");
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

        $repuestosToDelete = InPeticion::where('repuesto_id', $id)->get();
        $repuestos_formulario = RepuestosFormulario::where('repuesto_id', $id)->get();

        
        
        if($repuestosToDelete->contains('status', 'Incompleto')){
            flash('Este repuesto tiene peticiones pendientes')->error();
            return redirect("/repuestos");
        } else{
            if($repuestos_formulario->isNotEmpty()){
                flash('Este repuesto esta asociado a varios formularios')->error();
                return redirect("/repuestos");
            } else{
                $repuestos_categoria = CategoriaRepuesto::where('repuesto_id',$id)->delete();
                flash('Repuesto Eliminado con Exito')->success();
                $repuesto = Repuesto::find($id);
                $repuesto->delete();
                return redirect("/repuestos");
            }
        }
    }
}
