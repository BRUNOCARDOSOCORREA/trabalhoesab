<?php	
	if(!isset($_SESSION['usuario'])){
		header('location: index.php?mod=sair');
		exit(0);
	}
		
	$u =  $_SESSION['usuario'];

?>
<div class="row">			
			<nav class="navbar bg-dark">
				<ul class="nav">
					<?php   if ($u->eh_administrador):   ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle text-white" id="navbarCadastrosLink" data-bs-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" href="#">Cadastros</a>
						<ul class="dropdown-menu bg-dark" aria-labelledby="navbarCadastrosLink">
							<li><a class="dropdown-item text-white" href="index.php?mod=cliente" >Clientes</a></li>
							<li><a class="dropdown-item text-white" href="index.php?mod=acervo" >Acervo</a></li>
							<li><a class="dropdown-item text-white" href="index.php?mod=funcionario" >Funcionários</a></li>
							<li><a class="dropdown-item text-white" href="index.php?mod=promocao" >Promoções</a></li>
							<li><a class="dropdown-item text-white" href="index.php?mod=meta" >Metas de Venda</a></li>
							<li><a class="dropdown-item text-white" href="index.php?mod=usuario" >Usuários</a></li>
						</ul>
					</li>
					<?php    endif;     ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle text-white" id="navbarLocacaoLink" data-bs-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" href="#">Locação</a>
						<ul class="dropdown-menu bg-dark" aria-labelledby="navbarLocacaoLink">
							<li><a class="dropdown-item text-white" href="index.php?mod=locacao" >Registrar Locação</a></li>
							<li><a class="dropdown-item text-white" href="index.php?mod=devolucao" >Registrar Devolução</a></li>						
						</ul>
					</li>
				</ul>	
				<form class="form-inline">
					<a class="btn text-white" href="index.php?mod=sair">Sair</a>
				</form>	
			</nav>	
		</div>