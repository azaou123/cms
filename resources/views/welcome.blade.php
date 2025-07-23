@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-8 text-center">
            {{-- 🏷️ Title --}}
            <h1 class="display-4 fw-bold mb-3">Welcome to Club Management System</h1>
            <p class="lead mb-5 text-muted">
                Manage your club members, projects, and cells with ease.
            </p>

            {{-- ✨ Buttons --}}
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">
                    <i class="fas fa-user-plus me-2"></i> Register
                </a>
            </div>
        </div>
    </div>
</div>
@endsection