<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\FormResponse;

class DashboardController extends Controller
{
    public function index()
    {
        // Columns for the table (exclude id and timestamps)
        $excluded = ['id', 'created_at', 'updated_at'];
        $columns = array_filter(Schema::getColumnListing('form_responses'), fn($col) => !in_array($col, $excluded));

        // Get all form responses with related department for table display
        $responses = FormResponse::select($columns)->with('department')->get();

        // Employment Status aggregation by graduation_year using present_employment
        $employmentStatusData = FormResponse::select(
            'graduation_year',
            DB::raw("SUM(CASE WHEN present_employment = 'Employed' THEN 1 ELSE 0 END) as employed"),
            DB::raw("SUM(CASE WHEN present_employment = 'Unemployed' THEN 1 ELSE 0 END) as unemployed"),
            DB::raw("SUM(CASE WHEN present_employment = 'Self-Employed' THEN 1 ELSE 0 END) as self_employed")
        )
            ->groupBy('graduation_year')
            ->orderBy('graduation_year')
            ->get();

        // Job Related to Degree counts
        $jobRelatedDegree = FormResponse::select(
            DB::raw("SUM(CASE WHEN job_related_to_degree = 'Yes' THEN 1 ELSE 0 END) as yes"),
            DB::raw("SUM(CASE WHEN job_related_to_degree = 'No' THEN 1 ELSE 0 END) as no")
        )->first();

        // Graduated Courses counts using specialization field
        $graduatedCourses = FormResponse::select('specialization', DB::raw('count(*) as total'))
            ->groupBy('specialization')
            ->get();

        // Graduates Per Year counts by graduation_year
        $graduatesPerYear = FormResponse::select(
            'graduation_year as year',
            DB::raw('count(*) as total')
        )
            ->groupBy('graduation_year')
            ->orderBy('graduation_year')
            ->get();

        // First Job Timeline counts by time_to_first_job
        $firstJobTimeline = FormResponse::select('time_to_first_job', DB::raw('count(*) as total'))
            ->groupBy('time_to_first_job')
            ->get();

        // Additional counts for buttons:
        $recentResponsesCount = FormResponse::where('created_at', '>=', now()->subDays(30))->count();
        $employedCount = FormResponse::where('present_employment', 'Employed')->count();
        $unemployedCount = FormResponse::where('present_employment', 'Unemployed')->count();
        $selfEmployedCount = FormResponse::where('present_employment', 'Self-Employed')->count();

        return view('admin.dashboard', compact(
            'columns',
            'responses',
            'employmentStatusData',
            'jobRelatedDegree',
            'graduatedCourses',
            'graduatesPerYear',
            'firstJobTimeline',
            'recentResponsesCount',
            'employedCount',
            'unemployedCount',
            'selfEmployedCount'
        ));
    }

    public function exportCsv()
    {
        $filter = request('filter'); // expects 'all', 'employed', 'unemployed', 'self-employed'

        $excluded = ['id', 'created_at', 'updated_at'];
        $columns = array_filter(Schema::getColumnListing('form_responses'), fn($col) => !in_array($col, $excluded));

        $query = FormResponse::select($columns)->with('department');

        if ($filter === 'employed') {
            $query->where('present_employment', 'Employed');
        } elseif ($filter === 'unemployed') {
            $query->where('present_employment', 'Unemployed');
        } elseif ($filter === 'self-employed') {
            $query->where('present_employment', 'Self-Employed');
        }
        // else 'all' => no filtering

        $responses = $query->get();

        $filename = 'form_responses_export_' . ($filter ?? 'all') . '_' . date('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($responses, $columns) {
            $handle = fopen('php://output', 'w');

            // Header row
            $header = [];
            foreach ($columns as $col) {
                $header[] = ucwords(str_replace('_', ' ', $col));
            }
            if (in_array('department_id', $columns)) {
                $idx = array_search('department_id', $columns);
                $header[$idx] = 'Department';
            }
            fputcsv($handle, $header);

            // Data rows
            foreach ($responses as $responseRow) {
                $row = [];
                foreach ($columns as $col) {
                    if ($col === 'department_id') {
                        $row[] = $responseRow->department ? $responseRow->department->name : '-';
                    } elseif ($col === 'first_employment_date' && $responseRow->$col) {
                        $row[] = date('M d, Y', strtotime($responseRow->$col));
                    } elseif (is_array($responseRow->$col)) {
                        $row[] = implode(', ', $responseRow->$col);
                    } else {
                        $row[] = $responseRow->$col ?? '-';
                    }
                }
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");
        $response->headers->set('Cache-Control', 'no-store, no-cache');

        return $response;
    }

}
