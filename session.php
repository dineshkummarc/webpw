<?php
// set max session length
$session_length_seconds = 60/*s*/ * 30/*m*/ * 1/*h*/ * 1/*d*/;
ini_set('session.gc_maxlifetime', $session_length_seconds); // server should keep session data
session_set_cookie_params($session_length_seconds); // each client should remember their session id


// start session
session_start();

if (!isset($_SESSION['vault'])) { // if client is not logged in
	header('Location: login.php'); // redirect to login page
	die(); // abort current script
}
?>
