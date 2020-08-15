@extends('todos/layout')
@section('content')
    <div class="flex justify-between border-b pb-4 px-4">
        <h1 class="text-2xl">چک لیست شما</h1>
        <a href="{{route('todo.create')}}" class="mx-5 py-2 text-blue-400 cursor-pointer ">
            <span class="fas fa-plus-circle"/>
        </a>
    </div>
    <ul>
        <x-alert/>
        @foreach($todos as $todo)
            <li class="flex justify-between py-2">
                <div>
                    @include('todos.complete-button')
                </div>

                @if($todo->completed)
                    <p class="line-through">{{$todo->title}}</p>
                @else
                    <p>{{$todo->title}}</p>
                @endif
                <div>

                    <a href="{{route('todo.edit',$todo->id)}}" class="
                 cursor-pointer "> <span class="fas fa-edit px-2 text-orange-400"/></a>


                 <span
                            onclick="event.preventDefault();
                            if(confirm('آیا از حذف آیتم مطمئن هستید؟')){
                            document.getElementById('form-delete-{{$todo->id}}').submit()
                            }
                            "
                            class="fas fa-trash px-2 text-red-500 cursor-pointer"/>
                    <form style="display: none" method="post" id="{{'form-delete-'.$todo->id}}"
                          action="{{route('todo.destroy',$todo->id)}}">
                        @csrf
                        @method('delete')
                    </form>


                </div>
            </li>
        @endforeach
    </ul>
@endsection







