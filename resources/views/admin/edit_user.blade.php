@extends('layouts.admin')
@section('title', __("Modifier l'utilisateur"))
@section('title_content', __("admin"))
@section('content')

    <div class="pagetitle">
        <h1>Modifier l'utilisateur</h1>
    </div>

    <!-- Affichage des alertes -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Formulaire de Modification</h5>
           <form action="{{ route('admin.update_user', $user->id_user) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Nom -->
    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>

    <!-- Rôle (Admin ou User) -->
    <div class="mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select name="id_role" class="form-select" required>
            <option value="2" {{ old('id_role', $user->id_role) == 2 ? 'selected' : '' }}>User</option>
            <option value="1" {{ old('id_role', $user->id_role) == 1 ? 'selected' : '' }}>Admin</option>
        </select>
        <div class="invalid-feedback">Veuillez sélectionner un rôle !</div>
    </div>

    <!-- Numéro -->
    <div class="mb-3">
        <label for="numero" class="form-label">Numéro</label>
        <input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero', $user->numero) }}" required>
        <div class="invalid-feedback">Veuillez entrer un numéro valide !</div>
    </div>

    <!-- Bouton de soumission -->
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>

        </div>
    </div>

@endsection
