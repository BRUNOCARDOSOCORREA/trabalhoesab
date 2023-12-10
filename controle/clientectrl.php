<?php

	require_once(dirname(__FILE__) ."/../modelo/Cliente.php");		
	
	$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';	
		
	$item = new Cliente( $bd );	
		
	$_qde_itens_acervo = 0;
		
	if(isset($_POST['btnSalvar'])) {		
			
			$id = $_POST['hdnId'];			
			$nome= $_POST['txtNome'];
			$logradouro= $_POST['txtLogradouro'];
			$bairro = $_POST['txtBairro'];
			$cpf= $_POST['txtCPF'];
			$rg= $_POST['txtRG'];	

			$item->id = $id ;		
			$item->nome = $nome ;			
			$item->logradouro = $logradouro ;
			$item->bairro = $bairro ;
			$item->cpf = $cpf ;
			$item->rg = $rg ;
						
			if ($item->id == 0){
				if($item->verificarExistenciaNome($nome)){
					$_msg_erro = "Erro! Esse nome já está cadastrado no sistema!";
				} else{
					$_resultado = $item->salvar();
					$_msg_ok = $_resultado > 0?"Dados Registrados com Sucesso!":null;
					$_msg_erro = $_resultado > 0?null:"Erro! Esse código já está cadastrado no sistema!";
				}
			}else {
				$_resultado = $item->salvar();
				$_msg_ok = $_resultado > 0?"Dados Registrados com Sucesso!":null;
				$_msg_erro = $_resultado > 0?null:"Erro! Esse código já está cadastrado no sistema!";
			}
	}	
	if(isset($_POST['selCliente'])) {
		$cod = $_POST['selCliente']; 
		$_cliente = $item->buscar($cod);
	}
	if(isset($_POST['btnExcluir'])){			
		$id = $_POST['hdnId'];			
		$nome= $_POST['txtNome'];
		$logradouro= $_POST['txtLogradouro'];
		$bairro = $_POST['txtBairro'];
		$cpf= $_POST['txtCPF'];
		$rg= $_POST['txtRG'];	

		$item->id = $id ;		
		$item->nome = $nome ;			
		$item->logradouro = $logradouro ;
		$item->bairro = $bairro ;
		$item->cpf = $cpf ;
		$item->rg = $rg ;
			
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

	include(dirname(__FILE__) . "/../telas/formCliente.php");
?>