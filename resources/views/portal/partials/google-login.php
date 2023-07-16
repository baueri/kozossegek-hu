@if(env('GOOGLE_LOGIN_ENABLED'))
<?php $requestUri = request()->uri; ?>
<div id="g_id_onload"
     data-client_id="{{ env('GOOGLE_CLIENT_ID') }}"
     data-context="{{ $g_context ?? 'signin' }}"
     data-ux_mode="{{ $g_ux_mode ?? 'popup' }}"
     data-login_uri="@route('social_login', ['provider' => 'google', 'redirect_after' => $requestUri])"
     data-auto_prompt="false">
</div>

<div class="g_id_signin mb-3"
     data-type="{{ $g_type ?? 'standard' }}"
     data-shape="{{ $g_shape ?? 'rectangular' }}"
     data-theme="{{ $g_theme ?? 'outline' }}"
     data-text="{{ $g_text ?? 'continue_with' }}"
     data-size="{{ $g_size ?? 'large' }}"
     @if(!empty($width)) data-width="{{ $g_width }}" @endif
     data-logo_alignment="{{ $g_logo_alignment ?? 'center' }}"
     data-locale="hu">
</div>
@endif