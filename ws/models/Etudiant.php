<?php
require_once __DIR__ . '/../db.php';

class Etudiant {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM etudiant");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM etudiant WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO etudiant (nom, prenom, email, age) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data->nom, $data->prenom, $data->email, $data->age]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE etudiant SET nom = ?, prenom = ?, email = ?, age = ? WHERE id = ?");
        $stmt->execute([$data['nom'], $data['prenom'], $data['email'], $data['age'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM etudiant WHERE id = ?");
        $stmt->execute([$id]);
    }
}
