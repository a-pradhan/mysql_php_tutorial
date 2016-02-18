<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
// v1: simple logout
// session_start();

$_SESSION["admin_id"] = null;
$_SESSION["username"] = null;
redirect_to("login.php");
?>

<?php
// v2: destroy session
// assumes nothing else in session to keep

//session_start();
//$_SESSION = array();
//// if cookie with that session name exists make it expire
//if (isset($_COOKIE[session_name()])) {
//    setcookie(session_name(), '', time() - 42000, '/');
//}
//
//// destroys the session file on the server
//session_destroy();
//
//redirect_to("login..php");


?>
