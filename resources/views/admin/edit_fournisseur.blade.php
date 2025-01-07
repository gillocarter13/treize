@extends('layouts.admin')

@section('title', __("Gestion des Fournisseurs"))

@section("content")
<section class="section">
    <div class="d-flex justify-content-between mb-10">
        <!-- Bouton pour ajouter un fournisseur -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFournisseurModal">
            <i class="bi bi-plus-circle"></i> Ajouter Fournisseur
        </button>
    </div>

    <!-- Modal d'ajout de fournisseur -->
    <div class="modal fade" id="addFournisseurModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un Fournisseur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @extends('layouts.admin')

@section('title', 'Éditer Fournisseur')

@section('content')
<section class="section">
    <h1>Éditer Fournisseur</h1>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

 <form action="{{ route('fournisseur.edit', $fournisseur->id_fournisseur) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $fournisseur->nom) }}" required>
        </div>

        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact', $fournisseur->contact) }}" required>
        </div>

        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse', $fournisseur->adresse) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</section>
@endsection

                </div>
            </div>
        </div>
    </div>

   <table class="table datatable">
    <thead>
        <tr>
            <th><b>Id</b></th>
            <th><b>Nom</b></th>
            <th><b>Contact</b></th>
            <th><b>Adresse</b></th>
            <th><b>Actions</b></th>
        </tr>
    </thead>
    <tbody>
      @forelse ($fournisseurs as $fournisseur)
    <tr>
        <td>{{ $fournisseur->id_fournisseur }}</td>
        <td>{{ $fournisseur->nom }}</td>
        <td>{{ $fournisseur->contact }}</td>
        <td>{{ $fournisseur->adresse }}</td>
        <td>
            <a href="{{ route('fournisseur.edit', $fournisseur->id_fournisseur) }}" class="btn btn-warning">Éditer</a>
            <form action="{{ route('fournisseur.destroy', $fournisseur->id_fournisseur) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5">Aucun fournisseur trouvé</td>
    </tr>
@endforelse
    </tbody>
</table>

    <!-- Pagination -->
   <div class="d-flex justify-content-center">
    {{ $fournisseurs->links('pagination::bootstrap-5') }} <!-- Cela affichera les liens de pagination -->
</div>
@endsection
