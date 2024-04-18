<x-mail::message>
Dear Africa Relief Admin,

Please connect you QuickBooks Online account with Africa Relief App that will allow us to sync the data between QuickBooks and Africa Relief Website using APIs.

To connect your quickbooks, simply click on the button below:

<x-mail::button :url="$authorizationUrl">
Connect
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
