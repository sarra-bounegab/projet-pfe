<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Intervention #{{ $intervention->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Georgia', serif;
            font-size: 14px;
            color: #000;
            margin: 40px;
            line-height: 1.6;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: block;
                text-align: center;
                margin-top: 30px;
            }
            .no-print a {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                font-size: 16px;
                text-decoration: none;
                border-radius: 5px;
            }
        }
    </style>
</head>
<body class="bg-white">

<header class="text-center mb-10">
    <img src="{{ public_path('images/logo.png') }}" alt="Logo Entreprise" class="h-16 mx-auto mb-4">
    <h1 class="text-3xl font-bold mb-2">Rapport d'Intervention</h1>
    <h2 class="text-xl font-medium">Entreprise [Nom de l'entreprise]</h2>
</header>

<main class="px-6">
    <!-- Section Informations Générales -->
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 border-b pb-2">Informations Générales</h3>
        <table class="min-w-full text-left border-collapse">
            <tr class="border-b">
                <td class="py-2 font-bold">ID</td>
                <td class="py-2">{{ $intervention->id }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 font-bold">Titre</td>
                <td class="py-2">{{ $intervention->titre }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 font-bold">Statut</td>
                <td class="py-2">
                    <span class="px-3 py-1 rounded-full text-white 
                        {{ $intervention->status === 'Terminé' ? 'bg-green-500' : ($intervention->status === 'En cours' ? 'bg-yellow-500' : 'bg-gray-500') }}">
                        {{ $intervention->status }}
                    </span>
                </td>
            </tr>
            <tr class="border-b">
                <td class="py-2 font-bold">Date Intervention</td>
                <td class="py-2">{{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y') }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 font-bold">Description</td>
                <td class="py-2">{{ $intervention->description ?? 'Aucune description' }}</td>
            </tr>
        </table>
    </div>

    <!-- Section Détails Techniques (si intervention terminée) -->
    @if($intervention->status === 'Terminé')
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 border-b pb-2">Détails Techniques</h3>
        @forelse($intervention->details as $detail)
        <table class="min-w-full text-left border-collapse mb-4">
            <tr class="border-b">
                <td class="py-2 font-bold">Type</td>
                <td class="py-2">{{ $detail->type->type ?? 'Non défini' }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 font-bold">Technicien</td>
                <td class="py-2">{{ $detail->technicien->name ?? 'Non assigné' }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 font-bold">Statut</td>
                <td class="py-2">{{ $detail->status ?? 'N/A' }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 font-bold">Contenu</td>
                <td class="py-2">{{ $detail->contenu ?? 'Aucun contenu' }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 font-bold">Description</td>
                <td class="py-2">{{ $detail->description ?? 'Aucune description' }}</td>
            </tr>
        </table>
        @empty
        <p>Aucun détail technique enregistré.</p>
        @endforelse
    </div>
    @endif

    <!-- Section Historique des Actions -->
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 border-b pb-2">Historique des Actions</h3>
        @forelse($intervention->historiques as $historique)
        <p class="mb-4"><strong>{{ \Carbon\Carbon::parse($historique->created_at)->format('d/m/Y H:i') }} :</strong> {{ $historique->action }}</p>
        @empty
        <p>Aucune action historique enregistrée.</p>
        @endforelse
    </div>
    <p class="mt-4 space-x-4">
    <a href="javascript:window.print()" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
        Imprimer
    </a>
   
</p>

    
</main>

<footer class="no-print">
    <p><a href="javascript:window.print()" class="bg-blue-500 text-white px-6 py-2 rounded">Imprimer</a></p>
</footer>

</body>
</html>
