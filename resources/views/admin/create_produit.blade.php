@extends('layouts.admin')

@section('title', __("Gestion des Produits"))

@section("content")
<div class="pagetitle">
    <h1>Ajouter Produit</h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12 col-md-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gestion des Produits</h5>

                    <!-- Affichage des messages de succès et d'erreur -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
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

                    <!-- Bouton pour ajouter un produit -->
                    <div class="d-flex justify-content-between mb-10">
                       <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProduitModal">
                            <i class="bi bi-plus-circle"></i> Enregistrer Produit
                        </button>
                    </div>
<div class="modal fade" id="addProduitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('produit.store') }}" method="POST" onsubmit="return validateForm()">
                    @csrf
                    <div class="row mb-3">
                        <!-- Nom -->
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-box"></i></span>
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom du produit" required>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="col-md-6">
                            <label for="type" class="form-label">Type</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="aliment">Aliment</option>
                                    <option value="accessoires">Accessoire</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Unité de Mesure -->
                        <div class="col-md-6">
                            <label for="unité_de_mesure" class="form-label">Unité de Mesure</label>
                          <div class="input-group">
    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
    <select class="form-control" id="unité_de_mesure" name="unité_de_mesure" required>
        <option value="Kg">Kilo gramme</option>
        <option value="gramme">Gramme</option>
        <option value="L">Litre</option>
        <option value="nombre">Nombre</option>
    </select>
</div>

                        </div>

                        <!-- Fournisseur -->
                        <div class="col-md-6">
                            <label for="id_fournisseur" class="form-label">Fournisseur</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <select class="form-control" id="id_fournisseur" name="id_fournisseur" required>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id_fournisseur }}">{{ $fournisseur->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Quantité -->
                        <div class="col-md-6">
                            <label for="quantité" class="form-label">Quantité</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-layers"></i></span>
                                <input type="number" class="form-control" id="quantité" name="quantité" placeholder="Quantité en stock" required min="1">
                            </div>
                        </div>

                        <!-- Prix Unitaire -->
                        <div class="col-md-6">
                            <label for="prix_unitaire" class="form-label">Prix Unitaire (FCFA)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" placeholder="Prix par unité" required step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Seuil Alerte -->
                        <div class="col-md-6">
                            <label for="seuil_alerte" class="form-label">Seuil Alerte</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-exclamation-triangle"></i></span>
                                <input type="number" class="form-control" id="seuil_alerte" name="seuil_alerte" placeholder="Niveau d'alerte de stock" min="0">
                            </div>
                        </div>
                        <div class="col-md-6"></div> <!-- Colonne vide pour équilibrer -->
                    </div>

                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
                <div id="error-message" class="text-danger mt-2" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

               <!-- Tableau des produits -->
<table class="table datatable mt-4">
    <thead>
        <tr>
            <th>ID Produit</th>
            <th>Nom du Produit</th>
            <th>Type</th>
            <th>Fournisseur</th>
            <th>Quantité achat</th>
            <th>Prix Unitaire (FCFA)</th>
            <th>Prix Total (FCFA)</th>
            <th>Quantité stock</th>
            <th>Seuil Alerte</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($produits as $produit)
            <tr>
                <td>{{ $produit->id_produit }}</td>
                <td>{{ $produit->nom }}</td>
                <td>{{ $produit->type }}</td>
                <td>{{ $produit->fournisseur ? $produit->fournisseur->nom : 'Fournisseur non disponible' }}</td>
                <td>{{ $produit->quantité }}</td>
                <td>{{ $produit->prix_unitaire }}</td>
                <td>{{ $produit->prix_total }}</td>
                <td>{{ $produit->quantite_stock }}</td>
                <td>{{ $produit->seuil_alerte }}</td>
                <td>
                    <!-- Dropdown Actions -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $produit->id_produit }}" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $produit->id_produit }}">
                            <!-- Bouton Éditer -->
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editProduitModal{{ $produit->id_produit }}">
                                    Éditer
                                </button>
                            </li>
                            <!-- Formulaire de suppression -->
                        <li>
    <form id="delete-form-{{ $produit->id_produit }}" action="{{ route('produits.destroy', $produit->id_produit) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <button class="dropdown-item text-danger" onclick="confirmDelete({{ $produit->id_produit }})">
        Supprimer
    </button>
</li>

                        </ul>
                    </div>
                </td>
            </tr>

            <!-- Modal d'édition de produit -->
            <div class="modal fade" id="editProduitModal{{ $produit->id_produit }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Éditer un Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('produit.update', $produit->id_produit) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nom et Type -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_nom" class="form-label">Nom</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-box"></i></span>
                                <input type="text" class="form-control" id="edit_nom" name="nom" value="{{ $produit->nom }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label">Type</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="aliment" {{ $produit->type == 'aliment' ? 'selected' : '' }}>Aliment</option>
                                    <option value="accessoires" {{ $produit->type == 'accessoires' ? 'selected' : '' }}>Accessoire</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Unité de mesure et Fournisseur -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_unité" class="form-label">Unité de Mesure</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
 <select class="form-control" id="id_fournisseur" name="id_fournisseur" required>
                                        <option value="Kg">kilo gramme</option>
                                        <option value="gramme">gramme</option>
                                        <option value="L">littre</option>
                                        <option value="nombre">nombre</option>





                                </select>                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_id_fournisseur" class="form-label">Fournisseur</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-industry"></i></span>
                                <select class="form-control" id="edit_id_fournisseur" name="id_fournisseur" required>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id_fournisseur }}" {{ $produit->id_fournisseur == $fournisseur->id_fournisseur ? 'selected' : '' }}>
                                            {{ $fournisseur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Quantité et Prix Unitaire -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_quantité" class="form-label">Quantité</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                <input type="number" class="form-control" id="edit_quantité" name="quantité" value="{{ $produit->quantité }}" required min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_prix_unitaire" class="form-label">Prix Unitaire</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" class="form-control" id="edit_prix_unitaire" name="prix_unitaire" value="{{ $produit->prix_unitaire }}" required step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Prix Total et Seuil Alerte -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_seuil_alerte" class="form-label">Seuil Alerte</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-exclamation-triangle"></i></span>
                                <input type="number" class="form-control" id="edit_seuil_alerte" name="seuil_alerte" value="{{ $produit->seuil_alerte }}" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


        @empty
            <tr>
                <td colspan="12">Aucun produit trouvé</td>
            </tr>
        @endforelse
    </tbody>
</table>


                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $produits->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</section>
 <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="stockAlertToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Alerte Stock</strong>
            <small>Maintenant</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Certains produits ont atteint leur seuil de stock :
            <ul>
                @foreach($produits_en_alerte as $produit)
                    <li>{{ $produit->nom }} - {{ $produit->quantite_stock }} unités restantes.</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('stockAlertToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
</script>
@endsection
