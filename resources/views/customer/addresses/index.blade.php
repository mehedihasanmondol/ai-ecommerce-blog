@extends('layouts.customer')

@section('title', 'My Addresses')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Addresses</h1>
                <p class="text-gray-600 mt-1">Manage your shipping and billing addresses</p>
            </div>
        </div>
    </div>

    <!-- Addresses List Component -->
    @livewire('customer.address-manager')
</div>
@endsection
