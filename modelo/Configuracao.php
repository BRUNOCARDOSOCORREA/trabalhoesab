<?php
	
	
	class Configuracao {
		
		private $con;
		public $path;
		public $id = 0;
		public $habilitar_promocao_locacao_individual;
		public $habilitar_promocao_locacao_periodo;
		public $qde_locacao_individual;
		public $qde_locacao_periodo;
		public $valor_locacao;
		public $valor_multa;
						
		public function __construct($path){		
			$this->path = $path;				
			$this->con = new PDO("sqlite:$path");					
		}
	
		
		
		public function salvar(){
			$sql = "UPDATE 'main'.'tb_configuracao' 
						SET 
							'habilitar_promocao_locacao_individual'=:habilitar_promocao_locacao_individual, 
							'habilitar_promocao_locacao_periodo'=:habilitar_promocao_locacao_periodo, 
							qde_locacao_individual=:qde_locacao_individual, 
							qde_locacao_periodo=:qde_locacao_periodo,
							valor_multa=:valor_multa,
							valor_locacao=:valor_locacao;" ;
			
			$stmt = $this->con->prepare($sql);
				
			$stmt->bindParam(':habilitar_promocao_locacao_individual',$this->habilitar_promocao_locacao_individual);
			$stmt->bindParam(':habilitar_promocao_locacao_periodo',$this->habilitar_promocao_locacao_periodo);
			$stmt->bindParam(':qde_locacao_individual',$this->qde_locacao_individual);		
			$stmt->bindParam(':qde_locacao_periodo',$this->qde_locacao_periodo);			
			$stmt->bindParam(':valor_locacao',$this->valor_locacao);
			$stmt->bindParam(':valor_multa',$this->valor_multa);
									
			$stmt->execute();				
			$qde = $stmt->rowCount();
			
			return $qde;
		}	
		
		public function buscar(){
			$comm = $this->con->prepare("SELECT * FROM tb_configuracao  ;");
			
			$comm->execute();						
			$row = $comm->fetch(PDO::FETCH_ASSOC);
			
			$config = null;
			
			if ($row != null){ 	
					$config = new 	Configuracao($this->path);
					$config-> habilitar_promocao_locacao_individual =  $row['habilitar_promocao_locacao_individual'];				
					$config-> habilitar_promocao_locacao_periodo =  $row['habilitar_promocao_locacao_periodo'];
					$config-> qde_locacao_individual = $row['qde_locacao_individual'] ;				
					$config->qde_locacao_periodo =  $row['qde_locacao_periodo'];
					$config->valor_locacao =  $row['valor_locacao'];
					$config->valor_multa =  $row['valor_multa'];
			}
			
			return $config;
		}
		
		
	}
?>