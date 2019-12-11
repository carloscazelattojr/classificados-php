<?php require 'pages/header.php' ?>
<?php 
	if ( isset($_SESSION['cLogin'])  &&  empty($_SESSION['cLogin'])){
		?>
		<script type="text/javascript">window.location.href="login.php"</script>
		<?php 
		exit;
	}
 ?>

<div class="container">
	<h1>Meus Anúncios</h1>
	
	<a href="adicionar-anuncio.php" class="btn btn-success">Adicionar Anúncio</a>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Foto</th>
				<th>Título</th>
				<th>Valor</th>
				<th>Ações</th>
			</tr>
		</thead>
		<?php 
			require 'classes/anuncios.class.php';
			$a =  new Anuncios();
			$anuncios =  $a->getMeusAnuncios();
			foreach ($anuncios as $anuncio) {
				?>
				<tr>
					<th>
						<?php if ( !empty($anuncio['url']) ): ?>
						<img src="assets/images/anuncios/<?php echo $anuncio['url'];  ?>" height="50" border="0">
						<?php else: ?>
						<img src="assets/images/default.jpg"  height="50" border="0">					
						<?php endif; ?>		
					</th>

					<th><?php echo $anuncio['titulo']; ?></th>
					<th><?php echo 'R$ '. number_format($anuncio['valor'],2); ?></th>
					<th>
						<a href="alterar-anuncio.php?id=<?php echo $anuncio['id']; ?>" class='btn btn-primary'>Alterar</a>
						<a href="excluir-anuncio.php?id=<?php echo $anuncio['id']; ?>" class='btn btn-danger'>Excluir</a>

					</th>
				</tr>
				<?php 
			}
		?>


	</table>

</div>
<?php require 'pages/footer.php' ?>