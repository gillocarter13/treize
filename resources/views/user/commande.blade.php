@extends('layouts.user')

@section('title', __("Accueil"))
@section("title_content", __("Page"))
@section("content")
<div class="pagetitle">
    <h1>Enregistrer commande</h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"></h5>

                    <!-- Gestion des messages de succès/erreur -->
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

                    <!-- Bouton pour ouvrir la modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fullscreenModal">
                        <i class="bi bi-plus-circle"></i> Créer Commande
                    </button>

                    <div class="modal fade" id="fullscreenModal" tabindex="-1" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullscreenModalLabel">Commander des Plats</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <!-- Barre de recherche -->
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher des plats par nom ou prix...">
                    </div>

                    <!-- Formulaire pour ajouter les plats au panier -->
                    <form action="{{ route('cart.add') }}" method="POST" id="commandeForm" onsubmit="return validateForm();">
                        @csrf

                      <div class="row" id="platList">
    @foreach($plats as $plat)
        <div class="col-md-3 plat-item">
            <!-- Carte de plat avec une largeur définie -->
            <div class="card shadow-sm border-light mb-4 rounded" style="width: 18rem;">
                <!-- Image du plat avec vérification -->
                <img src="{{ $plat->image ? asset('storage/' . $plat->image) : asset('storage/default-plat.jpg') }}"
                     class="card-img-top" alt="Image de {{ $plat->nom }}"
                     style="max-height: 200px; object-fit: cover;">

                <div class="card-body">
                    <!-- Titre du plat -->
                    <h5 class="card-title">{{ $plat->nom }}</h5>
                    <!-- Description du plat -->
                    <p class="card-text">{{ $plat->description }}</p>

                    <!-- Prix du plat en bleu primaire -->
                    <p class="card-text"><strong>Prix:</strong> <span class=" plat-price">{{ $plat->prix }}</span> fcfa</p>

                    <!-- Champ de quantité -->
                    <div class="form-group">
                        <label for="quantite{{ $plat->id_plat }}">Quantité :</label>
                        <input type="hidden" name="plats[]" value="{{ $plat->id_plat }}">
                        <input type="number" name="quantites[{{ $plat->id_plat }}]" id="quantite{{ $plat->id_plat }}" class="form-control form-control-sm" min="0" value="0" style="width: 200px;">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


                        <!-- Conteneur pour afficher le message d'erreur -->
                        <div id="error-message" style="color: red; display: none;"></div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mt-2" id="submitCommandeBtn">Enregistrer Commande</button>
                        </div>
                    </form>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                    <!-- Panier: Affichage des produits dans le panier -->
                    <div class="mt-4">
                        <h3>Détails de la commande</h3>
            <table class="table datatable mt-4">
    <thead>
        <tr>
            <th>ID Commande</th>
            <th>Prix Total</th>
            <th>Nombre de Plats</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($commandes as $commande)
            @if($commande->type == 'simple') <!-- Condition pour le type simple -->
                <tr>
                    <td>{{ $commande->id_commande }}</td>
                    <td>
                        @php
                            $total = $commande->details->sum('prix_total');
                        @endphp
                        {{ $total }} fcfa
                    </td>
                    <td>
                        @php
                            $nombre_plat = $commande->details->sum('quantite');
                        @endphp
                        {{ $nombre_plat }}
                    </td>
                      <td>
    @if ($commande->status === 'en attente')
        <button class="btn btn-danger fw-semibold text-white">
             {{ ucfirst($commande->status) }}
        </button>
    @elseif ($commande->status === 'terminé')
        <button class="btn btn-success fw-semibold text-white ">
            <i class="bi bi-check-lg"></i> {{ ucfirst($commande->status) }}
        </button>
    @else
        <span>{{ ucfirst($commande->status) }}</span>
    @endif
</td>

                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                           <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @if ($commande->status == 'terminé')
        <!-- Statut terminé -->
        <li><button class="dropdown-item" disabled>Terminé</button></li>
    @else
        <!-- Bouton pour marquer comme terminé -->
        <li>
            <form action="{{ route('commandes.update', $commande->id_commande) }}" method="POST" id="update-form-{{ $commande->id_commande }}">
                @csrf
                @method('PUT')
                <button type="button" class="dropdown-item terminer-button" onclick="confirmUpdate({{ $commande->id_commande }})">
                    <i class="bi bi-check text-success"></i> Terminer
                </button>
            </form>
        </li>
    @endif

    <!-- Bouton Générer Facture -->
    <li>
        <form action="{{ route('facture.generate', $commande->id_commande) }}" method="GET" style="display: inline;">
            @csrf
            <button type="submit" class="dropdown-item"
                @if ($commande->status != 'terminé') disabled @endif>
                <i class="bi bi-file-earmark-ppt"></i> Générer Facture
            </button>
        </form>
    </li>

    <!-- Voir Détails -->
    <li>
        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $commande->id_commande }}">
            <i class="bi bi-info-circle text-primary"></i> Voir Détails
        </button>
    </li>
</ul>

                             <!-- Modal pour voir les détails -->
                <div class="modal fade" id="detailsModal{{ $commande->id_commande }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $commande->id_commande }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailsModalLabel{{ $commande->id_commande }}">Détails de la Commande #{{ $commande->id_commande }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table datatable mt-4">
                                    <thead>
                                        <tr>
                                            <th>Nom du Plat</th>
                                            <th>Quantité</th>
                                            <th>Prix Unitaire</th>
                                            <th>Prix Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($commande->details as $detail)
                                            <tr>
                                                <td>
                                                    @if($detail->plat)
                                                        {{ $detail->plat->nom }}
                                                    @else
                                                        Plat non disponible
                                                    @endif
                                                </td>
                                                <td>{{ $detail->quantite }}</td>
                                                <td>{{ $detail->prix_unitaire }} fcfa</td>
                                                <td>{{ $detail->prix_total }} fcfa</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
                        </div>
                    </td>
                </tr>

            @endif
        @endforeach
    </tbody>
</table>


                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Toast container -->
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
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Succès</strong>
            <small>Maintenant</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            La commande a été validée avec succès.
        </div>
    </div>
</div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('stockAlertToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
</script>
@endsection
