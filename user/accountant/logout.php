<?php
session_start();
if (isset($_SESSION['user_accountant'])) {
	unset($_SESSION['user_accountant']);
	header("Location:../index.php");

}

?>