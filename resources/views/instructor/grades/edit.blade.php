@extends('layouts.instructor')

@section('title', 'Edit Grade')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Grade</h1>
                <p class="text-gray-600 mt-1">Update grade for {{ $grade->student->first_name }} {{ $grade->student->last_name }} in {{ $grade->course->name }}</p>
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

    <form method="POST" action="{{ route('instructor.grades.update', $grade->id) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Student Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Student *</label>
                <select name="student_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('student_id') border-red-500 @enderror">
                    <option value="">Select Student</option>
                    @foreach($students ?? [] as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $grade->student_id) == $student->id ? 'selected' : '' }}>
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
                <select name="course_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('course_id') border-red-500 @enderror">
                    <option value="">Select Course</option>
                    @foreach($courses ?? [] as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $grade->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code }})
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject Field -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                <input type="text" 
                       name="subject" 
                       value="{{ old('subject', $grade->subject) }}" 
                       required
                       placeholder="e.g., Information Technology, Mathematics, etc."
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('subject') border-red-500 @enderror">
                @error('subject')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter the subject name</p>
            </div>

            <!-- Class Name Dropdown -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Class Name</label>
                <select name="class_name" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('class_name') border-red-500 @enderror">
                    <option value="">Select a class...</option>
                    <option value="THEO 110" {{ old('class_name', $grade->class_name) == 'THEO 110' ? 'selected' : '' }}>THEO 110</option>
                    <option value="THEP 120" {{ old('class_name', $grade->class_name) == 'THEP 120' ? 'selected' : '' }}>THEP 120</option>
                    <option value="THEO 130" {{ old('class_name', $grade->class_name) == 'THEO 130' ? 'selected' : '' }}>THEO 130</option>
                    <option value="BRIDG" {{ old('class_name', $grade->class_name) == 'BRIDG' ? 'selected' : '' }}>BRIDG</option>
                    <option value="Mathematics in the Modern World" {{ old('class_name', $grade->class_name) == 'Mathematics in the Modern World' ? 'selected' : '' }}>Mathematics in the Modern World</option>
                    <option value="BRIG2" {{ old('class_name', $grade->class_name) == 'BRIG2' ? 'selected' : '' }}>BRIG2</option>
                    <option value="PATHFIT 1" {{ old('class_name', $grade->class_name) == 'PATHFIT 1' ? 'selected' : '' }}>PATHFIT 1</option>
                    <option value="PATHFIT 2" {{ old('class_name', $grade->class_name) == 'PATHFIT 2' ? 'selected' : '' }}>PATHFIT 2</option>
                    <option value="NSTP 1" {{ old('class_name', $grade->class_name) == 'NSTP 1' ? 'selected' : '' }}>NSTP 1</option>
                    <option value="NSTP 2" {{ old('class_name', $grade->class_name) == 'NSTP 2' ? 'selected' : '' }}>NSTP 2</option>
                    <option value="Philippine Literature" {{ old('class_name', $grade->class_name) == 'Philippine Literature' ? 'selected' : '' }}>Philippine Literature</option>
                    <option value="Purposive Communication" {{ old('class_name', $grade->class_name) == 'Purposive Communication' ? 'selected' : '' }}>Purposive Communication</option>
                    <option value="The Contemporary World" {{ old('class_name', $grade->class_name) == 'The Contemporary World' ? 'selected' : '' }}>The Contemporary World</option>
                    <option value="IC-JEEP 110" {{ old('class_name', $grade->class_name) == 'IC-JEEP 110' ? 'selected' : '' }}>IC-JEEP 110</option>
                    <option value="IC-JEEP 120" {{ old('class_name', $grade->class_name) == 'IC-JEEP 120' ? 'selected' : '' }}>IC-JEEP 120</option>
                </select>
                @error('class_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Optional: Select the specific class section</p>
            </div>
        </div>

        <!-- Grade Components - Philippine System (1.0 - 5.0) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prelim Grade</label>
                <input type="number" 
                       name="prelim" 
                       value="{{ old('prelim', $grade->prelim) }}" 
                       step="0.01" 
                       min="1.0" 
                       max="5.0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('prelim') border-red-500 @enderror">
                @error('prelim')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter grade between 1.0 - 5.0</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Midterm Grade</label>
                <input type="number" 
                       name="midterm" 
                       value="{{ old('midterm', $grade->midterm) }}" 
                       step="0.01" 
                       min="1.0" 
                       max="5.0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('midterm') border-red-500 @enderror">
                @error('midterm')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter grade between 1.0 - 5.0</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prefinal Grade</label>
                <input type="number" 
                       name="prefinal" 
                       value="{{ old('prefinal', $grade->prefinal) }}" 
                       step="0.01" 
                       min="1.0" 
                       max="5.0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('prefinal') border-red-500 @enderror">
                @error('prefinal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter grade between 1.0 - 5.0</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Final Exam Grade</label>
                <input type="number" 
                       name="final" 
                       value="{{ old('final', $grade->final_exam) }}" 
                       step="0.01" 
                       min="1.0" 
                       max="5.0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('final') border-red-500 @enderror">
                @error('final')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter grade between 1.0 - 5.0</p>
            </div>
        </div>

        <!-- Current Final Grade Display -->
        <div class="mt-6 bg-gray-50 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Final Grade</label>
                    @if($grade->final_grade)
                        <p class="text-2xl font-bold {{ $grade->final_grade <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($grade->final_grade, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Status: {{ $grade->final_grade <= 3.0 ? 'Passed' : 'Failed' }}
                        </p>
                    @else
                        <p class="text-2xl font-bold text-yellow-600">Pending</p>
                        <p class="text-xs text-gray-500 mt-1">No final grade calculated yet</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Last updated</p>
                    <p class="text-sm font-medium">{{ $grade->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 mt-6">
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
                                <li>• 1.75 - Satisfactory</li>
                                <li>• 2.0 - Fair</li>
                                <li>• 2.25 - Fair</li>
                                <li>• 2.5 - Pass</li>
                                <li>• 2.75 - Pass</li>
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
                        <p class="text-sm text-blue-800 mt-2">
                            <strong>📊 Final Grade Calculation:</strong> The final grade will be automatically recalculated as the average of all entered grades when you save.<br>
                            A student passes if the final grade is <strong>3.0 or lower</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-8">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-save"></i>
                Update Grade
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
    // Add loading state to update button
    const updateForm = document.querySelector('form');
    const updateButton = updateForm?.querySelector('button[type="submit"]');
    if (updateButton) {
        updateForm.addEventListener('submit', function() {
            updateButton.disabled = true;
            updateButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
        });
    }
    
    // Optional: Auto-calculate final grade preview
    const gradeInputs = document.querySelectorAll('input[name="prelim"], input[name="midterm"], input[name="prefinal"], input[name="final"]');
    const finalGradeDisplay = document.querySelector('.text-2xl.font-bold');
    
    function calculatePreview() {
        let grades = [];
        gradeInputs.forEach(input => {
            if (input.value && input.value !== '') {
                grades.push(parseFloat(input.value));
            }
        });
        
        if (grades.length > 0 && finalGradeDisplay) {
            const average = grades.reduce((a, b) => a + b, 0) / grades.length;
            finalGradeDisplay.textContent = average.toFixed(2);
            finalGradeDisplay.className = `text-2xl font-bold ${average <= 3.0 ? 'text-green-600' : 'text-red-600'}`;
        }
    }
    
    gradeInputs.forEach(input => {
        input.addEventListener('input', calculatePreview);
    });
</script>
@endsection