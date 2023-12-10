<?php
	
	
	class Funcionario {
		
		public $con;
		public $path;
		public $id = 0;
		public $nome;
		public $logradouro;
		public $bairro;
		public $cpf;
		public $rg;
				
		public function __construct($path){		
			$this->path = $path;				
			$this->con = new PDO("sqlite:$path");					
		}
			
		public function listar(){			
			$comm = $this->con->query("SELECT * FROM tb_funcionario AS c ;");						
			$lista = $comm->fetchAll();			
			return $lista;
		}
		
		public function pesquisar($pesq=''){
			$where = "%$pesq%";		
			$stmt = $this->con->prepare("SELECT * FROM tb_funcionario WHERE nome LIKE :pesq ;");	
			$stmt->bindParam(':pesq',$where);		
			$stmt->execute();			
			$lista = $stmt->fetchAll();					
			return $lista;
		}
		
		public function verificarExistenciaNome($nome){
			$stmt = $this->con->prepare("SELECT * FROM tb_funcionario AS t WHERE nome = :nome;");	
			$stmt->bindParam(':nome',$nome);
			
			$stmt->execute();					
			$lista = $stmt->fetchAll();			
			return count($lista) > 0;
		}
		
		public function salvar(){
			
			if($this->id==0){						
				$sql = "INSERT INTO 'main'.'tb_funcionario' ('nome','logradouro', 'bairro', 'cpf', 'rg') VALUES (:nome, :logradouro, :bairro, :cpf, :rg);";
			
				$stmt = $this->con->prepare($sql);			
			
				$stmt->bindParam(':nome',$this->nome);
				$stmt->bindParam(':logradouro',$this->logradouro);
				$stmt->bindParam(':bairro',$this->bairro);		
				$stmt->bindParam(':cpf',$this->cpf);			
				$stmt->bindParam(':rg',$this->rg);			
			
				$stmt->execute();
				$this->id = $this->con->lastInsertId();
				$qde = $stmt->rowCount();
			} 
			else {				
				$sql = "UPDATE 'main'.'tb_funcionario' SET 'nome'=:nome, 'logradouro'=:logradouro, bairro=:bairro, cpf=:cpf, rg=:rg  WHERE  id=:id_cliente";
			
				$stmt = $this->con->prepare($sql);
				
				$stmt->bindParam(':nome',$this->nome);
				$stmt->bindParam(':logradouro',$this->logradouro);
				$stmt->bindParam(':bairro',$this->bairro);		
				$stmt->bindParam(':cpf',$this->cpf);			
				$stmt->bindParam(':rg',$this->rg);	
				$stmt->bindParam(':id_cliente',$this->id);
							
				$stmt->execute();				
				$qde = $stmt->rowCount();		
			}
			
			return $qde;
		}	
		
		public function buscar($codigo){
			$comm = $this->con->prepare("SELECT * FROM tb_funcionario AS c  WHERE id = :cod;");
			$comm->bindParam(':cod', $codigo);
			$comm->execute();						
			$row = $comm->fetch(PDO::FETCH_ASSOC);
			
			$cliente = null;
			
			if ($row != null){ 					
					$cliente = new Funcionario($this->path);
					$cliente->id = $row['id'];
					$cliente->nome = $row['nome'];
					$cliente->logradouro = $row['logradouro'];
					$cliente->bairro = $row['bairro'];
					$cliente->cpf = $row['cpf'];
					$cliente->rg = $row['rg'];
			}
			
			return $cliente;
		}
		
		public function excluir(){
			$sql = "DELETE FROM tb_funcionario  WHERE  id=:id_cliente;";
			
			$stmt = $this->con->prepare($sql);
						
			$stmt->bindParam(':id_cliente',$this->id);
							
			$stmt->execute();				
			$qde = $stmt->rowCount();	
			
			return $stmt->rowCount();
		}
			
	}
?>