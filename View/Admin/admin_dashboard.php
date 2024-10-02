<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin_dashboard.css">



</head>
<body>

    <!-- Navbar starts here -->
    <div class="navbar">
        <div class="navbar-left">
            <h1>DoctoCabinet Admin</h1> <!-- Replace with your site name -->
        </div>
        <div class="navbar-right">
            <div class="dropdown">
                <button class="dropbtn">Anas HEFIED ▼</button> <!-- User's name -->
                <div class="dropdown-content">
                    <a href="../../Php/index_app.php?action=logout">Déconnexion</a> <!-- Logout option -->
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar ends here -->

    <!-- Admin Dashboard Cards -->
    <div class="dashboard-container">
        <h1>Bienvenue sur le Tableau de Bord Admin</h1>
        <p class="welcome-msg">Vous êtes connecté avec succès !</p>
        
        <div class="card-container">
            <div class="card">
                <h2>Ajouter un Cabinet</h2>
                <p>Ajoutez un nouveau cabinet avec les informations du docteur.</p>
                <a href="add_cabinet.php" class="btn">Ajouter un Cabinet</a>
            </div>
            <div class="card">
                <h2>Voir la liste des Cabinets</h2>
                <p>Consultez et gérez la liste des cabinets disponibles.</p>
                <a href="cabinets_list.php" class="btn">Voir les Cabinets</a>
            </div>
            <div class="card">
                <h2>Voir les Patients</h2>
                <p>Consultez la liste des patients ayant réservé des rendez-vous.</p>
                <a href="patients_list.php" class="btn">Voir les Patients</a>
            </div>
        </div>
    </div>

</body>
</html>