<x-mail::message>
Dear,

This email to inform you that we have received a new application for the {{ $job_title }} position.<br>
Below are the details of the applicant:

Name: {{ $applicant_name }}<br>
Email: {{ $applicant_email }}<br>
Phone Number: {{ $applicant_phone }}<br>
Cover Letter: {{ $applicant_cover_letter }}

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
