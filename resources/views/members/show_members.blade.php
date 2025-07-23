@extends('layouts.app')

@section('content')



<div class="container">

    <div class="d-flex justify-content-start m-1">
        <form class="filter-form d-flex justify-content-start m-1" action="{{route('filter_membres')}}" method="POST">
            @csrf
            <div class=" m-1">
                <select id="roll-select" class="form-select ps-2 pe-5 border-black" name="roll">
                    <option value="">choose a roll</option>
                    <option value="leader">Leader</option>
                    <option value="secretary">secretary</option>
                    <option value="member">member</option>
                </select>
            </div>
            <div class=" m-1">
                <select id="cell-select" class="form-select ps-2 pe-5 border-black" name="cell" id="cells" aria-label="Default select example">
                    <option value="">choose a cell</option>
                    @foreach ($cells as $cell)
                        <option value="{{$cell->id}}">{{$cell->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class=" m-1">
                <select class="form-select ps-2 pe-5 border-black" name="project" id="project-select">
                    <option value="">choose a project</option>
                    @foreach ($projects as $project)
                        <option value="{{$project->id}} ">{{$project->name}} </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button id="filter-btn" type="submit" class="btn btn-primary">
                    Filtrer
                </button>
            </div>
        </form>


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
                            <tbody id="membre_container">
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

    // document.addEventListener('DOMContentLoaded', function() {






    //     const form=document.querySelector('.filter-form');
    //     const roll=form.querySelector('#roll-select');
    //     const cell=form.querySelector('#cell-select');
    //     const project=form.querySelector('#project-select');
    //     const filter_btn =form.querySelector("#filter-btn");

    //     filter_btn.addEventListener('click', function(event) {
    //         event.preventDefault();
    //         const formdata= new FormData(form);

    //         fetch(form.action,{
    //             method: 'POST',
    //             headers: {
    //                 'X-Requested-With': 'XMLHttpRequest',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //             },
    //             body: formdata

    //         })
    //         .then(response=>{
    //             if(!response.ok) throw new Error('Erreur réseau');
    //             return response.json();
    //         })
    //         .then(data => {
    //             if (data.success) {
    //                 alert("tous ca marche bien");
    //                 displaymembres(data.members);
    //             } else {

    //                 alert("erreur");
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Erreur:', error);
    //             if (error.message === 'Timeout') {
    //                 alert('Timeout: Le serveur met trop de temps à répondre.');
    //             } else if (error.message.includes('fetch')) {
    //                 alert('Erreur de connexion: Vérifiez votre connexion internet et que le serveur est accessible.');
    //             } else {
    //                 alert('Erreur: ' + error.message);
    //             }
    //         })
    //     })
    //     const membersContainer=document.querySelector('#membre_container');

    //     let nextPageUrl = '/members/json';

    //     function fetchMembers(url) {
    //         fetch(url)
    //             .then(res => res.json())
    //             .then(data => {
    //                 displaymembres(data.data);

    //                 nextPageUrl = data.next_page_url;

    //                 if (!nextPageUrl) {
    //                     document.getElementById('load-more-btn').style.display = 'none';
    //                 }
    //             });
    //     }

    //     function displaymembres(members){

    //         if (membersContainer) {
    //             membersContainer.innerHTML = '';
    //             members.forEach(member => {
    //                 const memberCard = document.createElement('div');
    //                 memberCard.className = 'member-card mb-3 p-3 border rounded bg-light';
    //                 memberCard.innerHTML = `
    //                     <div class="row align-items-center">
    //                         <div class="col-md-2">
    //                             <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%;">
    //                                 ${member.name ? member.name.charAt(0).toUpperCase() : 'U'}
    //                             </div>
    //                         </div>
    //                         <div class="col-md-4">
    //                             <h6 class="mb-1">${member.name || 'N/A'}</h6>
    //                             <small class="text-muted">${member.email || 'Email non disponible'}</small>
    //                         </div>
    //                         <div class="col-md-2">
    //                             <small class="text-muted">ID: ${member.id}</small>
    //                         </div>
    //                         <div class="col-md-2">
    //                             <span class="badge bg-info">
    //                                 ${member.cells ? member.cells.length : 0} cellule(s)
    //                             </span>
    //                         </div>
    //                         <div class="col-md-2">
    //                             <span class="badge bg-success">
    //                                 ${member.projects ? member.projects.length : 0} projet(s)
    //                             </span>
    //                         </div>
    //                     </div>
    //                     <div class="row mt-2">
    //                         <div class="col-md-6">
    //                             <strong>Cellules:</strong>
    //                             ${member.cells && member.cells.length > 0 ?
    //                                 member.cells.map(cell => `<span class="badge bg-secondary me-1">${cell.name}</span>`).join('') :
    //                                 '<span class="text-muted">Aucune</span>'
    //                             }
    //                         </div>
    //                         <div class="col-md-6">
    //                             <strong>Projets:</strong>
    //                             ${member.projects && member.projects.length > 0 ?
    //                                 member.projects.map(project => `<span class="badge bg-warning me-1">${project.name}</span>`).join('') :
    //                                 '<span class="text-muted">Aucun</span>'
    //                             }
    //                         </div>
    //                     </div>
    //                 `;
    //                 membersContainer.appendChild(memberCard);
    //             });
    //         }

    //     }



    // });

</script>



@endsection
