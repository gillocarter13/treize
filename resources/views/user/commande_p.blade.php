@extends('layouts.user')

@section('title', __("Accueil"))
@section("title_content", __("Page"))
@section("content")
<div class="pagetitle">
    <h1>Enregistrer commande personalisée</h1>
</div>
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title"></h5>
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

   <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPlatModal">
    <i class="bi bi-plus-circle"></i> Commande Personnaliser
</button>

    <!-- Modal pour ajouter une commande -->
    <div class="modal fade" id="addPlatModal" tabindex="-1" aria-labelledby="addPlatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPlatModalLabel">Enregistrer un Plat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('commandes.store_personnalisees') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="prix_unitaire" class="form-label">Prix Unitaire</label>
                            <input type="number" name="prix_unitaire" class="form-control" id="prix_unitaire" min="0" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantite_plat" class="form-label">Quantité du plat</label>
                            <input type="number" name="quantite_plat" class="form-control" id="quantite_plat" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="produits" class="form-label">Produits pour personnaliser le plat</label>
                          <select id="produits" name="produits[]" class="form-control" multiple>
    @foreach($produits_aliment as $produit)
        <option value="{{ $produit->id_produit }}">{{ $produit->nom }}</option>

    @endforeach
</select>

<div id="quantites-container"></div>
                        </div>
                        <div id="quantitesContainer"></div>
                        <button type="submit" class="btn btn-primary">Créer le plat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <div class="mt-4">
    <h3>Détails de la commande</h3>

    <table class="table datatable mt-4">
    <thead>
        <tr>
            <th>ID</th>
            <th>Prix Total (FCFA)</th>
            <th>Nombre de Plats</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if ($commandes->isEmpty())
            <tr>
                <td colspan="6" class="text-center">Aucune commande personnalisée pour le moment.</td>
            </tr>
        @else
            @foreach ($commandes as $commande)
                @if ($commande->type === 'personalisee')
                    <tr>
                        <td>{{ $commande->id_commande }}</td>
                        <td>
                            @php
                                $prixTotalCommande = $commande->details_personnalises->sum('prix_total');
                            @endphp
                            {{ $prixTotalCommande }} FCFA
                        </td>
                        <td>
                            @php
                                $nombrePlats = $commande->details_personnalises->first()->quantite_plat ?? 0;
                            @endphp
                            {{ $nombrePlats }}
                        </td>
                       <td>
                            @if ($commande->status === 'en attente')
                                <i class="bi bi-circle-fill clignotant"></i> {{ $commande->status }}
                            @elseif ($commande->status === 'terminé')
                                <i class="bi bi-check-lg" style="color: green;"></i> {{ $commande->status }}
                            @else
                                {{ $commande->status }}
                            @endif
                        </td>

                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $commande->id_commande }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $commande->id_commande }}">
    <!-- Bouton "Terminer" -->
    <li>
        <form action="{{ route('commandes.update', $commande->id_commande) }}" method="POST" id="update-form-{{ $commande->id_commande }}" style="display: inline;">
            @csrf
            @method('PUT')
            <button type="submit" class="dropdown-item" @if ($commande->status === 'terminé') disabled @endif>
                <i class="bi bi-check text-success"></i> Terminer
            </button>
        </form>
    </li>

    <!-- Bouton "Générer Facture" -->
    <li>
        <form action="{{ route('facture.generates', $commande->id_commande) }}" method="GET" style="display: inline;">
            @csrf
            <button type="submit" class="dropdown-item" @if ($commande->status !== 'terminé') disabled @endif>
                <i class="bi bi-file-earmark-ppt"></i> Générer Facture
            </button>
        </form>
    </li>

    <!-- Bouton "Voir Détails" -->
    <li>
        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $commande->id_commande }}">
            <i class="bi bi-info-circle text-primary"></i> Voir Détails
        </a>
    </li>
</ul>

                            </div>
                        </td>
                        <td>
                            <div class="modal fade" id="detailsModal{{ $commande->id_commande }}" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Détails de la Commande {{ $commande->id_commande }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Quantité</th>
                                                        <th>Prix Total (FCFA)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($commande->details_personnalises as $detail)
                                                        @php
                                                            $produitsQuantites = json_decode($detail->produits_quantites, true);
                                                        @endphp
                                                        @foreach ($produitsQuantites as $item)
                                                            @php
                                                                $produit = $produits_aliment[$item['id_produit']] ?? null;
                                                                $produitNom = $produit->nom ?? 'Produit inconnu';
                                                                $uniteMesure = $produit->unité_de_mesure ?? '';
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $produitNom }}</td>
                                                                <td>{{ $item['quantite'] }} {{ $uniteMesure }}</td>
                                                                <td>{{ $detail->prix_total }}</td>
                                                            </tr>
                                                        @endforeach
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
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>

</div>
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
