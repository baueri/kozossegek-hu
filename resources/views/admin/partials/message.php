@if($system_message = \Framework\Http\Message::flash())
    <div class="alert alert-{{ $system_message['type'] }}">
        {{ $system_message['message'] }}
        @if($list = $system_message['list'])
            <ul>
                @foreach($list as $list_message)
                    <li>{{ $list_message }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
