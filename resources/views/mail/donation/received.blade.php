<x-mail::message>
Dear {{ $name }},

We hope this email finds you well. On behalf of {{ config('app.name') }}, We want to sincerely thank you for your generous donation of
    ${{ $amount }}. Your support means the world to us and helps us continue our mission.<br>

Thank you again for being a part of our community. Your kindness and generosity make a real difference, and we are deeply grateful for your support.<br>

With gratitude,<br>
{{ config('app.name') }}
</x-mail::message>
