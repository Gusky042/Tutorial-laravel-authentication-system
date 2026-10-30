{{--
    Show/Hide button for a password input. Place it inside a "relative" wrapper next to
    the input, and give the input "pr-16" so text does not run under the button.
    resources/js/app.js makes it visible and handles clicks; it stays hidden without JavaScript.

    Usage: <x-password-toggle for="password" label="password" />
--}}
@props(['for', 'label' => 'password'])

<button type="button" data-toggle-password="{{ $for }}" aria-controls="{{ $for }}"
        aria-pressed="false" aria-label="Show {{ $label }}"
        class="absolute inset-y-0 right-0 hidden rounded-r-md px-3 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400">
    Show
</button>
