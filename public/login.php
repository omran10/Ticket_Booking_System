<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add error checking for empty fields
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $error = "Please fill in all fields";
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];
        
        // Add debug output
        // echo "Password entered: " . $password . "<br>";
        
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Add debug output
            // echo "Stored hash: " . $user['password'] . "<br>";
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                
                header("Location: index.php");
                exit();
            } else {
                $error = "Incorrect password";
            }
        } else {
            $error = "Username not found";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="auth-card">
                <div class="auth-header">
                    <h3>Login</h3>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                
                <form method="POST" class="auth-form">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn auth-btn w-100">Login</button>
                </form>
                
                <div class="text-center mt-4">
                    <a href="register.php" class="auth-link">Don't have an account? Register now</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



