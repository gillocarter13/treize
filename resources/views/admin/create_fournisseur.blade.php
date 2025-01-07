@extends('layouts.admin')

@section('title', __("Gestion des Fournisseurs"))

@section("content")
 <div class="pagetitle">
      <h1>ajouter fournisseur</h1>

    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>
<section class="section">
      @if(session('success'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-octagon me-1"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

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
                <form action="{{ route('fournisseur.store') }}" method="POST">
                    @csrf

                    <!-- Nom -->
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom du fournisseur" required>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" class="form-control" id="contact" name="contact" placeholder="Numéro de téléphone" required>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse du fournisseur" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Tableau des fournisseurs -->
    <table class="table datatable mt-4">
    <thead>
        <tr>
            <th><b>ID</b></th>
            <th><b>Nom</b></th>
            <th><b>Contact</b></th>
            <th><b>Adresse</b></th>
            <th><b>Actions</b></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($fournisseurs as $fournisseur)
            <!-- Modal d'édition de fournisseur -->
            <div class="modal fade" id="editFournisseurModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Éditer un Fournisseur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editFournisseurForm" action="{{ route('fournisseur.update', $fournisseur->id_fournisseur) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nom -->
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="edit_nom" name="nom" value="{{ $fournisseur->nom }}" placeholder="Nom du fournisseur" required>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="mb-3">
                        <label for="edit_contact" class="form-label">Contact</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" class="form-control" id="edit_contact" name="contact" value="{{ $fournisseur->contact }}" placeholder="Numéro de téléphone" required>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="mb-3">
                        <label for="edit_adresse" class="form-label">Adresse</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" id="edit_adresse" name="adresse" value="{{ $fournisseur->adresse }}" placeholder="Adresse du fournisseur" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>


            <tr>
                <td>{{ $fournisseur->id_fournisseur }}</td>
                <td>{{ $fournisseur->nom }}</td>
                <td>{{ $fournisseur->contact }}</td>
                <td>{{ $fournisseur->adresse }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="actionsDropdown{{ $fournisseur->id_fournisseur }}" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionsDropdown{{ $fournisseur->id_fournisseur }}">
                            <li>
                                <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#editFournisseurModal"
                                        onclick="editFournisseur('{{ $fournisseur->id_fournisseur }}', '{{ $fournisseur->nom }}', '{{ $fournisseur->contact }}', '{{ $fournisseur->adresse }}')">
                                    Éditer
                                </button>
                            </li>
                            <li>
                                <form action="{{ route('fournisseur.destroy', $fournisseur->id_fournisseur) }}" method="POST" id="delete-form-{{ $fournisseur->id_fournisseur }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger" type="button" onclick="confirmDelete({{ $fournisseur->id_fournisseur }})">Supprimer</button>
                                </form>
                            </li>
                        </ul>
                    </div>
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
        {{ $fournisseurs->links('pagination::bootstrap-5') }}
    </div>
</section>
@endsection
