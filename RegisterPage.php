<?php

include 'dbconn.php';
session_start();

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_btn'])){
    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $gender   = $_POST['gender'];
    $role_id  = intval($_POST['role_id']);

    $check_sql = "SELECT UserID FROM user WHERE Email = ? OR Username = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0){
        echo "<script>alert('Email or Username is already registered!'); window.location.href='RegisterPage.html';</script>";
        exit();
    }
    $stmt->close();

    $insert_user = "INSERT INTO user (RoleID, Email, Password, Username, Name, Gender) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_user);
    $stmt->bind_param("isssss", $role_id, $email, $password, $username, $name, $gender);

    if ($stmt->execute()){
        $new_user_id = $conn->insert_id;
        $stmt->close();

        if ($role_id == 1){ 
            $profile_sql = "INSERT INTO volunteerdriverprofile (PreferredPickUpLocation, PreferredDropOffLocation, UserID, StarRating, CurrentPoints) VALUES ('Not Set', 'Not Set', ?, 5.0, 0)";
            $p_stmt = $conn->prepare($profile_sql);
            $p_stmt->bind_param("i", $new_user_id);
            $p_stmt->execute();
            $p_stmt->close();

        } elseif ($role_id == 2){ 
            $profile_sql = "INSERT INTO passengerprofile (UserID, PreferredPickUpLocation, PreferredDropOffLocation, StarRating) VALUES (?, 'Not Set', 'Not Set', 5.0)";
            $p_stmt = $conn->prepare($profile_sql);
            $p_stmt->bind_param("i", $new_user_id);
            $p_stmt->execute();
            $p_stmt->close();
        }
        echo "<script>alert('Registration successful! Please log in.'); window.location.href='LoginPage.html';</script>";
    }else{
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>