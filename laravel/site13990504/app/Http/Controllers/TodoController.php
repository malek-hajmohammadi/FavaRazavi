<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoCreateRequest;
use App\todo;

use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function index()
    {
       $todos=todo::all();


       // return view('todos.index')->with(['todos'=>$todos]);
        return view('todos.index',compact('todos'));
    }
    public function create()
    {
        return view('todos.create');
    }
     public function edit(todo $todo)
    {

        return view('todos.edit',compact('todo'));
    }

    public function store(TodoCreateRequest $request){

       /* $rules=[
            'title'=>'required|max:10',
        ];
        $messages=[
            'title.max'=>'طول رشته بیش از حد مجاز است',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }*/

        todo::create($request->all());
        return redirect()->back()->with('message','TODO create successfully');
    }

    public function update(TodoCreateRequest $request,todo $todo){
        $todo->update(['title'=> $request->title]);
        return redirect(route('todo.index'))->with('message','لیست به روز شد');

    }


}
