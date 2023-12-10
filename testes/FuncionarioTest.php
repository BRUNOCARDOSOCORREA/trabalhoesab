<?php
	
	use PHPUnit\Framework\TestCase;
	
	require_once (__DIR__ . "/../modelo/Funcionario.php");
	
	class FuncionarioTest extends TestCase {
			private static $id_functionario;	
			
			public function testAdicionar (){				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new Funcionario($bd);
								
				$item-> nome = 'Leandro Peçanha Scardua';				
				$item->logradouro = 'Rua Pedro Palácios, 40';
				$item-> bairro = 'Centro' ;				
				$item->cpf = '097.189.777-83';
				$item-> rg = '1815806';			
				
				$qde = $item->salvar();
				
				$this->assertEquals(1, $qde);	
				$this->assertGreaterThan(0, $item->id);
				self::$id_functionario = $item->id;
			}			
			
			/**
			@depends testAdicionar
			*/
			public function testPesquisar (){				
				$this->AssertNotEquals(0, self::$id_functionario)	;
							
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new Funcionario($bd);
				
				$t = $item->pesquisar('Scardua');				
				$this->assertEquals(1, count($t));
				
				$t = $item->pesquisar('sooretama');				
				$this->assertEquals(0, count($t));					
			}
			
			/**
			@depends testAdicionar
			*/
			public function testVerificarExistenciaNome (){				
				$this->AssertNotEquals(0, self::$id_functionario)	;
							
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new Funcionario($bd);
				
				$t = $item->verificarExistenciaNome('Leandro Peçanha Scardua');				
				$this->assertTrue($t);
				
				$t = $item->verificarExistenciaNome('Leandro Peçanha');				
				$this->assertFalse($t);							
			}
			
			/**
			@depends testAdicionar
			*/
			public function testBuscar (){				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new Funcionario($bd);
				
				$cli = $item->buscar(self::$id_functionario);
				
				$this->assertNotNull($cli);				
				
				$this->assertEquals(self::$id_functionario, $cli->id);
				$this->assertEquals('Leandro Peçanha Scardua', $cli->nome);
				$this->assertEquals('Rua Pedro Palácios, 40', $cli->logradouro);
				$this->assertEquals('Centro', $cli->bairro);
				$this->assertEquals('097.189.777-83' , $cli->cpf);
				$this->assertEquals('1815806', $cli->rg );				
				
				$cli = $item->buscar('---');//buscar por um item inexistente
				$this->assertNull($cli);				
			}			
						
			/**
			@depends testAdicionar
			*/
			public function testAtualizar (){
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$cli = new Funcionario($bd);
				
				$cli = $cli->buscar(self::$id_functionario);
											
				$cli-> nome = 'Max Scardua';				
				$cli->logradouro = 'Avenida Vitoria Regia, 15';
				$cli-> bairro = 'Jardim Colorado' ;				
				$cli->cpf = '111-111-111-11';
				$cli-> rg = '12345678';	
				
				$qde = $cli->salvar();
				
				$midia = $cli->buscar(self::$id_functionario);
				
				$this->assertNotNull($cli);				
				
				$this->assertEquals(self::$id_functionario, $cli->id);
				$this->assertEquals('Max Scardua', $cli->nome);
				$this->assertEquals('Avenida Vitoria Regia, 15', $cli->logradouro);
				$this->assertEquals('Jardim Colorado' , $cli->bairro);
				$this->assertEquals('111-111-111-11' , $cli->cpf);
				$this->assertEquals('12345678', $cli->rg );			
			}
			
			/**			
			@depends testPesquisar
			@depends testBuscar			
			@depends testAtualizar
			*/
			public function testExcluir (){
			
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$cli = new Funcionario($bd);
				
				$cli = $cli->buscar(self::$id_functionario);
				$this->assertNotNull($cli);
				$cli->excluir();
				$cli = $cli->buscar(self::$id_functionario);
				$this->assertNull($cli);							
			
			}			
	}
?>