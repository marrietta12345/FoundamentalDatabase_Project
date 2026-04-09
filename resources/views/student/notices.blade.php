@extends('layouts.app')

@section('title', 'Notices & Announcements')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            Notices & Announcements
        </h1>
        <p class="text-gray-600 mt-1">Stay updated with the latest university announcements</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Notices</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ count($notices) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-bullhorn text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Important</p>
                    <p class="text-3xl font-bold text-red-600">
                        {{ collect($notices)->where('type', 'important')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Events</p>
                    <p class="text-3xl font-bold text-purple-600">
                        {{ collect($notices)->where('type', 'event')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-calendar-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Announcements</p>
                    <p class="text-3xl font-bold text-green-600">
                        {{ collect($notices)->where('type', 'announcement')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-bell text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notices List -->
    <div class="space-y-4">
        @foreach($notices as $notice)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-{{ $notice['color'] }}-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid {{ $notice['icon'] }} text-{{ $notice['color'] }}-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $notice['title'] }}</h3>
                            @if($notice['type'] == 'important')
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Important</span>
                            @elseif($notice['type'] == 'event')
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">Event</span>
                            @elseif($notice['type'] == 'academic')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Academic</span>
                            @elseif($notice['type'] == 'scholarship')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Scholarship</span>
                            @elseif($notice['type'] == 'exam')
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">Exam</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Announcement</span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm mb-3">{{ $notice['message'] }}</p>
                        <div class="flex items-center gap-4 text-xs text-gray-400">
                            <span><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($notice['date'])->format('F d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection