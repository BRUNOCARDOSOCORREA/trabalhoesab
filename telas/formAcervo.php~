<?php
	$filme = null;
	$habilita_nova_midia = !isset($_itens)||$_itens == null?"disabled":"";
	
	if ( (isset($_titulo) && $_titulo != null) or (isset($_midia) && $_midia != null) ){		
		if (isset($_midia) && $_midia != null) $_titulo = $_midia->titulo;
		
		$id   = $_titulo->id;
		$nome = $_titulo->nome;
		$nome_original  = $_titulo->nome_original;
		$descricao      = $_titulo->descricao;
		$ano_lancamento = $_titulo->ano_lancamento;
	}

?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de Controle de Locações</title>
	<!-- Bootstrap links CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<style>	
    	option:checked,
    	option:hover {
      	background-color: #85c1e9 ;
    	}
    	input:read-only {
  			background: #cdcdcd;
		}		
		textarea:read-only {
  			background: #cdcdcd;
		}
		.select_read_only {
			background: #cdcdcd;
			pointer-events: none;
			touch-action=none;
		}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="telas/js/formAcervo.js" ></script>
</head>
<body>
	
	<div class="container bg-secondary" style="--bs-bg-opacity: .25;">
		<!-- menu  -->
		<?php
				include ("menu.php");
		?>
		
		<!-- sistema -->
		<div class="row">
			<p class="h5 bg-danger mt-2 p-2" style="--bs-bg-opacity: .5;">Cadastro de Acervo</p>
		</div>
		<div class="row">
			<!-- formulario de pesquisa -->
			<div class="col-lg-4 ">
				<form id="form_pesquisa_titulo" action="index.php?mod=acervo" method="post" >
					<input type="hidden" name="hdnTituloSelecionado" value="" />
					<div class="row mt-2">					
							<div class="input-group-sm d-flex flex-nowrap">
								<input name="nome_filme" class="form-control ms-1" >
								<button type="submit" class="btn btn-success ms-1" name="btnPesquisar">Pesquisar</button>
								<button type="button" class="btn btn-primary ms-1" id="btnNovo" onclick="btnNovo_onclick();">Novo Titulo</button>
							</div>					
					</div>
					<div class="row  mt-2 ">							
								<p class="h6">Filmes</p>
									<select class="form-select  mx-2" multiple  id="selTitulo" name="selTitulo" onchange="selTitulo_onSelected();">										
										<?php	foreach($_lista as $ch=>$val): 	?>										
											<option value="<?= $val['id']?>" <?= isset($_titulo_selecionado) && $val['id']== $_titulo_selecionado ?'selected':'' ?> > <?= $val['nome']?></option>
										<?php endforeach;?>	
									</select>
						</div>
					
					<div id="tab_midia_disponivel" class="row  mt-2 " <?= isset($_exibir_lista_midias) && $_exibir_lista_midias==true?'':'hidden'?>	>
						<input type="hidden" name="hdnMidiaSelecionada" value="" />												
						<p class="h6 ">Midias Disponíveis na Locadora:</p>
						<button type="submit" class="btn btn-primary ms-1 w-40 <?=$habilita_nova_midia  ?>" onclick="btnNovaMidia_onclick();" id="btnNovaMidia" name="btnNovaMidia" >Nova Mídia</button>							
								<select id="selMidia" name="selMidia"  class="form-select  mx-2 " <?=$habilita_nova_midia  ?> multiple  onchange="selMidia_onSelected();">									
									<?php	
										foreach($_itens as $ch=>$val):
								   		$m = $val['id_midia'] ==1?'VHS':'DVD';
											$cod =  $val['codigo'] .' - '. $m ;	
									?>
										<option value="<?= $val['codigo']?>"><?= $cod ?></option>
									<?php endforeach;?>	
								</select>						
					</div>
				</form>
			</div>
			
			<!-- formulario de cadastro-->
			<div class="col-lg-8 ps-4">
				<form action="index.php?mod=acervo" method="post" onsubmit="return validar_filme();">
					<input type="hidden" id="hdnId" name="hdnId" value="<?= isset($_midia->id)?$_midia->id:0 ?>"/>
					<input type="hidden" id="hdnIdTitulo" name="hdnIdTitulo" value="<?= isset($_titulo->id)?$_titulo->id:0 ?>"/>
					<input type="hidden" id="hdnCodigo" name="hdnCodigo" value="<?= isset($_midia->codigo)?$_midia->codigo:0 ?>"/>
					
					<div class="row">
						<div class="col-12">
							<label for="txtTitulo" class="form-label">Titulo </label>
							<input class="form-control " id="txtTitulo" name="txtTitulo" value="<?= isset($_titulo->nome)?$_titulo->nome:'' ?>" >
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<label for="txtTitulo" class="form-label">Titulo Original</label>
							<input class="form-control " id="txtTituloOriginal" name="txtTituloOriginal" value="<?= isset($_titulo->nome_original)?$_titulo->nome_original:'' ?>" >
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<label for="txtDescricao" class="form-label">Descrição:</label>
							<textarea class="form-control " id="txtDescricao" name="txtDescricao" ><?= isset($_titulo->descricao)?$_titulo->descricao:'' ?></textarea>
						</div>	
					</div>
						<div class="row">
						<div class="col-6">
							<label for="txtCodigo" class="form-label">Ano de Lançamento:</label>
							<input type="text" min="1" class="form-control" id="txtAnoLancamento" name="txtAnoLancamento" value="<?= isset($_titulo->ano_lancamento)?$_titulo->ano_lancamento:'' ?>" >
						</div>
						<div class="col-6 ">
							<label for="txtQdeEstoque" class="form-label">Codigo</label>
							<input type="text" min="1" max="5" class="form-control " id="txtCodigo" name="txtCodigo" value="<?= isset($_midia->codigo)?$_midia->codigo:'' ?>">
						</div>
					</div>					
					<div class="row">
						<div class="col-6">
							<label for="txtPeriodoMaxEmprestimo" class="form-label">Período Máximo de Empréstimo (dias)</label>
							<input type="number" min="1" class="form-control" id="txtPeriodoMaxEmprestimo" name="txtPeriodoMaxEmprestimo" value="<?= isset($_midia->periodo_max)?$_midia->periodo_max:'' ?>"  >
						</div>						
					</div>
					<div class="row">
						<div class="col-6">
								<label for="" class="form-label">Mídia</label>										
								<select class="form-select " id="txtMidia" name="txtMidia"  >
								<option value="0" <?= !isset($_midia->midia)?'selected':''   ?> ></option>
								<?php foreach ($_midias as $ch=>$item): ?>
								
								<option value="<?= $item['id']?>" <?= isset($_midia->midia) && $_midia->midia == $item['id']?'selected':'' ?> >   <?= $item['nome']?> </option>
								<?php endforeach; ?>							
							</select>			
						</div>
						<div class="col-6">
							<label for="txtStatus" class="form-label">Status</label>
							<select class="form-select" id="txtStatus" name="txtStatus">
								<option value="0" <?= !isset($_midia->midia)?'selected':''   ?>></option>
								<?php foreach ($_situacao as $ch=>$item): ?>	
								<option value="<?= $item['id']?>" <?= isset($_midia->status) && $_midia->status == $item['id']?'selected':'' ?> >   <?= $item['nome']?> </option>
								<?php endforeach; ?>					
							</select>
							
						</div>					
					</div>
					<div class="row ">
						<div class="input-group-sm text-center pt-4" >
							<button type="submit" class="btn btn-success" id="btnSalvar" name="btnSalvar">Salvar</button>
							<!-- <button type="submit" class="btn btn-warning ms-2" id="btnCancelar" name="btnCancelar">Cancelar</button> -->
							<a href="index.php?mod=acervo" class="btn btn-warning ms-2">Cancelar</a>
							<button type="submit" class="btn btn-danger ms-2" id="btnExcluir" name="btnExcluir">Excluir</button>
						</div>
					</div>
					<div class="row ">
						<br>&nbsp;
					</div>
					<?php  if (isset($_msg_ok)):?>
					<div class="alert alert-success" role="alert">
						  <?= $_msg_ok ?>
					</div>
					<?php endif; ?>
					<?php  if (isset($_msg_erro)):?>
					<div class="alert alert-danger" role="alert">
  							<?= $_msg_erro ?>
					</div>
					<?php endif; ?>
					<div class="alert alert-danger" role="alert" id="validacao" style="visibility: hidden"></div>
				</form>
				<script>
					<?= empty($_POST)||(!isset($_POST['btnNovaMidia'])&&!isset($_POST['selMidia']))?"inicia_controle_acervo();":"" ?>
					<?= isset($_POST['btnNovaMidia'])?"nova_midia();":"" ?>
					<?= isset($_POST['selMidia'])?"editar_midia();":"" ?>					
				</script>
			</div>
		</div>	
					
	</div>
		       

	
</body>
</html>