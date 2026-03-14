@extends('admin.layouts.master')

@section('title', 'All Notifications')

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">All Notifications</h4>
                    <div class="page-title-right">
                        <form action="{{ route('mark.all.read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light waves-effect">
                                <i class="bx bx-check-double me-1"></i> Mark All as Read
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Type</th>
                                        <th>Notification Details</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($notifications as $notification)
                                    <tr class="{{ $notification->read_at ? 'opacity-75' : 'bg-light-info' }}">
                                        <td>
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle {{ $notification->read_at ? 'bg-secondary' : 'bg-primary' }}">
                                                    <i class="bx bx-cart font-size-16"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="font-size-14 mb-1">
                                                <a href="{{ route('orders.edit', $notification->data['slug']) }}" class="text-dark">
                                                    {{ $notification->data['title'] }}
                                                </a>
                                            </h5>
                                            <p class="text-muted mb-0">{{ $notification->data['message'] }}</p>
                                        </td>
                                        <td>
                                            <div class="text-dark">
                                                <i class="mdi mdi-calendar me-1"></i>
                                                {{ $notification->created_at->format('M d, Y') }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="mdi mdi-clock-outline me-1"></i>
                                                {{ $notification->created_at->format('h:i A') }} ({{ $notification->created_at->diffForHumans() }})
                                            </small>
                                        </td>
                                        <td>
                                            @if($notification->read_at)
                                                <span class="badge badge-soft-secondary">Read</span>
                                            @else
                                                <span class="badge badge-soft-success">New</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.edit', $notification->data['slug']) }}"
                                               class="btn btn-primary btn-sm waves-effect waves-light">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <img src="{{ asset('assets/images/no-data.png') }}" alt="" height="80" class="mb-3 d-block mx-auto">
                                            <h5 class="text-muted">No notifications found</h5>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
