<?php

	require_once(dirname(__FILE__) ."/../modelo/MetaVenda.php");		
	require_once(dirname(__FILE__) ."/../modelo/Funcionario.php");
	
	$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';	
		
	$item = new MetaVenda( $bd );	
	$f = new Funcionario($bd);
		
	$_qde_itens_acervo = 0;
		
	if(isset($_POST['btnSalvar'])) {						
			$id = $_POST['hdnId'];			
			$id_funcionario= $_POST['txtFuncionario'];
			$qde= $_POST['txtQde'];			
			$estah_vigente= $_POST['txtStatus'];
				

			$item->id = $id ;		
			$item->funcionario->id = $id_funcionario ;			
			$item->qde = $qde;			
			$item->estah_vigente = $estah_vigente ;
						
			if ($item->id == 0){
				if($item->verificarExistenciaMetaVigente($id_funcionario)){
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
	if(isset($_POST['selFuncionario'])) {
		$cod = $_POST['selFuncionario']; 
		$_meta_venda = $item->buscarPorFuncionario($cod);		
	}
	if(isset($_POST['btnExcluir'])){		
		$id = $_POST['hdnId'];			
		$id_funcionario= $_POST['txtFuncionario'];
		$qde= $_POST['txtQde'];			
		$estah_vigente= $_POST['txtStatus'];
				

		$item->id = $id ;		
		$item->funcionario->id = $id_funcionario ;			
		$item->qde = $qde;			
		$item->estah_vigente = $estah_vigente ;
			
		$_resultado = $item->excluir();
		$_msg_ok = $_resultado > 0?"Dados Excluídos com Sucesso!":null;
		$_msg_erro = $_resultado > 0?null:"Erro!";		
	}
	if(isset($_POST['btnPesquisar'])) {		
		$nome = $_POST['nome']; 
		$_lista = $f->pesquisar($nome);
	}
	else {
		$_lista = $f->listar();
	}
	
	$_funcionarios = $_lista;

	include(dirname(__FILE__) . "/../telas/formMetaVenda.php");
?>