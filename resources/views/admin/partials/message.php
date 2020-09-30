@if($system_message = \Framework\Http\Message::get())
    <div class="alert alert-{{ $system_message['type'] }}">
        {{ $system_message['message'] }}
    </div>
@endif