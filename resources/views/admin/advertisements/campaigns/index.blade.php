@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ad Campaigns</h1>
                <p class="mt-1 text-sm text-gray-600">Manage advertisement campaigns and track performance</p>
            </div>
            @if(auth()->user()->hasPermission('advertisements.create'))
            <a href="{{ route('admin.advertisements.campaigns.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Create Campaign
            </a>
            @endif
        </div>
    </div>

    {{-- Statistics Cards --}}
    @php
    $campaigns = \App\Modules\Advertisement\Models\AdCampaign::with(['impressions', 'clicks'])->get();
    $statistics = [
    'total' => $campaigns->count(),
    'active' => $campaigns->where('status', 'active')->count(),
    'paused' => $campaigns->where('status', 'paused')->count(),
    'total_impressions' => $campaigns->sum(fn($c) => $c->getTotalImpressions()),
    'total_clicks' => $campaigns->sum(fn($c) => $c->getTotalClicks()),
    ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-bullhorn text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-pause-circle text-2xl text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Paused</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['paused'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-eye text-2xl text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Impressions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['total_impressions']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-mouse-pointer text-2xl text-indigo-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Clicks</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['total_clicks']) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Campaigns Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Campaign
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Schedule
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Performance
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Priority
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaigns as $campaign)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $campaign->name }}</div>
                            @if($campaign->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($campaign->description, 60) }}</div>
                            @endif
                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                <span title="Creatives">
                                    <i class="fas fa-image"></i> {{ $campaign->creatives->count() }}
                                </span>
                                <span title="Ad Slots">
                                    <i class="fas fa-th-large"></i> {{ $campaign->slots->count() }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                        $statusColors = [
                        'active' => 'green',
                        'paused' => 'yellow',
                        'scheduled' => 'blue',
                        'completed' => 'gray'
                        ];
                        $color = $statusColors[$campaign->status] ?? 'gray';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                            {{ ucfirst($campaign->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div>
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ $campaign->start_date->format('M d, Y') }}
                        </div>
                        @if($campaign->end_date)
                        <div class="text-xs text-gray-500">
                            to {{ $campaign->end_date->format('M d, Y') }}
                        </div>
                        @else
                        <div class="text-xs text-gray-500">No end date</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            <div class="text-gray-900">
                                <i class="fas fa-eye text-purple-600 mr-1"></i>
                                {{ number_format($campaign->getTotalImpressions()) }}
                            </div>
                            <div class="text-gray-600">
                                <i class="fas fa-mouse-pointer text-indigo-600 mr-1"></i>
                                {{ number_format($campaign->getTotalClicks()) }}
                            </div>
                            <div class="text-xs font-semibold {{ $campaign->getCTR() > 2 ? 'text-green-600' : 'text-gray-500' }}">
                                CTR: {{ $campaign->getCTR() }}%
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                            {{ $campaign->priority }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            @if(auth()->user()->hasPermission('advertisements.analytics'))
                            <a href="{{ route('admin.advertisements.analytics.campaign', $campaign) }}"
                                class="text-purple-600 hover:text-purple-900"
                                title="Analytics">
                                <i class="fas fa-chart-line"></i>
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('advertisements.edit'))
                            <a href="{{ route('admin.advertisements.campaigns.edit', $campaign) }}"
                                class="text-indigo-600 hover:text-indigo-900"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('advertisements.delete'))
                            <form id="delete-campaign-{{ $campaign->id }}"
                                action="{{ route('admin.advertisements.campaigns.destroy', $campaign) }}"
                                method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('confirm-modal', { 
                                            detail: { 
                                                title: 'Delete Campaign', 
                                                message: 'Are you sure you want to delete this campaign? All associated creatives and tracking data will be permanently removed.',
                                                onConfirm: () => document.getElementById('delete-campaign-{{ $campaign->id }}').submit()
                                            } 
                                        }))"
                                    class="text-red-600 hover:text-red-900"
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No campaigns found.</p>
                        @if(auth()->user()->hasPermission('advertisements.create'))
                        <a href="{{ route('admin.advertisements.campaigns.create') }}"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Create First Campaign
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection