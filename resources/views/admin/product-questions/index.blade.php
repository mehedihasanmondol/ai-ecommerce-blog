@extends('layouts.admin')

@section('title', 'Product Questions Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Product Questions & Answers</h1>
            <p class="text-gray-600 mt-1">Manage and moderate customer questions</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filters Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.product-questions.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Questions</label>
                    <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search by question text..." value="{{ request('search') }}">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.product-questions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-redo mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Questions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Questions List</h3>
                <p class="text-sm text-gray-500 mt-1">
                    @if(request('status'))
                        Showing: <span class="font-medium">{{ ucfirst(request('status')) }}</span> questions
                    @else
                        Showing: <span class="font-medium">All</span> questions
                    @endif
                    ({{ $questions->total() }} total)
                </p>
            </div>
        </div>
        <div class="p-6">
            @if($questions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asked By</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answers</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Helpful</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($questions as $question)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $question->id }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        <a href="{{ route('products.show', $question->product->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            {{ Str::limit($question->product->name, 30) }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">{{ Str::limit($question->question, 50) }}</td>
                                    <td class="px-4 py-4 text-sm">
                                        <div class="text-gray-900">{{ $question->author_name }}</div>
                                        @if($question->user)
                                            <div class="text-xs text-gray-500">Registered User</div>
                                        @else
                                            <div class="text-xs text-gray-500">Guest</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($question->status == 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @elseif($question->status == 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $question->answer_count }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        <i class="fas fa-thumbs-up text-green-600"></i> {{ $question->helpful_count }}
                                        <i class="fas fa-thumbs-down text-red-600 ml-2"></i> {{ $question->not_helpful_count }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $question->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center gap-1">
                                            @if($question->status == 'pending')
                                                <form action="{{ route('admin.questions.approve', $question->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.questions.reject', $question->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.product-questions.show', $question->id) }}" class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.product-questions.destroy', $question->id) }}" method="POST" class="inline" id="delete-question-{{ $question->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="window.dispatchEvent(new CustomEvent('confirm-modal', { 
                                                            detail: { 
                                                                title: 'Delete Question', 
                                                                message: 'Are you sure you want to delete this question and all its answers? This action cannot be undone.',
                                                                onConfirm: () => document.getElementById('delete-question-{{ $question->id }}').submit()
                                                            } 
                                                        }))" 
                                                        class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-question-circle text-6xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 text-lg">No questions found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
