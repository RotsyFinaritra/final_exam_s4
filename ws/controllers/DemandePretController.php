<?php
require_once __DIR__ . '/../models/DemandePretModel.php';

class DemandePretController {
    private $demandePretModel;

    public function __construct() {
        $this->demandePretModel = new DemandePretModel();
    }

    public function getAll() {
        $demandes = $this->demandePretModel->getAll();
        Flight::json($demandes);
    }

    public function getOne($id) {
        $demande = $this->demandePretModel->getById($id);
        if ($demande) {
            Flight::json($demande);
        } else {
            Flight::json(['error' => 'Demande non trouvée'], 404);
        }
    }

    public function create() {
        $data = Flight::request()->data->getData();
        $requiredFields = ['id_client', 'date_demande', 'duree_demande', 'montant'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                Flight::json(['error' => "Le champ $field est requis"], 400);
                return;
            }
        }

        $result = $this->demandePretModel->insert($data);
        if ($result) {
            
            Flight::json(['success' => $result], 200);
        } else {
            Flight::json(['error' => 'Erreur lors de la création de la demande'], 500);
        }
    }

    public function update($id) {
        $data = Flight::request()->data->getData();
        $requiredFields = ['id_client', 'date_demande', 'duree_demande', 'montant', 'id_statut_demande'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                Flight::json(['error' => "Le champ $field est requis"], 400);
                return;
            }
        }

        $result = $this->demandePretModel->update($id, $data);
        if ($result) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['error' => 'Erreur lors de la mise à jour de la demande ou demande non trouvée'], 404);
        }
    }

    public function delete($id) {
        $result = $this->demandePretModel->delete($id);
        if ($result) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression de la demande ou demande non trouvée'], 404);
        }
    }
}
?>