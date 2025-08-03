<table class="table table-striped">
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
                         <form class="ms-1" action="{{ route('conversations.start-with-user') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-sm btn-info" title="{{ __('Start Chat') }}">
                                <i class="bi bi-chat-dots"></i>
                            </button>
                        </form>
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
<div class="mt-3 d-flex justify-content-center">
    {{ $users->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>
