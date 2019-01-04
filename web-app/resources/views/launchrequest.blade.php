@component('mail::message')
# Launch Request Created

Just a quick heads up, the app Synergy was just launched by {{$data['requester']}} from {{ $data['company_name'] }}

You can contact him on: {{ $data['contact'] }}
@component('mail::button', ['url' => 'localhost:8000'])
View Launch Requests
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
