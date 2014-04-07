<?php
	class my_connDb extends PDO {
		protected $pdo;

		function __construct() {
			$this -> pdo = new PDO('mysql:host=localhost;dbname=ourtrips_runner', 'ourtrips_runner', 'YE8zDrXr', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this -> pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}
?>
