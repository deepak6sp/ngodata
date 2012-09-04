<?php
/**
 * Interface: iDBFactory
 * @author Steve Cooke <sa_cooke@me.com>
 * Date: August 2, 2012
 * Adapted from: 
 */
interface iDBFactory {
	public static function createDatabaseHandler(iDBConfigReader $conf);
}
?>