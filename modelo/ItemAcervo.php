<?php
	
	class Titulo {
		public $id;
		public $nome;
		public $descricao;
		public $nome_original;
		public $ano_lancamento;
		
		public function __construct($_nome,$_nome_original,$_descricao,$_ano_lancamento) {
				$this->nome = $_nome;
				$this->descricao = $_descricao;
				$this->nome_original = $_nome_original;
				$this->ano_lancamento = $_ano_lancamento;
		}
	}
	
	class StatusMidia {
			public $nome;
			public $descricao;
			public static $CADASTRADO = 1;
			public static $DISPONIVEL = 2;
			public static $EmMANUTENCAO = 3;
			public static $BAIXADO = 4;	
	}

	class Midia {
			public $nome;
			public $descricao;
			
			public static $VHS = 1;
			public static $DVD = 2;		
	}
	
	
	class ItemAcervo {
		
		private $con;
		public $path;
		public $titulo;
		public $midia;
		public $status;		
		public $codigo;
		public $periodo_max;		
		public $id = 0;
		
		public function __construct($path){		
			$this->path = $path;				
			$this->con = new PDO("sqlite:$path");					
		}
			
		public function listar(){			
			$comm = $this->con->query("SELECT * FROM tb_titulo AS t ;");						
			$lista = $comm->fetchAll();			
			return $lista;
		}
		
		public function pesquisar($pesq=''){
			$where = "%$pesq%";		
			$stmt = $this->con->prepare("SELECT * FROM tb_titulo WHERE nome LIKE :pesq ;");	
			$stmt->bindParam(':pesq',$where);		
			$stmt->execute();			
			$lista = $stmt->fetchAll();					
			return $lista;
		}
		
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
			
	}
?>