<?php
	
	require_once 'System/Model.php';

	class UsuariosACK extends Model
	{
		protected $_name = "usuarios";

		public function getByEmail($email)
		{
			if(isset($email))
			{
				$query = array('email'=>$email);

				$result = $this->ioGet($query);
				return $result;
			}
			return false;
		}
	}
?>
