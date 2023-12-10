<?php
	
	use PHPUnit\Framework\TestCase;
	
	require_once (__DIR__ . "/../modelo/Configuracao.php");
	
	class ConfiguracaoTest extends TestCase {
			
			
			public function testBuscar (){	
						
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new Configuracao($bd);
				
				$cli = $item->buscar();
				
				$this->assertNotNull($cli);				
				
				$this->assertEquals(0, $cli->habilitar_promocao_locacao_individual);
				$this->assertEquals(0, $cli->habilitar_promocao_locacao_periodo);
				$this->assertEquals(5, $cli->qde_locacao_individual);
				$this->assertEquals(20, $cli->qde_locacao_periodo);
				$this->assertEquals(2.5, $cli->valor_locacao);
				$this->assertEquals(2.0, $cli->valor_multa);
			}			
					
			/**
			@depends testBuscar
			*/
			public function testAtualizar (){					
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$config = new Configuracao($bd);
				
				$config = $config->buscar();
											
				$config-> habilitar_promocao_locacao_individual = 1;				
				$config-> habilitar_promocao_locacao_periodo = 1;
				$config-> qde_locacao_individual = 4 ;				
				$config->qde_locacao_periodo = 25;	
				$config->valor_locacao = 3.5 ;
				$config->valor_multa = 1.0 ;
				
				$qde = $config->salvar();
				
				$config = $config->buscar();
				
				$this->assertNotNull($config);
				
				$this->assertEquals(1, $config->habilitar_promocao_locacao_individual);
				$this->assertEquals(1, $config->habilitar_promocao_locacao_periodo);
				$this->assertEquals(4, $config->qde_locacao_individual);
				$this->assertEquals(25, $config->qde_locacao_periodo);
				$this->assertEquals(3.5, $config->valor_locacao);
				$this->assertEquals(1.0, $config->valor_multa);
			}	
	}
?>