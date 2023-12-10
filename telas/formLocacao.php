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
}

	</style>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="telas/js/formRegistrarLocacao.js" ></script>
</head>
<body>
	
	<div class="container bg-secondary" style="--bs-bg-opacity: .25;">
		<!-- menu  -->		
		<?php
				include ("menu.php");
		?>
				
		<!-- sistema -->
		<div class="row">
			<p class="h5 bg-danger mt-2 p-2" style="--bs-bg-opacity: .5;">Registro de Locação</p>
		</div>
		<div class="row">
			<!-- formulario de pesquisa -->
			<div class="col-lg-4 ">
				<form id="form_pesquisa_cliente" action="index.php?mod=locacao" method="post" >
					<div class="row mt-2">					
							<div class="input-group-sm d-flex flex-nowrap">
								<input class="form-control ms-1" name="nome" >
								<button type="submit" class="btn btn-success ms-1" name="btnPesquisar">Pesquisar</button>
								<!-- <button type="button" class="btn btn-primary ms-1">Novo</button> -->
							</div>					
					</div>
					<div class="row  mt-2 ">							
								<p class="h6">Clientes</p>
									<select class="form-select  mx-2" multiple  id="selTitulo" name="selCliente" onchange="selCliente_onSelected();" >									
										<?php	foreach($_lista as $ch=>$val): 	?>										
											<option value="<?= $val['id']?>" <?= isset($_cliente_selecionado) && $val['id']== $_cliente_selecionado ?'selected':'' ?> > <?= $val['nome']?></option>
										<?php endforeach;?>	
									</select>
						</div>	
				</form>
				
			</div>
			
			<!-- formulario de cadastro-->
			<div class="col-lg-8 ps-4">
				<form action="index.php?mod=locacao" method="post" onsubmit="return validar();">
					
					<input type="hidden" id="txtvalorlocacao" value="<?= $_config->valor_locacao ?>"/>
					
					<div class="row">
						<div class="col-12">
							<label for="txtNome" class="form-label">Cliente</label>
							<input type="hidden" name="txtIdCliente" value="<?= isset($_cliente)?$_cliente->id:'0' ?>"/>
							<input class="form-control " id="txtNome" name="txtNome" value="<?= isset($_cliente)?$_cliente->nome:'' ?>" disabled>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<label for="txtEndereco" class="form-label">Data</label>
							<input class="form-control " id="txtdatalocacao" name="txtDtLocacao" disabled value="<?= date('d/m/Y') ?>"  >
						</div>	
							<div class="col-6">
							<label for="txtEndereco" class="form-label">Entrega</label>
							<input class="form-control " type="date" id="txtdataentrega" name="txtDtEntrega" disabled>																									
						</div>	
					</div>
					<div class="row">
						<div class="col-9 ">
							<label for="txtEndereco" class="form-label"></label>
							<select class="form-select  mx-2" id="selItens" name="selItens[]" multiple>								
							</select>
						</div>						
						<div class="col-3 ">
							<label for="txtEndereco" class="form-label"></label>	
							<div class="input-group-sm " >			
								<button type="button" class="btn btn-success bg-success" data-bs-toggle="modal" data-bs-target="#exampleModal" style="width:30px;" id="btnSelTitulo">+</button><br>
								<button type="button" class="btn btn-warning bg-danger mt-2" style="width:30px;" onclick="btndeltem_onclick();" id="btnDelTitulo">-</button><br>								
							</div>							
						</div>						
					</div>
					<div class="row">
						<div class="col-6">
								<label for="txtrg" class="form-label">Total:</label>
								<input class="form-control ms-1" id="txtqde"  disabled value="">					
						</div>	
						<div class="col-6">
								<label for="txtrg" class="form-label">Valor Total:</label>
								<input class="form-control ms-1" id="txtvalortotal" disabled readonly>					
						</div>									
					</div>					
					<div class="row ">
						<div class="input-group-sm text-center pt-4" >
							<button type="submit" class="btn btn-success" id="btnSalvar" name="btnSalvar">Salvar</button>
							<!-- <button type="submit" class="btn btn-warning ms-2" id="btnCancelar" name="btnCancelar">Cancelar</button> -->
							<a href="index.php?mod=locacao" class="btn btn-warning ms-2">Cancelar</a>							
						</div>
					</div>
					<div class="row ">
						<br>&nbsp;
					</div>					
				</form>				
				<div class="alert alert-danger" role="alert" id="validacao" style="visibility: hidden"></div>
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
			</div>
		</div>
		
		<!-- modal -->
		<!-- ADICIONAR TITULO -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  			<div class="modal-dialog" role="document">  			
  			<form id="form_pesquisa_titulo"  >
    			<div class="modal-content">
      			<div class="modal-header">
      				<h5 class="modal-title">Adicionar Titulo</h5>
      				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>     	
      			</div>
      			<div class="modal-body">
		      			<div class="alert alert-danger" role="alert" id="msg_erro" style="visibility: hidden" ></div>
      					<div class="row">
	      					<div class="col-9">
									<label for="txtrg" class="form-label">Código:</label>
									<input class="form-control ms-1" id="txtcod"   >
								</div>
								<div class="col-3">		
									<button id="btnbuscar" type="button" class="btn btn-success ms-1" onclick="btnbuscar_onclick();">Buscar</button>			
								</div>
      					</div>
      					<div class="row">
									
								<div class="col-6">
									<label for="txtrg" class="form-label">Mídia:</label>
									<input class="form-control ms-1" id="txtmidia" disabled readonly>					
								</div>									
							</div>
							<div class="row">
								<div class="col-12">
									<label for="txtrg" class="form-label">Nome:</label>
									<input class="form-control ms-1" id="txtnome" disabled >					
								</div>	
							</div>
							<div class="row">
								<div class="col-6">
									<label for="txtrg" class="form-label">Prazo de Empréstimo (dias):</label>
									<input class="form-control ms-1" id="txtprazo" disabled >					
								</div>	
								<div class="col-6">
									<label for="txtrg" class="form-label">Valor:</label>
									<input class="form-control ms-1" id="txtvalor" disabled readonly>					
								</div>									
							</div>
      			</div>
     				<div class="modal-footer">
      				<button type="button" class="btn btn-primary" id="btnadditem"  onclick="btnadditem_onclick();">Adicionar</button>      				
      				<a href="index.php?mod=locacao" class="btn btn-secondary"> Cancelar </a>
      			</div>
    			</div>
    			</form>
  			</div>
		</div>				
		<!-- modal -->	

		<!-- modal -->
		<!-- EFETUAR PAGAMENTO -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">
      			<div class="modal-header">
      				<h5 class="modal-title">Adicionar Titulo</h5>
      				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>     	
      			</div>
      			<div class="modal-body">
      					<div class="row">
								<div class="col-6">
									<label for="txtrg" class="form-label">Código:</label>
									<input class="form-control ms-1" id="txtcod"   >					
								</div>	
								<div class="col-6">
									<label for="txtrg" class="form-label">Mídia:</label>
									<input class="form-control ms-1" id="txtrg" name="txtrg" disabled readonly>					
								</div>									
							</div>
							<div class="row">
								<div class="col-12">
									<label for="txtrg" class="form-label">Nome:</label>
									<input class="form-control ms-1" id="txtrg" name="txtrg" disabled >					
								</div>	
							</div>
							<div class="row">
								<div class="col-6">
									<label for="txtrg" class="form-label">Prazo de Empréstimo :</label>
									<input class="form-control ms-1" id="txtrg" name="txtrg" disabled >					
								</div>	
								<div class="col-6">
									<label for="txtrg" class="form-label">Valor:</label>
									<input class="form-control ms-1" id="txtrg" name="txtrg" disabled readonly>					
								</div>									
							</div>
      			</div>
     				<div class="modal-footer">
      				<button type="button" class="btn btn-primary">Adicionar</button>
      				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      			</div>
    			</div>
  			</div>
		</div>				
		<!-- modal -->
				
	</div>		
	    
	
	<script>
		<?= empty($_POST)||(!isset($_POST['btnNovaMidia'])&&!isset($_POST['selCliente']))?"inicia_locacao();":"" ?>
		<?= isset($_POST['selCliente'])?"nova_locacao();":"" ?>							
	</script>
</body>
</html>
