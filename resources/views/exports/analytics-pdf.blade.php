<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analytics Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .info-box {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .info-item {
            flex: 1;
            min-width: 200px;
        }
        .info-item label {
            font-weight: bold;
            color: #4f46e5;
            display: block;
            font-size: 11px;
            margin-bottom: 3px;
        }
        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            flex: 1;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .stat-card h3 {
            margin: 0 0 5px;
            font-size: 11px;
            color: #6b7280;
        }
        .stat-card .value {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            margin: 20px 0 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #4f46e5;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .course-card {
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .course-header {
            background: #f3f4f6;
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .course-header h4 {
            margin: 0;
            font-size: 16px;
            color: #1f2937;
        }
        .course-stats {
            display: flex;
            gap: 20px;
            padding: 12px 15px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        .course-stats div {
            font-size: 12px;
        }
        .course-stats strong {
            color: #4f46e5;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #9ca3af;
        }
        .badge-pass {
            color: #10b981;
            font-weight: bold;
        }
        .badge-fail {
            color: #ef4444;
            font-weight: bold;
        }
        .badge-pending {
            color: #f59e0b;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Analytics Report</h1>
        <p>BrightSphere University - Instructor Analytics Dashboard</p>
    </div>

    <div class="info-box">
        <div class="info-grid">
            <div class="info-item">
                <label>INSTRUCTOR</label>
                <span>{{ $stats['instructor'] }}</span>
            </div>
            <div class="info-item">
                <label>EMAIL</label>
                <span>{{ $stats['instructor_email'] }}</span>
            </div>
            <div class="info-item">
                <label>EXPORT DATE</label>
                <span>{{ $stats['export_date'] }}</span>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Courses</h3>
            <div class="value">{{ $stats['total_courses'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Total Enrolled</h3>
            <div class="value">{{ $stats['total_enrolled'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Average Grade</h3>
            <div class="value">{{ $stats['avg_grade'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Passing Rate</h3>
            <div class="value">{{ $stats['passing_rate'] }}%</div>
        </div>
    </div>

    <div class="section-title">Course Analytics</div>

    @foreach($courses as $courseData)
    <div class="course-card">
        <div class="course-header">
            <h4>{{ $courseData['course']->name }}</h4>
            <p>{{ $courseData['course']->code }} | {{ $courseData['course']->class_code ?? 'No Class Code' }} | {{ $courseData['course']->class_name ?? 'No Class Name' }}</p>
            @if($courseData['course']->schedule)
                <p style="margin-top: 3px;"><strong>Schedule:</strong> {{ $courseData['course']->schedule }}</p>
            @endif
        </div>
        <div class="course-stats">
            <div><strong>Total Students:</strong> {{ $courseData['total_students'] }}</div>
            <div><strong>Average Grade:</strong> {{ $courseData['average_grade'] }}</div>
            <div><strong>Passing Rate:</strong> {{ $courseData['passing_rate'] }}%</div>
            <div><strong>Status:</strong> 
                @if($courseData['course']->status === 'active')
                    <span style="color: #10b981;">Active</span>
                @else
                    <span style="color: #ef4444;">Inactive</span>
                @endif
            </div>
        </div>
        
        @if(count($courseData['students']) > 0)
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Email</th>
                    <th>Prelim</th>
                    <th>Midterm</th>
                    <th>Prefinal</th>
                    <th>Final Exam</th>
                    <th>Final Grade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courseData['students'] as $student)
                <tr>
                    <td>{{ $student['name'] }}</td>
                    <td>{{ $student['student_id'] }}</td>
                    <td>{{ $student['email'] }}</td>
                    <td>{{ $student['prelim'] }}</td>
                    <td>{{ $student['midterm'] }}</td>
                    <td>{{ $student['prefinal'] }}</td>
                    <td>{{ $student['final_exam'] }}</td>
                    <td>
                        @if($student['final_grade'] != 'N/A')
                            <strong>{{ $student['final_grade'] }}</strong>
                        @else
                            {{ $student['final_grade'] }}
                        @endif
                    </td>
                    <td>
                        @if($student['final_grade'] != 'N/A')
                            @if($student['final_grade'] <= 3.0)
                                <span class="badge-pass">Passed</span>
                            @else
                                <span class="badge-fail">Failed</span>
                            @endif
                        @else
                            <span class="badge-pending">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="padding: 15px; text-align: center; color: #9ca3af;">
            No students enrolled in this course.
        </div>
        @endif
    </div>
    @endforeach

    <div class="footer">
        Generated on {{ $generated_at }} | BrightSphere University Analytics System
    </div>
</body>
</html>