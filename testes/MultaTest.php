<?php
	
	use PHPUnit\Framework\TestCase;
	
	require_once (__DIR__ . "/../modelo/Multa.php");
	
	class MultaTest extends TestCase {
			private static $id_multa;	
			
			public function testAdicionar (){				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new Multa($bd);
								
				$item-> data = '27/11/2023';				
				$item->valor = 123.0;
						
				
				$qde = $item->salvar();
				
				$this->assertEquals(1, $qde);	
				$this->assertGreaterThan(0, $item->id);
				self::$id_multa = $item->id;
			}			
			
			/*
			public function testPesquisar (){				
				$this->AssertNotEquals(0, self::$id_cliente)	;
							
				$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';
				$item = new Cliente($bd);
				
				$t = $item->pesquisar('Scardua');				
				$this->assertEquals(1, count($t));
				
				$t = $item->pesquisar('sooretama');				
				$this->assertEquals(0, count($t));					
			}
			*/
		
			/**
			@depends testAdicionar
			*/
			public function testBuscar (){				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new Multa($bd);
				
				$cli = $item->buscar(self::$id_multa);
				
				$this->assertNotNull($cli);				
				
				$this->assertEquals(self::$id_multa, $cli->id);
				$this->assertEquals('27/11/2023', $cli->data);
				$this->assertEquals(1, $cli->status);
				$this->assertEquals(123.0, $cli->valor);
				$this->assertEquals(null, $cli->data_cancelamento);
				$this->assertEquals(null , $cli->motivo_cancelamento);								
				
				$cli = $item->buscar('---');//buscar por um item inexistente
				$this->assertNull($cli);			
			}			
						
			/**
			@depends testAdicionar
			*/
			public function testAtualizar (){
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$m = new Multa($bd);
				
				$multa = $m->buscar(self::$id_multa);													
				$multa->status = 0;
				$multa->data_cancelamento = '28/11/2023';
				$multa->motivo_cancelamento = 'cliente com bom histórico';
				
				$qde = $multa->salvar();
				
				$multa = $m->buscar(self::$id_multa);
				
				$this->assertNotNull($multa);
				
				$this->assertEquals(self::$id_multa, $multa->id);
				$this->assertEquals('27/11/2023', $multa->data);
				$this->assertEquals(0, $multa->status);
				$this->assertEquals(123.0, $multa->valor);
				$this->assertEquals('28/11/2023', $multa->data_cancelamento);
				$this->assertEquals('cliente com bom histórico' , $multa->motivo_cancelamento);			
			}
			
			/*
			public function testExcluir (){
			
				$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';
				$cli = new Cliente($bd);
				
				$cli = $cli->buscar(self::$id_cliente);
				$this->assertNotNull($cli);
				$cli->excluir();
				$cli = $cli->buscar(self::$id_cliente);
				$this->assertNull($cli);
			}			
			*/
	}
?>