<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Maquina;
use App\Categoria;
use App\Http\Requests\MaquinaRequest;


class MaquinasController extends Controller
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
        $maquinas = Maquina::all();
        foreach ($maquinas as $maquina) {
            # code...
            $maquina->categoria;
        }
        return view("maquinas.index", ["maquinas" => $maquinas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $maquina = new Maquina;
        $categorias = Categoria::all();
        return view("maquinas.create", ["maquina" => $maquina, 'categorias' => $categorias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaquinaRequest $request)
    {
        //
        $maquina= new Maquina;
        $maquina->serie = $request->serie;
        $maquina->categoria_id = $request->modelo;

        if ($maquina->save()){
            return redirect("/maquinas");
        } else {
            return view("maquinas.create");
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
        $categorias = Categoria::all();
        
        $maquina = Maquina::find($id);
        return view("maquinas.edit", ["maquina" => $maquina, 'categorias' => $categorias]);
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
        $maquina = Maquina::find($id);
        $maquina->serie = $request->serie;
        $maquina->categoria_id = $request->modelo;
        
        if ($maquina->save()){
            return redirect("/maquinas");
        } else {
            return view("maquinas.edit");
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
        Maquina::destroy($id);
        return redirect("/maquinas");
    }
}
