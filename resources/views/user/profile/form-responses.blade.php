@extends('user.profile.profile')

@section('title', 'Form Responses')

@section('tab-content')

<link href="{{ asset('css/right-sidebar.css') }}" rel="stylesheet" />

<div x-data="{ open: false }" class="relative bg-white p-8 rounded-lg shadow max-w-5xl mx-auto space-y-8">

    <h2 class="text-2xl font-bold border-b pb-3 mb-6">My Form Responses</h2>

    <!-- Display info -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4 text-gray-700">
        <div><span class="font-semibold">Full Name:</span> {{ $formResponse->full_name ?? 'N/A' }}</div>
        <div><span class="font-semibold">Address:</span> {{ $formResponse->address ?? 'N/A' }}</div>
        <div><span class="font-semibold">Department: </span>{{ $formResponse->department->name ?? 'N/A' }}</div>
        <div><span class="font-semibold">Specialization:</span> {{ $formResponse->specialization ?? 'N/A' }}</div>
        <div><span class="font-semibold">Graduation Year:</span> {{ $formResponse->graduation_year ?? 'N/A' }}</div>
        <div><span class="font-semibold">Graduate Studies Within 12m?:</span> {{ $formResponse->graduate_studies_within_12m ?? 'N/A' }}</div>
        <div><span class="font-semibold">Present Employment:</span> {{ $formResponse->present_employment ?? 'N/A' }}</div>
        <div><span class="font-semibold">Had Job Before?:</span> {{ $formResponse->had_job_before ?? 'N/A' }}</div>
    </div>

    <div class="bg-gray-50 rounded-lg p-6 shadow-inner text-gray-800 space-y-3">
        <h3 class="font-semibold text-lg mb-4 border-b pb-2">Employment Details</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-3">
            <div><span class="font-semibold">First Employment Date:</span> {{ $formResponse->first_employment_date ?? 'N/A' }}</div>
            <div><span class="font-semibold">First Workplace:</span> {{ $formResponse->first_workplace ?? 'N/A' }}</div>
            <div><span class="font-semibold">Position:</span> {{ $formResponse->position ?? 'N/A' }}</div>
            <div><span class="font-semibold">Employer:</span> {{ $formResponse->employer ?? 'N/A' }}</div>
            <div><span class="font-semibold">Office Address:</span> {{ $formResponse->office_address ?? 'N/A' }}</div>
            <div><span class="font-semibold">Employer Contact:</span> {{ $formResponse->employer_contact ?? 'N/A' }}</div>
            <div><span class="font-semibold">Time to First Job:</span> {{ $formResponse->time_to_first_job ?? 'N/A' }}</div>
            <div><span class="font-semibold">Job Related to Degree?:</span> {{ $formResponse->job_related_to_degree ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-5 rounded shadow-inner text-sm text-gray-600">
            <h4 class="font-semibold mb-2">{{ $formResponse->optional_group_a_title ?? 'Optional Group A' }}</h4>
            @if(!empty($formResponse->optional_group_a_points))
                <ol class="list-decimal list-inside space-y-1">
                    @foreach($formResponse->optional_group_a_points as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ol>
            @else
                <p class="italic">No points provided.</p>
            @endif
        </div>

        <div class="bg-gray-50 p-5 rounded shadow-inner text-sm text-gray-600">
            <h4 class="font-semibold mb-2">{{ $formResponse->optional_group_b_title ?? 'Optional Group B' }}</h4>
            @if(!empty($formResponse->optional_group_b_points))
                <ol class="list-decimal list-inside space-y-1">
                    @foreach($formResponse->optional_group_b_points as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ol>
            @else
                <p class="italic">No points provided.</p>
            @endif
        </div>
    </div>

    <!-- Edit Button -->
    <div class="mt-8 flex justify-center">
        <button
            @click="open = true"
            class="px-6 py-3 border border-blue-600 rounded-md text-blue-600 font-semibold hover:bg-blue-600 hover:text-white transition"
        >
            Edit
        </button>
    </div>

    <!-- Modal -->
    <div
        x-show="open"
        x-cloak
        x-transition
         class="fixed top-0 left-0 w-screen h-screen bg-black/60 light-blur-2xl z-50 flex items-center justify-center"

    >
        <div
            @click.away="open = false"
            x-trap="open"
            class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative overflow-y-auto max-h-[80vh]"
        >
            <h3 class="text-xl font-semibold mb-4">Edit Form Responses</h3>

            @include('user.profile.form-responses-form', [
                'formResponse' => $formResponse,
                'departments' => $departments
            ])
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
