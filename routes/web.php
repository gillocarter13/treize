<?php

use App\Models\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CommandePersonnaliseeController;

Route::get('/password-reset', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/send-reset-password', [PasswordResetController::class, 'sendResetPassword'])->name(name: 'password.send');

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/connect', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profiles', [ProfileController::class, 'shows'])->name('profile.shows');

Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');
Route::post('/user/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::get('/user/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/user/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');
Route::post('/users', [ManageUserController::class, 'store'])->name('users.store');
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/home', [AdminHomeController::class, 'index'])->name('admin.home');
    Route::get('/admin/create_user', [AdminHomeController::class, 'createUser'])->name('admin.create_user');
   Route::post('/users', [ManageUserController::class, 'store'])->name('users.store');
    Route::get('/admin/users', [ManageUserController::class, 'index'])->name('admin.users');
    Route::get('/admin/edit_user/{id}', [ManageUserController::class, 'edit'])->name('admin.edit_user');
    Route::put('/admin/update_user/{id}', [ManageUserController::class, 'update'])->name('admin.update_user');
    Route::delete('/admin/delete_user/{id}', [ManageUserController::class, 'destroy'])->name('admin.delete_user');
    Route::get('/admin/create_product', [ProduitController::class, 'index'])->name('admin.create_produit');
    Route::post('/admin/produit', [ProduitController::class, 'store'])->name('produit.store');
    Route::patch('/plats/{id}/activer', [PlatController::class, 'activer'])->name('plats.activer');
    Route::patch('/plats/{id}/desactiver', [PlatController::class, 'desactiver'])->name('plats.desactiver');

    Route::get('produit/{id_produit}/edit', [ProduitController::class, 'edit'])->name('produit.edit');
    Route::put('/produits/{id_produit}', [ProduitController::class, 'update'])->name('produit.update');
    Route::delete('/produits/{id_produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');

    Route::delete('/produits/{id_produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');

    Route::get('/admin/create_fournisseur', [FournisseurController::class, 'index'])->name('admin.create_fournisseur');
    Route::post('/admin/fournisseur', [FournisseurController::class, 'store'])->name('fournisseur.store');
   Route::get('fournisseur/{id_fournisseur}/edit', [FournisseurController::class, 'edit'])->name('fournisseur.edit');
Route::put('/fournisseur/{id}', [FournisseurController::class, 'update'])->name('fournisseur.update');
    Route::delete('fournisseur/{id_fournisseur}', [FournisseurController::class, 'destroy'])->name('fournisseur.destroy');

    Route::post('/produit/store-historique', [ProduitController::class, 'storeHistoriqueCommande'])->name('produit.storeHistorique');
    Route::get('/commandes/historique', [CommandeController::class, 'cmdHistorique'])->name('commandes.historique');
    Route::get('/dashboard/total-achats', [DashboardController::class, 'totalAchats'])->name('totalAchats');


    Route::get('/admin/create_plat', [PlatController::class, 'index'])->name('admin.create_plat');
    Route::post('/plats', [PlatController::class, 'store'])->name('plat.store');

    Route::get('/admin/create_table', [TableController::class, 'index'])->name('admin.table');
    Route::post('/table', [TableController::class, 'store'])->name('table.store');
    Route::get('/plats/{id}/edit', [PlatController::class, 'edit'])->name('plats.edit');
    Route::put('/plats/{id_plat}', [PlatController::class, 'update'])->name('plats.update');
    Route::delete('/plats/{id_plat}', [PlatController::class, 'destroy'])->name('plats.destroy');
});

// Routes pour les utilisateurs
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/user/home', [UsersController::class, 'index'])->name('user.home');
    Route::get('/user/commande', [CommandeController::class, 'index'])->name('user.commande');
    // Route pour afficher le formulaire de commande (modal)
    Route::get('/commander', [CartController::class, 'showOrderForm'])->name('order.form');

    // Route pour ajouter une commande
    Route::get('/add-cart', [CartController::class, 'showAddCartForm'])->name('cart.form');
    Route::post('/add-carts', [CartController::class, 'stores'])->name('cart.add');
    // Route pour afficher les commandes (historique des commandes)
    Route::get('/commandes', [OrderController::class, 'showOrders'])->name('orders.show');
    // Route pour afficher le formulaire de commande
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');

    // Route pour ajouter une commande
    // Afficher une commande spécifique
    Route::get('/commandes/{id}', [OrderController::class, 'showOrder'])->name('orders.showDetail');
    Route::post('/commande/personnalisee', [CommandeController::class, 'storePersonnalisee'])->name('commande.storePersonnalisee');
    Route::put('commandes/{id}/update-status', [CommandeController::class, 'updateStatus'])->name('commandes.updateStatus');

    Route::get('/user/commandes', [CommandePersonnaliseeController::class, 'index'])->name('user.commande_p');
    Route::post('/commandes/personnalisees/store', [CommandePersonnaliseeController::class, 'store'])->name('commandes.store_personnalisees');
    // Mettre à jour le statut d'une commande
    Route::post('/commandes/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/user/create_user', [AdminHomeController::class, 'createUser'])->name('admin.create_user');

    Route::put('/commandes/{id}', [CommandeController::class, 'update'])->name('commandes.update');
    Route::get('/facture/generates  /{id_commande}', [CommandeController::class, 'generateInvoice'])->name('facture.generate');
    Route::get('/facture/generate/{id_commande}', [CommandePersonnaliseeController::class, 'generateInvoice'])->name('facture.generates');

    // Autres routes pour les utilisateurs simples
});

