<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistiques</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .stat-box { padding: 15px; margin: 10px; border: 1px solid #ddd; display: inline-block; width: 45%; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="title">Statistiques Générales</div>

    <div class="stat-box">
        <h3>Total Utilisateurs</h3>
        <p>{{ $totalUsers }}</p>
    </div>

    <div class="stat-box">
        <h3>Total Interventions</h3>
        <p>{{ $totalInterventions }}</p>
    </div>

    <div class="stat-box">
        <h3>Interventions Terminées</h3>
        <p>{{ $completedInterventions }}</p>
    </div>

    <div class="stat-box">
        <h3>Interventions en Cours</h3>
        <p>{{ $pendingInterventions }}</p>
    </div>

</body>
</html>
