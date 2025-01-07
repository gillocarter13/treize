@extends('layouts.admin')

@section('title', __("Historique des Commandes"))

@section("content")
<section class="section">
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

    <!-- Tableau des historiques de commandes -->
    <table class="table datatable">
        <thead>
            <tr>
                <th><b>ID Commande</b></th>
                <th><b>Produit</b></th>
                <th><b>Fournisseur</b></th>
                <th><b>Quantité</b></th>
                <th><b>Prix Unitaire</b></th>
                <th><b>Prix Total</b></th>
                <th><b>Seuil Alerte</b></th>
                <th><b>Date</b></th> <!-- Nouvelle colonne pour la date -->

            </tr>
        </thead>
        <tbody>
            @forelse ($historiques as $historique)
                <tr>
                    <td>{{ $historique->id_commande }}</td>
                    <td>{{ $historique->produit->nom }}</td> <!-- Utilisation de la relation produit -->
                    <td>{{ $historique->fournisseur->nom }}</td> <!-- Utilisation de la relation fournisseur -->
                    <td>{{ $historique->quantité }}</td>
                    <td>{{ number_format($historique->prix_unitaire, 2, ',', ' ') }} fcfa</td>
                    <td>{{ number_format($historique->prix_total, 2, ',', ' ') }} fcfa</td>
                    <td>{{ $historique->seuil_alerte }}</td>
                                       <td>{{ $historique->created_at->format('d/m/Y H:i') }}</td> <!-- Afficher la date formatée -->

                </tr>
            @empty
                <tr>
                    <td colspan="8">Aucune commande trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
