<?php
	require_once(dirname(__FILE__) ."/../modelo/Funcionario.php");
	
	class MetaVenda {		
		private $con;
		public $path;
		
		public $funcionario;
		public $id = 0;
		public $qde;
		public $estah_vigente;		
		public $dt_termino;
		
				
		public function __construct($path){		
			$this->path = $path;				
			$this->con = new PDO("sqlite:$path");
			$this->funcionario = new Funcionario($path);			
		}
			
		public function listar(){	
		/*			
			$comm = $this->con->query("SELECT *,u.id as id_usuario FROM tb_usuario as u  INNER JOIN tb_funcionario as f on (f.id = u.id_funcionario);" );						
			$lista = $comm->fetchAll();			
			return $lista;		
			*/
		}
		
		public function pesquisar($pesq){						
			$where = "%$pesq%";		
			$stmt = $this->con->prepare("SELECT *,mv.id as id_meta_venda FROM tb_meta_venda as mv  INNER JOIN tb_funcionario as f on (f.id = mv.id_funcionario) WHERE f.nome  LIKE :pesq ;");	
			$stmt->bindParam(':pesq',$where);		
			$stmt->execute();			
			$lista = $stmt->fetchAll();					
			return $lista;						
		}
		
		public function verificarExistenciaMetaVigente($id_funcionario){
			$stmt = $this->con->prepare("SELECT * FROM tb_meta_venda AS t WHERE id_funcionario = :id_funcionario AND estah_vigente=1;");	
			$stmt->bindParam(':id_funcionario', $id_funcionario);
			
			$stmt->execute();					
			$lista = $stmt->fetchAll();			
			return count($lista) > 0;
		}
		
		
		public function salvar(){					
			if($this->id==0){						
				$sql = "INSERT INTO 'main'.'tb_meta_venda' ('id_funcionario','qde') VALUES (:id_funcionario, :qde);";
			
				$stmt = $this->con->prepare($sql);			
			
				$stmt->bindParam(':id_funcionario',$this->funcionario->id);
				$stmt->bindParam(':qde',$this->qde);
												
			
				$stmt->execute();
				$this->id = $this->con->lastInsertId();
				$qde = $stmt->rowCount();
			} 
			else {
				$extra = $this->estah_vigente == 0? ",'dt_termino'= date() ": "";		
				$sql = "UPDATE 'main'.'tb_meta_venda' SET 'qde'=:qde, 'estah_vigente'=:estah_vigente $extra WHERE  id=:id_meta_venda";
			
				$stmt = $this->con->prepare($sql);				
				
				$stmt->bindParam(':qde',$this->qde);
				$stmt->bindParam(':estah_vigente',$this->estah_vigente);
				$stmt->bindParam(':id_meta_venda',$this->id);				
				
				$stmt->execute();				
				$qde = $stmt->rowCount();		
			}
			
			return $qde;					
		}	
		
		public function buscar($codigo){	
			$comm = $this->con->prepare("SELECT *,mv.id as id_meta_venda FROM tb_meta_venda as mv  INNER JOIN tb_funcionario as f on (f.id = mv.id_funcionario) WHERE mv.id  = :cod ;");
			$comm->bindParam(':cod', $codigo);
			$comm->execute();						
			$row = $comm->fetch(PDO::FETCH_ASSOC);
			
			$usuario = null;
			
			if ($row != null){ 					
					$usuario = new MetaVenda($this->path);
					$usuario->id = $row['id_meta_venda'];					
					$usuario->qde = $row['qde'];
					$usuario->estah_vigente = $row['estah_vigente'];
					$usuario->dt_termino = $row['dt_termino'];
					
					$usuario->funcionario->id = $row['id_funcionario'];
					$usuario->funcionario->nome = $row['nome'];
					$usuario->funcionario->logradouro = $row['logradouro'];
					$usuario->funcionario->bairro = $row['bairro'];
					$usuario->funcionario->cpf = $row['cpf'];
					$usuario->funcionario->rg = $row['rg'];					
			}
			
			return $usuario;
		}
		
		public function buscarPorFuncionario($id_funcionario){	
			$comm = $this->con->prepare("SELECT *,mv.id as id_meta_venda FROM tb_meta_venda as mv  INNER JOIN tb_funcionario as f on (f.id = mv.id_funcionario) WHERE mv.id_funcionario  = :cod ;");
			$comm->bindParam(':cod', $id_funcionario);
			$comm->execute();						
			$row = $comm->fetch(PDO::FETCH_ASSOC);
			
			$usuario = null;
			
			if ($row != null){ 					
					$usuario = new MetaVenda($this->path);
					$usuario->id = $row['id_meta_venda'];					
					$usuario->qde = $row['qde'];
					$usuario->estah_vigente = $row['estah_vigente'];
					$usuario->dt_termino = $row['dt_termino'];
					
					$usuario->funcionario->id = $row['id_funcionario'];
					$usuario->funcionario->nome = $row['nome'];
					$usuario->funcionario->logradouro = $row['logradouro'];
					$usuario->funcionario->bairro = $row['bairro'];
					$usuario->funcionario->cpf = $row['cpf'];
					$usuario->funcionario->rg = $row['rg'];					
			}
			
			return $usuario;
		}
		
		public function excluir(){	
			$sql = "DELETE FROM tb_meta_venda  WHERE  id=:id_meta_venda;";
			
			$stmt = $this->con->prepare($sql);
						
			$stmt->bindParam(':id_meta_venda',$this->id);
							
			$stmt->execute();				
			$qde = $stmt->rowCount();	
			
			return $stmt->rowCount();
		}	
	}
?>