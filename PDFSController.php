<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repuesto;
use App\Formulario;
use App\RepuestosFormulario;

class PDFSController extends Controller
{
    //
    public function index(){
    	$formularios = Formulario::all();
		foreach ($formularios as $formulario) {
			$formulario->repuestos;
			# code...
		}


		return view("pdfs.listado_reportes", ['formularios'=> $formularios]);
	}


    public function crearPdf( $vistaurl, $tipo , $id){

		$formulario = Formulario::find($id);
		
		
		$fecha = Formulario::getFecha($formulario->fecha);
		
			
	   	$pdf= \PDF::loadView('pdfs.reporte', ['formulario'=> $formulario, 'fecha'=> $fecha])->setPaper('a4', 'landscape');
	 	
	   	if($tipo==1){
	   		return $pdf->stream('reporte');
	   	}
	   	if($tipo==2){
	   		return $pdf->download('reporte.pdf');
	   	}
    }

    public function crear_reporte($tipo, $id){
	   	$vistaurl="pdfs.reporte";
	  
	   	return $this->crearPdf($vistaurl ,$tipo , $id);
   }

   public function eliminar_reporte( $id){
   		$repuestos_formulario = RepuestosFormulario::where('formulario_id', $id)->delete(); 
	   	
	   	$formulario = Formulario::destroy($id);

	   	return back();
   }
}
