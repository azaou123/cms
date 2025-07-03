@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>{{ __('Manage Members') }} - {{ $cell->name }}
                    </h5>
                    <a href="{{ route('cells.show', $cell) }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>{{ __('Back to Cell') }}
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="row">
                        <!-- Current Members -->
                        <div class="col-lg-8">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="bi bi-people me-2"></i>{{ __('Current Members') }}
                                    <span class="badge bg-primary ms-2">{{ count($members) }}</span>
                                </h5>
                                <!-- Search Filter -->
                                <div class="d-flex align-items-center">
                                    <div class="input-group" style="width: 250px;">
                                        <span class="input-group-text bg-light">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" id="memberSearch" class="form-control" placeholder="{{ __('Search members...') }}">
                                    </div>
                                </div>
                            </div>

                            @if (count($members) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle" id="membersTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>{{ __('Member') }}</th>
                                            <th>{{ __('Role') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th class="text-center">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($members as $member)
                                        <tr class="member-row">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold member-name">{{ $member->name }}</div>
                                                        <small class="text-muted member-email">{{ $member->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <form action="{{ route('cells.members.update', [$cell, $member->id]) }}" method="POST" class="role-update-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="d-flex align-items-center">
                                                        <select name="role" class="form-select form-select-sm me-2" style="width: 130px;">
                                                            <option value="leader" {{ $member->pivot->role === 'leader' ? 'selected' : '' }}>
                                                                Leader
                                                            </option>
                                                            <option value="secretary" {{ $member->pivot->role === 'secretary' ? 'selected' : '' }}>
                                                                Secretary
                                                            </option>
                                                            <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>
                                                                Member
                                                            </option>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="{{ __('Update Role') }}">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $member->isOnline() ? 'success' : 'secondary' }} d-flex align-items-center" style="width: fit-content;">
                                                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                                                    {{ $member->isOnline() ? 'Online' : 'Offline' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- Chat Button -->
                                                    <form action="{{ route('conversations.start-with-user') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                        <button type="submit" class="btn btn-sm btn-info" title="{{ __('Start Chat') }}">
                                                            <i class="bi bi-chat-dots"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Remove Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-member-btn" 
                                                            title="{{ __('Remove Member') }}"
                                                            data-member-id="{{ $member->id }}"
                                                            data-member-name="{{ $member->name }}"
                                                            data-remove-url="{{ route('cells.members.remove', [$cell, $member->id]) }}">
                                                        <i class="bi bi-person-dash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">{{ __('No members found.') }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Add New Member -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-person-plus me-2"></i>{{ __('Add New Member') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if (count($users) > 0)
                                    <form action="{{ route('cells.members.add', $cell) }}" method="POST" id="addMemberForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="user_search" class="form-label">{{ __('Search User') }}</label>
                                            <div class="position-relative">
                                                <input type="text" id="user_search" class="form-control" placeholder="{{ __('Type to search users...') }}" autocomplete="off">
                                                <div class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm" id="user_dropdown" style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;">
                                                    <!-- Search results will be populated here -->
                                                </div>
                                            </div>
                                            <input type="hidden" id="user_id" name="user_id" class="@error('user_id') is-invalid @enderror" required>
                                            @error('user_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="role" class="form-label">{{ __('Assign Role') }}</label>
                                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                                <option value="leader">Leader</option>
                                                <option value="secretary">Secretary</option>
                                                <option value="member" selected>Member</option>
                                            </select>
                                            @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success" id="addMemberBtn" disabled>
                                                <i class="bi bi-person-plus-fill me-2"></i>{{ __('Add Member') }}
                                            </button>
                                        </div>
                                    </form>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2 mb-0">{{ __('All users are already members of this cell.') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Member Statistics -->
                            <div class="card border-0 shadow-sm mt-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-bar-chart me-2"></i>{{ __('Statistics') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="fw-bold text-primary">{{ $members->where('pivot.role', 'leader')->count() }}</div>
                                            <small class="text-muted">Leaders</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-warning">{{ $members->where('pivot.role', 'secretary')->count() }}</div>
                                            <small class="text-muted">Secretaries</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-success">{{ $members->where('pivot.role', 'member')->count() }}</div>
                                            <small class="text-muted">Members</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="fw-bold text-success">{{ $members->filter(fn($m) => $m->isOnline())->count() }}</div>
                                            <small class="text-muted">Online</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="fw-bold text-secondary">{{ $members->filter(fn($m) => !$m->isOnline())->count() }}</div>
                                            <small class="text-muted">Offline</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Custom Alert Overlay -->
<div id="customAlert" class="custom-alert-overlay" style="display: none;">
    <div class="custom-alert-content">
        <div class="custom-alert-header">
            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
            <span id="alertTitle">Confirmation</span>
        </div>
        <div class="custom-alert-body">
            <p id="alertMessage">Are you sure?</p>
        </div>
        <div class="custom-alert-footer">
            <button type="button" class="btn btn-secondary me-2" onclick="closeCustomAlert()">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmAlertBtn">Confirm</button>
        </div>
    </div>
</div>

<!-- Success/Error Toast -->
<div id="toastContainer" class="toast-container">
    <div id="customToast" class="custom-toast" style="display: none;">
        <div class="custom-toast-content">
            <i id="toastIcon" class="bi bi-check-circle-fill me-2"></i>
            <span id="toastMessage">Success!</span>
        </div>
        <button type="button" class="custom-toast-close" onclick="closeToast()">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>

<!-- Hidden form for member removal -->
<form id="removeMemberForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User data for search
    const users = @json($users ?? []);
    
    // Custom Alert Functions
    window.showCustomAlert = function(title, message, confirmCallback) {
        document.getElementById('alertTitle').textContent = title;
        document.getElementById('alertMessage').textContent = message;
        document.getElementById('customAlert').style.display = 'flex';
        
        document.getElementById('confirmAlertBtn').onclick = function() {
            closeCustomAlert();
            if (confirmCallback) confirmCallback();
        };
    };
    
    window.closeCustomAlert = function() {
        document.getElementById('customAlert').style.display = 'none';
    };
    
    window.showToast = function(message, type = 'success') {
        const toast = document.getElementById('customToast');
        const icon = document.getElementById('toastIcon');
        const messageEl = document.getElementById('toastMessage');
        
        messageEl.textContent = message;
        
        if (type === 'success') {
            icon.className = 'bi bi-check-circle-fill me-2 text-success';
            toast.style.backgroundColor = '#d1e7dd';
            toast.style.borderLeft = '4px solid #198754';
        } else {
            icon.className = 'bi bi-exclamation-triangle-fill me-2 text-danger';
            toast.style.backgroundColor = '#f8d7da';
            toast.style.borderLeft = '4px solid #dc3545';
        }
        
        toast.style.display = 'flex';
        
        // Auto hide after 4 seconds
        setTimeout(() => {
            closeToast();
        }, 4000);
    };
    
    window.closeToast = function() {
        document.getElementById('customToast').style.display = 'none';
    };
    
    // Member search functionality
    const memberSearch = document.getElementById('memberSearch');
    const membersTable = document.getElementById('membersTable');
    
    if (memberSearch && membersTable) {
        memberSearch.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = membersTable.querySelectorAll('.member-row');
            
            rows.forEach(row => {
                const name = row.querySelector('.member-name').textContent.toLowerCase();
                const email = row.querySelector('.member-email').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // User search for adding new members
    const userSearch = document.getElementById('user_search');
    const userDropdown = document.getElementById('user_dropdown');
    const userIdInput = document.getElementById('user_id');
    const addMemberBtn = document.getElementById('addMemberBtn');
    
    if (userSearch && userDropdown && userIdInput) {
        userSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            if (searchTerm.length < 2) {
                userDropdown.style.display = 'none';
                userIdInput.value = '';
                addMemberBtn.disabled = true;
                return;
            }
            
            const filteredUsers = users.filter(user => 
                user.name.toLowerCase().includes(searchTerm) || 
                user.email.toLowerCase().includes(searchTerm)
            );
            
            if (filteredUsers.length > 0) {
                userDropdown.innerHTML = filteredUsers.map(user => `
                    <div class="dropdown-item-custom p-2 border-bottom" data-user-id="${user.id}" data-user-name="${user.name}" style="cursor: pointer;">
                        <div class="d-flex align-items-center">
                            <div class="avatar-placeholder bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                ${user.name.substring(0, 2).toUpperCase()}
                            </div>
                            <div>
                                <div class="fw-semibold">${user.name}</div>
                                <small class="text-muted">${user.email}</small>
                            </div>
                        </div>
                    </div>
                `).join('');
                
                userDropdown.style.display = 'block';
                
                // Add click handlers to dropdown items
                userDropdown.querySelectorAll('.dropdown-item-custom').forEach(item => {
                    item.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        const userName = this.getAttribute('data-user-name');
                        
                        userSearch.value = userName;
                        userIdInput.value = userId;
                        userDropdown.style.display = 'none';
                        addMemberBtn.disabled = false;
                    });
                    
                    item.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f8f9fa';
                    });
                    
                    item.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                    });
                });
            } else {
                userDropdown.innerHTML = '<div class="p-2 text-muted">No users found</div>';
                userDropdown.style.display = 'block';
            }
        });
        
        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userSearch.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.style.display = 'none';
            }
        });
    }
    
    // Remove member functionality
    document.querySelectorAll('.remove-member-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const memberName = this.getAttribute('data-member-name');
            const removeUrl = this.getAttribute('data-remove-url');
            
            showCustomAlert(
                'Remove Member',
                `Are you sure you want to remove ${memberName} from this cell? This action cannot be undone.`,
                function() {
                    const form = document.getElementById('removeMemberForm');
                    form.action = removeUrl;
                    form.submit();
                }
            );
        });
    });
    
    // Role update confirmation
    document.querySelectorAll('.role-update-form select').forEach(select => {
        const originalValue = select.value;
        
        select.addEventListener('change', function() {
            const form = this.closest('.role-update-form');
            const memberName = form.closest('tr').querySelector('.member-name').textContent;
            const newRole = this.value;
            const selectElement = this;
            
            showCustomAlert(
                'Change Role',
                `Are you sure you want to change ${memberName}'s role to ${newRole}?`,
                function() {
                    // Highlight the update button
                    const updateBtn = form.querySelector('button[type="submit"]');
                    updateBtn.style.backgroundColor = '#198754';
                    updateBtn.style.borderColor = '#198754';
                    updateBtn.classList.add('pulse');
                    showToast('Role changed! Click the save button to confirm.');
                }
            );
            
            // Reset if modal is closed without confirmation
            const alertOverlay = document.getElementById('customAlert');
            const handleOverlayClick = function(e) {
                if (e.target === alertOverlay) {
                    selectElement.value = originalValue;
                    alertOverlay.removeEventListener('click', handleOverlayClick);
                }
            };
            alertOverlay.addEventListener('click', handleOverlayClick);
        });
    });
    
    // Add member form validation
    document.getElementById('addMemberForm').addEventListener('submit', function(e) {
        const userIdInput = document.getElementById('user_id');
        if (!userIdInput.value) {
            e.preventDefault();
            showToast('Please select a user first', 'error');
            return false;
        }
        
        // Show loading state
        const btn = document.getElementById('addMemberBtn');
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Adding...';
        btn.disabled = true;
    });
    
    // Show session messages
    @if (session('success'))
        setTimeout(() => {
            showToast('{{ session('success') }}', 'success');
        }, 500);
    @endif
    
    @if (session('error'))
        setTimeout(() => {
            showToast('{{ session('error') }}', 'error');
        }, 500);
    @endif
});
</script>

<style>
.custom-alert-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.custom-alert-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 90%;
    margin: 20px;
}

.custom-alert-header {
    padding: 20px 20px 10px;
    font-size: 18px;
    font-weight: 600;
    border-bottom: 1px solid #e9ecef;
}

.custom-alert-body {
    padding: 20px;
}

.custom-alert-footer {
    padding: 10px 20px 20px;
    text-align: right;
}

.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.custom-toast {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 15px 20px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 300px;
    border-left: 4px solid #198754;
}

.custom-toast-content {
    display: flex;
    align-items: center;
}

.custom-toast-close {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    padding: 0;
    margin-left: 10px;
}

.avatar-placeholder {
    flex-shrink: 0;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.btn-group .btn {
    border-radius: 0.25rem !important;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.input-group-text {
    border-right: none;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

#user_dropdown {
    border-top: none !important;
    max-height: 250px;
    overflow-y: auto;
}

.dropdown-item-custom:hover {
    background-color: #f8f9fa;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.pulse {
    animation: pulse 1s infinite;
}
</style>
@endsection