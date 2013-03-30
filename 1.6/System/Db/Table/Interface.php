<?php
	/**
	 * interface para classes de banco de dados
	 */
	interface System_Db_Table_Interface 
	{
		function create(array $where);

		function get(array $where=null,$params=null,$columns=null);

		function updateOrCreate(array $set,array $where,$params=null);

		function delete($where);

		function update(array $set,$where);

		function query(string $sql);

		function getTree(array $where=null,$params=null,$columns=null);
	}
?>