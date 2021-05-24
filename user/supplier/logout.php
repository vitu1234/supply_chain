<?php
session_start();
if (isset($_SESSION['user_supplier'])) {
	unset($_SESSION['user_supplier']);
	header("Location:../index.php");

}

?>