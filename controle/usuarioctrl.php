<?php

	require_once(dirname(__FILE__) ."/../modelo/Usuario.php");		
	require_once(dirname(__FILE__) ."/../modelo/Funcionario.php");
	
	$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';	
		
	$item = new Usuario( $bd );	
		
	$_qde_itens_acervo = 0;
		
	if(isset($_POST['btnSalvar'])) {					
			$id = $_POST['hdnId'];			
			$id_funcionario= $_POST['txtFuncionario'];
			$login= $_POST['txtLogin'];
			$senha = $_POST['txtSenha'];
			$eh_administrador= $_POST['txtPerfil'];
			$status= $_POST['txtStatus'];	

			$item->id = $id ;		
			$item->funcionario->id = $id_funcionario ;			
			$item->login = $login;
			$item->senha = $senha ;
			$item->eh_administrador = $eh_administrador ;
			$item->status = $status ;
						
			if ($item->id == 0){
				if($item->verificarExistenciaLogin($login)){
					$_msg_erro = "Erro! Esse Login já está cadastrado no sistema!";
				} else{
					$_resultado = $item->salvar();
					$_msg_ok = $_resultado > 0?"Dados Registrados com Sucesso!":null;
					$_msg_erro = $_resultado > 0?null:"Erro! Esse Login já está cadastrado no sistema!";
				}
			}else {
				$_resultado = $item->salvar();
				$_msg_ok = $_resultado > 0?"Dados Registrados com Sucesso!":null;
				$_msg_erro = $_resultado > 0?null:"Erro! Esse Login já está cadastrado no sistema!";
			}			
	}	
	if(isset($_POST['selUsuario'])) {
		$cod = $_POST['selUsuario']; 
		$_usuario = $item->buscar($cod);				
	}
	if(isset($_POST['btnExcluir'])){
		$id = $_POST['hdnId'];			
		$id_funcionario= $_POST['txtFuncionario'];
		$login= $_POST['txtLogin'];
		$senha = $_POST['txtSenha'];
		$eh_administrador= $_POST['txtPerfil'];
		$status= $_POST['txtStatus'];	

		$item->id = $id ;		
		$item->funcionario->id = $id_funcionario ;			
		$item->login = $login;
		$item->senha = $senha ;
		$item->eh_administrador = $eh_administrador ;
		$item->status = $status ;
			
		$_resultado = $item->excluir();
		$_msg_ok = $_resultado > 0?"Dados Excluídos com Sucesso!":null;
		$_msg_erro = $_resultado > 0?null:"Erro!";	
			
	}
	if(isset($_POST['btnPesquisar'])) {
		$nome = $_POST['nome']; 
		$_lista = $item->pesquisar($nome);			
	}
	else {
		$_lista = $item->listar();		
	}
	
	$f = new Funcionario($bd);
	$_funcionarios = $f->listar();

	include(dirname(__FILE__) . "/../telas/formUsuario.php");
?>