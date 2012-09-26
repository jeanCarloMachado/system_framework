<?php
	interface System_DB_Table_Interface 
	{
		function create($array);

		function get($array,$params=null,$columns=null);

		function updateOrCreate($set,$where,$params=null);

		function delete($where);

		function update(array $set,$where);

		function query($query);
	}
?>