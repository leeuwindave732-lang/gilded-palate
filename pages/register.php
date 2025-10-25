<?php
include '../includes/db.php';
include '../includes/functions.php';

// Redirect logged-in users
if(isLoggedIn()){
    header("Location: ../pages/products.php");
    exit;
}

$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if(empty($name)) $errors[] = "Name is required.";
    if(empty($email)) $errors[] = "Email is required.";
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if(empty($password)) $errors[] = "Password is required.";
    if($password !== $confirm_password) $errors[] = "Passwords do not match.";

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0) $errors[] = "Email already registered.";
    $stmt->close();

    // If no errors, insert user
    if(empty($errors)){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        if($stmt->execute()){
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['name'] = $name;
            header("Location: ../pages/products.php");
            exit;
        } else {
            $errors[] = "Failed to register. Try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Gilded Palate</title>
<link rel="stylesheet" href="../Assets/CSS/styles.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <h1>Register</h1>
        <p>Create your account to start ordering</p>

        <?php if(!empty($errors)): ?>
            <div class="error">
                <?php foreach($errors as $error) echo "<p>$error</p>"; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="name" placeholder="Full Name" value="<?php if(isset($name)) echo htmlspecialchars($name); ?>" required>
            <input type="email" name="email" placeholder="Email Address" value="<?php if(isset($email)) echo htmlspecialchars($email); ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" class="btn"><span>Register</span></button>
        </form>

        <a href="../pages/login.php">Already have an account? Login here.</a>
    </div>
</div>
</body>
</html>


