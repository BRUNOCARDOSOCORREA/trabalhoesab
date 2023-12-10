<?php
/*
if(isset($_locacao)){
	$hj = date_create(date('Y-m-d')); 
	$entrega = date_create(implode('-', array_reverse(explode('/', $_locacao->data_entrega))));
	$delta = date_diff($entrega, $hj)->format('%a');		
}
*/

if(isset($_locacao)){
	$valor_total = $_locacao->valor_base + $_multa;
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
}

	</style>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="telas/js/formRegistrarDevolucao.js" ></script>
</head>
<body>
	
	<div class="container bg-secondary" style="--bs-bg-opacity: .25;">
	
		<!-- menu  -->
		<?php
				include ("menu.php");
		?>
				
		<!-- sistema -->
		<div class="row">
			<p class="h5 bg-danger mt-2 p-2" style="--bs-bg-opacity: .5;">Registro de Devolução</p>
		</div>
		<div class="row">
			<!-- formulario de pesquisa -->
			<div class="col-lg-4 ">
				<form id="form_pesquisa_cliente" action="index.php?mod=devolucao" method="post" >
					<div class="row mt-2">					
							<div class="input-group-sm d-flex flex-nowrap">
								<input class="form-control ms-1" name="nome" >
								<button type="submit" class="btn btn-success ms-1" name="btnPesquisar">Pesquisar</button>
								<!-- <button type="button" class="btn btn-primary ms-1">Novo</button> -->
							</div>					
					</div>
					<div class="row  mt-2 ">							
								<p class="h6">Clientes</p>
									<select class="form-select  mx-2" multiple  id="selTitulo" name="selLocacao" onchange="selLocacao_onSelected();" >									
										<?php	foreach($_lista as $val): 	?>										
											<option value="<?= $val['id_locacao']?>" > <?= $val['nome']?></option>
										<?php endforeach;?>	
									</select>
						</div>	
				</form>
				
			</div>
			
			<!-- formulario de cadastro-->
			<div class="col-lg-8 ps-4">
				<form action="index.php?mod=devolucao" method="post" onsubmit="return validar();"> 
					
					<input type="hidden" id="txtIdlocacao" name="txtIdlocacao" value="<?= isset($_locacao)?$_locacao->id:'' ?>"/>
					<input type="hidden" id="txtvalorlocacao" name="txtvalorlocacao" value="<?= $_config->valor_locacao ?>"/>
					
					<div class="row">
						<div class="col-12">
							<label for="txtNome" class="form-label">Cliente</label>
							<input type="hidden" name="txtIdCliente" value="<?= isset($_locacao)?$_locacao->id:'0' ?>"/>
							<input class="form-control " id="txtNome" name="txtNome" value="<?= isset($_locacao)?$_locacao->cliente->nome:'' ?>" disabled>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<label for="txtEndereco" class="form-label">Data</label>
							<input class="form-control " id="txtdatalocacao" name="txtDtLocacao" disabled value="<?= isset($_locacao)?$_locacao->data_locacao:'' ?>"  >
						</div>	
							<div class="col-6">
							<label for="txtEndereco" class="form-label">Entrega</label>
							<input class="form-control " type="text" id="txtdataentrega" name="txtDtEntrega" disabled value="<?= isset($_locacao)?$_locacao->data_entrega:'' ?>">																									
						</div>	
					</div>
					<div class="row">
						<div class="col-9 ">
							<label for="txtEndereco" class="form-label"></label>
							<select class="form-select  mx-2" id="selItens" multiple>
							<?php
								if(isset($_locacao)):
									foreach($_locacao->listaItemLocacao as $item):   
										$midia = $item->midia==1?'VHS':'DVD';
							?>
							<option value="<?= $item->id?>"><?= $item->titulo->nome . ' - ' . $midia ?></option>
							<?php 
									endforeach;
								endif;
							?>								
							</select>
						</div>			
					</div>
					<div class="row">
						<div class="col-6">
								<label for="txtrg" class="form-label">Qde de Itens:</label>
								<input class="form-control ms-1" id="txtqde"  disabled value="<?= isset($_locacao)?count($_locacao->listaItemLocacao):'' ?>">					
						</div>	
						<div class="col-6">
								<label for="txtrg" class="form-label">Valor Locação:</label>
								<input class="form-control ms-1" id="txtvalorlocacao" disabled readonly value="<?= isset($_locacao)?$_locacao->valor_base:'' ?>" >					
						</div>									
					</div>
					<div class="row">
						<div class="col-6">
								<label for="txtrg" class="form-label">Valor Desconto:</label>
								<input class="form-control ms-1" id="txtqde"  disabled value="<?= isset($_locacao)?$_locacao->valor_desconto:'' ?>">					
						</div>	
						<div class="col-6">
								<label for="txtrg" class="form-label">Valor Multa:</label>
								<input class="form-control ms-1" id="txtvalortotal" disabled readonly value="<?= isset($_multa)?$_multa:'0' ?>" >					
						</div>									
					</div>					
					<div class="row ">
						<div class="input-group-sm text-center pt-4" >
							<?php  if(isset($_multa) && $_multa > 0):  ?>
							<!-- <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalSenha" >Cancelar Multa</button> -->
							<?php  endif; ?>
							<button type="submit" class="btn btn-success" id="btnSalvar" name="btnSalvar">Salvar</button>
							<button type="submit" class="btn btn-warning ms-2" id="btnCancelar" name="btnCancelar">Cancelar</button>								
							<button type="button" class="btn btn-primary bg-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"  id="btnPagamento">Pagamento</button><br>						
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
		<!-- EFETUAR PAGAMENTO -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">
      			<div class="modal-header">
      				<h5 class="modal-title">Efetuar Pagamento</h5>
      				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>     	
      			</div>
      			<div class="modal-body">
      					<input type="hidden" id="qdediasatraso" value="<?= isset($_delta)?$_delta:0 ?>" />
      					<!--
      					<div class="row"> 
								<div class="col-6">
									<label for="txtrg" class="form-label">Cupom Disponível:</label>
									<select class="form-select  mx-2" multiple  id="selCupom" name="selCupom"  >
									</select>				
								</div>	
								<div class="col-6">
									<label for="txtrg" class="form-label">Valor:</label>
									<input class="form-control ms-1" id="txtrg" name="txtrg" disabled readonly>					
								</div>									
							</div>
							-->
							<div class="row"> <!-- totalização -->
								<div class="col-12">
									<table>
										<tr>
											<td>Locação</td>
											<td>R$ <?= isset($_locacao)?$_locacao->valor_base:'' ?></td>
										</tr>
										<tr>
											<td>Desconto:</td>
											<td>R$ 0</td>											
										</tr>
										<tr>
											<td>Multa:</td>
											<td>R$ <?= isset($_multa)?$_multa:'0' ?></td>
										</tr>
									</table>					
								</div>	
							</div>
							<div class="row">								
									<div class="col-3">
										<label for="txtrg" class="form-label">Total:</label>
									</div>
									<div class="col-9">
										<input class="form-control ms-1" id="txtqde"  disabled value="R$ <?= isset($valor_total)?$valor_total:'' ?>">
									</div>									
							</div>
							<div class="row"> <!-- Mensagem de erro -->
								<div class="col-12">
									<div class="alert alert-danger" role="alert" id="msg">  										
									</div>					
								</div>
																
							</div>
      			</div><br clear="left" />
     				<div class="modal-footer"> <!-- botões -->     					
      				<button type="button" class="btn btn-primary">Registrar</button>      				
      				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      			</div>
    			</div>
  			</div>
		</div>				
		<!-- modal -->
		
		<!--
		<form action="index.php?mod=devolucao" method="post" > 
		<div class="modal fade" id="modalSenha" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">
      			<div class="modal-header">
      				<h5 class="modal-title">Cancelar Multa</h5>
      				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>     	
      			</div>
      			<div class="modal-body">
      				<div class="row">
      					<label for="txtrg" class="form-label">Motivo:</label>
      					<input class="form-control ms-1" id="txtMotivo" name="txtMotivo"   value="">
      				</div>
      				<div class="row">
      					<label for="txtrg" class="form-label">Senha:</label>      					
      					<input class="form-control ms-1" id="txtSenha" name="txtSenha"   value="">		
      				</div>
      			</div>
      			<div class="modal-footer">  
      				<input type="hidden" id="hdnCarimbo" value="0" >  					
      				<button type="button" class="btn btn-primary" id="btnCancelarMulta" onclick="btnCancelarMulta_onclick();" >Registrar Cancelamento</button>      				
     					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Desistir</button>
      			</div>
      		</div>
      	</div>
      </div>		
      </form>
      -->			
	</div>		
	
	<script>
		<?= empty($_POST)||(!isset($_POST['btnNovaMidia'])&&!isset($_POST['selLocacao']))?"inicia_locacao();":"" ?>
									
	</script>
</body>
</html>
