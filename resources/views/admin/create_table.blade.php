@extends('layouts.admin')

@section('title', __("Gestion des Tables"))

@section("content")
 <div class="pagetitle">
      <h1>Ajouter une Table</h1>
 </div><!-- End Page Title -->

 <section class="section">
     <div class="row">
         <div class="col-lg-12">
             <div class="card">
                 <div class="card-body">
                     <h5 class="card-title"></h5>
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

                         <div class="d-flex justify-content-between mb-10">
                             <!-- Bouton pour ajouter une table -->
                             <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTableModal">
                                 <i class="bi bi-plus-circle"></i> Ajouter Table
                             </button>
                         </div>

                         <!-- Modal d'ajout de table -->
                         <div class="modal fade" id="addTableModal" tabindex="-1">
                             <div class="modal-dialog modal-xl">
                                 <div class="modal-content">
                                     <div class="modal-header">
                                         <h5 class="modal-title">Ajouter une Table</h5>
                                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                     </div>
                                     <div class="modal-body">
                                         <form action="{{ route('table.store') }}" method="POST">
                                             @csrf
                                          <div class="mb-3">
                                                <label for="nom_table" class="form-label">Nom de la Table</label>
                                                <input type="text" class="form-control" id="nom_table" name="nom_table" required>
                                            </div>
                                             <div class="mb-3">
                                                 <label for="capacité" class="form-label">Capacité</label>
                                                 <input type="number" class="form-control" id="capacité" name="capacité" required>
                                             </div>

                                             <button type="submit" class="btn btn-primary">Ajouter</button>
                                         </form>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <!-- Tableau des tables -->
                         <table class="table datatable mt-4">
                             <thead>
                                 <tr>
                                     <th><b>ID</b></th>
                                     <th><b>Nom</b></th>
                                     <th><b>Capacité</b></th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @forelse ($tables as $table)
                                 <!-- Modal d'édition de table -->
                                 <div class="modal fade" id="editTableModal{{ $table->id_table }}" tabindex="-1">
                                     <div class="modal-dialog modal-xl">
                                         <div class="modal-content">
                                             <div class="modal-header">
                                                 <h5 class="modal-title">Éditer une Table</h5>
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body">
                                                 <form id="editTableForm" action="" method="POST">
                                                     @csrf
                                                     @method('PUT')
                                                     <div class="mb-3">
                                                         <label for="edit_nom{{ $table->id_table }}" class="form-label">Nom de la Table</label>
                                                         <input type="text" class="form-control" id="edit_nom{{ $table->id_table }}" name="nom" value="{{ $table->nom }}" required>
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="edit_capacité{{ $table->id_table }}" class="form-label">Capacité</label>
                                                         <input type="number" class="form-control" id="edit_capacité{{ $table->id_table }}" name="capacité" value="{{ $table->capacité }}" required>
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="edit_emplacement{{ $table->id_table }}" class="form-label">Emplacement</label>
                                                         <input type="text" class="form-control" id="edit_emplacement{{ $table->id_table }}" name="emplacement" value="{{ $table->emplacement }}" required>
                                                     </div>
                                                     <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                                 </form>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <tr>
                                     <td>{{ $table->id_table }}</td>
                                     <td>{{ $table->nom_table }}</td>
                                     <td>{{ $table->capacité }}</td>

                                 </tr>
                                 @empty
                                 <tr>
                                     <td colspan="5">Aucune table trouvée</td>
                                 </tr>
                                 @endforelse
                             </tbody>
                         </table>

                         <!-- Pagination -->
                         <div class="d-flex justify-content-center">
                             {{ $tables->links('pagination::bootstrap-5') }}
                         </div>
                     </section>
                 </div>
             </div>
         </div>
     </div>
 </section>
@endsection
