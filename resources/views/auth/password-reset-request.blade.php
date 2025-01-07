<!-- resources/views/auth/password-reset-request.blade.php -->
@extends('layouts.app')

@section('title', 'Demande de réinitialisation du mot de passe')

@push('styles')
<!-- Si vous avez besoin de styles personnalisés, ajoutez-les ici -->
@endpush

@section('content')
<main class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center py-4">
                    <a href="index.html" class="logo d-flex align-items-center w-auto">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="">
                        <span class="d-none d-lg-block">Restaurant 13</span>
                    </a>
                </div>
                <h5 class="card-title text-center">Réinitialiser le mot de passe</h5>
                <p class="text-center">Veuillez entrer votre adresse email pour recevoir un mot de passe temporaire.</p>

                <!-- Affichage des messages de succès ou d'erreur -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Formulaire de demande de réinitialisation -->
                <form action="{{ route('password.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50">Envoyer le mot de passe</button>
                    </div>
                </form>

                <!-- Lien vers la page de connexion -->
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Retour à la page de connexion</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
