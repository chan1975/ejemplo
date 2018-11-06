<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Http\Requests\CategoriaRequest;
use Laracasts\Flash\FlashServiceProvider;

class CategoriasController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $categorias = Categoria::all();
        return view("categorias.index", ["categorias" => $categorias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categoria = new Categoria;
        return view("categorias.create", ["categoria" => $categoria]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        //
        $categoria= new Categoria;
        $categoria->name = $request->name;

        if ($categoria->save()){
            
            return redirect("/categorias");
        } else {
            return view("categorias.create");
        }
    }

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $categoria = Categoria::find($id);
        return view("categorias.edit", ["categoria" => $categoria]);
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
        //
        $categoria = Categoria::find($id);
        $categoria->name = $request->name;
        
        if ($categoria->save()){
            return redirect("/categorias");
        } else {
            return view("categorias.edit");
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
        $categoria = Categoria::find($id);
        $maquinas = $categoria->maquinas()->get();
        $repuestos = $categoria->repuestos()->get();
        
        if($maquinas->isNotEmpty()){
            flash('Existen Maquinas que dependen de este modelo')->error();
            return redirect("/categorias");
        } else{
            if($repuestos->isNotEmpty()){
                flash('Existen Repuestos que dependen de este modelo')->error();
                return redirect("/categorias");
            } else{
                Categoria::destroy($id);
                return redirect("/categorias");
            }
            
        }
        
        
    }
}
