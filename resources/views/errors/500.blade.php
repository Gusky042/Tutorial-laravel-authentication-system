@extends('layouts.guest')

@section('title', 'Something went wrong')

@section('content')
    <p class="text-sm font-semibold text-gray-500">500</p>
    <h1 class="mt-1 text-xl font-semibold">Something went wrong</h1>
    <p class="mt-2 text-sm text-gray-600">
        An unexpected error happened on our side. Please try again in a moment.
    </p>

    <a href="{{ url('/') }}"
       class="mt-6 inline-block rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
        Go to the home page
    </a>
@endsection
