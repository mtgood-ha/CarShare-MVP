<?php
include 'dbconn.php';
session_start();

$host = "localhost";    
$user = "root";
$password = "";
$database = "finalassignment"; 

$conn = mysqli_connect($host, $user, $password, $database);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_btn'])){

    $inputEmail = trim($_POST['inputEmail']);
    $password = trim($_POST['password']);
    $role_id  = intval($_POST['role_id']);

    $sql = "SELECT UserID, RoleID, Username, Password, Name, AccountStatus FROM user WHERE (Email = ? OR Username = ?) AND RoleID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $inputEmail, $inputEmail, $role_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1){
        $user = $result->fetch_assoc();

        if (isset($user['AccountStatus']) && strcasecmp($user['AccountStatus'], 'Banned') === 0){
            echo "<script>alert('Your account has been banned. Please contact the admin for further assistance.'); window.location.href='LoginPage.html';</script>";
            exit();
        }

        if ($password === $user['Password']){
            
            $_SESSION['user_id']  = $user['UserID'];
            $_SESSION['role_id']  = $user['RoleID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['name']     = $user['Name'];

            switch ($user['RoleID']){
                case 1:
                    header("Location: MainPageVD.php");
                    break;
                case 2:
                    header("Location: MainPageP.php");
                    break;
                case 3:
                    header("Location: AdminDashboard.php");
                    break;
                case 4:
                    header("Location: RewardManagerHomepage.html");
                    break;
                default:
                    header("Location: index.php");
                    break;
            }
            exit();

        }else{
            echo "<script>alert('Invalid password! Please try again.'); window.location.href='LoginPage.html';</script>";
        }
    }else{
        echo "<script>alert('No user found with those credentials or role!'); window.location.href='LoginPage.html';</script>";
    }

    $stmt->close();
}else{
    header("Location: LoginPage.html");
    exit();
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
</body>
</html>