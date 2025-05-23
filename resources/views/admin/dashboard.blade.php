@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        <!-- Top Panels: Employment Status (line chart) & Job Related to Degree (donut chart) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Employment Status -->
            <div class="col-span-2 bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 mb-4">Employment Status</h2>
                <canvas id="employmentStatusChart" class="w-full h-48"></canvas>
            </div>

            <!-- Job Related to Degree -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 mb-4">Job Related to Degree</h2>
                <canvas id="jobRelatedDegreeChart" class="w-full h-48"></canvas>
            </div>
        </div>

        <!-- Buttons for filter and export -->
        <div class="bg-white rounded-lg shadow p-4 flex flex-wrap gap-3 items-center">

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M3 10h11M9 21V3" />
                </svg>
                Recent Responses {{ $recentResponsesCount }}
            </button>

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-8 0v2" />
                </svg>
                Employed {{ $employedCount }}
            </button>

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M19 21v-2a4 4 0 0 0-3-3.87" />
                </svg>
                Unemployed {{ $unemployedCount }}
            </button>

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3" />
                </svg>
                Self-Employed {{ $selfEmployedCount }}
            </button>

            <!-- Export Filter Dropdown -->
            <select id="exportFilter" class="border rounded px-3 py-2 text-sm ml-auto">
                <option value="all" selected>All</option>
                <option value="employed">Employed</option>
                <option value="unemployed">Unemployed</option>
                <option value="self-employed">Self-Employed</option>
            </select>

            <!-- Export CSV Button -->
            <a href="{{ route('admin.dashboard.exportCsv', ['filter' => 'all']) }}"
                id="exportCsvBtn"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 inline-flex items-center">
                Export CSV
            </a>
        </div>

        <!-- Dynamic Table of Form Responses -->
        <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-700">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="p-2">#</th>
                        @foreach ($columns as $col)
                            <th class="p-2">{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($responses as $index => $response)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="p-2 font-semibold">{{ $index + 1 }}</td>
                            @foreach ($columns as $col)
                                <td class="p-2">
                                    @if ($col === 'department_id')
                                        {{ $response->department ? $response->department->name : '-' }}
                                    @elseif ($col === 'first_employment_date' && $response->$col)
                                        {{ \Carbon\Carbon::parse($response->$col)->format('M d, Y') }}
                                    @elseif (is_array($response->$col))
                                        {{ implode(', ', $response->$col) }}
                                    @else
                                        {{ $response->$col ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bottom Panels -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Graduated Course -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-700 mb-4">Graduated Course</h3>
                <canvas id="graduatedCourseChart" class="w-full h-40"></canvas>
            </div>

            <!-- Graduates Per Year -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-700 mb-4">Graduates Per Year</h3>
                <canvas id="graduatesPerYearChart" class="w-full h-40"></canvas>
                <p class="mt-2 text-gray-400 text-xs italic">Sales for this month</p>
            </div>

            <!-- First Job Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-700 mb-4">First Job Timeline</h3>
                <canvas id="firstJobTimelineChart" class="w-full h-40"></canvas>
            </div>

        </div>

    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Export filter dropdown changes export CSV URL dynamically
        document.getElementById('exportFilter').addEventListener('change', function() {
            const filter = this.value;
            const exportBtn = document.getElementById('exportCsvBtn');
            const baseUrl = "{{ route('admin.dashboard.exportCsv') }}";
            exportBtn.href = `${baseUrl}?filter=${filter}`;
        });

        // Employment Status Line Chart
        const employmentLabels = {!! json_encode($employmentStatusData->pluck('graduation_year')) !!};
        const employedData = {!! json_encode($employmentStatusData->pluck('employed')) !!};
        const unemployedData = {!! json_encode($employmentStatusData->pluck('unemployed')) !!};
        const selfEmployedData = {!! json_encode($employmentStatusData->pluck('self_employed')) !!};

        const ctxEmployment = document.getElementById('employmentStatusChart').getContext('2d');
        new Chart(ctxEmployment, {
            type: 'line',
            data: {
                labels: employmentLabels,
                datasets: [
                    {
                        label: 'Employed',
                        borderColor: '#2563eb',
                        backgroundColor: 'transparent',
                        data: employedData,
                        tension: 0.4,
                        fill: false,
                    },
                    {
                        label: 'Unemployed',
                        borderColor: '#a855f7',
                        backgroundColor: 'transparent',
                        data: unemployedData,
                        tension: 0.4,
                        fill: false,
                    },
                    {
                        label: 'Self-Employed',
                        borderColor: '#22c55e',
                        backgroundColor: 'transparent',
                        data: selfEmployedData,
                        tension: 0.4,
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle' } }
                },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Job Related to Degree Donut Chart
        const ctxJobDegree = document.getElementById('jobRelatedDegreeChart').getContext('2d');
        new Chart(ctxJobDegree, {
            type: 'doughnut',
            data: {
                labels: ['No', 'Yes'],
                datasets: [{
                    data: [{{ $jobRelatedDegree->no ?? 0 }}, {{ $jobRelatedDegree->yes ?? 0 }}],
                    backgroundColor: ['#3b82f6', '#f97316'],
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                cutout: '75%'
            }
        });

        // Graduated Course Pie Chart
        const ctxGradCourse = document.getElementById('graduatedCourseChart').getContext('2d');
        const courseLabels = {!! json_encode($graduatedCourses->pluck('specialization')) !!};
        const courseData = {!! json_encode($graduatedCourses->pluck('total')) !!};
        new Chart(ctxGradCourse, {
            type: 'pie',
            data: {
                labels: courseLabels,
                datasets: [{
                    data: courseData,
                    backgroundColor: ['#3b82f6', '#ef4444', '#f97316'], // extend if needed
                    hoverOffset: 15
                }]
            },
            options: { responsive: true }
        });

        // Graduates Per Year Bar Chart
        const ctxGradYear = document.getElementById('graduatesPerYearChart').getContext('2d');
        const gradYearLabels = {!! json_encode($graduatesPerYear->pluck('year')) !!};
        const gradYearData = {!! json_encode($graduatesPerYear->pluck('total')) !!};
        new Chart(ctxGradYear, {
            type: 'bar',
            data: {
                labels: gradYearLabels,
                datasets: [{
                    label: 'Graduates',
                    data: gradYearData,
                    backgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // First Job Timeline Horizontal Bar Chart
        const ctxFirstJob = document.getElementById('firstJobTimelineChart').getContext('2d');
        const firstJobLabels = {!! json_encode($firstJobTimeline->pluck('time_to_first_job')) !!};
        const firstJobData = {!! json_encode($firstJobTimeline->pluck('total')) !!};
        new Chart(ctxFirstJob, {
            type: 'bar',
            data: {
                labels: firstJobLabels,
                datasets: [{
                    label: 'Count',
                    data: firstJobData,
                    backgroundColor: '#3b82f6'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: { x: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endsection
