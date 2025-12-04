<!-- <?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "outdoor_shop";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Connection Failed : " . mysqli_connect_error());
}
?>
<?php include "config.php";

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = mysqli_query($conn, "INSERT INTO users VALUES('', '$username', '$email', '$password', NOW())");

    if($query){
        header("Location: login.php?success=registered");
    } else {
        echo "Gagal mendaftar!";
    }
}
?>

<form action="" method="POST" class="max-w-md p-6 mx-auto bg-white rounded-lg shadow-lg">
    <h2 class="mb-4 text-2xl font-bold">Register</h2>
    <input name="username" class="w-full p-2 mb-3 border" placeholder="Username" required>
    <input name="email" type="email" class="w-full p-2 mb-3 border" placeholder="Email" required>
    <input name="password" type="password" class="w-full p-2 mb-3 border" placeholder="Password" required>
    <button name="register" class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">Daftar</button>
</form>
<?php 
session_start();
include "config.php";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user  = mysqli_fetch_assoc($query);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else {
        echo "<script>alert('Email atau Password salah!');</script>";
    }
}
?>

<form action="" method="POST" class="max-w-md p-6 mx-auto bg-white rounded-lg shadow-lg">
    <h2 class="mb-4 text-2xl font-bold">Login</h2>
    <input name="email" type="email" class="w-full p-2 mb-3 border" placeholder="Email" required>
    <input name="password" type="password" class="w-full p-2 mb-3 border" placeholder="Password" required>
    <button name="login" class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">Login</button>
</form>
    <?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php?error=notlogin");
    exit;
}
?>
<?php include "wajib_login.php"; ?>

<h1>Welcome, <?= $_SESSION['username']; ?> 👋</h1>
<a href="logout.php" class="font-bold text-red-600">Logout</a> -->
