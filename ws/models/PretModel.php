<?php
require_once 'BaseModel.php';

class PretModel extends BaseModel {
    public function getAll() {
        return $this->fetchAll("SELECT * FROM pret");
    }

    public function getById($id) {
        return $this->fetchOne("SELECT * FROM pret WHERE id = ?", [$id]);
    }

    public function insert($data) {
        $sql = "INSERT INTO pret (
                    id_type_pret, id_client, date_debut, duree, date_fin, montant, id_type_remboursement
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [
            $data['id_type_pret'],
            $data['id_client'],
            $data['date_debut'],
            $data['duree'],
            $data['date_fin'],
            $data['montant'],
            $data['id_type_remboursement'] ?? null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE pret SET 
                    id_type_pret = ?, 
                    id_client = ?, 
                    date_debut = ?, 
                    duree = ?, 
                    date_fin = ?, 
                    montant = ?, 
                    id_type_remboursement = ?
                WHERE id = ?";
        return $this->execute($sql, [
            $data['id_type_pret'],
            $data['id_client'],
            $data['date_debut'],
            $data['duree'],
            $data['date_fin'],
            $data['montant'],
            $data['id_type_remboursement'] ?? null,
            $id
        ]);
    }

    public function delete($id) {
        return $this->execute("DELETE FROM pret WHERE id = ?", [$id]);
    }
}
