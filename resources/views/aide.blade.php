@extends('layouts.app')

<!-- Définition de la variable de couleur principale pour ANEP -->
<style>
:root {
    --anep-primary: #38A169; /* Couleur verte par défaut */
    --anep-secondary: #2F855A;
    --anep-light: #C6F6D5;
}
</style>

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-10">
        <h1 class="text-4xl font-bold" style="color: var(--anep-primary)">Centre d'aide ANEP</h1>
        <p class="text-xl text-gray-600 mt-3 pb-3 border-b border-gray-200">Découvrez comment utiliser efficacement notre application de gestion des interventions</p>
    </div>

    {{-- SECTION 1 -  SOUMETTRE UNE INTERVENTION --}}
    <div class="bg-white rounded-lg shadow-md mb-8 overflow-hidden">
        <div class="px-6 py-4 text-white" style="background-color: var(--anep-primary)">
            <h2 class="text-xl font-semibold flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Soumettre une intervention
            </h2>
        </div>
        <div class="p-6">
            <div class="flex items-center mb-6">
                <div class="p-3 rounded-full mr-4" style="background-color: var(--anep-light)">
                    <i class="fas fa-plus-circle fa-lg" style="color: var(--anep-primary)"></i>
                </div>
                <p class="text-lg">Pour signaler un problème, cliquez sur <span class="px-2 py-1 rounded text-sm text-white" style="background-color: var(--anep-primary)">Ajouter interventions</span>  dans la barre latérale.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="pl-4 py-2" style="border-left: 4px solid var(--anep-primary)">
                    <h3 class="text-lg font-medium flex items-center" style="color: var(--anep-primary)">
                        <i class="fas fa-pen mr-2"></i>
                        Titre
                    </h3>
                    <p class="mt-2 text-gray-700">Choisissez un titre court et précis décrivant le problème rencontré.</p>
                </div>
                <div class="pl-4 py-2" style="border-left: 4px solid var(--anep-primary)">
                    <h3 class="text-lg font-medium flex items-center" style="color: var(--anep-primary)">
                        <i class="fas fa-align-left mr-2"></i>
                        Description
                    </h3>
                    <p class="mt-2 text-gray-700">Détaillez clairement la panne ou l'incident pour faciliter sa résolution.</p>
                </div>
                <div class="pl-4 py-2" style="border-left: 4px solid var(--anep-primary)">
                    <h3 class="text-lg font-medium flex items-center" style="color: var(--anep-primary)">
                        <i class="fas fa-tag mr-2"></i>
                        Type 
                    </h3>
                    <p class="mt-2 text-gray-700">Sélectionnez le type qui convient avec votre probleme</p>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-medium mb-4 flex items-center" style="color: var(--anep-primary)">
                    <i class="fas fa-th-large mr-2"></i>
                    Sélection du type d'intervention
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="radio" id="typeHardware" name="interventionType" value="hardware" checked class="hidden peer">
                        <label for="typeHardware" class="block p-4 bg-white rounded-lg shadow-sm border border-gray-200 cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="mr-4" style="color: var(--anep-primary)">
                                    <i class="fas fa-desktop fa-2x"></i>
                                </div>
                                <div>
                                    <span class="block font-medium mb-1">Matériel</span>
                                    <span class="text-sm text-gray-500">Pannes physiques: PC, imprimante, réseau, périphériques, téléphonie</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="relative">
                        <input type="radio" id="typeSoftware" name="interventionType" value="software" class="hidden peer">
                        <label for="typeSoftware" class="block p-4 bg-white rounded-lg shadow-sm border border-gray-200 cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="mr-4" style="color: var(--anep-primary)">
                                    <i class="fas fa-code fa-2x"></i>
                                </div>
                                <div>
                                    <span class="block font-medium mb-1">Logiciel</span>
                                    <span class="text-sm text-gray-500">Bugs, installations, lenteurs, erreurs d'applications, comptes utilisateurs </span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="p-4 flex items-start" style="background-color: var(--anep-light); border-left: 4px solid var(--anep-primary)">
                <i class="fas fa-info-circle mr-3 mt-1" style="color: var(--anep-primary)"></i>
                <div>
                    Après l'envoi, votre demande sera <strong>analysée puis approuvée par un administrateur</strong> avant d'être assignée à un technicien pour prise en charge.
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2 - SUIVI DE TRAITEMENT  --}}
    <div class="bg-white rounded-lg shadow-md mb-8 overflow-hidden">
        <div class="px-6 py-4 text-white" style="background-color: var(--anep-primary)">
            <h2 class="text-xl font-semibold flex items-center">
                <i class="fas fa-tasks mr-2"></i>
                Suivi de votre intervention
            </h2>
        </div>
        <div class="p-6">
            <div class="pl-6 space-y-6" style="border-left: 2px solid var(--anep-primary)">
                <div class="relative pb-6">
                    <div class="absolute -left-8 top-0 p-1 rounded-full" style="background-color: var(--anep-primary)">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium" style="color: var(--anep-primary)">Notification automatique</h3>
                    <p class="mt-2 text-gray-700">Vous recevrez une notification par <strong>e-mail</strong> lorsque le technicien commencera ou terminera l'intervention.</p>
                </div>
                
                <div class="relative pb-6">
                    <div class="absolute -left-8 top-0 p-1 rounded-full" style="background-color: var(--anep-primary)">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium" style="color: var(--anep-primary)">Fiche d'intervention</h3>
                    <p class="mt-2 text-gray-700">À la fin, une <strong>fiche détaillée</strong> de l'intervention sera générée avec l'ensemble des actions effectuées.</p>
                </div>
                
                <div class="relative">
                    <div class="absolute -left-8 top-0 p-1 rounded-full" style="background-color: var(--anep-primary)">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium" style="color: var(--anep-primary)">Historique complet</h3>
                    <p class="mt-2 text-gray-700">Vous pourrez consulter l'historique complet dans la section <strong>"Historique des interventions"</strong>.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3 - STATISTIQUES ET HISTORIQUE  --}}
    <div class="bg-white rounded-lg shadow-md mb-8 overflow-hidden">
        <div class="px-6 py-4 text-white" style="background-color: var(--anep-primary)">
            <h2 class="text-xl font-semibold flex items-center">
                <i class="fas fa-chart-pie mr-2"></i>
                Statistiques et historique
            </h2>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-2/3 md:pr-6">
                    <h3 class="text-lg font-medium mb-3" style="color: var(--anep-primary)">Tableau de bord analytique</h3>
                    <p>Depuis la page <span class="px-2 py-1 rounded text-sm" style="background-color: var(--anep-light); color: var(--anep-secondary)">Statistiques</span>, consultez en temps réel :</p>
                    <ul class="mt-4 space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-chart-bar mr-3" style="color: var(--anep-primary)"></i>
                            <span>Le nombre total d'interventions soumises par période</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock mr-3" style="color: var(--anep-primary)"></i>
                            <span>Les interventions en attente, en cours ou clôturées</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-calendar-alt mr-3" style="color: var(--anep-primary)"></i>
                            <span>Les dates, durées de traitement et techniciens affectés</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chart-line mr-3" style="color: var(--anep-primary)"></i>
                            <span>L'évolution mensuelle des demandes d'intervention</span>
                        </li>
                    </ul>
                </div>
                <div class="md:w-1/3 mt-6 md:mt-0 text-center">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <i class="fas fa-chart-line fa-5x mx-auto opacity-75" style="color: var(--anep-primary)"></i>
                        <p class="text-gray-600 mt-2">Aperçu tableau de bord</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 4 - PROFIL UTILISATEUR  --}}
    <div class="bg-white rounded-lg shadow-md mb-8 overflow-hidden">
        <div class="px-6 py-4 text-white" style="background-color: var(--anep-primary)">
            <h2 class="text-xl font-semibold flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                Gérer mon profil
            </h2>
        </div>
        <div class="p-6">
            <p class="mb-6">Dans la barre latérale, cliquez sur votre nom pour afficher le menu déroulant de gestion de profil.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4" style="background-color: var(--anep-primary)">
                        <i class="fas fa-user-edit fa-lg text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">Informations personnelles</h3>
                    <p class="text-gray-600 text-sm">Modifier le nom et prénom</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4" style="background-color: var(--anep-primary)">
                        <i class="fas fa-lock fa-lg text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">Sécurité</h3>
                    <p class="text-gray-600 text-sm">Changer le mot de passe</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4" style="background-color: var(--anep-primary)">
                        <i class="fas fa-envelope fa-lg text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">Contact</h3>
                    <p class="text-gray-600 text-sm">Mettre à jour l'adresse email</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <div class="rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4" style="background-color: var(--anep-primary)">
                        <i class="fas fa-users fa-lg text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">Organisation</h3>
                    <p class="text-gray-600 text-sm">Changer de service/département</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 5 - IMPRESSION FICHE INTERVENTIONS --}}
    <div class="bg-white rounded-lg shadow-md mb-8 overflow-hidden">
        <div class="px-6 py-4 text-white" style="background-color: var(--anep-primary)">
            <h2 class="text-xl font-semibold flex items-center">
                <i class="fas fa-print mr-2"></i>
                Imprimer une intervention
            </h2>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-2/3">
                    <p>Une fois qu'une intervention est clôturée, accédez à son détail depuis l'historique et cliquez sur le bouton 
                        <button class="btn btn-sm text-black border">
                            <i class="fas fa-print mr-1"></i> Imprimer
                        </button> 
                        pour générer une version PDF ou papier.
                    </p>
                    
                    <div class="mt-4">
                        <h3 class="text-lg font-medium" style="color: var(--anep-primary)">La fiche d'intervention comprend :</h3>
                        <div class="flex flex-wrap">
                            <span class="badge bg-light text-dark m-1 p-2"><i class="fas fa-heading mr-1"></i> Titre</span>
                            <span class="badge bg-light text-dark m-1 p-2"><i class="fas fa-align-left mr-1"></i> Description</span>
                            <span class="badge bg-light text-dark m-1 p-2"><i class="fas fa-user-tie mr-1"></i> Nom Technicien</span>
                            <span class="badge bg-light text-dark m-1 p-2"><i class="fas fa-tasks mr-1"></i> Tâches effectuées</span>
                            <span class="badge bg-light text-dark m-1 p-2"><i class="fas fa-calendar-check mr-1"></i> Date de clôture</span>
                           
                        </div>
                    </div>
                </div>
                <div class="md:w-1/3 text-center">
                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection