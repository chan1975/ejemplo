<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use jeremykenedy\LaravelRoles\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserForm;
use App\Peticion;
use App\InPeticion;
use App\Formulario;
use App\RepuestosFormulario;
class UsersController extends Controller
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



    public function index()
    {
        //
        $id = User::getId();
        $user = User::find($id);
        $users= User::all();
        //dd($users);
        return view("users.index", ["users" => $users, "user_auth"=> $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
        $user = new User;

        return view("users.create", ["user" => $user , 'roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserForm $request)
    {
        //


        $user= new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        //asignacion de roles 
        $role_id = $request->role;
        $role = Role::find($role_id);
        
        if ($user->save()){
            $user->attachRole($role);
            return redirect("/users");
        } else {
            return view("users.create");
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
        //
        $user = User::find($id);
        return view('users.show', ['user'=> $user]);
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
        $user = User::find($id);
        $delete_pedidos = InPeticion::where('user_id', $user->id)->delete();
        $formularios = Formulario::where('user_id',$user->id)->get();
        if($formularios->isNotEmpty()){
            foreach ($formularios as $formulario) {
                # code...
                RepuestosFormulario::where('formulario_id', $formulario->id)->delete();
            }
        }

        Formulario::where('user_id',$user->id)->delete();
        $user->delete();
        
        return redirect("/users");
    }
}
