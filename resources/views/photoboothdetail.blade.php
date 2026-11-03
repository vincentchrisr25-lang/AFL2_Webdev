@extends('layouts.app')

@section('title', $client->client_name)

@section('content')
    @include('components.client-detail', ['client' => $client, 'listingRoute' => 'photobooth'])
@endsection
