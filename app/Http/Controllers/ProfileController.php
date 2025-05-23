<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\FormResponse;
use App\Models\Department;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{

    
    // Show Account Info and Alumni Data
    public function accountInfo()
    {
        $user = Auth::user();

        // Fetch alumni data just like in the Dashboard controller
        $alumni = DB::table('form_responses')
            ->join('users', 'form_responses.user_id', '=', 'users.id')
            ->select(
                'form_responses.full_name as full_name',
                DB::raw('YEAR(form_responses.graduation_year) as batch_year'),
                'users.profile_photo'
            )
            ->distinct()
            ->orderByDesc('batch_year')
            ->get();

        return view('user.profile.account-info', [
            'user' => $user,
            'alumni' => $alumni, // Pass alumni data to the view
        ]);
    }

    // Update Profile Photo
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = uniqid().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('profile_photos', $filename, 'public');

            // Optional: Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->profile_photo = $path;
            $user->save();
        }

        return back()->with('success', 'Profile photo updated!');
    }

    // Update About Me Section
    public function updateAboutMe(Request $request)
    {
        $request->validate([
            'about_me' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $user->about_me = $request->about_me;
        $user->save();

        return back()->with('success', 'About Me updated!');
    }

    // Show Form Responses
    public function formResponses()
    {
        $user = Auth::user();

        // Fetch alumni data for the current user
        $alumni = DB::table('form_responses')
            ->join('users', 'form_responses.user_id', '=', 'users.id')
            ->select(
                'form_responses.full_name as full_name',
                DB::raw('YEAR(form_responses.graduation_year) as batch_year'),
                'users.profile_photo'
            )
            ->distinct()
            ->orderByDesc('batch_year')
            ->get();

        $formResponse = $user->formResponse ?: new FormResponse();
        $departments = Department::all();

        return view('user.profile.form-responses', compact('formResponse', 'user', 'departments', 'alumni'));
    }

    // Update Form Responses
    public function updateFormResponses(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|digits:4',
            'graduate_studies_within_12m' => 'required|in:Yes,No',
            'present_employment' => 'nullable|string|max:255',
            'had_job_before' => 'nullable|in:Yes,No',
            'first_employment_date' => 'nullable|date',
            'first_workplace' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'office_address' => 'nullable|string|max:255',
            'employer_contact' => 'nullable|string|max:255',
            'time_to_first_job' => 'nullable|string|max:255',
            'job_related_to_degree' => 'nullable|in:Yes,No',
            'optional_group_a_title' => 'nullable|string|max:255',
            'optional_group_a_points_text' => 'nullable|string',
            'optional_group_b_title' => 'nullable|string|max:255',
            'optional_group_b_points_text' => 'nullable|string',
        ]);

        $validated['optional_group_a_points'] = $validated['optional_group_a_points_text']
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['optional_group_a_points_text']))))
            : [];

        $validated['optional_group_b_points'] = $validated['optional_group_b_points_text']
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['optional_group_b_points_text']))))
            : [];

        unset($validated['optional_group_a_points_text'], $validated['optional_group_b_points_text']);

        $formResponse = $user->formResponse()->firstOrNew([]);
        $formResponse->fill($validated);
        $formResponse->save();

        return back()->with('success', 'Form responses updated successfully!');
    }

    // Fetch Posts for User
    public function myPosts()
    {
        $user = Auth::user();

        // Fetch posts created by the user
        $posts = $user->posts()->withCount(['likes', 'comments', 'reposts'])->with('media')->latest()->get();

        // Fetch alumni data for the current user
        $alumni = DB::table('form_responses')
            ->join('users', 'form_responses.user_id', '=', 'users.id')
            ->select(
                'form_responses.full_name as full_name',
                DB::raw('YEAR(form_responses.graduation_year) as batch_year'),
                'users.profile_photo'
            )
            ->distinct()
            ->orderByDesc('batch_year')
            ->get();

        return view('user.profile.my-posts', compact('posts', 'alumni'));
    }

    // Fetch Comments for User
    public function myComments()
    {
        $user = Auth::user();

        // Fetch comments made by the user
        $comments = $user->comments()->with('post')->latest()->get();

        // Fetch alumni data for the current user
        $alumni = DB::table('form_responses')
            ->join('users', 'form_responses.user_id', '=', 'users.id')
            ->select(
                'form_responses.full_name as full_name',
                DB::raw('YEAR(form_responses.graduation_year) as batch_year'),
                'users.profile_photo'
            )
            ->distinct()
            ->orderByDesc('batch_year')
            ->get();

        return view('user.profile.my-comments', compact('comments', 'alumni'));
    }


    // Fetch Hidden Posts for User
    public function hiddenPosts()
    {
        $user = Auth::user();

        // Fetch hidden posts created by the user
        $posts = $user->posts()->where('hidden', true)->latest()->get();

        // Fetch alumni data for the current user
        $alumni = DB::table('form_responses')
            ->join('users', 'form_responses.user_id', '=', 'users.id')
            ->select(
                'form_responses.full_name as full_name',
                DB::raw('YEAR(form_responses.graduation_year) as batch_year'),
                'users.profile_photo'
            )
            ->distinct()
            ->orderByDesc('batch_year')
            ->get();

        return view('user.profile.hidden-posts', compact('posts', 'alumni'));
    }
}
