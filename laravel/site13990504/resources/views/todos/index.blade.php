@extends('todos/layout')
@section('content')
    <div class="flex justify-center border-b pb-4">
        <h1 class="text-2xl">چک لیست شما</h1>
        <a href="/todos/create" class="mx-5 py-1 px-1 bg-blue-400 cursor-pointer rounded">ایجاد آیتم جدید</a>
    </div>
    <ul>
        <x-alert/>
        @foreach($todos as $todo)
            <li class="flex justify-between py-2">
                <p>{{$todo->title}}</p>
                <div>

                <a href="{{'todos/'.$todo->id.'/edit'}}" class="
                 cursor-pointer "> <span class="fas fa-edit px-2 text-orange-400"/></a>
                @if($todo->completed)
                    <span class="fas fa-check px-2 text-green-300"/>
                    @else
                        <span class="fas fa-check px-2 text-gray-300 cursor-pointer "/>

                    @endif
                </div>
            </li>
        @endforeach
    </ul>
@endsection







