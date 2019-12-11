<?php 
	require 'pages/header.php'; 
	require 'classes/anuncios.class.php';
	require 'classes/usuario.class.php';
	require 'classes/categorias.class.php';

	$a = new Anuncios();
	$u = new Usuarios();	
	$c = new Categorias();	

	$filtros = array(
		'categoria' => '',
		'preco' => '',
		'estado' => ''
	);
	if ( isset($_GET['filtros']) ) {
		$filtros = $_GET['filtros'];
	}


	$total_anuncios = $a->getTotalAnuncios($filtros);
	$total_usuarios = $u->getTotalUsuarios('Usuarios');

	
	$p = 1;
	if (isset( $_GET['p']) && !empty($_GET['p']) ){
		$p=	addslashes($_GET['p']);
	}	

	$perPage = 2;
	$total_paginas = ceil($total_anuncios/$perPage);

	$anuncios = $a->getUltimosAnuncios($p, $perPage, $filtros);
	
	$categorias = $c->getLista();

?>

	<div class="container-fluid">
		<div class="jumbotron">
			
			<h2>Temos <?php echo $total_anuncios  ?> anúncios cadastrados</h2>
			<p>e <?php echo $total_usuarios ?> usuários cadastrados</p>
		</div>
	</div>


	<div class="row">
		<div class="col-sm-3">
			<h4>Pesquisa Avançada</h3>
			<form method="GET">
				
				<div class="form-group">
					<label for="categoria">Categoria: </label>
					<select id="categoria" name="filtros[categoria]" class="form-control">
						<option></option>	
						<?php foreach ($categorias as  $cat): ?>
							<option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $filtros['categoria'])?'selected="selected"':'' ?> ><?php echo utf8_encode($cat['nome']); ?></option>	
						<?php endforeach; ?>
					</select>
				</div>	

				<div class="form-group">
					<label for="preco">Preço: </label>
					<select id="preco" name="filtros[preco]" class="form-control">
						<option></option>	
						<option value="0-100" <?php echo ($filtros['preco']==='0-100')?'selected="selected"':'' ?> >R$ 0,00 a R$ 100,00</option>	
						<option value="101-500" <?php echo ($filtros['preco']==='101-500')?'selected="selected"':'' ?>>R$ 101,00 a R$ 500,00</option>	
						<option value="501-1000" <?php echo ($filtros['preco']==='501-1000')?'selected="selected"':'' ?>>R$ 501,00 a R$ 1000,00</option>	
						<option value="1001-999999999" <?php echo ($filtros['preco']==='1001-999999999')?'selected="selected"':'' ?>>R$ 1001,00 a superior</option>	
					</select>
				</div>	


				<div class="form-group">
					<label for="estado">Estado de Conservação: </label>
					<select id="estado" name="filtros[estado]" class="form-control">
						<option></option>	
						<option value="0" <?php echo ($filtros['estado']==='0')?'selected="selected"':'' ?> >Ruim</option>	
						<option value="1" <?php echo ($filtros['estado']==='1')?'selected="selected"':'' ?>>Bom</option>	
						<option value="2" <?php echo ($filtros['estado']==='2')?'selected="selected"':'' ?>>Ótimo</option>							
					</select>
				</div>	

				<div class="form-group">
					<input type="submit" value="Buscar" class="btn btn-info">
					
				</div>

			</form>
		</div>
		<div class="col-sm-9">
			<h4>Últimos Anúncios</h3>
				<table class="table table-striped">
					<tbody>
						<?php foreach($anuncios as $anuncio): ?>
							<tr>
								<td>
									<?php if ( !empty($anuncio['url']) ): ?>
									<img src="assets/images/anuncios/<?php echo $anuncio['url'];  ?>" height="50" border="0">
									<?php else: ?>
									<img src="assets/images/default.jpg"  height="50" border="0">					
									<?php endif; ?>		
								</td>
								<td>
									<a href="produto.php?id=<?php echo $anuncio['id']; ?>"><?php echo utf8_encode($anuncio['titulo']) ; ?> </a><br>
									<?php echo utf8_encode($anuncio['categoria']); ?>
								</td>
								<td><?php echo 'R$ '. number_format($anuncio['valor'],2); ?></td>

								
							</tr>
						<?php endforeach?>							
					</tbody>					
				</table>
				<ul class='pagination'>
					<?php for($q=1; $q<=$total_paginas;$q++): ?>
						<li class="<?php echo ($p==$q)?'active':''; ?>" ><a href="index.php?<?php 
						$w = $_GET;
						$w['p'] = $q;
						echo http_build_query($w); 
						?>"><?php echo $q; ?></a></li>
					<?php endfor; ?>
				</ul>


		</div>
	</div>

<?php require 'pages/footer.php'; ?>