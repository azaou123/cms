<!-- resources/views/settings/layout.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Club Settings
                </div>
                <div class="card-body p-0">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('settings.general') ? 'active bg-primary text-white' : '' }}" href="{{ route('settings.general') }}">
                                <i class="fas fa-cog fa-fw mr-2"></i> General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('settings.social-media') ? 'active bg-primary text-white' : '' }}" href="{{ route('settings.social-media') }}">
                                <i class="fas fa-share-alt fa-fw mr-2"></i> Social Media
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('settings.appearance') ? 'active bg-primary text-white' : '' }}" href="{{ route('settings.appearance') }}">
                                <i class="fas fa-palette fa-fw mr-2"></i> Appearance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('settings.system') ? 'active bg-primary text-white' : '' }}" href="{{ route('settings.system') }}">
                                <i class="fas fa-sliders-h fa-fw mr-2"></i> System Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('settings.notifications') ? 'active bg-primary text-white' : '' }}" href="{{ route('settings.notifications') }}">
                                <i class="fas fa-bell fa-fw mr-2"></i> Notifications
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @yield('settings-content')
        </div>
    </div>
</div>
@endsection