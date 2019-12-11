<?php require 'config.php' ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Classificados</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">	
	<script type="text/javascript" src="assets/css/jquery.min.js"></script>
	<script type="text/javascript" src="assets/css/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/css/script.js"></script>
</head>
<body>

	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="./" class="navbar-brand">Classificados</a>
			</div>
			<div class="nav navbar-nav navbar-right">
				<?php if ( isset($_SESSION['cLogin'])  &&  !empty($_SESSION['cLogin'])):	 ?>
					<li><a href="meus-anuncios.php">Meus Anúncios</a></li>
					<li><a href="./">Meus Dados</a></li>
					<li><a href="sair.php">Sair</a></li>
				<?php else: ?>
					<li><a href="cadastrar.php">Cadastrar</a></li>
					<li><a href="login.php">Login</a></li>
				<?php endif; ?>
			</div>
		</div>
	</nav>
