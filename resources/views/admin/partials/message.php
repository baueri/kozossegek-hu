@if($system_message = flash())
    <div class="alert alert-{{ $system_message['type'] }} shadow-sm">
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
