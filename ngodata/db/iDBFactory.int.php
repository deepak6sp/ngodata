<?php
interface iDBFactory {
	public static function createDatabaseHandler(iDBConfigReader $conf);
}
?>