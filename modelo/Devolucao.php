<?php
	//require_once(dirname(__FILE__) ."/../modelo/Cliente.php");
	require_once(dirname(__FILE__) ."/../modelo/Funcionario.php");
	
	class Devolucao {
		
		private $con;
		public $path;
		public $id = 0;
		//public $cliente;
		//public $funcionario;
		public $pagamento;		
		public $cupomDesconto;
		public $locacao;
		public $multa;				
		//public $listaItemLocacao =[];
		//public $data_locacao;
		public $data_devolucao;
		public $valor_base=0;
		public $valor_multa=0;
		public $valor_desconto=0;
		public $valor_total=0;			
		
		
		public function __construct($path){
			$this->path = $path;				
			$this->con = new PDO("sqlite:$path");		
			$this->locacao = new Locacao($path);
			$this->funcionario = new Funcionario($path);
		}
		
		
				
		public function salvar(){			
			
			if($this->id==0){		
				
				$sql = "INSERT INTO 'main'.'tb_devolucao' ('id_funcionario', 'dt_devolucao') VALUES (:id_funcionario, date()  );";
			
				$stmt = $this->con->prepare($sql);			
							
				$stmt->bindParam(':id_funcionario',$this->funcionario->id);							
							
				$stmt->execute();
				$this->id = $this->con->lastInsertId();
				$qde = $stmt->rowCount();
				
			} 
			else {
				
				$sql = "UPDATE 'main'.'tb_locacao' SET 'valor_total'=:valor_total, valor_multa=:valor_multa, valor_desconto=:valor_desconto  WHERE  id=:id_locacao;";
			
				$stmt = $this->con->prepare($sql);
				
				$stmt->bindParam(':valor_total',$this->valor_total);
				$stmt->bindParam(':valor_multa',$this->valor_multa);
				$stmt->bindParam(':valor_desconto',$this->valor_desconto);			
				$stmt->bindParam(':id_locacao',$this->id);
							
				$stmt->execute();				
				$qde = $stmt->rowCount();								
			}
			
			return $qde;
		}	
		
		/*
		private function buscarItens($locacao){
			$sql = "SELECT *
						FROM tb_item_locacao AS il
						INNER JOIN tb_locacao as l ON (il.id_locacao = l.id)
						INNER JOIN tb_item_acervo AS ia ON (il.id_item_acervo = ia.id)
						INNER JOIN tb_titulo as t ON (t.id = ia.id_titulo)
						WHERE il.id_locacao= :cod;";	
 					
			$comm = $this->con->prepare( $sql );
			$comm->bindParam(':cod', $locacao->id);
			$comm->execute();			
						
			$rows = $comm->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($rows as $val){				
				$item = new ItemAcervo($this->path);
				$item->adicionarTitulo($val['nome'],$val['nome_original'],$val['descricao'],$val['ano_lancamento']);				
				$item->status = $val['id_situacao_item_acervo'];
				$item->midia = $val['id_midia'];
				$item->codigo = $val['codigo'];
				$item->periodo_max = $val['periodo_max'];
				
				$locacao->adicionarItem($item);
			}			
		}			
					
		public function buscar($id){
			$sql = "SELECT *, l.id as id_locacao,c.id as id_cliente, f.id as id_funcionario 
						FROM tb_locacao AS l
						INNER JOIN tb_cliente AS c ON (l.id_cliente = c.id)
						INNER JOIN tb_funcionario AS f ON (l.id_funcionario = f.id)
						WHERE 
 						l.id = :cod";
 			
 			$loc=null;		
			$comm = $this->con->prepare( $sql );
			$comm->bindParam(':cod', $id);
			$comm->execute();	
			$row = $comm->fetch(PDO::FETCH_ASSOC);
			
			$midia = null;
			
			if ($row != null){ 
				$cliente = new Cliente($this->path);								
				$cliente-> nome = $row['nome'];				
				$cliente->logradouro = $row['logradouro'];
				$cliente-> bairro = $row['bairro'] ;				
				$cliente->cpf = $row['cpf'];
				$cliente-> rg = $row['rg'];				
				$cliente-> id = $row['id_cliente'];
				
				$funcionario = new Funcionario($this->path);
				$funcionario-> nome = $row['nome'];				
				$funcionario->logradouro = $row['logradouro'];
				$funcionario-> bairro = $row['bairro'] ;				
				$funcionario->cpf = $row['cpf'];
				$funcionario-> rg = $row['rg'];
				$funcionario-> id = $row['id_funcionario'];
				
				$loc = new Locacao($this->path);		
				$loc->id = $row['id_locacao'];						
				$loc->cliente = $cliente;
				$loc->funcionario = $funcionario;
				$loc->data_locacao = implode('/', array_reverse(explode('-', $row['data_locacao'])));;
				$loc->data_entrega = $row['data_entrega'];;
				$loc->valor_base = $row['valor_base'];
				$loc->valor_total = $row['valor_total'];
				$loc->valor_multa = $row['valor_multa'];
				$loc->valor_desconto = $row['valor_desconto'];
				
				$this->buscarItens($loc);
			}
			
			return $loc;
		}
		*/
		
					
		public function listarLocacoesAbertas(){			
			$comm = $this->con->query("SELECT l.id as id_locacao, c.nome 
												FROM tb_locacao AS l 
												INNER JOIN tb_cliente AS c	ON (c.id = l.id_cliente)
												WHERE l.id_devolucao IS NULL");						
			$lista = $comm->fetchAll();			
			return $lista;
		}
		
		public function pesquisar($pesq=''){
			$where = "%$pesq%";
			$stmt = $this->con->prepare("SELECT l.id as id_locacao, c.nome 
												FROM tb_locacao AS l 
												INNER JOIN tb_cliente AS c	ON (c.id = l.id_cliente)
												WHERE l.id_devolucao IS NULL AND nome LIKE :nome;");	
			$stmt->bindParam(':nome',$where);
			$stmt->execute();	
																	
			$lista = $stmt->fetchAll();			
			return $lista;
		}
		/*
		public function listarMidia(){
			$comm = $this->con->query("SELECT * FROM tb_midia ORDER BY id;");						
			$lista = $comm->fetchAll();			
			return $lista;
		}
		
		public function listarStatusMidia(){
			$comm = $this->con->query("SELECT * FROM tb_situacao_item_acervo ORDER BY id;");						
			$lista = $comm->fetchAll();			
			return $lista;
		}
		
		public function listarMidiaPorTitulo($id_titulo){
			$stmt = $this->con->prepare("SELECT *FROM tb_titulo AS t INNER JOIN tb_item_acervo AS ia ON (t.id = ia.id_titulo) WHERE t.id=:id_titulo;");	
			$stmt->bindParam(':id_titulo',$id_titulo);
			
			$stmt->execute();					
			$lista = $stmt->fetchAll();			
			return $lista;
		}
		
		public function verificarExistenciaCodigo($cod){
			$stmt = $this->con->prepare("SELECT * FROM tb_item_acervo AS t WHERE codigo = :cod;");	
			$stmt->bindParam(':cod',$cod);
			
			$stmt->execute();					
			$lista = $stmt->fetchAll();			
			return count($lista) > 0;
		}
		
		public function buscarTitulo($id_titulo){
			$stmt = $this->con->prepare("SELECT * FROM tb_titulo AS t WHERE t.id=:id_titulo;");	
			$stmt->bindParam(':id_titulo',$id_titulo);			
			$stmt->execute();					
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);	
			$titulo = null;
			
			if($row!= null){
				$_id = $row['id'];
				$_nome = $row['nome'];
				$_nome_original=$row['nome_original'];
				$_descricao=$row['descricao'];
				$_ano_lancamento=$row['ano_lancamento'];
				
				$titulo = new Titulo($_nome,$_nome_original,$_descricao,$_ano_lancamento);	
				$titulo->id = $_id;
			}
					
			return $titulo;
		}
		
		public function adicionarTitulo($nome, $nome_original,$descricao,$ano_lancamento) {				
			$this->titulo = new Titulo($nome, $nome_original,$descricao,$ano_lancamento);
		}
			
		private function salvarTitulo(){
			$sql = "INSERT INTO 'main'.'tb_titulo' (nome, nome_original, ano_lancamento, descricao) VALUES ( :nome, :nome_original, :ano_lancamento, :descricao);";
			
			$stmt = $this->con->prepare($sql);			
			$stmt->bindParam(':nome',$this->titulo->nome);
			$stmt->bindParam(':nome_original',$this->titulo->nome_original);
			$stmt->bindParam(':ano_lancamento',$this->titulo->ano_lancamento);
			$stmt->bindParam(':descricao',$this->titulo->descricao);			
			
			$stmt->execute();
			$this->titulo->id = $this->con->lastInsertId();
			
			return $stmt->rowcount();			 
		}
		
		private function excluirTitulo(){			
			$sql = "DELETE FROM tb_titulo  WHERE  id=:id_titulo;";
			
			$stmt = $this->con->prepare($sql);
						
			$stmt->bindParam(':id_titulo',$this->titulo->id);
							
			$stmt->execute();				
			return $stmt->rowCount();
		}
		
		public function salvar(){
			if($this->titulo->id == 0)			
				$this->salvarTitulo();
			
			if($this->id==0){		
				
				$sql = "INSERT INTO 'main'.'tb_item_acervo' ('codigo','id_midia', 'id_titulo', 'id_situacao_item_acervo', 'periodo_max') VALUES (:codigo, :id_midia, :id_titulo, :id_situacao_item_acervo, :periodo_max);";
			
				$stmt = $this->con->prepare($sql);			
			
				$stmt->bindParam(':id_midia',$this->midia);
				$stmt->bindParam(':id_titulo',$this->titulo->id);
				$stmt->bindParam(':id_situacao_item_acervo',$this->status);		
				$stmt->bindParam(':codigo',$this->codigo);			
				$stmt->bindParam(':periodo_max',$this->periodo_max);			
			
				$stmt->execute();
				$this->id = $this->con->lastInsertId();
				$qde = $stmt->rowCount(); 
			} 
			else {
				$sql = "UPDATE 'main'.'tb_item_acervo' SET 'id_situacao_item_acervo'=:id_situacao_item_acervo, 'periodo_max'=:periodo_max  WHERE  id=:id_item_acervo;";
			
				$stmt = $this->con->prepare($sql);
				
				$stmt->bindParam(':id_situacao_item_acervo',$this->status);
				$stmt->bindParam(':periodo_max',$this->periodo_max);			
				$stmt->bindParam(':id_item_acervo',$this->id);
							
				$stmt->execute();				
				$qde = $stmt->rowCount();				
			}
			
			return $qde;
		}	
		
		public function buscar($codigo){
			$comm = $this->con->prepare("SELECT t.*,t.id as id_titulo,ia.*, ia.id as id_item_acervo FROM tb_titulo AS t INNER JOIN tb_item_acervo AS ia ON (t.id = ia.id_titulo) WHERE ia.codigo=:cod;");
			$comm->bindParam(':cod', $codigo);
			$comm->execute();						
			$row = $comm->fetch(PDO::FETCH_ASSOC);
			
			$midia = null;
			
			if ($row != null){ 
					$midia = new ItemAcervo($this->path);
					
					$midia->adicionarTitulo($row['nome'],$row['nome_original'],$row['descricao'],$row['ano_lancamento']);
					
					$midia->titulo->id = $row['id_titulo'];
					$midia->codigo = $row['codigo'];
					$midia->status = $row['id_situacao_item_acervo'];
					$midia->midia = $row['id_midia'];						
					$midia->periodo_max = $row['periodo_max'];
					$midia->id = $row['id_item_acervo'];
			}
			
			return $midia;
		}
		
		public function excluir(){
			$sql = "DELETE FROM tb_item_acervo  WHERE  id=:id_item_acervo;";
			
			$stmt = $this->con->prepare($sql);
						
			$stmt->bindParam(':id_item_acervo',$this->id);
							
			$stmt->execute();				
			$qde = $stmt->rowCount();	
			
			if($qde > 0)
				$this->excluirTitulo();
			
			return $stmt->rowCount();
		}
			*/
	}
	
?>