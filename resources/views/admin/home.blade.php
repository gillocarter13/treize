@extends('layouts.admin')

@section('title', __("Accueil"))
@section("title_content", __("Page"))
@section("content")
<div class="container">
    <div class="form-col-md-3">
        <section class="section dashboard">
            <div class="row">
                <!-- Card for Total Sales (Ventes) -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filtrer par</h6>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.home', ['filtreVentes' => 'aujourdhui']) }}">Aujourd'hui</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.home', ['filtreVentes' => 'mois']) }}">Ce Mois</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.home', ['filtreVentes' => 'annee']) }}">Cette Année</a></li>
                                <li>
                                    <form action="{{ route('admin.home') }}" method="GET" class="dropdown-item">
                                        <input type="date" name="date_ventes" class="form-control" onchange="this.form.submit()">
                                        <button type="submit" style="display: none;">Filtrer</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                Ventes
                                <span>| {{ request('filtreVentes') === 'aujourdhui' ? "Aujourd'hui" : (request('filtreVentes') === 'mois' ? 'Ce Mois' : (request('filtreVentes') === 'annee' ? 'Cette Année' : (request('date_ventes') ? \Carbon\Carbon::parse(request('date_ventes'))->translatedFormat('d F Y') : ''))) }}</span>
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $sommeEncaissee }} FCFA</h6>
                                    <span class="text-success small pt-1 fw-bold">+{{ $totalPlatsVendus }} plats vendus</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card for Total Purchases (Achats) -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filtrer par</h6>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.home', ['filtreAchats' => 'aujourdhui']) }}">Aujourd'hui</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.home', ['filtreAchats' => 'mois']) }}">Ce Mois</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.home', ['filtreAchats' => 'annee']) }}">Cette Année</a></li>
                                <li>
                                    <form action="{{ route('admin.home') }}" method="GET" class="dropdown-item">
                                        <input type="date" name="date_achats" class="form-control" onchange="this.form.submit()">
                                        <button type="submit" style="display: none;">Filtrer</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                Achats
                                <span>| {{ request('filtreAchats') === 'aujourdhui' ? "Aujourd'hui" : (request('filtreAchats') === 'mois' ? 'Ce Mois' : (request('filtreAchats') === 'annee' ? 'Cette Année' : (request('date_achats') ? \Carbon\Carbon::parse(request('date_achats'))->translatedFormat('d F Y') : ''))) }}</span>
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                                        <i class="bi bi-cart"></i>

                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalAchats }} FCFA</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          <!-- Top Selling -->
<div class="col-12">
  <div class="card top-selling overflow-auto" style="height: 300px;">
  

    <div class="card-body pb-0">
      <h5 class="card-title">Top Selling  <span>| aujourdhui</span></h5>

      @if ($topPlats->isEmpty())
        <div class="text-center text-muted">
          <p>Aucune vente n'a encore été enregistrée pour cette période.</p>
        </div>
      @else
        <table class="table table-borderless">
          <thead>
            <tr>
              <th scope="col">Product</th>
              <th scope="col">Price</th>
              <th scope="col">Sold</th>
              <th scope="col">Revenue</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($topPlats as $plat)
              <tr>
                <td><a href="#" class="text-primary fw-bold">{{ $plat['nom_plat'] }}</a></td>
                <td>{{ $plat['prix'] }} fcfa</td>
                <td class="fw-bold">{{ $plat['quantite_vendue'] }}</td>
                <td>{{ $plat['revenue_total'] }} fcfa</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif

    </div>
  </div>
</div>


        </section>
    </div>
</div>
@endsection
