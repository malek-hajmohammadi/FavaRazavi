@if($todo->completed)
    <span
        onclick="event.preventDefault();document.getElementById('form-inCompleted-{{$todo->id}}').submit()"
        class="fas fa-check px-2 text-green-400 cursor-pointer"/>
    <form style="display: none" method="post" id="{{'form-inCompleted-'.$todo->id}}"
          action="{{route('todo.inComplete',$todo->id)}}">
        @csrf
        @method('delete')
    </form>
@else
    <span
        onclick="event.preventDefault();document.getElementById('form-completed-{{$todo->id}}').submit()"
        class="fas fa-check px-2 text-gray-300 cursor-pointer "/>
    <form style="display: none" method="post" id="{{'form-completed-'.$todo->id}}"
          action="{{route('todo.complete',$todo->id)}}">
        @csrf
        @method('put')
    </form>

@endif
