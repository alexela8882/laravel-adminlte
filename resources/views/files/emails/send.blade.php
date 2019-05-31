<div>

	<strong>Hi! {{ $to }}</strong><br><br>

	<em><u>{{ $file }}</u></em> has been uploaded to your account in Laravel+AdminLTE. Please login
	<a href="http://localhost:8000">here</a> to download your file.

	<br><br>
	<strong>
		Regards,<br>
		{{ $from }}<br>
		<a href="mailto:{{ $from_email }}">{{ $from_email }}</a>
	</strong>
</div>