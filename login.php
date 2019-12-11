<?php require 'pages/header.php'; ?>

<?php 
	if ( isset($_SESSION['cLogin'])  &&  !empty($_SESSION['cLogin'])){
		?>
		<script type="text/javascript">window.location.href="./"</script>
		<?php 
		exit;
	}
 ?>
 
<div class="container">
	<h1>Login</h1>
	<?php 
		require 'classes/usuario.class.php';
		$u = new Usuarios();

		if ( isset($_POST['email'])  &&  !empty($_POST['email']) ){
			$email 		= addslashes($_POST['email']);
			$senha 		= $_POST['senha'];

			if ( $u->login($email, $senha) ){
				?>
					<script type="text/javascript">window.location.href="./";</script>
				<?php 
			} else {
				?>
				<div class="alert alert-danger">
					Email e/ou Senha inválidos! 
				</div>
				<?php 
			}
		}
	 ?>
	<form method="POST">
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control">
		</div>
		<div class="form-group">
			<label for="senha">Senha:</label>
			<input type="password" name="senha" id="senha" class="form-control">
		</div>
		<input type="submit" value="Entrar" class="btn btn-default">		
	</form>
	

</div>

<?php require 'pages/footer.php'; ?>