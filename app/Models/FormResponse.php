<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'address',
        'department_id',  // changed from 'department' string to 'department_id' foreign key
        'specialization',
        'graduation_year',
        'graduate_studies_within_12m',
        'present_employment',
        'had_job_before',
        'first_employment_date',
        'first_workplace',
        'position',
        'employer',
        'office_address',
        'employer_contact',
        'time_to_first_job',
        'job_related_to_degree',
        'optional_group_a_points',
        'optional_group_a_title',
        'optional_group_b_points',
        'optional_group_b_title',
    ];

    protected $casts = [
        'optional_group_a_points' => 'array',
        'optional_group_b_points' => 'array',
        'first_employment_date' => 'date',
        'graduation_year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add this relationship
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
