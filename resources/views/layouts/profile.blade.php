@extends('layouts.admin')
@section('title', __("Accueil"))
@section("title_content", __("Page"))
@section("content")

<section class="section profile">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
     @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <!-- Affichage de l'image de profil -->
                    @if(Auth::check())
                        @php
                            $profileImage = Auth::user()->profile_image
                                ? asset('storage/' . Auth::user()->profile_image)
                                : asset('assets/img/default-profile.png'); // Chemin vers l'image par défaut
                        @endphp
                        <img src="{{ $profileImage }}" alt="Profile" class="rounded-circle" width="150">
                        <h2>{{ Auth::user()->name }}</h2>
                        <span>{{ Auth::user()->role->nom ?? 'Rôle inconnu' }}</span>
                    @else
                        <h2>Kevin Anderson</h2>
                        <h3>Web Designer</h3>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-2">

                        <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">

                            <!-- Profile Edit Form -->
                          <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <!-- Lien pour sélectionner une image -->
                                    <a href="#" id="upload-link" class="col-md-4 col-lg-3 col-form-label">Sélectionner une image</a>
                                    <div class="col-md-8 col-lg-9">
                                        <div class="pt-2">
                                            <!-- Champ d'input pour le fichier -->
                                            <input type="file" id="profileImage" name="profile_image" accept="image/*" style="display: none;">
                                            <button type="submit" class="btn btn-primary btn-sm" title="Télécharger l'image de profil">
                                                <i class="bi bi-upload"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form><!-- End Profile Edit Form -->
                        </div>

                     <div class="tab-pane fade pt-3" id="profile-change-password">

    <!-- Change Password Form -->
                           <form action="{{ route('profile.change_password') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="current_password" type="password" class="form-control" id="currentPassword" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="new_password" type="password" class="form-control" id="newPassword" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                        <input name="new_password_confirmation" type="password" class="form-control" id="renewPassword" required>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>

</div>

                    </div><!-- End Bordered Tabs -->
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
