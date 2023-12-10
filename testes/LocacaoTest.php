<?php
	
	use PHPUnit\Framework\TestCase;
	
	require_once (__DIR__ . "/../modelo/Locacao.php");
	require_once (__DIR__ . "/../modelo/Cliente.php");
	require_once (__DIR__ . "/../modelo/Funcionario.php");
	require_once (__DIR__ . "/../modelo/ItemAcervo.php");
	
	class LocacaoTest extends TestCase {			
			private static $cliente;
			private static $funcionario;		
			private static $item1, $item2, $item3;
			private static $bd ;
			private static $locacao;
			
			public function testAdicionarItens (){				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$loc = new Locacao($bd);
								
				$loc->cliente = self::$cliente;
				$loc->funcionario = self::$funcionario;
				$loc->data_locacao;
				$loc->valor_base=0;
				$loc->valor_total=0;	
				
				$this->assertEquals(0, count($loc->listaItemLocacao) );
				
				$loc->adicionarItem( self::$item1 );
				$this->assertEquals(1, count($loc->listaItemLocacao) );
				$loc->adicionarItem( self::$item1 );
				$this->assertEquals(1, count($loc->listaItemLocacao) );
				$loc->adicionarItem( self::$item2 );
				$this->assertEquals(2, count($loc->listaItemLocacao) );
				$loc->adicionarItem( self::$item2 );
				$this->assertEquals(2, count($loc->listaItemLocacao) );
				$loc->adicionarItem( self::$item3 );
				$this->assertEquals(3, count($loc->listaItemLocacao) );
				$loc->adicionarItem( self::$item3 );
				$this->assertEquals(3, count($loc->listaItemLocacao) );
			}	
					
			/**
			@depends testAdicionarItens
			*/			
			public function testRemoverItens (){				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$loc = new Locacao($bd);
								
				$loc->cliente = self::$cliente;
				$loc->funcionario = self::$funcionario;
				$loc->data_locacao;
				$loc->valor_base=0;
				$loc->valor_total=0;	
			
				$loc->adicionarItem( self::$item1 );
				$loc->adicionarItem( self::$item2 );				
				$loc->adicionarItem( self::$item3 );	
				
				$loc->retirarItem( "B9999");// codigo inexistente
				$this->assertEquals(3, count($loc->listaItemLocacao) );
				
				$loc->retirarItem( self::$item1->codigo);
				$this->assertEquals(2, count($loc->listaItemLocacao) );
				$loc->retirarItem( self::$item1->codigo);
				$this->assertEquals(2, count($loc->listaItemLocacao) );
				$loc->retirarItem( self::$item2->codigo);
				$this->assertEquals(1, count($loc->listaItemLocacao) );
				$loc->retirarItem( self::$item2->codigo);
				$this->assertEquals(1, count($loc->listaItemLocacao) );
				$loc->retirarItem( self::$item3->codigo);
				$this->assertEquals(0, count($loc->listaItemLocacao) );
			}
			
			
			public function testSalvar (){
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$loc = new Locacao($bd);
								
				$loc->cliente = self::$cliente;
				$loc->funcionario = self::$funcionario;
				//$loc->data_locacao = '25/11/2023';
				$loc->data_entrega = '29/11/2023';
				$loc->valor_base=12;
				$loc->valor_total=12;	
				$loc->adicionarItem( self::$item1 );
				$loc->adicionarItem( self::$item2 );				
				$loc->adicionarItem( self::$item3 );
								
				$qde = $loc->salvar();
				$this->assertEquals(1,$qde);
				self::$locacao = $loc;
			}
			
			/**
			@depends testSalvar
			*/	
			public function testBuscar(){				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$l = new Locacao($bd);				
				$loc = $l->buscar( self::$locacao->id);
				
				$this->assertNotNull($loc);
				$this->assertEquals($loc->cliente->id, self::$cliente->id);
				$this->assertEquals($loc->funcionario->id, self::$funcionario->id);
				$this->assertEquals(12, $loc->valor_base);
				$this->assertEquals(date('d/m/Y'), $loc->data_locacao);
				$this->assertEquals('29/11/2023', $loc->data_entrega);
				$this->assertEquals(12, $loc->valor_total);
				$this->assertEquals(3, count($loc->listaItemLocacao));
			}
			
			
			/**
			@depends testSalvar
			*/	
			public function testVerificarLocacaoItem(){
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$l = new Locacao($bd);				
				
				$item1 = self::$item1;
				$item2 = self::$item2;
				$item3 = self::$item3;
				
				$flag = $l->verificarExistenciaLocacaoItem( $item1->codigo );
				$this->assertTrue( $flag);
				
				$flag = $l->verificarExistenciaLocacaoItem( $item2->codigo);
				$this->assertTrue( $flag);
				
				$flag = $l->verificarExistenciaLocacaoItem( $item3->codigo );
				$this->assertTrue( $flag);
				
				$flag = $l->verificarExistenciaLocacaoItem( 'LPS' );
				$this->assertFalse( $flag);
			}
			
			/**
			@depends testBuscar
			*/
			public function testAlterar(){
				$l = self::$locacao;				
				$l->valor_desconto = 4.4;
				$l->valor_multa = 3.1;
				$l->valor_total = 15;
				$qde = $l->salvar();
				
				$this->assertEquals(1, $qde);
				
				$loc = $l->buscar( self::$locacao->id);
				
				$this->assertNotNull($loc);
				$this->assertEquals($loc->cliente->id, self::$cliente->id);
				$this->assertEquals($loc->funcionario->id, self::$funcionario->id);
				$this->assertEquals(12, $loc->valor_base);
				$this->assertEquals(15, $loc->valor_total);
				$this->assertEquals(3.1, $loc->valor_multa);
				$this->assertEquals(4.4, $loc->valor_desconto);
				$this->assertEquals(date('d/m/Y'), $loc->data_locacao);
				$this->assertEquals("29/11/2023", $loc->data_entrega);
				$this->assertEquals(3, count($loc->listaItemLocacao));
			}

			
			
			
			
			
			
			
			
			
			
			/*  METODOS QUE SERÃO EXECUTADOS ANTES E DEPOIS DE CADA TESTE */ 
			public static function setUpBeforeClass():void{
				self::$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';	
				/*
				 CLIENTE
				*/
				$cliente = new Cliente(self::$bd);
								
				$cliente-> nome = 'Leandro Peçanha Scardua';				
				$cliente->logradouro = 'Rua Pedro Palácios, 40';
				$cliente-> bairro = 'Centro' ;				
				$cliente->cpf = '097.189.777-83';
				$cliente-> rg = '1815806';			
				
				$qde = $cliente->salvar();				
				
				self::$cliente = $cliente ;	
				/*
				FUNCIONARIO
				*/
				$funcionario = new Funcionario(self::$bd);
				$funcionario-> nome = 'Leandro Peçanha Scardua';				
				$funcionario->logradouro = 'Rua Pedro Palácios, 40';
				$funcionario-> bairro = 'Centro' ;				
				$funcionario->cpf = '097.189.777-83';
				$funcionario-> rg = '1815806';			
				
				$qde = $funcionario->salvar();				
				
				self::$funcionario = $funcionario;	
				
				/*
					ITEMACERVO
				*/		
				$_nome = "Who's That Knocking at My Door";
				$_nome_original = 'Quem Bate à Minha Porta?' ;
				$_descricao = "Who's That Knocking at My Door, originalmente intitulado I Called First, é um filme do gênero drama de 1967, escrito e dirigido por Martin Scorsese, em sua estreia como diretor de longa-metragens, e estrelando Harvey Keitel, em seu primeiro papel no cinema. Venceu o Chicago International Film Festival de 1968" ;
				$_ano_lancamento = 1968 ;
				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';
				$item1 = new ItemAcervo(self::$bd);
				
				$item1->adicionarTitulo($_nome,$_nome_original,$_descricao,$_ano_lancamento);				
				$item1->status = StatusMidia::$CADASTRADO;
				$item1->midia = Midia::$DVD;				
				$item1->codigo = "A0001";				
				$item1->periodo_max = 3;
				
				$qde = $item1->salvar();
				
				$_nome = 'Boxcar Bertha';
				$_nome_original = 'Sexy e Marginal - Uma Mulher da Rua' ;
				$_descricao = "Boxcar Bertha (br Sexy e Marginal; pt Uma Mulher da Rua) é um filme estadunidense de 1972, dos gêneros drama, policial, romance[1] e suspense[2], dirigido por Martin Scorsese e baseado no livro Sister of the Road, de Ben L. Reitman. Este segundo filme de Scorsese foi produzido por Roger Corman e distribuído pela American International Pictures. " ;
				$_ano_lancamento = 1972 ;
				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';
				$item2 = new ItemAcervo(self::$bd);
				
				$item2->adicionarTitulo($_nome,$_nome_original,$_descricao,$_ano_lancamento);				
				$item2->status = StatusMidia::$CADASTRADO;
				$item2->midia = Midia::$DVD;				
				$item2->codigo = "A0002";				
				$item2->periodo_max = 3;			
				$qde = $item2->salvar();
				
				$_nome = 'Mean Streets';
				$_nome_original = 'Caminhos Perigosos - Os Cavaleiros do Asfalto' ;
				$_descricao = 'O cotidiano de dois indivíduos no submundo dos guetos italianos de Nova York. Charlie (Harvey Keitel) trabalha com o tio mafioso, realizando cobranças, mas tem esperanças de recomeçar a vida ao lado do amor de sua vida. Já o seu melhor amigo, Johnny Boy (Robert De Niro), é um rapaz revoltado que vive se metendo em encrencas por causa de dívidas de jogos' ;
				$_ano_lancamento = 1966 ;
				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';
				$item3 = new ItemAcervo(self::$bd);
				
				$item3->adicionarTitulo($_nome,$_nome_original,$_descricao,$_ano_lancamento);				
				$item3->status = StatusMidia::$CADASTRADO;
				$item3->midia = Midia::$DVD;				
				$item3->codigo = "A0003";				
				$item3->periodo_max = 3;
				
				$qde = $item3->salvar();
				
				self::$item1 = $item1;
				self::$item2 = $item2;
				self::$item3 = $item3;				
			}
			
			public static function tearDownAfterClass():void {				
				self::$cliente->excluir();
				self::$funcionario->excluir();
				self::$item1->excluir();
				self::$item2->excluir();
				self::$item3->excluir();
				self::$item1->excluir();
				self::$item2->excluir();
				self::$item3->excluir();				
			}
	}
?>