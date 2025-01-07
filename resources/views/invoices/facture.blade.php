<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facture #{{ $commande->id }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>
  <h1>Facture #{{ $commande->id }}</h1>
  <p>Date de la commande : {{ $commande->created_at }}</p>
  <p>Statut : {{ $commande->status }}</p>

  <table>
    <thead>
      <tr>
        <th>Plat</th>
        <th>Quantité</th>
        <th>Prix Unitaire</th>
        <th>Prix Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($commande->details as $detail)
      <tr>
        <td>{{ $detail->plat->nom }}</td>
        <td>{{ $detail->quantite }}</td>
        <td>{{ number_format($detail->prix_unitaire, 2, ',', ' ') }} €</td>
        <td>{{ number_format($detail->prix_total, 2, ',', ' ') }} €</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <h2>Total : {{ number_format($commande->details->sum('prix_total'), 2, ',', ' ') }} €</h2>
</body>

</html>