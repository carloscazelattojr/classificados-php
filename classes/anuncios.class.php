<?php 


class Anuncios{

	public function getMeusAnuncios(){

		global $pdo;
		$array = array();

		$sql = $pdo->prepare("SELECT a.*, (SELECT ai.url FROM anuncios_imagens ai WHERE ai.id_anuncio = a.id limit 1) as url FROM anuncios a WHERE id_usuario = :id_usuario");
		$sql->bindValue(":id_usuario",$_SESSION['cLogin']);
		$sql->execute();

		if ( $sql->rowCount()>0 ) {
			$array = $sql->fetchAll();
		}

		return $array;
	}

	public function getTotalAnuncios($filtros){
		global $pdo;
		

		$filtroString = array("1=1");
		if ( !empty($filtros['categoria'])){
			$filtroString[] = 'a.id_categoria = :id_categoria';
		}
		if ( !empty($filtros['preco'])){
			$filtroString[] = 'a.valor BETWEEN :preco1 AND :preco2';
		}
		if ( !empty($filtros['estado'])){
			$filtroString[] = 'a.estado = :estado';
		}



		$sql = $pdo->prepare("SELECT * FROM anuncios  A WHERE ".implode(" AND ", $filtroString) );
		if ( !empty($filtros['categoria'])){
			$sql->bindValue("id_categoria",$filtros['categoria'] );
		}
		if ( !empty($filtros['preco'])){
			$preco = explode('-',$filtros['preco']);
			$sql->bindValue("preco1",$preco[0] );
			$sql->bindValue("preco2",$preco[1] );
		}
		if ( !empty($filtros['estado'])){
			$sql->bindValue("estado",$filtros['estado'] );
		}		
		$sql->execute();
		return $sql->rowCount();
	}

	public function getAnuncio($id){
		
		$array = array();
		global $pdo;

		$sql = $pdo->prepare("SELECT c.nome AS categoria, u.nome AS nome_usuario, u.telefone AS telefone_usuario, a.* FROM anuncios a, categorias c, usuarios u WHERE a.id = :id and c.id = a.id_categoria and u.id = a.id_usuario");
		$sql->bindValue(":id", $id);
		$sql->execute();

		if ( $sql->rowCount()>0 ){
			$array 			= $sql->fetch();
			$array['fotos'] = array(); //crio em branco

			//Selecionar as fotos
			$sql = $pdo->prepare("SELECT * FROM anuncios_imagens WHERE id_anuncio = :id_anuncio");
			$sql->bindValue(":id_anuncio", $id);
			$sql->execute();

			if ( $sql->rowCount() > 0){
				$array['fotos'] = $sql->fetchAll();
			}

		}

		return $array;
	}


	public function addAnuncio($titulo, $categoria, $descricao, $valor, $estado ){

		global $pdo;


		$sql = $pdo->prepare("INSERT INTO anuncios (id_usuario, id_categoria, titulo, descricao, valor, estado)	value (:id_usuario, :id_categoria, :titulo, :descricao, :valor,  :estado)");
		
		$sql->bindValue(":id_usuario", $_SESSION['cLogin']);
		$sql->bindValue(":id_categoria", $categoria);
		$sql->bindValue(":titulo", $titulo );
		$sql->bindValue(":descricao", $descricao );
		$sql->bindValue(":valor", $valor );
		$sql->bindValue(":estado", $estado);
		$sql->execute();

	}
	
