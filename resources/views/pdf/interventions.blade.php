<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Interventions</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .logo { max-width: 150px; }
        .date { text-align: right; font-size: 14px; }
        .signature { margin-top: 50px; text-align: left; }
        .signature hr { width: 200px; }
        .title { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .intervention { border-bottom: 1px solid #ddd; padding: 10px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <h2>ANEP</h2>
    </div>

    <!-- Date du jour -->
    <p class="date">Date : {{ now()->format('d/m/Y') }}</p>



    <div class="title">Détails des Interventions</div>

    @foreach($interventions as $intervention)
    <div class="intervention">
        <p><span class="label">Nom :</span> {{ $intervention->nom_intervention }}</p>
        <p><span class="label">Utilisateur :</span> {{ $intervention->user->name ?? 'N/A' }}</p>
        <p><span class="label">Technicien :</span> {{ $intervention->technician->name ?? 'N/A' }}</p>
        <p><span class="label">Type :</span> {{ $intervention->type }}</p>
        <p><span class="label">Date de Création :</span> {{ $intervention->date_creation }}</p>
        <p><span class="label">Date de Fin :</span> {{ $intervention->date_fin ?? 'Non définie' }}</p>
        <p><span class="label">Statut :</span> {{ $intervention->status }}</p>
        <p><span class="label">Description :</span> {{ $intervention->description }}</p>
    </div>
    @endforeach

</body>
</html>
