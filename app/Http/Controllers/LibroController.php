<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

class LibroController extends Controller{

    public function index(){
        $librosDatos = Libro::all();
        return response()->json($librosDatos);
    }

    public function save(Request $request){
        $libro = new Libro();
        $libro->titulo = $request->input('titulo');
        $libro->imagen = $request->input('imagen');
        // $libro->save();
        return response()->json($libro);
    }
}