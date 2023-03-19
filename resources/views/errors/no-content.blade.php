@extends('layouts.course')

@section('title')
    {{ __('No Content') }} | {{ config('app.name') }}
@endsection

@section('content')
    <div class="text-center">
        <h2 class="mt-5">Ups!... no results found</h2> <br>
        <img src="{{ asset('images/illustrations/oops.svg') }}" alt="no-content" width="300px" height="300px" /> <br>
        <a onclick="window.history.back();" class="text-primary font-weight-medium">
            {{ __('Back to previous page') }}
        </a>
    </div>
@endsection
