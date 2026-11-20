@extends('layouts.app')

@push('head')
    <!-- embed.js defines <metabase-dashboard> and the other Metabase components. -->
    <script defer src="{{ $metabaseSiteUrl }}/app/embed.js"></script>
    <script>
        window.metabaseConfig = {
            isGuest: true,
            instanceUrl: {{ Js::from($metabaseSiteUrl) }},
        };
    </script>
@endpush

@section('content')
    <h1>Analytics for {{ $customer->name }}</h1>

    <p>
        You're signed in as <strong>{{ auth()->user()->email }}</strong>, who belongs to {{ $customer->name }}.
        The token for this embed locks the dashboard's Customer ID filter to <strong>{{ $customer->id }}</strong>,
        so every chart shows only {{ $customer->name }}'s rows. The Date filter is editable, so you can change it.
    </p>

    <!-- The server signs a fresh token for each page load. -->
    <metabase-dashboard token="{{ $token }}" with-title="true"></metabase-dashboard>
@endsection
