<?php
	require_once(dirname(__FILE__) ."/../modelo/Locacao.php");
	
	class Pagamento {
		
		private $con;
		public $path;
		public $id = 0;
		public $locacao;
		public $valor_locacao;
		public $valor_multa;
		public $valor_desconto;
		public $valor_total;
		public $data;
		public $status;
		public $data_cancelamento;
		public $motivo_cancelamento;
				
		public function __construct($path){		
			$this->path = $path;				
			$this->con = new PDO("sqlite:$path");
			$this->locacao = new Locacao($path);			
		}
		/*	
		public function listar(){				
			$comm = $this->con->query("SELECT *,u.id as id_usuario FROM tb_usuario as u  INNER JOIN tb_funcionario as f on (f.id = u.id_funcionario);" );						
			$lista = $comm->fetchAll();			
			return $lista;		
		}
		
		public function pesquisar($pesq=''){			
			$where = "%$pesq%";		
			$stmt = $this->con->prepare("SELECT *,u.id as id_usuario FROM tb_usuario as u  INNER JOIN tb_funcionario as f on (f.id = u.id_funcionario) WHERE f.nome  LIKE :pesq ;");	
			$stmt->bindParam(':pesq',$where);		
			$stmt->execute();			
			$lista = $stmt->fetchAll();					
			return $lista;			
		}
		
		public function verificarExistenciaLogin($nome){			
			$stmt = $this->con->prepare("SELECT * FROM tb_usuario AS t WHERE login = :nome;");	
			$stmt->bindParam(':nome',$nome);
			
			$stmt->execute();					
			$lista = $stmt->fetchAll();			
			return count($lista) > 0;			
		}
		
		*/
		public function salvar(){	
			
			if($this->id==0){						
				$sql = "INSERT INTO 'main'.'tb_pagamento' ('id_locacao', 'valor_locacao', 'valor_multa', 'valor_desconto', 'data', 'status') VALUES (:id_locacao, :valor_locacao, :valor_multa, :valor_desconto, :data, :status );";
			
				$stmt = $this->con->prepare($sql);			
			
				$stmt->bindParam(':id_locacao',$this->locacao->id);
				$stmt->bindParam(':valor_locacao',$this->valor_locacao);				
				$stmt->bindParam(':valor_desconto',$this->valor_desconto);		
				$stmt->bindParam(':valor_multa',$this->valor_multa);						
				$stmt->bindParam(':data',$this->data);						
				$stmt->bindParam(':status',$this->status);
				
				$stmt->execute();
				$this->id = $this->con->lastInsertId();
				$qde = $stmt->rowCount();
			} 
			else {/*
				$sql = "UPDATE 'main'.'tb_pagamento' SET 'senha'=:senha, 'status'=:status, 'eh_administrador'= :eh_administrador WHERE  id=:id_pagamento";
			
				$stmt = $this->con->prepare($sql);				
				
				$stmt->bindParam(':senha',$this->senha);
				$stmt->bindParam(':status',$this->status);		
				$stmt->bindParam(':id_usuario',$this->id);
				$stmt->bindParam(':eh_administrador',$this->eh_administrador);
				$stmt->bindParam(':status',$this->status);
											
				$stmt->execute();				
				$qde = $stmt->rowCount();
				*/
			}
			
			return $qde;			
		}	
		
		public function cancelar($motivo){
				$sql = "UPDATE 'main'.'tb_pagamento' SET 'data_cancelamento'= date(), 'motivo_cancelamento'=:motivo_cancelamento WHERE  id=:id_pagamento";
			
				$stmt = $this->con->prepare($sql);				
				$stmt->bindParam(':motivo_cancelamento',$motivo);
				$stmt->bindParam(':id_pagamento',$this->id);
										
				$stmt->execute();				
				return $stmt->rowCount();
		}
		
		
		public function buscar($codigo){			
			$comm = $this->con->prepare("SELECT *,p.id as id_pagamento FROM tb_pagamento AS p INNER JOIN tb_locacao AS l ON (p.id_locacao = l.id) WHERE p.id= :cod ;");
			$comm->bindParam(':cod', $codigo);
			$comm->execute();						
			$row = $comm->fetch(PDO::FETCH_ASSOC);
			
			$pag = null;
			
			if ($row != null){ 					
					$pag = new Pagamento($this->path);
					$pag->id = $row['id_pagamento'];					
					$pag->valor_locacao = $row['valor_locacao'];
					$pag->valor_desconto = $row['valor_desconto'];
					$pag->valor_multa = $row['valor_multa'];
					$pag->data = $row['data'];
					$pag->status = $row['status'];
					$pag->motivo_cancelamento = $row['motivo_cancelamento'];
					$pag->data_cancelamento = implode('/', array_reverse(explode('-', $row['data_cancelamento'])));				
					
					$pag->locacao->id = $row['id_locacao'];
					$pag->locacao->data_locacao = $row['data_locacao'];
					$pag->locacao->valor_base = $row['valor_base'];					
					$pag->locacao->valor_multa = $row['valor_multa'];
					$pag->locacao->valor_desconto = $row['valor_desconto'];
					$pag->locacao->valor_total = 	$pag->locacao->valor_base + $pag->locacao->valor_multa - $pag->locacao->valor_desconto;			
			}
			
			return $pag;			
		}
		
		/*
		public function excluir(){			
			$sql = "DELETE FROM tb_usuario  WHERE  id=:id_usuario;";
			
			$stmt = $this->con->prepare($sql);
						
			$stmt->bindParam(':id_usuario',$this->id);
							
			$stmt->execute();				
			$qde = $stmt->rowCount();	
			
			return $stmt->rowCount();			
		}
			*/
	}
?>