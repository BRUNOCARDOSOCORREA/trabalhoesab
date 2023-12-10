<?php
	require_once(dirname(__FILE__) ."/modelo/Usuario.php");
		
	session_start();
	
	$mod = [
		'login'=>'/controle/loginctrl.php',
		'acervo' => '/controle/acervoctrl.php',
		'funcionario' => '/controle/funcionarioctrl.php',
		'cliente' => '/controle/clientectrl.php',
		'meta' => '/controle/metavendactrl.php',
		'promocao' => '/controle/promocaoctrl.php',
		'locacao' => '/controle/registrarlocacaoctrl.php',
		'devolucao' => '/controle/registrardevolucaoctrl.php',
		'usuario' => '/controle/usuarioctrl.php',
		'sair'   => '/controle/sair.php',
		'boas_vindas' => '/controle/boasvindas.php',
		'buscar_titulo' => '/controle/ajaxBuscarTitulo.php',
		'cancelar_multa' => '/controle/ajaxValidarCancelamento.php'
	];
	
	$m = isset($_GET['mod'])?$_GET['mod']:'login';	
	
	include(dirname(__FILE__) . $mod[ $m ]);
	
?>
