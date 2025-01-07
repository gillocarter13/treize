    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <script>
        var csrfToken ="{{csrf_token()}}";
    </script>

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    @yield('css')
<!-- Ajouter ce lien dans la section <head> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vendor CSS Files -->
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">

    <!-- =======================================================
    * Template Name: NiceAdmin
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Updated: Mar 17 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
       <style>
        /* Cacher l'input file */
        input[type="file"] {
            display: none;
        }
    </style>
    </head>

    <body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{asset('assets/img/logo.png')}}" alt="">
            <span class="d-none d-lg-block">Restaurant</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
            <a class="nav-link nav-icon search-bar-toggle " href="#">
                <i class="bi bi-search"></i>
            </a>
            </li><!-- End Search Icon-->



            <li class="nav-item dropdown pe-3">

                   <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    @php
    $profileImage = Auth::check() && Auth::user()->profile_image
        ? asset('storage/' . Auth::user()->profile_image)
        : asset('assets/img/default-profile.png'); // Image par défaut si aucune image n'est définie
@endphp

<!-- Le code existant pour afficher l'image dans la navbar -->
<img src="{{ $profileImage }}" alt="Profile" class="rounded-circle" width="40">
            @if(Auth::check())
                <span class="d-none d-md-block dropdown-toggle ps-2">
                    {{ Auth::user()->name }}
                </span>
            @else
                <span class="d-none d-md-block dropdown-toggle ps-2">Guest</span>
            @endif
          </a><!-- End Profile Image Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              @if(Auth::check())
                  <h6>{{ Auth::user()->name }}</h6>
                  <span>{{ Auth::user()->email }}</span>
              @else
                  <h6>Guest</h6>
                  <span>Not logged in</span>
              @endif
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

           <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.shows') }}">
                    <i class="bi bi-person"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
                <li>
                <hr class="dropdown-divider">
                </li>


                <li>
                <hr class="dropdown-divider">
                </li>

                <li>
                <a href="{{ route('logout') }}"
    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="bi bi-box-arrow-right"></i>
    Sign Out
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
                </li>

            </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
<a class="nav-link collapsed" href="{{ route('user.home') }}">
            <i class="bi bi-grid"></i>
            <span>Accueil</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people"></i><span>commande</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

            <li>
                <a href="{{ route('user.commande') }}">
                    <i class="bi bi-circle"></i><span>enregstrer commande</span>
                </a>
                <a href="{{ route('user.commande_p') }}">
                    <i class="bi bi-circle"></i><span> commande personalisée</span>
                </a>
            </li>
        </ul>

    </li>



</ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
        <h1></h1>
        </div><!-- End Page Title -->

        @yield('content')
        <section class="section">

        </section>

        @if ($errors->any())
            <div>
                <div class="text-center bg-warning col-md-2">Something went wrong!</div>
            </div>
            <ul>
                @foreach ($errors->all() as $error )
                <li class="list-unstyled text-center">{{$error }}</li>
                @endforeach
            </ul>

        @endif



    </main><!-- End #main -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    @yield('js')
    <!-- Vendor JS Files -->
    <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('assets/vendor/echarts/echarts.min.js')}}"></script>
    <script src="{{asset('assets/vendor/quill/quill.min.js')}}"></script>
    <script src="{{asset('assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
    <script src="{{asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
    function confirmUpdate(commandeId) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Vous êtes sur le point de terminer cette commande.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, terminer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Soumettre le formulaire de mise à jour lorsque la confirmation est validée
                document.getElementById('update-form-' + commandeId).submit();
            }
        });
    }
    </script>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const platItems = document.querySelectorAll('.plat-item');

        platItems.forEach(item => {
            const title = item.querySelector('.card-title').textContent.toLowerCase();
            const price = item.querySelector('.plat-price').textContent.toLowerCase();

            // Filtre par le nom du plat ou le prix
            if (title.includes(query) || price.includes(query)) {
                item.style.display = 'block'; // Affiche l'élément
            } else {
                item.style.display = 'none'; // Cache l'élément
            }
        });
    });
</script>
<script>document.addEventListener('DOMContentLoaded', function () {
    const produitsSelect = document.getElementById('produits');
    const quantitesContainer = document.getElementById('quantites-container');

    // Fonction pour mettre à jour les champs de quantité
    function updateQuantitesFields() {
        // Vider le conteneur des champs de quantité
        quantitesContainer.innerHTML = '';

        // Récupérer les produits sélectionnés
        const selectedOptions = Array.from(produitsSelect.selectedOptions);

        // Pour chaque produit sélectionné, créer un champ de quantité
        selectedOptions.forEach(function(option) {
            const produitId = option.value;
            const produitNom = option.text;

            // Créer un label et un champ de texte pour la quantité
            const label = document.createElement('label');
            label.textContent = `Quantité pour ${produitNom}: `;
            label.setAttribute('for', `quantite_${produitId}`);

            const input = document.createElement('input');
            input.type = 'number';
            input.name = `quantites[${produitId}]`; // Chaque champ a un nom unique lié à l'ID du produit
            input.id = `quantite_${produitId}`;
            input.classList.add('form-control');
            input.min = 1; // Quantité minimum = 1
            input.placeholder = `Quantité pour ${produitNom}`;

            // Ajouter le label et l'input dans le conteneur
            quantitesContainer.appendChild(label);
            quantitesContainer.appendChild(input);
        });
    }

    // Attacher un événement à la sélection des produits
    produitsSelect.addEventListener('change', updateQuantitesFields);
});
</script>

    </body>

    </html>
