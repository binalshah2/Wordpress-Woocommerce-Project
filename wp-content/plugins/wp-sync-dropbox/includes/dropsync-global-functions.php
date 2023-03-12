<?php
/**
 * Globally-accessible functions
 *
 * @link 		http://scopeship.com
 * @since 		1.0.0
 *
 * @package		Lib
 * @subpackage 	Lib/includes
 */

/**
 * Returns the result of the get_max global function
 */
function dropsync_get_max( $array ) {

	return Dropsync_Globals::get_max( $array );

}


class Dropsync_Globals {

	/**
	 * Returns the count of the largest arrays
	 *
	 * @param 		array 		$array 		An array of arrays to count
	 * @return 		int 					The count of the largest array
	 */
 	public static function get_max( $array ) {

 		if ( empty( $array ) ) { return '$array is empty!'; }

 		$count = array();

		foreach ( $array as $name => $field ) {

			$count[$name] = count( $field );

		} //

		$count = max( $count );

		return $count;

 	} // get_max()

} // class
