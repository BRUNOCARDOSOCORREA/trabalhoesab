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
	<script src="telas/js/formUsuario.js" ></script>
</head>
<body>
	
	<div class="container bg-secondary" style="--bs-bg-opacity: .25;">
		<!-- menu  -->
		<?php
				include ("menu.php");
		?>
		
		<!-- sistema -->
		<div class="row">
			<p class="h5 bg-danger mt-2 p-2" style="--bs-bg-opacity: .5;">Cadastro de Usuario</p>
		</div>
		<div class="row">
			<!-- formulario de pesquisa -->
			<div class="col-lg-4 ">
				<form id="form_pesquisa_titulo" action="index.php?mod=usuario" method="post" >
					<input type="hidden" name="hdnTituloSelecionado" value="" />
					<div class="row mt-2">					
							<div class="input-group-sm d-flex flex-nowrap">
								<input name="nome" class="form-control ms-1" >
								<button type="submit" class="btn btn-success ms-1" name="btnPesquisar">Pesquisar</button>
								<button type="button" class="btn btn-primary ms-1" id="btnNovo" onclick="btnNovo_onclick();">Novo Usuário</button>
							</div>					
					</div>
					<div class="row  mt-2 ">							
								<p class="h6">Usuários</p>
									<select class="form-select  mx-2" multiple  id="selUsuario" name="selUsuario" onchange="selUsuario_onSelected();" >										
										<?php	foreach($_lista as $ch=>$val): 	?>										
											<option value="<?= $val['id_usuario']?>" <?= isset($_usuario_selecionado) && $val['id_usuario']== $_usuario_selecionado ?'selected':'' ?> > <?= $val['nome']?></option>
										<?php endforeach;?>	
									</select>
						</div>					
				</form>
			</div>
			
			<!-- formulario de cadastro-->
			<div class="col-lg-8 ps-4">
				<form id="form_pesquisa_titulo" action="index.php?mod=usuario" method="post" onsubmit="return validar();">
					<input type="hidden" id="hdnId" name="hdnId" value="<?= isset($_usuario->id)?$_usuario->id:0 ?>"/>	
					
					<div class="row">
						<div class="col-12">
							<label for="txtTitulo" class="form-label">Funcionário:</label>
							<select class="form-select" id="txtFuncionario" name="txtFuncionario">
								<option value="0" <?= !isset($_usuario->funcionario)?'selected':''   ?>></option>
								<?php foreach ($_funcionarios as $ch=>$item): ?>	
								<option value="<?= $item['id']?>" <?= isset($_usuario->funcionario) && $_usuario->funcionario->id== $item['id']?'selected':'' ?> >   <?= $item['nome']?> </option>
								<?php endforeach; ?>					
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<label for="txtLogin" class="form-label">Login:</label>
							<input class="form-control " id="txtLogin" name="txtLogin" value="<?= isset($_usuario->login)?$_usuario->login:'' ?>" >
						</div>	
					</div>
					<div class="row">
						<div class="col-12">
							<label for="txtSenha" class="form-label">Senha:</label>
							<input class="form-control " id="txtSenha" name="txtSenha" value="<?= isset($_usuario->senha)?$_usuario->senha:'' ?>" >
						</div>	
					</div>
					<div class="row">
						<div class="col-4">
							<label for="txtSenha" class="form-label">Perfil:</label>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="txtPerfil" id="txtperfilfuncionario" value="0" <?= isset($_usuario) && $_usuario->eh_administrador==0?'checked':'' ?> >
  								<label class="form-check-label" for="txtperfilfuncionario">Funcionário</label>
							</div>
							<div class="form-check">
  								<input class="form-check-input" type="radio" name="txtPerfil" id="txtperfiladministrador" value="1" <?= isset($_usuario)&& $_usuario->eh_administrador==1?'checked':'' ?> >
  								<label class="form-check-label" for="txtperfiladministrador">Administrador</label>
  							</div>
						</div>
						<div class="col-4 ">
							<label for="txtSenha" class="form-label">Status:</label>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="txtStatus" id="txtstatusativo" value="1" <?= isset($_usuario)&&$_usuario->status=='1'? 'checked':'' ?> >
  								<label class="form-check-label" for="txtstatus" >Ativo</label>
							</div>
							<div class="form-check">
  								<input class="form-check-input" type="radio" name="txtStatus" id="txtstatusdesativado" value="0"  <?= isset($_usuario)&&$_usuario->status=='0'? 'checked':'' ?> >
  								<label class="form-check-label" for="txtstatus"  >Desativado</label>
  							</div>
						</div>
						<div class="col-4 ">
							&nbsp;
						</div>
					</div>					
					<div class="row ">
						<div class="input-group-sm text-center pt-4" >
							<button type="submit" class="btn btn-success" id="btnSalvar" name="btnSalvar">Salvar</button>
							<!-- <button type="submit" class="btn btn-warning ms-2" id="btnCancelar" name="btnCancelar">Cancelar</button>  -->
							<a href="index.php?mod=usuario" class="btn btn-warning ms-2">Cancelar</a>
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
					<?= empty($_POST)||(!isset($_POST['btnNovaMidia'])&&!isset($_POST['selUsuario']))?"inicia_usuario();":"" ?>
					<?= isset($_POST['btnNovaMidia'])?"nova_midia();":"" ?>
					<?= isset($_POST['selUsuario'])?"editar_usuario();":"" ?>					
				</script>
				
			</div>
		</div>
		
					
	</div>
	
</body>
</html>