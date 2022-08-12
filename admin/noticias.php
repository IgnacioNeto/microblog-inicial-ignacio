<?php 
use Microblog\Noticia;
use Microblog\Utilitarios;

require_once "../inc/cabecalho-admin.php";

$noticia = new Noticia;

/* Captura o id e o tipo do usuario logado
e associando estes valores às propriedades do objeto usuario */
$noticia->usuario->setId($_SESSION['id']);
$noticia->usuario->setTipo($_SESSION['tipo']);
$listaDeNoticias = $noticia->listar();

// Teste
// Utilitarios::dump($listaDeNoticias);

?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">
		
		<h2 class="text-center">
		Notícias <span class="badge bg-dark"><?=count($listaDeNoticias)?></span>
		</h2>

		<p class="text-center mt-5">
			<a class="btn btn-primary" href="noticia-insere.php">
			<i class="bi bi-plus-circle"></i>	
			Inserir nova notícia</a>
		</p>
				
		<div class="table-responsive">
		
		

			<table class="table table-hover">
				<thead class="table-light">
					<tr>
                        <th>Título</th>
                        <th>Data</th>

                        <?php if($_SESSION['tipo'] == 'admin') { ?>
							<th>Autor</th>
						<?php } ?>

						<th class="text-center">Destaque</th>
						<th class="text-center" colspan="2">Operações</th>
						
					</tr>
				</thead>

				<tbody>
				<?php foreach($listaDeNoticias as $noticia){ ?>
					<tr>
                        <td> <?=$noticia['titulo']?> </td>
                        <td> <?=Utilitarios::formataData($noticia['data'])?> </td>

						<?php if($_SESSION['tipo'] === 'admin') { ?>
						
							<!-- ?? Operador de Coalescência Nula:
							Na prática, o valor à esquerda é exibido, (desde que ele
							exista), caso contrário o valor a direita é exibido -->
                        	
							<?php
							if ( $noticia['autor'] ) { ?>
							<td><?=Utilitarios::limitaCaractere($noticia['autor'])?></td> 
							<?php
								} else {
							?> <td>Equipe Microblog</td> <?php
								}
							} ?>
						
							<td class="text-center"><?=$noticia['destaque']?></td>

						<td class="text-center">
							<a class="btn btn-warning" 
							href="noticia-atualiza.php?id=<?=$noticia['id']?>">
							<i class="bi bi-pencil"></i> Atualizar
							</a>		
						</td>
						<td>
							<a class="btn btn-danger excluir" 
							href="noticia-exclui.php?id=<?=$noticia['id']?>">
							<i class="bi bi-trash"></i> Excluir
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>                
			</table>
	</div>
		
	</article>
</div>


<?php 
require_once "../inc/rodape-admin.php";
?>

