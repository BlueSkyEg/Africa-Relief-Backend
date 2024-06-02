<x-mail::message>
Dear,

This email to inform you that we have received a new message through our website's contact form.<br>
Below are the details of the message:

Name: {{ $sender_name }}<br>
Email: {{ $sender_email }}<br>
Phone Number: {{ $sender_phone }}<br>
Address: {{ $sender_address }}<br>
Message: {{ $sender_message }}

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
