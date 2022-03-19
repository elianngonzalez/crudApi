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

    public function onliOne($id){
        $libro = Libro::find($id);
        return response()->json($libro);
    }

    public function save(Request $request){
        $libro = new Libro();
        
        if($request->hasFile("imagen")){
            $nombreArchivo = $request->file("imagen")->getClientOriginalName();

            $nuevoNombreArchivo = Carbon::now()->timestamp."_".$nombreArchivo;
        }

        $libro->titulo = $request->input('titulo');
        $libro->imagen = $request->input('imagen');
        // $libro->save();
        return response()->json($nuevoNombreArchivo);
    }

    //public function delete (Request $request){}
}