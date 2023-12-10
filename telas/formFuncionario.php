<?php

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
	<script src="telas/js/formFuncionario.js" ></script>
</head>
<body>
	
	<div class="container bg-secondary" style="--bs-bg-opacity: .25;">
		<!-- menu  -->		
		<?php
				include ("menu.php");
		?>
		
		<!-- sistema -->
		<div class="row">
			<p class="h5 bg-danger mt-2 p-2" style="--bs-bg-opacity: .5;">Cadastro de Funcionários</p>
		</div>
		<div class="row">
			<!-- formulario de pesquisa -->
			<div class="col-lg-4 ">
				<form id="form_pesquisa_titulo" action="index.php?mod=funcionario" method="post" >
					<input type="hidden" name="hdnTituloSelecionado" value="" />
					<div class="row mt-2">					
							<div class="input-group-sm d-flex flex-nowrap">
								<input name="nome" class="form-control ms-1" >
								<button type="submit" class="btn btn-success ms-1" name="btnPesquisar">Pesquisar</button>
								<button type="button" class="btn btn-primary ms-1" id="btnNovo" onclick="btnNovo_onclick();">Novo Funcionário</button>
							</div>					
					</div>
					<div class="row  mt-2 ">							
								<p class="h6">Funcionários</p>
									<select class="form-select  mx-2" multiple  id="selTitulo" name="selFuncionario" onchange="selFuncionario_onSelected();" >										
										<?php	foreach($_lista as $ch=>$val): 	?>										
											<option value="<?= $val['id']?>" <?= isset($_cliente_selecionado) && $val['id']== $_cliente_selecionado ?'selected':'' ?> > <?= $val['nome']?></option>
										<?php endforeach;?>	
									</select>
						</div>					
				</form>
			</div>
			
			<!-- formulario de cadastro-->
			<div class="col-lg-8 ps-4">
				<form action="index.php?mod=funcionario" method="post" onsubmit="return validar();">
					<input type="hidden" id="hdnId" name="hdnId" value="<?= isset($_funcionario->id)?$_funcionario->id:0 ?>"/>					
					
					<div class="row">
						<div class="col-12">
							<label for="txtTitulo" class="form-label">Nome: </label>
							<input class="form-control " id="txtNome" name="txtNome" value="<?= isset($_funcionario->nome)?$_funcionario->nome:'' ?>" >
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<label for="txtTitulo" class="form-label">Endereço:</label>
							<input class="form-control " id="txtLogradouro" name="txtLogradouro" value="<?= isset($_funcionario->logradouro)?$_funcionario->logradouro:'' ?>" >
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<label for="txtDescricao" class="form-label">Bairro:</label>
							<input class="form-control " id="txtBairro" name="txtBairro" value="<?= isset($_funcionario->bairro)?$_funcionario->bairro:'' ?>" >							
						</div>	
					</div>
						<div class="row">
						<div class="col-6">
							<label for="txtCodigo" class="form-label">CPF::</label>		
							<input class="form-control " id="txtCPF" name="txtCPF" value="<?= isset($_funcionario->cpf)?$_funcionario->cpf:'' ?>" >				
							
						</div>
						<div class="col-6 ">
							<label for="txtQdeEstoque" class="form-label">RG:</label>
							<input class="form-control " id="txtRG" name="txtRG" value="<?= isset($_funcionario->rg)?$_funcionario->rg:'' ?>" >						
							
						</div>
					</div>			
					
					<div class="row ">
						<div class="input-group-sm text-center pt-4" >
							<button type="submit" class="btn btn-success" id="btnSalvar" name="btnSalvar">Salvar</button>
							<!-- button type="submit" class="btn btn-warning ms-2" id="btnCancelar" name="btnCancelar">Cancelar</button> -->
							<a href="index.php?mod=funcionario" class="btn btn-warning ms-2"> Cancelar</a>
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
					<?= empty($_POST)||(!isset($_POST['btnNovaMidia'])&&!isset($_POST['selFuncionario']))?"inicia_funcionario();":"" ?>
					<?= isset($_POST['btnNovaMidia'])?"nova_midia();":"" ?>
					<?= isset($_POST['selMidia'])?"editar_midia();":"" ?>					
				</script>
			</div>
		</div>	
					
	</div>
		       

	
</body>
</html>