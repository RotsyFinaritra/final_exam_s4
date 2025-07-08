<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des prêts</title>
  <link rel="stylesheet" href="<?= STATIC_URL ?>/css/main.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/components.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/forms.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/tables.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/css/loan-page.css">
</head>
<body>

    <?php include"navbar.php"?>

    <h1>Liste des prêts</h1>

    <div style="margin-bottom: 10px;">
    <label>Type prêt :</label>
    <select id="filtreTypePret">
        <option value="">-- Tous --</option>
    </select>

    <label>Client :</label>
    <select id="filtreClient">
        <option value="">-- Tous --</option>
    </select>

    <label>Date début :</label>
    <input type="date" id="filtreDateDebut">

    <label>Date fin :</label>
    <input type="date" id="filtreDateFin">

    <button onclick="filtrerPrets()">Filtrer</button>
    <button onclick="resetFiltre()">Réinitialiser</button>
    </div>


  <table id="table-prets">
    <thead>
      <tr>
        <th>ID</th>
        <th>Type Prêt</th>
        <th>Client</th>
        <th>Date Début</th>
        <th>Durée (mois)</th>
        <th>Date Fin</th>
        <th>Montant</th>
        <th>Statut</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <script>
    const apiBase = "<?= API_URL ?>";
    
  </script>
  <script src="<?= STATIC_URL ?>/js/script.js">
    
  </script>
  <script>
    chargerPrets();
    chargerClients();
    chargerTypesPret();
  </script>

</body>
</html>
