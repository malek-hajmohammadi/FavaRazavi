<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function uploadAvatar(Request $request){
       // $request->image->store('images','public');
        if($request->hasFile('image')){
            $fileName=$request->image->getClientOriginalName();

            $this->deleteOldImage();


            $request->image->storeAs('images',$fileName,'public');
            auth()->user()->update(['avatar'=>$fileName]);
        }

        return redirect()->back();
    }
    protected function deleteOldImage(){
        if(auth()->user()->avatar){
            Storage::delete('/public/images/'.auth()->user()->avatar);
        }
    }
    public function index(){
       // DB::insert('insert into users(name,email,password) values (?,?,?)',[
       //     'Malek','malek.hajmohammadi@gmail.com','password'
       // ]);
//        DB::update('update users set name=? where id=1',['مالک']);
//        DB::delete('delete from users where id=1 ');

//        $record=DB::select('select * from users');
//        return $record;

//        $user=new User();
//        $user->name="  رضا جواد";
//        $user->email="javadRezi@yah.com";
//        $user->password=bcrypt("password");
//        $user->save();

       // User::where('id',3)->delete();
       // User::where('id',4)->update(['name'=>'مینا جوادی']);

        $data=[
            'name'=>'رضاعلی',
            'email' =>'aliReza@yahoo.com',
            'password'=>"153245"
        ];
      //  User::create($data);


        $user=User::all();
        return $user;

        return view('home');
    }
}
