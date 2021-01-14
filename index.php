<?php

/*

  Nama Faizal Rifaldy , Rifki Renaldi

*/

  date_default_timezone_set("Asia/Jakarta");
  require_once 'class/class.php';
  $pengguna = new Pengguna();

?>


<!DOCTYPE html>
<html>
	<head>
		<title> PBO UAS GAME</title>
		
		<link rel="stylesheet" type="text/css" href="asets/css/style.css">
		
		<script type="text/javascript" src="asets/js/jquery.js"></script>
		<script src="/asets/js/uas-project.js"></script>
	</head>


	<?php 
	if(isset($_GET['page'])){
		$page = $_GET['page'];

		switch ($page) {
			case 'home':
				include "halaman/login.php";
				break;
			case 'daftar':
				include "halaman/daftar.php";
				break;		
			default:
				echo "<center><h3>Maaf. Halaman tidak di temukan! akan di alihkan ke halaman utama</h3></center><meta http-equiv='refresh' content='5; url=".$pengguna->Urlwebnya()."index.php'>";
				
				break;
		}
	}else{
		include "halaman/login.php";
	}

	 ?>


</html>