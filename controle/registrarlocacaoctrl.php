<?php

	require_once(dirname(__FILE__) ."/../modelo/Cliente.php");
	require_once(dirname(__FILE__) ."/../modelo/Usuario.php");
	require_once(dirname(__FILE__) ."/../modelo/Funcionario.php");	
	require_once(dirname(__FILE__) ."/../modelo/Locacao.php");		
	require_once(dirname(__FILE__) ."/../modelo/Configuracao.php");	
	require_once (__DIR__ . "/../modelo/ItemAcervo.php");
			
	$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';	
	
	$item = new Cliente( $bd );

	// SELECIONA CLIENTE
	if(isset($_POST['selCliente'])) {
		$cod = $_POST['selCliente']; 
		$_cliente = $item->buscar($cod);
	}
	//PESQUISAR CLIENTE
	if(isset($_POST['btnPesquisar'])) {
		$nome = $_POST['nome']; 
		$_lista = $item->pesquisar($nome);
	}
	else {
		$_lista = $item->listar();		
	}
	// REGISTRAR LOCAÇÃO
	if(isset($_POST['btnSalvar'])) {
		$c = new Configuracao( $bd );
		$config = $c->buscar();
		$usuario_logado = $_SESSION['usuario'] ;
				
		$dt = $_POST['txtDtEntrega'];
		$cliente= $_POST['txtIdCliente'];
		
		$itens = $_POST['selItens'];
				
		$l = new Locacao($bd);	
		$l->cliente->id = $cliente ; 
		$l->funcionario = isset($usuario_logado->funcionario)?$usuario_logado->funcionario:0 ;
		$l->data_entrega = $dt;
		
		$filme = new ItemAcervo($bd);
				
		foreach($itens as $cod){
			$filme =$filme->buscar($cod);
			
			$l->adicionarItem($filme);		 
		}
		
		$l->valor_base = $config->valor_locacao * count($itens);
		
		$_resultado = $l->salvar();
		$_msg_ok = $_resultado > 0?"Dados Registrados com Sucesso!":null;
		$_msg_erro = $_resultado > 0?null:"Erro! Esse código já está cadastrado no sistema!";
	}
		
	$c = new Configuracao( $bd );
	$_config = $c->buscar();
	
	include(dirname(__FILE__) . "/../telas/formLocacao.php");
?>