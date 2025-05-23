@extends('layouts.main')

@section('content')
<div class="w-full max-w-full mx-auto p-8 bg-white rounded-lg shadow-md"> <!-- palitan max-w-7xl to max-w-full para full width -->
    <h1 class="text-2xl font-semibold mb-6">My Profile</h1>

    <ul class="flex border-b border-gray-300 mb-8 text-sm">
        @php
            $tabs = [
                'account-info' => 'Account Info',
                'form-responses' => 'Form Responses',
                'my-posts' => 'My Posts',
                'my-comments' => 'My Comments',
                'hidden-posts' => 'Hidden Posts',
            ];
        @endphp

        @foreach ($tabs as $key => $label)
            <li class="-mb-px mr-6">
                <a href="{{ route('profile.' . $key) }}"
                   class="inline-block py-2 px-3 font-medium
                   {{ request()->routeIs('profile.' . $key) 
                       ? 'border-b-2 border-blue-600 text-blue-600' 
                       : 'text-gray-500 hover:text-blue-600' }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @yield('tab-content')
    </div>
</div>
@endsection
