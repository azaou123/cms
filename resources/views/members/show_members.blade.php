@extends('layouts.app')

@section('content')



<div class="container">

    <div class="d-flex justify-content-start m-1">
        <div class=" m-1">
            <select class="form-select ps-2 pe-5 border-black" name="cells" id="cells">
                <option value="cells1">choose a roll</option>
                <option value="cells1">cellone</option>
                <option value="cells1">cellone</option>
            </select>
        </div>
         <div class=" m-1">
            <select class="form-select ps-2 pe-5 border-black" name="cells" id="cells" aria-label="Default select example">
                <option value="null">choose a cell</option>
                @foreach ($cells as $cell)
                    <option value="{{$cell->id}}">{{$cell->name}} </option>
                @endforeach
            </select>
        </div>
         <div class=" m-1">
            <select class="form-select ps-2 pe-5 border-black" name="cells" id="cells">
                <option value="cells1">choose a project</option>
                @foreach ($projects as $project)
                    <option value="{{$project->id}} ">{{$project->name}} </option>
                @endforeach
            </select>
        </div>

    </div>


    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('All membres') }}</span>
                    <a href="" class="btn btn-primary btn-sm">{{ __('Create New member') }}</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif


                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Member') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('phone') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                <tr>
                                    <td>
                                        <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                    </td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        <span class="badge {{ $member->status === 'active' ? 'bg-success' : ($member->status === 'inactive' ? 'bg-warning' : 'bg-secondary') }}">
                                            {{ ucfirst($member->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $member->phone}}</td>
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
                                        </div>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $members->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
</script>



@endsection
