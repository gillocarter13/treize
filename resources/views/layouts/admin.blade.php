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
  <link href="assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    @yield('css')
<!-- Ajouter ce lien dans la section <head> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vendor CSS Files -->
    {{-- <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"> --}}
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
                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}">
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
<a class="nav-link collapsed" href="{{ route('admin.home') }}">
            <i class="bi bi-grid"></i>
            <span>Accueil</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people"></i><span>Utilisateur</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

            <li>
                <a href="{{ route('admin.users') }}">
                    <i class="bi bi-circle"></i><span>Gestion des utilisateurs</span>
                </a>
            </li>
        </ul>

    </li>
     <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-person-add"></i><span>Fournisseurs</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('admin.create_fournisseur') }}">
              <i class="bi bi-circle"></i><span>Ajouter Fournisseur</span>
            </a>
          </li>

        </ul>
    </li>

 <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-cup-hot"></i><span>Produits</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('admin.create_produit') }}">
              <i class="bi bi-circle"></i><span>Ajouter produit</span>
            </a>
          </li>

        </ul>
    </li>


 <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Menu</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('admin.create_plat') }}">
              <i class="bi bi-circle"></i><span>Ajouter Menu</span>
            </a>

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





    </main><!-- End #main -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    @yield('js')
    <!-- Vendor JS Files -->
    <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
    {{-- <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script> --}}
    <script src="{{asset('assets/vendor/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('assets/vendor/echarts/echarts.min.js')}}"></script>
    <script src="{{asset('assets/vendor/quill/quill.min.js')}}"></script>
    <script src="{{asset('assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
    <script src="{{asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.getElementById('upload-link').addEventListener('click', function(e) {
        e.preventDefault(); // Empêche le lien d'agir comme un lien normal
        document.getElementById('profileImage').click(); // Ouvre la fenêtre de sélection de fichier
    });

    document.getElementById('profileImage').addEventListener('change', function() {
        // Met à jour le texte du lien avec le nom du fichier sélectionné
        var fileName = this.files[0] ? this.files[0].name : "Sélectionner une image";
        document.getElementById('upload-link').textContent = fileName;
    });
</script>
<script>
    function editFournisseur(id, nom, contact, adresse) {
        document.getElementById('editFournisseurForm').action = "{{ url('fournisseur') }}/" + id; // Mettre à jour l'URL du formulaire
        document.getElementById('edit_nom').value = nom;
        document.getElementById('edit_contact').value = contact;
        document.getElementById('edit_adresse').value = adresse;
    }
</script>
<script>
  function confirmDelete(produitId) {
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Cette action ne peut pas être annulée!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            // Soumettre le formulaire de suppression lorsque la confirmation est validée
            document.getElementById('delete-form-' + produitId).submit();
        }
    });
}

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const produitsSelect = document.getElementById('produits');
    const quantitesContainer = document.getElementById('quantitesContainer');

    // Fonction pour générer des champs de quantité pour chaque produit sélectionné
    function generateQuantityFields(selectedProduits) {
        // Vide le conteneur avant d'ajouter de nouveaux champs
        quantitesContainer.innerHTML = '';

        // Pour chaque produit sélectionné, créer un champ de quantité
        selectedProduits.forEach(function (produit) {
            const produitId = produit.value;
            const produitNom = produit.text;

            // Créer le conteneur de l'input
            const div = document.createElement('div');
            div.classList.add('mb-3');

            const label = document.createElement('label');
            label.textContent = `Quantité pour ${produitNom}`;
            label.classList.add('form-label');

            const input = document.createElement('input');
            input.type = 'number';
            input.name = `quantites[${produitId}]`;  // Utilise l'ID du produit comme clé
            input.classList.add('form-control');
            input.min = 0;
            input.step = 0.01;
            input.required = true;

            div.appendChild(label);
            div.appendChild(input);

            quantitesContainer.appendChild(div);
        });
    }

    // Écouter le changement de sélection de produits pour générer des champs de quantité
    produitsSelect.addEventListener('change', function () {
        generateQuantityFields(Array.from(produitsSelect.selectedOptions));
    });

    // Fonction pour ouvrir la modale d'édition avec les données existantes du plat
    window.openEditModal = function(plat) {
        document.getElementById('editNom').value = plat.nom;
        document.getElementById('editDescription').value = plat.description;
        document.getElementById('editPrix').value = plat.prix;

        // Pré-remplir les produits et quantités
        let produitsQuantites = JSON.parse(plat.produits_quantites);

        // Sélectionner les produits dans la liste déroulante
        produitsSelect.value = '';
        Array.from(produitsSelect.options).forEach(option => {
            if (produitsQuantites.some(pq => pq.id_produit == option.value)) {
                option.selected = true;
            }
        });

        // Générer les champs de quantités pour les produits pré-sélectionnés
        generateQuantityFields(Array.from(produitsSelect.selectedOptions));

        // Remplir les champs de quantités avec les valeurs existantes
        produitsQuantites.forEach(pq => {
            const quantiteInput = document.querySelector(`input[name="quantites[${pq.id_produit}]"]`);
            if (quantiteInput) {
                quantiteInput.value = pq.quantite;
            }
        });

        // Définir l'action du formulaire
        document.getElementById('editPlatForm').action = '/plats/' + plat.id_plat;

        // Ouvrir la modale
        var editModal = new bootstrap.Modal(document.getElementById('editPlatModal'));
        editModal.show();
    };
});
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const produitsSelect = document.getElementById('produits');
    const quantitesContainer = document.getElementById('quantitesContainer');

    produitsSelect.addEventListener('change', function () {
        // Vide le conteneur à chaque changement de sélection
        quantitesContainer.innerHTML = '';

        // Parcourir les produits sélectionnés
        Array.from(produitsSelect.selectedOptions).forEach(function (option) {
            const produitId = option.value;
            const produitNom = option.text;
            const unite = option.getAttribute('data-unite'); // Récupérer l'unité de mesure

            // Créer un champ de quantité pour chaque produit sélectionné
            const div = document.createElement('div');
            div.classList.add('mb-3');

            const label = document.createElement('label');
            label.textContent = `Quantité pour ${produitNom} (${unite})`; // Afficher l'unité de mesure
            label.classList.add('form-label');

            const input = document.createElement('input');
            input.type = 'number';
            input.name = `quantites[${produitId}]`;  // Utilise l'ID du produit comme clé
            input.classList.add('form-control');
            input.min = 0;
            input.step = 0.01;
            input.required = true;

            div.appendChild(label);
            div.appendChild(input);

            quantitesContainer.appendChild(div);
        });
    });
});
</script>

