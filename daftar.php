<?php
include "koneksi.php";

$username = $_POST['username'];
$email    = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// cek apakah email sudah terdaftar
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($check) > 0){
    echo "Email sudah terdaftar!";
    exit;
}

// simpan data
$query = "INSERT INTO users (username, email, password)
          VALUES ('$username', '$email', '$password')";

if(mysqli_query($conn, $query)){
    echo "Pendaftaran berhasil! Silakan login.";
    header("Refresh:1; url=login.html");
} else {
    echo "Gagal daftar!";
}
?>
<?php
$conn = mysqli_connect("localhost", "root", "", "toko");

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
<?php
// koneksi.php
$conn = mysqli_connect("localhost","root","","db_summitx");
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
<?php
// wajib_login.php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
?>
<?php
// login.php
session_start();
include "koneksi.php";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
    if($res && mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);
        if($user['password'] && password_verify($pass, $user['password'])){
            // set session (simpan minimal info)
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'photo' => $user['photo'] ?? null
            ];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Email atau password salah.";
        }
    } else {
        $error = "User tidak ditemukan.";
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
  <?php if(!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
  </form>

  <p>atau</p>
  <a href="google_login.php">Login dengan Google</a>
  <p>Belum punya akun? <a href="register.php">Daftar</a></p>
</body>
</html>
<?php
// logout.php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>
<?php
include "wajib_login.php"; // start session & check
$user = $_SESSION['user'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Dashboard</title></head>
<body>
  <h1>Halo, <?= htmlspecialchars($user['username'] ?? $user['email']); ?></h1>
  <?php if(!empty($user['photo'])): ?>
    <img src="<?= htmlspecialchars($user['photo']); ?>" width="80" alt="avatar">
  <?php endif; ?>

  <p>Email: <?= htmlspecialchars($user['email']); ?></p>

  <a href="logout.php">Logout</a>
</body>
</html>
<?php
// google_login.php
require_once 'vendor/autoload.php'; // composer autoload (google/apiclient)
session_start();

$client = new Google_Client();
$client->setClientId('PASTE_CLIENT_ID_HERE');
$client->setClientSecret('PASTE_CLIENT_SECRET_HERE');
$client->setRedirectUri('http://localhost/your-folder/google_callback.php');
$client->addScope('email');
$client->addScope('profile');

$authUrl = $client->createAuthUrl();
header("Location: $authUrl");
exit;
<?php
// google_callback.php
require_once 'vendor/autoload.php';
session_start();
include "koneksi.php";

$client = new Google_Client();
$client->setClientId('PASTE_CLIENT_ID_HERE');
$client->setClientSecret('PASTE_CLIENT_SECRET_HERE');
$client->setRedirectUri('http://localhost/your-folder/google_callback.php');

if(isset($_GET['code'])){
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if(isset($token['error'])){
        die("Error mendapatkan token: " . $token['error_description']);
    }

    $client->setAccessToken($token['access_token']);

    // ambil profil user
    $oauth2 = new Google_Service_Oauth2($client);
    $google_user = $oauth2->userinfo->get();

    $google_id = $google_user->getId();
    $email     = $google_user->getEmail();
    $name      = $google_user->getName();
    $photo     = $google_user->getPicture();

    // cek user di DB
    $email_esc = mysqli_real_escape_string($conn, $email);
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email_esc' LIMIT 1");

    if($res && mysqli_num_rows($res) > 0){
        // update google_id & photo jika perlu
        $user = mysqli_fetch_assoc($res);
        $uid = (int)$user['id'];
        mysqli_query($conn, "UPDATE users SET google_id='".mysqli_real_escape_string($conn,$google_id)."', photo='".mysqli_real_escape_string($conn,$photo)."' WHERE id=$uid");
    } else {
        // buat user baru
        $name_esc = mysqli_real_escape_string($conn, $name);
        $photo_esc = mysqli_real_escape_string($conn, $photo);
        $googleid_esc = mysqli_real_escape_string($conn, $google_id);

        mysqli_query($conn, "INSERT INTO users (google_id, username, email, photo) VALUES ('$googleid_esc','$name_esc','$email_esc','$photo_esc')");
        $uid = mysqli_insert_id($conn);
    }

    // ambil data user yg terbaru
    $res2 = mysqli_query($conn, "SELECT * FROM users WHERE email='$email_esc' LIMIT 1");
    $user = mysqli_fetch_assoc($res2);

    // set session
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'username' => $user['username'],
        'photo' => $user['photo']
    ];

    header("Location: dashboard.php");
    exit;
} else {
    echo "Tidak ada code dari Google.";
}
<!-- jika sudah login -->
<?php if(isset($_SESSION['user'])): ?>
  <img src="<?= htmlspecialchars($_SESSION['user']['photo'] ?? 'avatar-default.png') ?>" width="32" class="rounded-full">
  <a href="dashboard.php"><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Akun') ?></a>
  <a href="logout.php">Logout</a>
<?php else: ?>
  <a href="login.php">Login</a>
  <a href="google_login.php">Login dengan Google</a>
<?php endif; ?>
