@if (Session::has('error'))
    {{ trans(Session::get('reason')) }}
@endif

{{ Form::open(array('url' => 'password/reset/'.$token, 'method' => 'post')) }}
	New password: {{ Form::password('password') }}
	<br/>Confirm new password: {{ Form::password('password_confirmation') }}
	<br/>{{ Form::submit('Submit') }}
{{ Form::close() }}