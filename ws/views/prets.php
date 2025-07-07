<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des prêts</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>

    <h1>Liste des prêts</h1>
    <a href="<?= BASE_URL ?>/demande_form_view">Ajouter une demande de pret</a>
    <form action="<?= BASE_URL ?>/prets/generateTableauAmortissement/1" method="post">
        <button type="submit">Tester</button>
    </form>
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
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <script>
    const apiBase = "<?= API_URL ?>";
    
  </script>
  <script src="<?= STATIC_URL ?>/script.js">
    
  </script>
  <script>
    chargerPrets();
    chargerClients();
    chargerTypesPret();
  </script>

</body>
</html>
