<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bright Sphere • Students</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f5f7fb; }
        .sidebar { background: linear-gradient(180deg, #1a1c2e 0%, #2d2f42 100%); transition: all 0.3s ease; }
        .nav-item { transition: all 0.3s ease; border-left: 3px solid transparent; }
        .nav-item:hover { background: rgba(255, 255, 255, 0.1); border-left-color: #6366f1; }
        .nav-item.active { background: rgba(99, 102, 241, 0.15); border-left-color: #6366f1; }
        .nav-item.active i, .nav-item.active span { color: #6366f1; }
        .table-row-hover:hover { background: rgba(99, 102, 241, 0.05); }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #818cf8; }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="sidebar w-72 flex-shrink-0 hidden md:flex flex-col text-white shadow-2xl">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                        <i class="fa-solid fa-bolt text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight">Bright Sphere</h1>
                        <p class="text-xs text-indigo-300 font-semibold uppercase tracking-wider mt-1">Student Portal</p>
                    </div>
                </div>
            </div>

            <div class="px-6 mb-8">
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-lg">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                            <p class="text-xs text-indigo-300 flex items-center gap-1 mt-1">
                                <i class="fa-solid fa-graduation-cap"></i>
                                {{ ucfirst(auth()->user()->role) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 px-4 space-y-2">
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider px-4 mb-2">Menu</p>
                
                <!-- Dashboard - Fixed route -->
                <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie w-6 text-lg"></i><span class="font-medium">Dashboard</span>
                </a>

                <!-- Classes/Courses - Fixed route -->
                <a href="{{ route('student.courses') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('student.courses*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user w-6 text-lg"></i><span class="font-medium">Courses</span>
                </a>

                <!-- Registration (changed from Grades) - Fixed route -->
                <a href="{{ route('student.registration') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('student.registration') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-list w-6 text-lg"></i><span class="font-medium">Registration</span>
                </a>

                <div class="border-t border-white/10 my-4"></div>
                
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider px-4 mb-2">Management</p>
                
                <!-- Students - Fixed route -->
                <a href="{{ route('students.index') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('students.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate w-6 text-lg"></i><span class="font-medium">Students</span>
                </a>
            </div>

            <div class="p-6 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item flex items-center gap-4 px-4 py-3 w-full rounded-xl text-red-300 hover:text-red-200 transition">
                        <i class="fa-solid fa-right-from-bracket w-6 text-lg"></i><span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Navigation - Fixed -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
            <div class="flex justify-around p-3">
                <a href="{{ route('dashboard') }}" class="text-indigo-600"><i class="fa-solid fa-chart-pie text-xl"></i></a>
                <a href="{{ route('student.courses') }}" class="text-gray-400"><i class="fa-solid fa-chalkboard-user text-xl"></i></a>
                <a href="{{ route('student.registration') }}" class="text-gray-400"><i class="fa-solid fa-clipboard-list text-xl"></i></a>
                <a href="{{ route('students.index') }}" class="text-gray-400"><i class="fa-solid fa-user-graduate text-xl"></i></a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
                <div class="px-8 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold gradient-text">STUDENT MANAGEMENT</h2>
                    <div class="flex items-center gap-6">
                        <button class="text-gray-500 hover:text-indigo-600 transition"><i class="fa-solid fa-magnifying-glass text-lg"></i></button>
                        <div class="text-sm text-gray-500 border-l border-gray-200 pl-6">
                            <i class="fa-regular fa-calendar mr-2"></i>{{ now()->format('l, F j, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600"><i class="fa-solid fa-check"></i></div>
                            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600"><i class="fa-solid fa-exclamation-triangle"></i></div>
                            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
                        <ul class="list-disc list-inside text-red-600">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Home / Students</p>
                            <h1 class="text-3xl font-extrabold text-slate-900">Student Directory</h1>
                            <p class="text-slate-500 mt-1">View student profiles, enrollment status, and assigned classes.</p>
                        </div>
                        <div>
                            <button onclick="openAddStudentModal()" class="inline-flex items-center gap-2 border border-indigo-600 text-indigo-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-50 transition">
                                <i class="fa-solid fa-plus"></i> Add Student
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-4 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h2 class="text-lg font-semibold">All Students</h2>
                            <p class="text-sm text-gray-500">Currently showing {{ $studentData ? count($studentData) : 0 }} students.</p>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="searchInput" placeholder="Search students..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                            <button class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-indigo-700"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm border-collapse">
                            <thead class="bg-gray-100 text-xs uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="px-4 py-3">Student ID</th>
                                    <th class="px-4 py-3">Student</th>
                                    <th class="px-4 py-3">Program</th>
                                    <th class="px-4 py-3">Year Level</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
                                @forelse($studentData ?? [] as $student)
                                <tr class="table-row-hover border-b border-gray-100">
                                    <td class="px-4 py-3"><span class="text-sm font-mono text-indigo-600">{{ $student['student_id'] }}</span></td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-{{ $student['avatar_color'] }}-100 flex items-center justify-center text-{{ $student['avatar_color'] }}-600 font-bold text-sm mr-2">
                                                {{ $student['initial'] }}
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $student['fullname'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $student['program'] }}</td>
                                    <td class="px-4 py-3">{{ $student['year_level'] }}</td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs px-2 py-1 rounded-full bg-{{ $student['status_color'] }}-100 text-{{ $student['status_color'] }}-700">
                                            {{ $student['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button onclick="editStudent({{ $student['id'] }})" class="text-indigo-600 hover:text-indigo-800 mr-2 transition" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button onclick="deleteStudent({{ $student['id'] }})" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No students yet. Click "Add Student" to get started.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl shadow p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase text-indigo-500">Enrollment</span>
                            <i class="fa-solid fa-users text-indigo-500"></i>
                        </div>
                        <p class="mt-3 text-3xl font-bold">{{ $totalStudents ?? 0 }}</p>
                        <p class="text-gray-500 text-sm mt-1">Total students</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase text-green-500">Active</span>
                            <i class="fa-solid fa-circle-check text-green-500"></i>
                        </div>
                        <p class="mt-3 text-3xl font-bold">{{ $activeStudents ?? 0 }}</p>
                        <p class="text-gray-500 text-sm mt-1">Currently active</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase text-red-500">Inactive</span>
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        </div>
                        <p class="mt-3 text-3xl font-bold">{{ $inactiveStudents ?? 0 }}</p>
                        <p class="text-gray-500 text-sm mt-1">Inactive students</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 sticky top-0 bg-white">
                <h3 class="text-xl font-bold text-gray-900">Add New Student</h3>
                <button onclick="closeAddStudentModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form method="POST" action="{{ route('students.store') }}">
                @csrf
                <div class="space-y-3">
                    <div><input type="text" name="first_name" placeholder="First Name *" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    <div><input type="text" name="middle_name" placeholder="Middle Name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    <div><input type="text" name="last_name" placeholder="Last Name *" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    <div><input type="email" name="email" placeholder="Email *" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    <div><input type="tel" name="phone" placeholder="Phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    
                    <div>
                        <select name="program" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">Select Program</option>
                            @foreach($programs ?? [] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="year_level" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">Select Year Level</option>
                            @foreach($yearLevels ?? [] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeAddStudentModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Add Student</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editStudentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 sticky top-0 bg-white">
                <h3 class="text-xl font-bold text-gray-900">Edit Student</h3>
                <button onclick="closeEditStudentModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form id="editStudentForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_student_id">
                <div class="space-y-3">
                    <div><input type="text" name="first_name" id="edit_first_name" placeholder="First Name *" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    <div><input type="text" name="middle_name" id="edit_middle_name" placeholder="Middle Name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    <div><input type="text" name="last_name" id="edit_last_name" placeholder="Last Name *" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    <div><input type="email" name="email" id="edit_email" placeholder="Email *" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    <div><input type="tel" name="phone" id="edit_phone" placeholder="Phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"></div>
                    
                    <div>
                        <select name="program" id="edit_program" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">Select Program</option>
                            @foreach($programs ?? [] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="year_level" id="edit_year_level" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">Select Year Level</option>
                            @foreach($yearLevels ?? [] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="status" id="edit_status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditStudentModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Update Student</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddStudentModal() { 
            document.getElementById('addStudentModal').style.display = 'flex'; 
        }
        function closeAddStudentModal() { 
            document.getElementById('addStudentModal').style.display = 'none'; 
        }
        function openEditStudentModal() { 
            document.getElementById('editStudentModal').style.display = 'flex'; 
        }
        function closeEditStudentModal() { 
            document.getElementById('editStudentModal').style.display = 'none'; 
        }
        
        function editStudent(id) {
            fetch(`/students/${id}/edit-data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_student_id').value = data.id;
                    document.getElementById('edit_first_name').value = data.first_name || '';
                    document.getElementById('edit_middle_name').value = data.middle_name || '';
                    document.getElementById('edit_last_name').value = data.last_name || '';
                    document.getElementById('edit_email').value = data.email || '';
                    document.getElementById('edit_phone').value = data.phone || '';
                    document.getElementById('edit_program').value = data.program || '';
                    document.getElementById('edit_year_level').value = data.year_level || '';
                    document.getElementById('edit_status').value = data.status || 'Active';
                    document.getElementById('editStudentForm').action = `/students/${data.id}`;
                    openEditStudentModal();
                })
                .catch(error => { 
                    console.error('Error:', error); 
                    alert('Could not load student data'); 
                });
        }
        
        function deleteStudent(id) {
            if(confirm('Are you sure you want to delete this student?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/students/${id}`;
                const csrf = document.createElement('input'); 
                csrf.name = '_token'; 
                csrf.value = '{{ csrf_token() }}';
                const method = document.createElement('input'); 
                method.name = '_method'; 
                method.value = 'DELETE';
                form.appendChild(csrf); 
                form.appendChild(method);
                document.body.appendChild(form); 
                form.submit();
            }
        }
        
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('#studentTableBody tr').forEach(row => {
                if (row.cells && row.cells.length > 0) {
                    let text = '';
                    for (let i = 0; i < row.cells.length - 1; i++) {
                        text += row.cells[i].innerText.toLowerCase() + ' ';
                    }
                    row.style.display = text.includes(val) ? '' : 'none';
                }
            });
        });
        
        window.onclick = function(event) {
            if(event.target.classList && event.target.classList.contains('fixed') && event.target.id === 'addStudentModal') closeAddStudentModal();
            if(event.target.classList && event.target.classList.contains('fixed') && event.target.id === 'editStudentModal') closeEditStudentModal();
        }
        
        setTimeout(function() { 
            document.querySelectorAll('.bg-green-50, .bg-red-50').forEach(el => el.style.display = 'none'); 
        }, 5000);
    </script>
</body>
</html>