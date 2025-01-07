@extends('layouts.admin')
@section('title', 'Gestion des utilisateurs')
@section('content')
<div class="pagetitle">
      <h1>ajouter utilisateur </h1>

    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>
<section class="section">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
     @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Container for search bar and button -->
    <div class="d-flex justify-content-between mb-10">

        <!-- Button to trigger the modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ExtralargeModal">
            <i class="bi bi-plus-circle"></i> Ajouter utilisateur
        </button>
    </div>

    <!-- Modal -->
   <div class="modal fade" id="ExtralargeModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('users.store') }}" method="post" class="row g-3 needs-validation" enctype="multipart/form-data">
                    @csrf

                    <div class="col-md-4">
                        <label for="name" class="form-label">Nom :</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                            <div class="invalid-feedback">Veuillez entrer votre nom !</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="email" class="form-label">Email :</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
                            <div class="invalid-feedback">Veuillez entrer une adresse email valide !</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="password" class="form-label">Mot de passe :</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" class="form-control" id="password" required>
                            <div class="invalid-feedback">Veuillez entrer le mot de passe !</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="password_confirmation" class="form-label">Confirmez le mot de passe :</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                            <div class="invalid-feedback">Veuillez confirmer le mot de passe !</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="id_role" class="form-label">Rôle :</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <select name="id_role" id="id_role" class="form-control">
                                <option value="1">Admin</option>
                                <option value="2">Employé</option>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un rôle !</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="numero" class="form-label">Numéro de téléphone :</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="numero" class="form-control" id="numero" value="{{ old('numero') }}" required>
                            <div class="invalid-feedback">Veuillez entrer un numéro de téléphone !</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="adresse" class="form-label">Adresse :</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                            <input type="text" name="adresse" class="form-control" id="adresse" value="{{ old('adresse') }}" required>
                            <div class="invalid-feedback">Veuillez entrer une adresse !</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agree" required>
                            <label class="form-check-label" for="agree">
                                Agree to terms and conditions
                            </label>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>

   <table class="table datatable mt-4">
    <thead>
        <tr>
            <th><b>Id</b></th>
            <th><b>Nom</b></th>
            <th><b>Email</b></th>
            <th><b>Rôle</b></th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->id_user }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->nom ?? 'Rôle inconnu' }}</td>
                <td>
                    <!-- Menu Dropdown pour Actions -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="actionsDropdown{{ $user->id_user }}" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionsDropdown{{ $user->id_user }}">
     
                            <li>
                                <form action="{{ route('admin.delete_user', $user->id_user) }}" method="POST" id="delete-form-{{ $user->id_user }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $user->id_user }})">Supprimer</button>
                                </form>
                            </li>
                        </ul>
<!-- Modal d'Édition -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Éditer un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nom :</label>
                        <input type="text" name="name" class="form-control" id="edit_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email :</label>
                        <input type="email" name="email" class="form-control" id="edit_email" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_id_role" class="form-label">Rôle :</label>
                        <select name="id_role" id="edit_id_role" class="form-control" required>
                            <option value="1">Admin</option>
                            <option value="2">Employé</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_numero" class="form-label">Numéro de téléphone :</label>
                        <input type="text" name="numero" class="form-control" id="edit_numero" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Aucun utilisateur trouvé</td>
            </tr>
        @endforelse
    </tbody>
</table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
    </div>

@endsection
