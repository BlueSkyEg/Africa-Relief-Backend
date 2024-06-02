<x-mail::message>
Dear,

This email to inform you that we have received a new volunteer application.<br>
Below are the details of the application:

Name: {{ $applicant_name }}<br>
Email: {{ $applicant_email }}<br>
Phone Number: {{ $applicant_phone }}<br>
Address: {{ $applicant_address }}<br>
Message: {{ $applicant_message }}

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
