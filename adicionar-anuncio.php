<?php require 'pages/header.php' ?>
<?php 
	if ( isset($_SESSION['cLogin'])  &&  empty($_SESSION['cLogin'])){
		?>
		<script type="text/javascript">window.location.href="login.php"</script>
		<?php 
		exit;
	}

	require 'classes/anuncios.class.php';
	$a = new Anuncios();
	if ( isset($_POST['titulo']) && !empty($_POST['titulo']) ) {
		$titulo 	= addslashes($_POST['titulo'])	;
		$categoria 	= addslashes($_POST['categoria'])	;
		$valor 		= $_POST['valor']	;
		$descricao 	= addslashes($_POST['descricao'])	;
		$estado 	= addslashes($_POST['estado'])	;

		$a->addAnuncio($titulo, $categoria,  $descricao, $valor, $estado );
		?>
		<div class="alert alert-success"> Anúnico adicionado com sucesso! 
		<a href="meus-anuncios.php" class="alert-link">Voltar para <strong>Meus Anúncios</strong></a>
		</div>
		<?php 
	}


 ?>


<div class="container">
	<h1>Novo Anúncio</h1>
	<form method="POST" enctype="multipart/form-data">
		
		<div class="form-group">
			<label for="categoria">Categorioa:</label>
			<select name="categoria" id=categoria class="form-control">
				<?php 
					require 'classes/categorias.class.php';
					$c = new Categorias();
					$lista = $c->getLista();

					foreach ($lista as $item) {
					?>
						<option value="<?php echo $item['id']; ?>"><?php echo utf8_encode($item['nome']) ?> </option>
					<?php 
					}
				 ?>
			</select>
		</div>	
		<div class="form-group">
			<label for="titulo">Título:</label>
			<input type="text" name="titulo" id="titulo" class="form-control">
		</div>	
		<div class="form-group">
			<label for="valor">Valor:</label>
			<input type="text" name="valor" id="valor" class="form-control">
		</div>	
		<div class="form-group">
			<label for="descricao">Descrição:</label>
			<textarea class="form-control" name="descricao"></textarea>
		</div>	
		<div class="form-group">
			<label for="estado">Estado de Convervação:</label>
			<select name="estado" id=estado class="form-control">
				<option value = "0">Ruim</option>
				<option value = "1">Bom</option>
				<option value = "2">Ótimo</option>
			</select>
		</div>	

		<input type="submit" value="Adicionar" class="btn btn-success">


	</form>
	

</div>



 <?php require 'pages/footer.php' ?>