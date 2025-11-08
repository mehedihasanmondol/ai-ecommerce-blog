@extends('layouts.admin')

@section('title', 'Question Details')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Question Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.product-questions.index') }}">Questions</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.product-questions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Question Card -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Question</h6>
                    @if($question->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($question->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="mb-3">{{ $question->question }}</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Asked by:</strong> {{ $question->author_name }}</p>
                            @if($question->user)
                                <p class="mb-1"><small class="text-muted">Registered User</small></p>
                            @else
                                <p class="mb-1"><small class="text-muted">Guest ({{ $question->user_email }})</small></p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Date:</strong> {{ $question->created_at->format('M d, Y h:i A') }}</p>
                            <p class="mb-1"><strong>Product:</strong> 
                                <a href="{{ route('products.show', $question->product->slug) }}" target="_blank">
                                    {{ $question->product->name }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <span class="me-3">
                            <i class="fas fa-thumbs-up text-success"></i> {{ $question->helpful_count }} Helpful
                        </span>
                        <span>
                            <i class="fas fa-thumbs-down text-danger"></i> {{ $question->not_helpful_count }} Not Helpful
                        </span>
                    </div>

                    @if($question->status == 'pending')
                        <div class="btn-group" role="group">
                            <form action="{{ route('admin.questions.approve', $question->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Approve Question
                                </button>
                            </form>
                            <form action="{{ route('admin.questions.reject', $question->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-times me-1"></i> Reject Question
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Answers Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Answers ({{ $question->answers->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($question->answers as $answer)
                        <div class="border rounded p-3 mb-3 {{ $answer->is_best_answer ? 'border-primary bg-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>{{ $answer->author_name }}</strong>
                                    @if($answer->is_verified_purchase)
                                        <span class="badge bg-success ms-2">
                                            <i class="fas fa-check-circle"></i> Verified Purchase
                                        </span>
                                    @endif
                                    @if($answer->is_best_answer)
                                        <span class="badge bg-primary ms-2">
                                            <i class="fas fa-star"></i> Best Answer
                                        </span>
                                    @endif
                                    @if($answer->status == 'pending')
                                        <span class="badge bg-warning ms-2">Pending</span>
                                    @elseif($answer->status == 'rejected')
                                        <span class="badge bg-danger ms-2">Rejected</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $answer->created_at->diffForHumans() }}</small>
                            </div>
                            
                            <p class="mb-2">{{ $answer->answer }}</p>
                            
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-3">
                                    <i class="fas fa-thumbs-up text-success"></i> {{ $answer->helpful_count }}
                                </span>
                                <span>
                                    <i class="fas fa-thumbs-down text-danger"></i> {{ $answer->not_helpful_count }}
                                </span>
                            </div>

                            <div class="btn-group btn-group-sm" role="group">
                                @if($answer->status == 'pending')
                                    <form action="{{ route('admin.answers.approve', $answer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.answers.reject', $answer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                @endif
                                @if(!$answer->is_best_answer && $answer->status == 'approved')
                                    <form action="{{ route('admin.answers.best', $answer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-star"></i> Mark as Best
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No answers yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total Answers</span>
                            <strong>{{ $question->answer_count }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Approved Answers</span>
                            <strong>{{ $question->answers->where('status', 'approved')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Pending Answers</span>
                            <strong>{{ $question->answers->where('status', 'pending')->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('products.show', $question->product->slug) }}#qa" target="_blank" class="btn btn-info btn-sm w-100 mb-2">
                        <i class="fas fa-external-link-alt me-1"></i> View on Frontend
                    </a>
                    <form action="{{ route('admin.product-questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question and all its answers?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-trash me-1"></i> Delete Question
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
