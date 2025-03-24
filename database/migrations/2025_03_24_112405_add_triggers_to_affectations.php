<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Trigger pour l'insertion
        DB::unprepared("
            CREATE TRIGGER after_affectation_insert
            AFTER INSERT ON affectations
            FOR EACH ROW
            BEGIN
                INSERT INTO historique_interventions (
                    intervention_id, user_id, technicien_id, ancien_technicien_id, titre, description, statut, date_creation, date_modification, action
                )
                SELECT 
                    i.id, NEW.user_id, NEW.technicien_id, NULL, i.titre, i.description, i.statut, NOW(), NOW(), 'Attribution'
                FROM interventions i
                WHERE i.id = NEW.intervention_id;
            END;
        ");

        // Trigger pour la mise à jour
        DB::unprepared("
            CREATE TRIGGER after_affectation_update
            AFTER UPDATE ON affectations
            FOR EACH ROW
            BEGIN
                IF OLD.technicien_id IS NOT NULL AND NEW.technicien_id IS NOT NULL AND OLD.technicien_id != NEW.technicien_id THEN
                    INSERT INTO historique_interventions (
                        intervention_id, user_id, technicien_id, ancien_technicien_id, titre, description, statut, date_creation, date_modification, action
                    )
                    SELECT 
                        i.id, NEW.user_id, NEW.technicien_id, OLD.technicien_id, i.titre, i.description, i.statut, NOW(), NOW(), 'Modification'
                    FROM interventions i
                    WHERE i.id = NEW.intervention_id;
                END IF;
            END;
        ");

        // Trigger pour la suppression
        DB::unprepared("
            CREATE TRIGGER after_affectation_delete
            AFTER DELETE ON affectations
            FOR EACH ROW
            BEGIN
                INSERT INTO historique_interventions (
                    intervention_id, user_id, technicien_id, ancien_technicien_id, titre, description, statut, date_creation, date_modification, action
                )
                SELECT 
                    i.id, OLD.user_id, NULL, OLD.technicien_id, i.titre, i.description, i.statut, NOW(), NOW(), 'Annulation'
                FROM interventions i
                WHERE i.id = OLD.intervention_id;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS after_affectation_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_affectation_update");
        DB::unprepared("DROP TRIGGER IF EXISTS after_affectation_delete");
    }
};
