@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Users</h5>
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newUserModal">New User</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- 🔎 Filters --}}
                    <form method="GET" action="{{ route('users') }}" class="row g-3 mb-4" id="filterForm">
                        <div class="col-md-4">
                            <label for="cell" class="form-label">Filter by Cell</label>
                            <select name="cell" id="cell" class="form-select">
                                <option value="">All Cells</option>
                                @foreach ($cells as $cell)
                                    <option value="{{ $cell->id }}" {{ request('cell') == $cell->id ? 'selected' : '' }}>
                                        {{ $cell->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="project" class="form-label">Filter by Project</label>
                            <select name="project" id="project" class="form-select">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Name or Email" value="{{ request('search') }}">
                                <button class="btn btn-outline-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="userTableWrapper">
                        <table class="table table-striped">
                            <!-- Rest of the table code remains unchanged -->
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Cell</th>
                                    <th>Projects</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ optional($user->cell)->name ?? '—' }}</td>
                                        <td>
                                            @forelse ($user->projects as $proj)
                                                <span class="badge bg-info text-dark mb-1">{{ $proj->name }}</span>
                                            @empty
                                                <span class="text-muted">No projects</span>
                                            @endforelse
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="" class="btn btn-sm btn-primary ms-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger ms-1">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $users->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New User Modal remains unchanged -->
<div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
    <!-- Modal content remains unchanged -->
</div>

<script>
    document.getElementById('filterForm').addEventListener('change', function(e) {
        if (e.target.matches('select')) {
            this.submit();
        }
    });

    // Optional: Add debouncing for search input if you want to keep AJAX functionality
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const query = new URLSearchParams(formData).toString();

            fetch(`{{ route('users') }}?${query}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('userTableWrapper').innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
        }, 300);
    });
</script>
@endsection