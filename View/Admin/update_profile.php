<?php
session_start();
require_once '../../config/database.php'; // S'assurer que le chemin est correct

// Vérifiez si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../View/Auth/login.php");
    exit();
}

$conn = connectDB();
$user_id = $_SESSION['user_id'];
$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];

    try {
        $conn->beginTransaction();

        // Vérification de l'unicité du numéro de téléphone uniquement via PHP
        $query_check_phone = "SELECT id FROM users WHERE phone = :phone AND id != :user_id";
        $stmt_check_phone = $conn->prepare($query_check_phone);
        $stmt_check_phone->bindParam(':phone', $phone);
        $stmt_check_phone->bindParam(':user_id', $user_id);  // S'assurer de ne pas vérifier pour l'utilisateur lui-même
        $stmt_check_phone->execute();

        if ($stmt_check_phone->rowCount() > 0) {
            $error_message = "Erreur : Le numéro de téléphone est déjà utilisé par un autre utilisateur.";
        } else {
            // Mettre à jour le numéro de téléphone de l'admin si unique
            $query_users = "UPDATE users SET phone = :phone WHERE id = :user_id";
            $stmt_users = $conn->prepare($query_users);
            $stmt_users->bindParam(':phone', $phone);
            $stmt_users->bindParam(':user_id', $user_id);
            $stmt_users->execute();

            if ($stmt_users->rowCount() > 0) {
                $success_message = "Votre profil a été mis à jour avec succès.";
            } else {
                $error_message = "Erreur lors de la mise à jour du profil : aucun changement détecté.";
            }
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        $error_message = "Erreur lors de la mise à jour du profil : " . $e->getMessage();
    }
}

// Récupérer les informations actuelles de l'admin
$query = "SELECT username, email, phone FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à Jour Mon Profil (Admin)</title>
    <style>
   body {
    font-family: 'Roboto', sans-serif;
    background-image: url('../../images/images1.jpg'); /* Add your image path here */
    background-size: cover; /* Ensures the image covers the entire page */
    background-position: center; /* Centers the image */
    background-repeat: no-repeat; /* Prevents repeating */
    margin: 0;
    padding: 0;
    
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}


        .container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            margin: 20px;
        }

        h2 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 30px;
            font-weight: 500;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            text-align: left;
        }

        input[type="tel"] {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="tel"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button.btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 14px 0;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            font-weight: 500;
        }

        button.btn:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .error, .success {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: left;
            font-size: 14px;
            font-weight: 500;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        a.btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 14px 0;
            text-decoration: none;
            font-size: 16px;
            border-radius: 6px;
            margin-top: 15px;
            transition: background-color 0.3s ease;
            width: 100%;
            font-weight: 500;
        }

        a.btn:hover {
            background-color: #218838;
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            input[type="tel"], button.btn, a.btn {
                font-size: 14px;
                padding: 12px 0;
            }
        }
    </style>


</head>
<body>
    <div class="container" style="margin-bottom: 200px; ">
        <h2>Mettre à Jour Mon Profil (Admin)</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="update_profile.php" method="POST">

            <label for="phone">Numéro de Téléphone:</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required value="<?php echo htmlspecialchars($admin['phone']); ?>">

            <button type="submit" class="btn">Mettre à Jour</button>
        </form>

        <a href="admin-dashboard.php" class="btn">Retour au Tableau de Bord</a>
    </div>
</body>
</html>
