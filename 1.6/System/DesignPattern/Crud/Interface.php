<?php
	interface System_DesignPattern_Crud_Interface {

		public function retrieve($where);

		public function update($set,$where);

		public function create($set);

		public function remove($where);
	}
?>