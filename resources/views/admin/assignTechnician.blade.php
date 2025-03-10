<!-- Modal d’assignation -->
<div id="assignTechnicianModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-5 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-3">Attribuer un technicien</h2>
        <form id="assignTechnicianForm" method="POST">
    @csrf
    @method('PUT')

            
            <input type="hidden" name="technicien_id" value="{{ Auth::id() }}">
            <input type="hidden" id="intervention_id" name="intervention_id">
            
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                Confirmer l’attribution
            </button>
            <button type="button" id="closeModal" class="ml-2 bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                Annuler
            </button>
        </form>
    </div>
</div>


