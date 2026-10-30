@extends('layouts.guest')

@section('title', 'Session expired')

@section('content')
    <p class="text-sm font-semibold text-gray-500">419</p>
    <h1 class="mt-1 text-xl font-semibold">Your session expired, please try again</h1>
    <p class="mt-2 text-sm text-gray-600">
        For your security, forms expire after a while. Go back, reload the page and submit the form again.
    </p>

    <a href="{{ url('/') }}"
       class="mt-6 inline-block rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
        Start again
    </a>
@endsection
