<?php
	final class my_getFilename {
		static function normal() {
			return substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1, strlen($_SERVER['PHP_SELF']));
		}
	}
?>
