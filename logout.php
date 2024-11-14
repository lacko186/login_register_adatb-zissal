<?php
// Elindítjuk a sessiont
session_start();

// Töröljük az összes session változót
$_SESSION = array();

// Töröljük a session sütit
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Megszüntetjük a sessiont
session_destroy();

// Átirányítjuk a felhasználót a bejelentkező oldalra
header("Location: login.php");
exit();
?>