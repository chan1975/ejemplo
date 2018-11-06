<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Peticion;
use App\User;
use App\Categoria;
use App\Repuesto;
use App\CategoriaRepuesto;
use Illuminate\Support\Facades\DB;
class MainController extends Controller {
//public function login(){
//	return view('auth.login',[]);
//}
	public function __construct()
    {
        $this->middleware('auth');
    }

	public function home(Request $request){
		
		

		$user_id = User::getId();
		if($user_id == '1'){
			$user = User::find($user_id);
			$user->attachRole('1');
			
		}

		$categoria = Categoria::find($request->modelo); 

		//dd($request->modelo);
		if(is_null($categoria)){
			$repuestos = Repuesto::lates()->simplePaginate(10);

		}else{
			 
			$repuestos = DB::table('repuestos')
			->join('categoria_repuestos', 'categoria_repuestos.repuesto_id' , '=', 'repuestos.id')
			->join('categorias', 'categorias.id', '=', 'categoria_repuestos.categoria_id')
			->select('repuestos.*', 'categorias.name')
			->where('categoria_repuestos.categoria_id',$request->modelo )->simplePaginate(10);
			//xdd($repuestos);
			
		}
		

		$categorias = Categoria::all();
		

		return view('main.home', ["repuestos"=> $repuestos, 'categorias' => $categorias]);
	}
}