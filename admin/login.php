<?php 
session_start();
require_once '../functions.php';

if ($_POST['login']) {
    $user = loginAdmin($_POST['username'], $_POST['password']);
    if ($user) {
        $_SESSION['admin'] = $user;
        header('Location: index.php');
        exit;
    }
    $error = 'Login gagal!';
}
?>
<!DOCTYPE html>
<html>
<head><title>Login Admin</title><link rel="stylesheet" href="../style.css"></head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh">
    <div style="background:#fff; padding:30px; border-radius:16px; width:300px">
        <h2 style="text-align:center">Login Admin</h2>
        <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Email" style="width:100%; padding:10px; margin:10px 0">
            <input type="password" name="password" placeholder="Password" style="width:100%; padding:10px; margin:10px 0">
            <button type="submit" name="login" style="width:100%">Login</button>
        </form>
    </div>
</body>
</html>