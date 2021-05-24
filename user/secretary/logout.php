<?php
session_start();
if (isset($_SESSION['user_secretary'])) {
	unset($_SESSION['user_secretary']);
	header("Location:../index.php");

}

?>