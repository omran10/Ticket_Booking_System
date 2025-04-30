<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add validation for empty fields
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['full_name'])) {
        $error = "Please fill in all fields";
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        
        
        $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        
        if ($check->num_rows > 0) {
            $error = "Username already exists";
        } else {
            $sql = "INSERT INTO users (username, password, full_name) 
                    VALUES ('$username', '$password', '$full_name')";
            
            if ($conn->query($sql)) {
                $_SESSION['success'] = "Account created successfully";
                header("Location: login.php");
                exit();
            } else {
                $error = "An error occurred. Please try again";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="auth-card">
                <div class="auth-header">
                    <h3>Create New Account</h3>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="auth-form">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn auth-btn w-100">Create Account</button>
                </form>
                
                <div class="text-center mt-4">
                    <a href="login.php" class="auth-link">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



