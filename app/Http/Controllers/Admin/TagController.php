<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    
    // ? Protege las url para que un usuario si rol no pueda acceder
    public function __construct()
    {
        $this->middleware('can:admin.tags.index')->only('index');
        $this->middleware('can:admin.tags.create')->only('create','store');
        $this->middleware('can:admin.tags.edit')->only('edit','update');
        $this->middleware('can:admin.tags.destroy')->only('destroy');
    }
    public function index()
    {
        $tags=Tag::all();
        return view('admin.tags.index',compact('tags'));
    }

   
    public function create()
    {
        $colors=[
            'red'=>'Color rojo',
            'yellow'=>'Color amarillo',
            'green'=>'Color verde',
            'blue'=>'Color azul',
            'indigo'=>'Color indigo',
            'purple'=>'Color morado',
            'pink'=>'Color rosado',
        ];
        return view('admin.tags.create',compact('colors'));
    }

    
    public function store(Request $request)
    {
        // ?Reglas de validacion
       $request->validate([
           'name'=>'required',
           'slug'=>'required|unique:tags',
           'color'=>'required',
       ]);
       $tag= Tag::create($request->all());
       return redirect()->route('admin.tags.edit',compact('tag'))->with('info','La etiqueta se creó con exito');
    }

    
    

    public function edit(Tag $tag)
    {
        $colors=[
            'red'=>'Color rojo',
            'yellow'=>'Color amarillo',
            'green'=>'Color verde',
            'blue'=>'Color azul',
            'indigo'=>'Color indigo',
            'purple'=>'Color morado',
            'pink'=>'Color rosado',
        ];
        return view('admin.tags.edit',compact('tag','colors'));
    }

    
    public function update(Request $request,Tag $tag)
    {
         // ?Reglas de validacion
       $request->validate([
        'name'=>'required',
        'slug'=>"required|unique:tags,slug,$tag->id",
        'color'=>'required',
        ]);
        $tag->update($request->all());
        return redirect()->route('admin.tags.edit',$tag)->with('info','La etiqueta se actualizo con exito');
    }

    
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('info','La etiqueta se elimino con exito');
    }
}
