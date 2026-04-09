@extends('layouts.instructor')

@section('title', 'Add New Grade')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add New Grade</h1>
                <p class="text-gray-600 mt-1">Record grades for students in your courses</p>
            </div>
            <a href="{{ route('instructor.grades.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Grades
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
        <div class="flex items-center gap-2 text-red-600 mb-2">
            <i class="fa-solid fa-exclamation-triangle"></i>
            <strong class="text-sm">Please fix the following errors:</strong>
        </div>
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('instructor.grades.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Student Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Student *</label>
                <select name="student_id" id="student_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('student_id') border-red-500 @enderror">
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" data-student-name="{{ $student->first_name }} {{ $student->last_name }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id ?? $student->id }})
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Course Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course *</label>
                <select name="course_id" id="course_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('course_id') border-red-500 @enderror">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-course-name="{{ $course->name }}" data-course-code="{{ $course->code }}" data-class-name="{{ $course->class_name }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code }}) - {{ $course->class_name ?? 'No Class' }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject/Class (Auto-filled) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Class/Subject *</label>
                <input type="text" 
                       name="subject" 
                       id="subject"
                       value="{{ old('subject') }}" 
                       required
                       readonly
                       placeholder="Will be auto-filled based on selection"
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('subject') border-red-500 @enderror">
                @error('subject')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Class and subject information will be automatically loaded</p>
            </div>

            <!-- Class Name (Auto-filled) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Class Section</label>
                <input type="text" 
                       name="class_name" 
                       id="class_name"
                       value="{{ old('class_name') }}" 
                       readonly
                       placeholder="Will be auto-filled based on course selection"
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('class_name') border-red-500 @enderror">
                @error('class_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Class section will be automatically loaded</p>
            </div>

            <!-- Grade Components - Philippine System (1.0 - 5.0) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prelim Grade</label>
                <input type="number" 
                       name="prelim" 
                       id="prelim"
                       value="{{ old('prelim') }}" 
                       step="0.01" 
                       min="1.0" 
                       max="5.0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('prelim') border-red-500 @enderror"
                       oninput="calculateFinalGrade()">
                @error('prelim')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter grade between 1.0 - 5.0</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Midterm Grade</label>
                <input type="number" 
                       name="midterm" 
                       id="midterm"
                       value="{{ old('midterm') }}" 
                       step="0.01" 
                       min="1.0" 
                       max="5.0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('midterm') border-red-500 @enderror"
                       oninput="calculateFinalGrade()">
                @error('midterm')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter grade between 1.0 - 5.0</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prefinal Grade</label>
                <input type="number" 
                       name="prefinal" 
                       id="prefinal"
                       value="{{ old('prefinal') }}" 
                       step="0.01" 
                       min="1.0" 
                       max="5.0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('prefinal') border-red-500 @enderror"
                       oninput="calculateFinalGrade()">
                @error('prefinal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter grade between 1.0 - 5.0</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Final Exam Grade</label>
                <input type="number" 
                       name="final" 
                       id="final"
                       value="{{ old('final') }}" 
                       step="0.01" 
                       min="1.0" 
                       max="5.0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('final') border-red-500 @enderror"
                       oninput="calculateFinalGrade()">
                @error('final')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter grade between 1.0 - 5.0</p>
            </div>
        </div>

        <!-- Calculated Final Grade Display -->
        <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-800">Calculated Final Grade</p>
                    <p class="text-3xl font-bold text-indigo-600" id="calculatedFinalGrade">-</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-indigo-800">Status</p>
                    <p class="text-xl font-bold" id="gradeStatus">-</p>
                </div>
            </div>
            <p class="text-xs text-indigo-600 mt-2">* Final grade is automatically calculated as the average of all entered grades</p>
        </div>

        <div class="mt-8">
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-info-circle text-blue-600 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Philippine Grading System:</p>
                        <div class="grid grid-cols-2 gap-4 mt-2 text-sm">
                            <div>
                                <p class="font-semibold text-blue-800">✅ Passing Grades (1.0 - 3.0):</p>
                                <ul class="text-blue-700 mt-1 space-y-1">
                                    <li>• 1.0 - Excellent</li>
                                    <li>• 1.25 - Very Good</li>
                                    <li>• 1.5 - Good</li>
                                    <li>• 1.75 - Very Satisfactory</li>
                                    <li>• 2.0 - Satisfactory</li>
                                    <li>• 2.25 - Fairly Satisfactory</li>
                                    <li>• 2.5 - Fair</li>
                                    <li>• 2.75 - Passing</li>
                                    <li>• 3.0 - Pass (Minimum Passing Grade)</li>
                                </ul>
                            </div>
                            <div>
                                <p class="font-semibold text-blue-800">❌ Failing Grades (Above 3.0):</p>
                                <ul class="text-blue-700 mt-1 space-y-1">
                                    <li>• 3.25 - Conditional/Fail</li>
                                    <li>• 3.5 - Fail</li>
                                    <li>• 4.0 - Fail</li>
                                    <li>• 5.0 - Failed</li>
                                    <li>• INC - Incomplete</li>
                                    <li>• W - Withdrawn</li>
                                    <li>• DRP - Dropped</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-3 p-2 bg-yellow-100 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <strong>⚠️ Important:</strong> 
                                <span class="font-semibold">Grades 1.0 to 3.0 = PASSING</span> | 
                                <span class="font-semibold">Grades above 3.0 (3.25, 3.5, 4.0, 5.0) = FAILING</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-8">
            <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2 shadow-md">
                <i class="fa-solid fa-save"></i>
                Save Grade
            </button>
            <a href="{{ route('instructor.grades.index') }}" 
               class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-semibold transition text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Auto-fill subject and class when course is selected
    document.getElementById('course_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const courseName = selectedOption.getAttribute('data-course-name') || '';
        const courseCode = selectedOption.getAttribute('data-course-code') || '';
        const className = selectedOption.getAttribute('data-class-name') || '';
        
        // Set subject field
        if (courseName && courseCode) {
            document.getElementById('subject').value = `${courseName} (${courseCode})`;
        } else if (courseName) {
            document.getElementById('subject').value = courseName;
        } else {
            document.getElementById('subject').value = '';
        }
        
        // Set class name field
        document.getElementById('class_name').value = className || '';
    });
    
    // Auto-fill subject when student is selected (optional - if student has specific class)
    document.getElementById('student_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const studentName = selectedOption.getAttribute('data-student-name') || '';
        
        // You can add additional logic here to load student-specific class
        console.log('Selected student:', studentName);
    });
    
    // Calculate final grade based on all grade components
    function calculateFinalGrade() {
        const prelim = parseFloat(document.getElementById('prelim').value) || 0;
        const midterm = parseFloat(document.getElementById('midterm').value) || 0;
        const prefinal = parseFloat(document.getElementById('prefinal').value) || 0;
        const final = parseFloat(document.getElementById('final').value) || 0;
        
        let count = 0;
        let total = 0;
        
        if (prelim > 0) { total += prelim; count++; }
        if (midterm > 0) { total += midterm; count++; }
        if (prefinal > 0) { total += prefinal; count++; }
        if (final > 0) { total += final; count++; }
        
        let finalGrade = '-';
        let status = '-';
        let statusColor = 'text-gray-600';
        
        if (count > 0) {
            finalGrade = (total / count).toFixed(2);
            
            // Determine status based on Philippine grading system
            if (finalGrade <= 3.0) {
                status = '✅ PASSING';
                statusColor = 'text-green-600';
                
                // Add remarks based on grade
                if (finalGrade <= 1.0) status += ' - Excellent';
                else if (finalGrade <= 1.25) status += ' - Very Good';
                else if (finalGrade <= 1.5) status += ' - Good';
                else if (finalGrade <= 1.75) status += ' - Very Satisfactory';
                else if (finalGrade <= 2.0) status += ' - Satisfactory';
                else if (finalGrade <= 2.25) status += ' - Fairly Satisfactory';
                else if (finalGrade <= 2.5) status += ' - Fair';
                else if (finalGrade <= 2.75) status += ' - Passing';
                else if (finalGrade <= 3.0) status += ' - Pass';
            } else {
                status = '❌ FAILING';
                statusColor = 'text-red-600';
                
                if (finalGrade <= 3.25) status += ' - Conditional';
                else if (finalGrade <= 3.5) status += ' - Failed';
                else if (finalGrade <= 4.0) status += ' - Poor';
                else status += ' - Failed';
            }
        }
        
        document.getElementById('calculatedFinalGrade').textContent = finalGrade;
        document.getElementById('gradeStatus').innerHTML = status;
        document.getElementById('gradeStatus').className = `text-xl font-bold ${statusColor}`;
        
        // Optional: Set a hidden input for final grade if you want to save it
        if (document.getElementById('final_grade')) {
            document.getElementById('final_grade').value = finalGrade !== '-' ? finalGrade : '';
        }
    }
    
    // Trigger calculation when page loads if there are existing values
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger change event on course select to auto-fill subject
        const courseSelect = document.getElementById('course_id');
        if (courseSelect.value) {
            const event = new Event('change');
            courseSelect.dispatchEvent(event);
        }
        
        // Calculate initial final grade if any grades are pre-filled
        calculateFinalGrade();
    });
    
    // Add input event listeners to all grade inputs
    const gradeInputs = ['prelim', 'midterm', 'prefinal', 'final'];
    gradeInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', calculateFinalGrade);
        }
    });
</script>
@endsection