<script>
function openEditModal(plat) {
    // Remplir les champs du formulaire avec les données du plat
    document.getElementById('editNom').value = plat.nom;
    document.getElementById('editDescription').value = plat.description;
    document.getElementById('editPrix').value = plat.prix;

    // Gérer les produits et quantités
    let produitsQuantites = JSON.parse(plat.produits_quantites);
    document.querySelectorAll('#editProduits option').forEach(option => {
        option.selected = produitsQuantites.some(pq => pq.id_produit == option.value);
    });
    produitsQuantites.forEach(pq => {
        document.getElementById('editQuantite_' + pq.id_produit).value = pq.quantite;
    });

    // Définir l'action du formulaire
    document.getElementById('editPlatForm').action = '/plats/' + plat.id_plat;

    // Ouvrir la modale
    var editModal = new bootstrap.Modal(document.getElementById('editPlatModal'));
    editModal.show();
}
</script>


    <!-- Template Main JS File -->
    <script src="{{asset('assets/js/main.js')}}"></script>
<script>
        // Sélectionner le lien et l'input file
        const uploadLink = document.getElementById('upload-link');
        const fileInput = document.getElementById('profileImage');

        // Ajouter un événement click au lien
        uploadLink.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du lien
            fileInput.click(); // Déclenche l'événement click sur l'input file
        });
    </script>
<script>
    // Script pour ajouter des champs de quantité dynamiquement selon les produits sélectionnés
    document.getElementById('produits').addEventListener('change', function() {
        var selectedProducts = Array.from(this.selectedOptions).map(option => option.text);
        var container = document.getElementById('produitsQuantitesContainer');
        container.innerHTML = ''; // Vider le conteneur avant d'ajouter de nouveaux éléments

        selectedProducts.forEach(product => {
            var div = document.createElement('div');
            div.classList.add('mb-3');
            div.innerHTML = `
                <label for="${product}" class="form-label">Quantité pour ${product}</label>
                <input type="number" class="form-control" id="${product}" name="quantites[${product}]" required min="1">
            `;
            container.appendChild(div);
        });
    });


</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script>
function validateForm() {
    var quantity = parseInt(document.getElementById('quantité').value);
    var alertThreshold = parseInt(document.getElementById('seuil_alerte').value);
    var errorMessage = document.getElementById('error-message');

    // Réinitialiser le message d'erreur
    errorMessage.style.display = 'none';
    errorMessage.textContent = '';

    // Vérifier si le seuil d'alerte est inférieur à la quantité
    if (alertThreshold >= quantity) {
        errorMessage.textContent = 'Le seuil d\'alerte doit être strictement inférieur à la quantité.';
        errorMessage.style.display = 'block'; // Afficher le message d'erreur
        return false; // Empêcher l'envoi du formulaire
    }

    return true; // Permettre l'envoi du formulaire
}
</script>
    </body>

    </html>