	public function editAnuncio($titulo, $categoria, $descricao, $valor, $estado, $fotos, $id ){

		global $pdo;

		$sql = $pdo->prepare("UPDATE anuncios SET id_usuario = :id_usuario, id_categoria = :id_categoria, titulo = :titulo, descricao = :descricao, valor = :valor, estado = :estado WHERE id = :id");
		
		$sql->bindValue(":id_usuario", $_SESSION['cLogin']);
		$sql->bindValue(":id_categoria", $categoria);
		$sql->bindValue(":titulo", $titulo );
		$sql->bindValue(":descricao", $descricao );
		$sql->bindValue(":valor", $valor );
		$sql->bindValue(":estado", $estado);
		$sql->bindValue(":id", $id);
		$sql->execute();

		if (count($fotos) > 0 ){
			for($q = 0; $q < count($fotos['tmp_name']); $q++){
				$tipo = $fotos['type'][$q];

				if (in_array($tipo, array('image/jpeg', 'image/png','image/bmp'))){
					$tmpname = md5( time().rand(0,9999)).'.jpg';
					move_uploaded_file( $fotos['tmp_name'][$q], 'assets/images/anuncios/'.$tmpname );

					list($width_orig, $height_orig) = getimagesize('assets/images/anuncios/'.$tmpname);

					$ratio = $width_orig/$height_orig; 

					$width  = 500;
					$height = 500;

					if ($width/$height > $ratio) {
						$width = $height_orig*$ratio;
					} else {
						$height = $width/$ratio;
					}

					$img = imagecreatetruecolor($width, $height);
					if ($tipo == 'image/jpeg'){
						$origi = imagecreatefromjpeg('assets/images/anuncios/'.$tmpname);
					} elseif ($tipo == 'image/png'){
						$origi = imagecreatefrompng('assets/images/anuncios/'.$tmpname);
					} elseif ($tipo == 'image/bmp'){
						$origi = imagecreatefrombmp('assets/images/anuncios/'.$tmpname);
					}
					
				 	imagecopyresampled($img, $origi, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				 	imagejpeg($img, 'assets/images/anuncios/'.$tmpname, 80);

				 	$sql = $pdo->prepare("INSERT INTO anuncios_imagens SET id_anuncio=:id_anuncio, url=:url ");
				 	$sql->bindValue('id_anuncio', $id);
				 	$sql->bindValue('url',$tmpname);
				 	$sql->execute();

					
				}	
			}
			
		}


	}

	public function excluirAnuncio($id){

		global $pdo;

		//Primeiro: apagar as imagens do Anúncio.
		$sql = $pdo->prepare("DELETE FROM anuncios_imagens WHERE id_anuncio = :id");
		$sql->bindValue(':id', $id);
		$sql->execute();
		
		//Segundo: Apagar o anúncio.
		$sql = $pdo->prepare("DELETE FROM anuncios WHERE id = :id");
		$sql->bindValue(':id', $id);
		$sql->execute();

	}

	public function excluirFoto($id){
		global $pdo;

		$id_anuncio = 0;

		//Primeiro: Pegaro ID do anuncio
		$sql = $pdo->prepare("SELECT id_anuncio FROM anuncios_imagens WHERE id = :id");
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount()>0){
			$row = $sql->fetch();
			$id_anuncio = $row['id_anuncio'];

			$sql = $pdo->prepare("DELETE FROM anuncios_imagens WHERE id = :id");
			$sql->bindValue(':id', $id);
			$sql->execute();
		}
		return $id_anuncio;
	}

	public function getUltimosAnuncios($page, $perPage, $filtros){
		global $pdo;

		$offset = ($page - 1) * $perPage;

		$filtroString = array("1=1");
		if ( !empty($filtros['categoria'])){
			$filtroString[] = 'a.id_categoria = :id_categoria';
		}
		if ( !empty($filtros['preco'])){
			$filtroString[] = 'a.valor BETWEEN :preco1 AND :preco2';
		}
		if ( !empty($filtros['estado'])){
			$filtroString[] = 'a.estado = :estado';
		}



		$array = array();
		$sql = $pdo->prepare("SELECT a.*,  c.nome AS categoria, (SELECT ai.url  FROM anuncios_imagens ai WHERE ai.id_anuncio=a.id LIMIT 1 ) AS url FROM categorias c, anuncios a WHERE c.id= a.id_categoria  AND ".implode(' AND ', $filtroString)." ORDER BY a.id DESC limit $offset, $perPage");
		if ( !empty($filtros['categoria'])){
			$sql->bindValue("id_categoria",$filtros['categoria'] );
		}
		if ( !empty($filtros['preco'])){
			$preco = explode('-',$filtros['preco']);
			$sql->bindValue("preco1",$preco[0] );
			$sql->bindValue("preco2",$preco[1] );
		}
		if ( !empty($filtros['estado'])){
			$sql->bindValue("estado",$filtros['estado'] );
		}


		$sql->execute();

		if ( $sql->rowCount() > 0){
			$array = $sql->fetchAll();
		}

		return $array;

	}





}




?>