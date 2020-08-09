<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
    @if(session()->has('message'))
        {{$slot}}

        <div class="py-4 px-2 bg-green-300">
            {{session()->get('message')}}
        </div>
        {{session()->forget('message')}}
    @elseif(session()->has('error'))
        <div class="py-4 px-2 bg-red-300">
            {{session()->get('error')}}
        </div>
        {{session()->forget('error')}}
    @endif

    @if ($errors->any())
        <div class="py-4 px-2 bg-red-300">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
