@extends('layouts.admin')

@section('title', __("Gestion des Plats"))

@section("content")
<div class="pagetitle">
    <h1>Ajouter un MENU</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12 col-md-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gestion des MENU</h5>

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

                    <!-- Bouton pour ajouter un plat -->
                    <div class="d-flex justify-content-between mb-10">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlatModal">
                            <i class="bi bi-plus-circle"></i> Enregistrer un MENU
                        </button>
                    </div>

                    <table class="table datatable mt-4">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Produits et Quantités</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plats as $plat)
                            <tr>
                                <td>{{ $plat->id_plat }}</td> <!-- Utilisez id_plat ici -->
                                <td>{{ $plat->nom }}</td>
                                <td>{{ $plat->description }}</td>
                                <td>{{ number_format($plat->prix, 2, ',', ' ') }} fcfa</td>
                                <td>
                                    @php
                                    $produitsQuantites = json_decode($plat->produits_quantites, true);
                                    @endphp
                                    <ul>
                                        @foreach ($produitsQuantites as $item)
                                        @php
                                        $produit = $produits_aliment[$item['id_produit']] ?? null;
                                        $produitNom = $produit->nom ?? 'Produit inconnu';
                                        $uniteMesure = $produit->unité_de_mesure ?? '';
                                        @endphp
                                        <li>
                                            Produit: {{ $produitNom }} - Quantité: {{ $item['quantite'] }} {{ $uniteMesure }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                <td>
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Actions
        </button>
        <ul class="dropdown-menu">
            <li>
                @if ($plat->is_active)
                    <form action="{{ route('plats.desactiver', $plat->id_plat) }}" method="POST">
                        @csrf
                        @method('PATCH') <!-- Specify PATCH method -->
                        <button type="submit" class="dropdown-item">Désactiver</button>
                    </form>
                @else
                    <form action="{{ route('plats.activer', $plat->id_plat) }}" method="POST">
                        @csrf
                        @method('PATCH') <!-- Specify PATCH method -->
                        <button type="submit" class="dropdown-item">Activer</button>
                    </form>
                @endif
            </li>
            <li>
                <button class="dropdown-item text-danger" onclick="confirmDelete({{ $plat->id_plat }})">Supprimer</button>
                <form id="delete-form-{{ $plat->id_plat }}" action="{{ route('plats.destroy', $plat->id_plat) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </li>
        </ul>
    </div>
</td>



                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>



                    <!-- Modal d'ajout de plat -->
                    <div class="modal fade" id="addPlatModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enregistrer un Plat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('plat.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Nom du plat -->
    <div class="mb-3">
        <label for="nom" class="form-label">Nom du plat</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-utensils"></i></span>
            <input type="text" name="nom" class="form-control" id="nom" required>
        </div>
    </div>

    <!-- Description -->
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
            <textarea name="description" class="form-control" id="description" required></textarea>
        </div>
    </div>

    <!-- Prix -->
    <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-house-door"></i></span>
            <input type="number" name="prix" class="form-control" id="prix" min="0" step="0.01">
        </div>
    </div>

    <!-- Sélection des produits -->
    <div class="mb-3">
        <label for="produits" class="form-label">Produits</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-list"></i></span>
            <select id="produits" name="produits[]" class="form-control" multiple>
                @php
                    // Convertir la collection en tableau et trier par nom
                    $produitsArray = $produits_aliment->toArray();

                    usort($produitsArray, function($a, $b) {
                        return strcmp($a['nom'], $b['nom']);
                    });
                @endphp
                @foreach($produitsArray as $produit)
                    <option value="{{ $produit['id_produit'] }}" data-unite="{{ $produit['unité_de_mesure'] }}">
                        {{ $produit['nom'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Conteneur pour les champs de quantités -->
    <div id="quantitesContainer"></div>

 <div class="mb-3">
    <label for="image" class="form-label">Image du Plat</label>
    <input type="file" name="image" class="form-control" id="image">
</div>
    <button type="submit" class="btn btn-primary">Créer le plat</button>
</form>

            </div>
        </div>
    </div>
</div>
      </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')

@endsection
