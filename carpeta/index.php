<?php 

if (isset($_SESSION['usuario'])) {
	header('Location: registroHorasv2.php');
}else{
	header('Location: login.php');
}

?>