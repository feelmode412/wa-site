{{ @$message }}

{{ Form::open(array('url' => 'password/remind', 'method' => 'post')) }}
	{{ Form::text('email') }}
	{{ Form::submit('Send Reminder') }}
{{ Form::close() }}