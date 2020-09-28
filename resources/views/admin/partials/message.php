@if($system_message)
    <div class="alert alert-{{ $system_message['type'] }}">
        {{ $system_message['message'] }}
    </div>
@endif