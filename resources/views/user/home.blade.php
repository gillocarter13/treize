@extends('layouts.user')

@section('title', __("Accueil"))
@section("title_content", __("Page"))
@section("content")
<div class="container">
    <div class="form-col-md-3">

        @if (session('alert'))
            @php
                $alert = session('alert');
            @endphp
            <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                {{ $alert['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    </div>

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                        

                            <div class="card-body">
                                <h5 class="card-title">Plats Vendus <span>| Aujourd'hui</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalPlatsVendus }}</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Total Encaissement Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Montant Tot <span>| Aujourd'hui</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cash"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($sommeEncaissee, 2, ',', ' ') }} Fcfa</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

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
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('stockAlertToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
</script>
@endsection
