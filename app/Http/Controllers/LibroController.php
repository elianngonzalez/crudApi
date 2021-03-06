<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

use Carbon\Carbon;

class LibroController extends Controller{

	public function index(){
		$librosDatos = Libro::all();
		return response()->json($librosDatos);
	}

	public function verOne($id){
		$libro = Libro::find($id);

		if($libro){            
			$titulo = $libro->titulo;
			$imagen = $libro->imagen;
			
			$libroDatos = [
				'id'     => $id,
				'titulo' => $titulo,
				'imagen' => $imagen
			];
			return response()->json([$libroDatos], 200);
		}
		
		return response()->json(['error' => 'No se encontrĂ³ el libro'], 404);
	}

	public function guardar (Request $request){
		$libro = new Libro();
		

		if($request->hasFile("imagen") && $request->input('titulo')){
			$nombreArchivo = $request->file("imagen")->getClientOriginalName();
			$nuevoNombreArchivo = Carbon::now()->timestamp."_".$nombreArchivo;
			$carpetaDestino = "./upload/";
			$request->file("imagen")->move($carpetaDestino, $nuevoNombreArchivo);
			$libro->titulo = $request->input('titulo');
			$libro->imagen = ltrim($carpetaDestino, ".").$nuevoNombreArchivo;

			$libro->save();

			return response()->json([
				'mensaje' => 'Libro guardado correctamente',
				'libro' => $libro
			], 201);
		}

		return response()->json([
			'error' => 'No se pudo guardar el libro'
		], 500);
	}


	public function eliminar($id){
		$libro = Libro::find($id);

		if($libro){
			$rutaImagen = base_path("public").$libro->imagen;
			if (file_exists($rutaImagen)){
				unlink($rutaImagen);
			}
			$libro->delete();
			return response()->json([
				'mensaje' => 'Libro eliminado correctamente'
			], 200);
		}

		return response()->json(['error' => 'No se encontrĂ³ el libro'], 404);
	}

	public function actualizar (Request $request, $id){
		$libro = Libro::find($id);


		if($libro and $request->input('titulo')){
			$libro->titulo = $request->input('titulo');
			$libro->save();
			return response()->json([
				'mensaje' => 'Libro actualizado correctamente',
				'libro' => $libro
			], 200);
		};
		if (!$request->input('titulo')){
			return response()->json(['error' => 'el input esta vacio'], 404);
		};

		if($request->hasFile("imagen")){

			if($libro){
				$rutaImagen = base_path("public").$libro->imagen;
				if (file_exists($rutaImagen)){
					unlink($rutaImagen);
				}
				$libro->delete();
			}    
			$nombreArchivo = $request->file("imagen")->getClientOriginalName();
			$nuevoNombreArchivo = Carbon::now()->timestamp."_".$nombreArchivo;
			$carpetaDestino = "./upload/";
			$request->file("imagen")->move($carpetaDestino, $nuevoNombreArchivo);
			$libro->imagen = ltrim($carpetaDestino, ".").$nuevoNombreArchivo;
			$libro->save();
		}


		return response()->json(['error' => 'No se encontrĂ³ el libro'], 404);
	}

	//public function delete (Request $request){}
}