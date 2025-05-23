<form method="POST" action="{{ route('profile.form-responses.update') }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <!-- Fields for editing form responses -->
        <div>
            <label for="full_name" class="block font-semibold mb-1">Full Name</label>
            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $formResponse->full_name) }}"
                required class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('full_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="address" class="block font-semibold mb-1">Address</label>
            <input type="text" name="address" id="address" value="{{ old('address', $formResponse->address) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="department_id" class="block font-semibold mb-1">Department</label>
            <select name="department_id" id="department_id"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                <option value="" disabled {{ old('department_id', $formResponse->department_id ?? '') == '' ? 'selected' : '' }}>-- Select Department --</option>

                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id', $formResponse->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="specialization" class="block font-semibold mb-1">Specialization</label>
            <input type="text" name="specialization" id="specialization"
                value="{{ old('specialization', $formResponse->specialization) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('specialization') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="graduation_year" class="block font-semibold mb-1">Graduation Year</label>
            <input type="number" name="graduation_year" id="graduation_year"
                value="{{ old('graduation_year', $formResponse->graduation_year) }}" min="1900" max="2100"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('graduation_year') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="graduate_studies_within_12m" class="block font-semibold mb-1">Graduate Studies Within 12
                Months?</label>
            <select name="graduate_studies_within_12m" id="graduate_studies_within_12m"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                <option value="Yes" {{ old('graduate_studies_within_12m', $formResponse->graduate_studies_within_12m) == 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ old('graduate_studies_within_12m', $formResponse->graduate_studies_within_12m) == 'No' ? 'selected' : '' }}>No</option>
            </select>
            @error('graduate_studies_within_12m') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="present_employment" class="block font-semibold mb-1">Present Employment</label>
            <input type="text" name="present_employment" id="present_employment"
                value="{{ old('present_employment', $formResponse->present_employment) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('present_employment') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="had_job_before" class="block font-semibold mb-1">Had Job Before?</label>
            <select name="had_job_before" id="had_job_before"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
                <option value="Yes" {{ old('had_job_before', $formResponse->had_job_before) == 'Yes' ? 'selected' : '' }}>
                    Yes</option>
                <option value="No" {{ old('had_job_before', $formResponse->had_job_before) == 'No' ? 'selected' : '' }}>No
                </option>
            </select>
            @error('had_job_before') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="first_employment_date" class="block font-semibold mb-1">First Employment Date</label>
            <input type="date" name="first_employment_date" id="first_employment_date"
                value="{{ old('first_employment_date', $formResponse->first_employment_date) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('first_employment_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="first_workplace" class="block font-semibold mb-1">First Workplace</label>
            <input type="text" name="first_workplace" id="first_workplace"
                value="{{ old('first_workplace', $formResponse->first_workplace) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('first_workplace') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="position" class="block font-semibold mb-1">Position</label>
            <input type="text" name="position" id="position" value="{{ old('position', $formResponse->position) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('position') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="employer" class="block font-semibold mb-1">Employer</label>
            <input type="text" name="employer" id="employer" value="{{ old('employer', $formResponse->employer) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('employer') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="office_address" class="block font-semibold mb-1">Office Address</label>
            <input type="text" name="office_address" id="office_address"
                value="{{ old('office_address', $formResponse->office_address) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('office_address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="employer_contact" class="block font-semibold mb-1">Employer Contact</label>
            <input type="text" name="employer_contact" id="employer_contact"
                value="{{ old('employer_contact', $formResponse->employer_contact) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('employer_contact') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="time_to_first_job" class="block font-semibold mb-1">Time to First Job</label>
            <input type="text" name="time_to_first_job" id="time_to_first_job"
                value="{{ old('time_to_first_job', $formResponse->time_to_first_job) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('time_to_first_job') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="job_related_to_degree" class="block font-semibold mb-1">Job Related to Degree?</label>
            <select name="job_related_to_degree" id="job_related_to_degree"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
                <option value="Yes" {{ old('job_related_to_degree', $formResponse->job_related_to_degree) == 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ old('job_related_to_degree', $formResponse->job_related_to_degree) == 'No' ? 'selected' : '' }}>No</option>
            </select>
            @error('job_related_to_degree') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="optional_group_a_title" class="block font-semibold mb-1">Optional Group A Title</label>
            <input type="text" name="optional_group_a_title" id="optional_group_a_title"
                value="{{ old('optional_group_a_title', $formResponse->optional_group_a_title) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('optional_group_a_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="optional_group_a_points_text" class="block font-semibold mb-1">Optional Group A Points (one per
                line)</label>
            <textarea name="optional_group_a_points_text" id="optional_group_a_points_text" rows="4"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">{{ old('optional_group_a_points_text', is_array($formResponse->optional_group_a_points) ? implode("\n", $formResponse->optional_group_a_points) : '') }}</textarea>
            @error('optional_group_a_points_text') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="optional_group_b_title" class="block font-semibold mb-1">Optional Group B Title</label>
            <input type="text" name="optional_group_b_title" id="optional_group_b_title"
                value="{{ old('optional_group_b_title', $formResponse->optional_group_b_title) }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            @error('optional_group_b_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="optional_group_b_points_text" class="block font-semibold mb-1">Optional Group B Points (one per
                line)</label>
            <textarea name="optional_group_b_points_text" id="optional_group_b_points_text" rows="4"
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">{{ old('optional_group_b_points_text', is_array($formResponse->optional_group_b_points) ? implode("\n", $formResponse->optional_group_b_points) : '') }}</textarea>
            @error('optional_group_b_points_text') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

    </div>

    <div class="mt-6 flex justify-end space-x-3">
        <button type="button" @click="open = false"
            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save Changes</button>
    </div>
</form>