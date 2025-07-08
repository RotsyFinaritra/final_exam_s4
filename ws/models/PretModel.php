<?php
require_once 'BaseModel.php';

class PretModel extends BaseModel
{

    public function generateTableauAmortissement($idPret)
    {
        // Récupérer les infos du prêt
        $pret = $this->getById($idPret);
        if (!$pret) {
            throw new Exception("Prêt non trouvé");
        }

        $capital = (float) $pret['montant'];
        $duree = (int) $pret['duree'];
        $assurance_mensuelle = isset($pret['assurance']) ? (float) $pret['assurance'] : 0.0;
        $date_debut = new DateTime($pret['date_debut']);

        $idTypePret = $pret['id_type_pret'];

        $typePretModel = new TypePretModel();
        $typePret = $typePretModel->getById($idTypePret);

        $taux_mensuel = $typePret['taux'] / 100;


        // Calcul de l'annuité
        $annuite = $capital * (($taux_mensuel * pow(1 + $taux_mensuel, $duree)) / (pow(1 + $taux_mensuel, $duree) - 1));
        // $annuite = $capital * $taux_mensuel / (1 - pow(1 + $taux_mensuel, -$duree));
        $capital_restant = $capital;

        // Préparer l'insertion
        $sql = "INSERT INTO tableau_amortissement (
            id_pret, numero_mois, annee, capital_restant, amortissement, interet, assurance, annuite, date_paiement
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $delai = $pret['delai'];

        for ($i = 0; $i < $duree; $i++) {
            $interet = $capital_restant * $taux_mensuel;
            $amortissement = $annuite - $interet;
            if ($amortissement < 0)
                $amortissement = 0;


            $date_paiement = clone $date_debut;
            $date_paiement->modify("+$delai months");
            $date_paiement->modify("+$i months");

            $mois = (int) $date_paiement->format('n'); // 1-12
            $annee = (int) $date_paiement->format('Y');
            $capital_restant -= $amortissement;

            $stmt->execute([
                $idPret,
                $mois,
                $annee,
                round($capital_restant, 2),
                round($amortissement, 2),
                round($interet, 2),
                round($assurance_mensuelle, 2),
                round($annuite, 2),
                $date_paiement->format("Y-m-d")
            ]);


        }

        return true;
    }



    public function getAll()
    {
        $sql = "
        SELECT 
            p.id,
            p.date_debut,
            p.duree,
            p.date_fin,
            p.montant,
            tp.id AS id_type_pret,
            tp.nom AS type_pret,
            c.id AS id_client,
            c.nom AS client_nom,
            c.prenom AS client_prenom,
            tr.id AS id_type_remboursement,
            tr.nom AS type_remboursement,
            sp.id AS id_statut_pret,
            sp.nom AS statut_pret
        FROM pret p
        JOIN type_pret tp ON p.id_type_pret = tp.id
        JOIN client c ON p.id_client = c.id
        LEFT JOIN type_remboursement tr ON p.id_type_remboursement = tr.id
        LEFT JOIN (
            SELECT ps1.id_pret, ps1.id_statut_pret
            FROM pret_statut_pret ps1
            INNER JOIN (
                SELECT id_pret, MAX(date) AS max_date
                FROM pret_statut_pret
                GROUP BY id_pret
            ) ps2 ON ps1.id_pret = ps2.id_pret AND ps1.date = ps2.max_date
        ) dernier_statut ON p.id = dernier_statut.id_pret
        LEFT JOIN statut_pret sp ON dernier_statut.id_statut_pret = sp.id
    ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
{
    $sql = "
        SELECT 
            p.*,
            tp.nom AS type_pret,
            c.nom AS client_nom,
            c.prenom AS client_prenom,
            tr.nom AS type_remboursement,
            sp.id AS id_statut_pret,
            sp.nom AS statut_pret
        FROM pret p
        JOIN type_pret tp ON p.id_type_pret = tp.id
        JOIN client c ON p.id_client = c.id
        LEFT JOIN type_remboursement tr ON p.id_type_remboursement = tr.id
        LEFT JOIN (
            SELECT ps1.id_pret, ps1.id_statut_pret
            FROM pret_statut_pret ps1
            INNER JOIN (
                SELECT id_pret, MAX(date) AS max_date
                FROM pret_statut_pret
                GROUP BY id_pret
            ) ps2 ON ps1.id_pret = ps2.id_pret AND ps1.date = ps2.max_date
        ) dernier_statut ON p.id = dernier_statut.id_pret
        LEFT JOIN statut_pret sp ON dernier_statut.id_statut_pret = sp.id
        WHERE p.id = ?
    ";

    return $this->fetchOne($sql, [$id]);
}


    public function insert($data)
    {
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

    public function update($id, $data)
    {
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

    public function delete($id)
    {
        return $this->execute("DELETE FROM pret WHERE id = ?", [$id]);
    }

    public function getPretsByCriteria($criteria)
    {
        $sql = "
            SELECT 
                p.id,
                p.date_debut,
                p.duree,
                p.date_fin,
                p.montant,
                tp.id AS id_type_pret,
                tp.nom AS type_pret,
                c.id AS id_client,
                c.nom AS client_nom,
                c.prenom AS client_prenom,
                tr.id AS id_type_remboursement,
                tr.nom AS type_remboursement,
                sp.id AS id_statut_pret,
                sp.nom AS statut_pret
            FROM pret p
            JOIN type_pret tp ON p.id_type_pret = tp.id
            JOIN client c ON p.id_client = c.id
            LEFT JOIN type_remboursement tr ON p.id_type_remboursement = tr.id
            LEFT JOIN (
            SELECT ps1.id_pret, ps1.id_statut_pret
            FROM pret_statut_pret ps1
            INNER JOIN (
                SELECT id_pret, MAX(date) AS max_date
                    FROM pret_statut_pret
                    GROUP BY id_pret
                ) ps2 ON ps1.id_pret = ps2.id_pret AND ps1.date = ps2.max_date
            ) dernier_statut ON p.id = dernier_statut.id_pret
            LEFT JOIN statut_pret sp ON dernier_statut.id_statut_pret = sp.id
                WHERE 1=1
            ";

        $params = [];
        if (!empty($criteria['id_type_pret'])) {
            $sql .= " AND p.id_type_pret = ?";
            $params[] = $criteria['id_type_pret'];
        }
        if (!empty($criteria['id_client'])) {
            $sql .= " AND p.id_client = ?";
            $params[] = $criteria['id_client'];
        }
        if (!empty($criteria['date_debut'])) {
            $sql .= " AND p.date_debut >= ?";
            $params[] = $criteria['date_debut'];
        }
        if (!empty($criteria['date_fin'])) {
            $sql .= " AND p.date_fin <= ?";
            $params[] = $criteria['date_fin'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>