@extends('layouts.admin')

@section('title', 'All Reviews')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Product Reviews</h1>
        <a href="{{ route('admin.reviews.pending') }}" 
           class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
            View Pending Reviews
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Reviews Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form id="bulkActionForm" method="POST">
            @csrf
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <select id="bulkAction" class="px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Bulk Actions</option>
                        <option value="approve">Approve Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" 
                            onclick="executeBulkAction()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Apply
                    </button>
                </div>
                <div class="text-sm text-gray-600">
                    Total: {{ $reviews->total() }} reviews
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reviewer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rating
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Review
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <tr>
                            <td class="px-6 py-4">
                                <input type="checkbox" name="review_ids[]" value="{{ $review->id }}" class="review-checkbox rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($review->product->image)
                                        <img src="{{ Storage::url($review->product->image) }}" 
                                             alt="{{ $review->product->name }}"
                                             class="w-10 h-10 rounded object-cover mr-3">
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($review->product->name, 30) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $review->reviewer_name }}</div>
                                @if($review->is_verified_purchase)
                                    <span class="text-xs text-green-600">Verified Purchase</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ Str::limit($review->review, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($review->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @elseif($review->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $review->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                <a href="{{ route('admin.reviews.show', $review->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">View</a>
                                
                                @if($review->status === 'pending')
                                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">Reject</button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this review?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No reviews found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.review-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Bulk action execution
    function executeBulkAction() {
        const action = document.getElementById('bulkAction').value;
        const form = document.getElementById('bulkActionForm');
        const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');

        if (!action) {
            alert('Please select an action');
            return;
        }

        if (checkedBoxes.length === 0) {
            alert('Please select at least one review');
            return;
        }

        if (action === 'approve') {
            form.action = '{{ route("admin.reviews.bulk-approve") }}';
        } else if (action === 'delete') {
            if (!confirm('Are you sure you want to delete selected reviews?')) {
                return;
            }
            form.action = '{{ route("admin.reviews.bulk-delete") }}';
        }

        form.submit();
    }
</script>
@endpush
@endsection
