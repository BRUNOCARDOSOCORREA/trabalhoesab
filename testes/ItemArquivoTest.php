<?php
	
	use PHPUnit\Framework\TestCase;
	
	require_once (__DIR__ . "/../modelo/ItemAcervo.php");
	
	class ItemArquivoTest extends TestCase {
			private static $id_titulo;
			private static $cod_midia;
			
			public function testCriarTitulo(){
				$_nome = 'Três homens em conflito';
				$_nome_original = 'Il buono, il brutto, il cattivo' ;
				$_descricao = 'Para três homens a Guerra Civil não foi um inferno. Era prática.' ;
				$_ano_lancamento = 1966 ;
				
				$tit = new Titulo($_nome,$_nome_original,$_descricao,$_ano_lancamento);
				$this->assertEquals($tit->nome, $_nome);
				$this->assertEquals($tit->nome_original,$_nome_original);
				$this->assertEquals($tit->descricao,$_descricao );
				$this->assertEquals($tit->ano_lancamento, $_ano_lancamento);				
			}
			
			public function testAdicionarTitulo(){
				$_nome = 'Três homens em conflito';
				$_nome_original = 'Il buono, il brutto, il cattivo' ;
				$_descricao = 'Para três homens a Guerra Civil não foi um inferno. Era prática.' ;
				$_ano_lancamento = 1966 ;
				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora.sqlite';
				$item = new ItemAcervo($bd);
				
				$item->adicionarTitulo($_nome,$_nome_original,$_descricao,$_ano_lancamento);
				
				$this->assertEquals($item->titulo->nome, $_nome);
				$this->assertEquals($item->titulo->nome_original,$_nome_original);
				$this->assertEquals($item->titulo->descricao,$_descricao );
				$this->assertEquals($item->titulo->ano_lancamento, $_ano_lancamento);
			}
			
			public function testListarMidia(){
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				$midias = $item->listarMidia();
				
				$this->assertEquals(2, count($midias));
				$vhs = $midias[0];
				$dvd = $midias[1];
				
				$this->assertEquals('VHS', $vhs['nome']);
				$this->assertEquals('Fita de Vídeo Cassete', $vhs['descricao']);
				
				$this->assertEquals('DVD', $dvd['nome']);
				$this->assertEquals('Disco ótico', $dvd['descricao']);				
			}
			
			public function testListarStatusMidia(){
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				$status = $item->listarStatusMidia();
				
				$this->assertEquals(4, count($status));
				$cadastrado = $status[0];
				$disponivel = $status[1];
				$em_manutencao = $status[2];
				$baixado = $status[3];
				
				$this->assertEquals('Cadastrado', $cadastrado['nome']);
				$this->assertEquals('O item foi registrado mas ainda não pode ser empresatado', $cadastrado['descricao']);
				
			 	$this->assertEquals('Disponível', $disponivel['nome']);
				$this->assertEquals('O item pode ser emprestado', $disponivel['descricao']);
				
				$this->assertEquals('Em Manutenção', $em_manutencao['nome']);
				$this->assertEquals('O item não pode ser emprestado porque está em manutenção', $em_manutencao['descricao']);
				
				$this->assertEquals('Baixado', $baixado['nome']);
				$this->assertEquals('O item nao está mais disponível pra empréstimo porque está inutilizável', $baixado['descricao']);
			}
			
			public function testAdicionarComNovoTitulo (){
				$_nome = 'Três homens em conflito';
				$_nome_original = 'Il buono, il brutto, il cattivo' ;
				$_descricao = 'Para três homens a Guerra Civil não foi um inferno. Era prática.' ;
				$_ano_lancamento = 1966 ;
				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				
				$item->adicionarTitulo($_nome,$_nome_original,$_descricao,$_ano_lancamento);				
				$item->status = StatusMidia::$CADASTRADO;
				$item->midia = Midia::$DVD;				
				$item->codigo = "FARDVD001";				
				$item->periodo_max = 3;
				
				$qde = $item->salvar();
				
				$this->assertEquals(1, $qde);	
				$this->assertGreaterThan(0, $item->titulo->id);
				self::$id_titulo = $item->titulo->id;
				self::$cod_midia = $item->codigo;
			}
			
			/**
			@depends testAdicionarComNovoTitulo
			*/
			public function testListarMidiaPorTitulo (){
				$this->AssertNotEquals(0, self::$id_titulo)	;
							
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				
				$midias = $item->listarMidiaPorTitulo(self::$id_titulo);				
				$this->assertEquals(1, count($midias));
				
				$midias = $item->listarMidiaPorTitulo(9999);// um numero qualquer que não exista cadastrado
				$this->assertEquals(0, count($midias));
			}
			
			/**
			@depends testAdicionarComNovoTitulo
			*/
			public function testPesquisarTitulo (){
				$this->AssertNotEquals(0, self::$id_titulo)	;
							
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				
				$t = $item->pesquisar('homens');				
				$this->assertEquals(1, count($t));
				
				$t = $item->pesquisar('sooretama');				
				$this->assertEquals(0, count($t));				
			}
			
			/**
			@depends testAdicionarComNovoTitulo
			*/
			public function testVerificarExistenciaCodigo (){
				$this->AssertNotEquals(0, self::$id_titulo)	;
							
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				
				$t = $item->verificarExistenciaCodigo('FARDVD001');				
				$this->assertTrue($t);
				
				$t = $item->verificarExistenciaCodigo('LPS001');				
				$this->assertFalse($t);				
			}
			
			/**
			@depends testAdicionarComNovoTitulo
			*/
			public function testBuscarMidia (){
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				
				$midia = $item->buscar(self::$cod_midia);
				
				$this->assertNotNull($midia);
				$this->assertNotNull($midia->titulo);
				
				$this->assertEquals(self::$cod_midia, $midia->codigo);
				$this->assertEquals('Três homens em conflito', $midia->titulo->nome);
				$this->assertEquals('Il buono, il brutto, il cattivo', $midia->titulo->nome_original);
				$this->assertEquals('Para três homens a Guerra Civil não foi um inferno. Era prática.', $midia->titulo->descricao);
				$this->assertEquals(1966 , $midia->titulo->ano_lancamento);
				$this->assertEquals(StatusMidia::$CADASTRADO, $midia->status );
				$this->assertEquals(Midia::$DVD, $midia->midia );								
				$this->assertEquals(3, $midia->periodo_max );
				
				$midia = $item->buscar('---');//buscar por um item inexistente
				$this->assertNull($midia);
			}
			
			/**
			@depends testAdicionarComNovoTitulo
			*/
			public function testBuscarTitulo (){
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				$titulo = $item->buscarTitulo(self::$id_titulo);
				$this->assertNotNull($titulo);
				
				$this->assertEquals(self::$id_titulo, $titulo->id);
				$this->assertEquals('Três homens em conflito', $titulo->nome);
				$this->assertEquals('Il buono, il brutto, il cattivo', $titulo->nome_original);
				$this->assertEquals('Para três homens a Guerra Civil não foi um inferno. Era prática.', $titulo->descricao);
				$this->assertEquals(1966 , $titulo->ano_lancamento);																				
			}
			
			/**
			@depends testAdicionarComNovoTitulo
			*/
			public function testAtualizarMidia (){				
				
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				
				$midia = $item->buscar(self::$cod_midia);
											
				$midia->status = StatusMidia::$BAIXADO;		
				$midia->periodo_max = 10;
				
				$qde = $midia->salvar();
				
				$midia = $item->buscar(self::$cod_midia);
				
				$this->assertNotNull($midia);
				$this->assertNotNull($midia->titulo);
				
				$this->assertEquals(self::$cod_midia, $midia->codigo);
				$this->assertEquals('Três homens em conflito', $midia->titulo->nome);
				$this->assertEquals('Il buono, il brutto, il cattivo', $midia->titulo->nome_original);
				$this->assertEquals('Para três homens a Guerra Civil não foi um inferno. Era prática.', $midia->titulo->descricao);
				$this->assertEquals(1966 , $midia->titulo->ano_lancamento);
				$this->assertEquals(StatusMidia::$BAIXADO, $midia->status );
				$this->assertEquals(Midia::$DVD, $midia->midia );								
				$this->assertEquals(10, $midia->periodo_max );
			}
			
			/**
			@depends testListarMidiaPorTitulo
			@depends testPesquisarTitulo
			@depends testBuscarMidia
			@depends testBuscarTitulo
			@depends testAtualizarMidia
			*/
			public function testExcluir (){	
			
				$bd = dirname(__FILE__) . '/../banco_dados/locadora_teste.sqlite';
				$item = new ItemAcervo($bd);
				
				$midia = $item->buscar(self::$cod_midia);
				$this->assertNotNull($midia);
				$midia->excluir();
				$midia = $item->buscar(self::$cod_midia);
				$this->assertNull($midia);								
				
			}			
	}
?>