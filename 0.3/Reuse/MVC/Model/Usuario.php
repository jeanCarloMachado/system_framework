	<?php
	class Usuario extends Model
	{
		protected $_name = "usuarios_ikro";
		protected $_passwordColumn = "senha_responsavel";
		protected $_login = "desp_cod";
		
		//GET GENÉRICO
		//PEGA PASSANDO UM ARRAY DE WHERE
		public function get($where)
		{
			$result = $this->ioGet($where);
			return $result;
		}

		/**
		 * sobreescreve a  função de criar usuário
		 * @param  [type] $array [description]
		 * @return [type]        [description]
		 */
		public function ioCreate($array)
		{

			if(!array_key_exists($this->_passwordColumn, $array)) {

				$password = new System_Auth_Password;
				$newPassword  = $password->generate();
				$array[$this->_passwordColumn] = $newPassword['encrypt'];
			}

			parent::ioCreate($array);
		}

		/**
		 * dá update no password
		 * @param  [type] $password    [description]
		 * @param  [type] $where       [description]
		 * @param  [type] $params=null [description]
		 * @return [type]              [description]
		 */
		public function updatePassword($password,$where,$params=null)
		{
			$set[$this->_passwordColumn] = $password;
			$result = $this->ioUpdate($set,$where);
					
			return $result;
		}


		public function updateUser($setOuter,$whereOuter,$params=null)
		{	
			//dg($setOuter);
			if(array_key_exists('senha_responsavel',$setOuter))
				$setOuter['senha_responsavel'] = md5($setOuter['senha_responsavel']);

			/**
			 * insere o pais
			 * @var Pais
			 */
			$pais = new Pais;
			$set = array('nome'=> $setOuter['pais']);
			$where = array('nome'=> $setOuter['pais']);			
			$paisId = $pais->updateOrCreate($set,$where,null);
			/**
			 * insere o estado 
			 */
			$estado = new Estado;
			$set = array('sigla'=> $setOuter['uf'],
						'pais_id'=>$paisId);
			$where = array('sigla'=> $setOuter['uf'],
						'pais_id'=>$paisId);
			$estadoId = $estado->updateOrCreate($set,$where,null);
			/**
			 * insere a cidade
			 */
			$cidade = new Cidade;
			$set = array('nome'=> $setOuter['cidade'],
						 'cep' => $setOuter['cep'],
						'estados_id'=>$estadoId);

			$where = array('nome'=> $setOuter['cidade'],
						'estados_id'=>$estadoId);

			$cidadeId = $cidade->updateOrCreate($set,$where,null);

			/**
			 * insere o usuario
			 */
			$setOuter['cidade_id'] = $cidadeId;
			$result = $this->ioUpdate($setOuter,$whereOuter);
					
			return $result;
		}

		/**
		 * pega todos os elementos filhos desse objeto
		 * @param  [array] $where       [array com as clausulas do elemento principal]
		 * @param  [array] $params=null [parametros extras para a consulta como ordem]
		 * @param  [int] $module      [o modulo do objeto correspondente do ack]
		 * @return [array]              [array onde cada elemento terá seus filhos agregados a si]
		 */
		public function getTree($where,$params=null,$module=0)
		{
			$this->setTableName($this->_name);
			
			$tabelaPai['relationCollumn'][0]  = 'id';
			$tabelaPai['whereClausule'] = $where;
			$tabelaPai['addParam'] = $params;
			
	   		/**
	   		 * PEGA AS FOTOS
	   		 */
			$tabelasFilhas[0]['name']  = 'fotos';
			$tabelasFilhas[0]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[0]['whereClausule'] = array('status' => '1',
													   'visivel_pt' => '1',
													   'modulo'=>$module);
			$result = $this->getRelation($tabelaPai,$tabelasFilhas);

			return $result;
		}

		/**
		 * Retorna os últimos usuarios que compraram o produto pelo id do protudo e a  cidade do usuario
		 * (essa função foi feita priorizando o desempenho e não a orientação a objetos)
		 * @param  [int] $id [id do produto]
		 * @return [array]     [arvore com os usuarios]
		 */
		// public function getLatestBuyersByProductIdAndCityId($id,$cityId,$module)
		// {
		// 	/**
		// 	 * pega os id's dos pedidos em pedidos_produtos
		// 	 */
		// 	$pedidosProdutos = new Pedidos_produtos;
		// 	$pedidosProdutosRestul = $pedidosProdutos->get(array('produtos_id'=>$id));
			
		// 	/**
		// 	 * container de todos os pedidos
		// 	 * @var array
		// 	 */
		// 	$pedidosArray = array();
		// 	$currPedido = $pedidosProdutosRestul[0]['pedidos_id'];

		// 	$pedido = new Pedido();
		// 	/**
		// 	 * PEGA CADA PEDIDO PELO ID passado anteriormente
		// 	 */
		// 	foreach($pedidosProdutosRestul as $pedidoProdutoId => $pedidoProduto)
		// 	{	
		// 		/**
		// 		 * Só consulta o pedido caso ele já não tenha sido consultado
		// 		 */
		// 		if($currPedido != $pedidoProduto['pedidos_id'])
		// 		{
		// 			$pedidosArray[] = $pedidoProduto['pedidos_id'];
		// 		}
				
		// 	}
		// 	*
		// 	 * PEGA OS PEDIDOS PELO ID EM ORDEM CRONOLÓGICA
		// 	 * @var [ARRAY]
			 
		// 	$query = "SELECT DISTINCT usuario_id FROM `".$pedido->getTableName()."` WHERE ";
			
		// 	/**
		// 	 * adiciona todos os id's a query
		// 	 * @var [type]
		// 	 */
		// 	foreach($pedidosArray as $cellId => $pedidoId)
		// 	{
		// 		$query.= "`id` = $pedidoId OR ";
		// 	}		
		// 	/**
		// 	 * REMOVE OS 3 ULTIMOS CARACTERES DA QUERY
		// 	 */
		// 	$query= substr($query,0,-3);

		// 	$query.= "ORDER BY data ASC;";
		// 	/**
		// 	 * PEGA OS ID'S DOS USUARIOS
		// 	 * @var [type]
		// 	 */
		// 	$usersArray = $pedidoResult = $pedido->run($query);

		// 	/**
		// 	 * PEGA OS DADOS DOS USUARIOS PELOS ID'S
		// 	 */			
		// 	foreach($usersArray as $cellColumn => $usuarioId)
		// 	{
		// 		$tmp = $this->getTree(array('id'=>$usuarioId['usuario_id']),null,$module);
		// 		$result[] = $tmp[0];
		// 	}

		// 	return $result;			
		// }
	}
?>