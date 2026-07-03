
<?php
session_start();
include 'db.php';

if (isset($_POST['register'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$password')";
    if ($conn->query($sql)) {
        $success = "Registration successful! Login now.";
    } else {
        $error = "Error: Email already exists or ".$conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SaveSmart ROSCA - Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Register to SaveSmart ROSCA</h2>
        <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>