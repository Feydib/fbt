<?php
 /**
 * path.php
 *
 * PHP version 5
 *
 * This file is part of FreeBetTournament Project
 *
 * @author    Fabrice BREBION <fabrice@brebion.info>
 * @version   SVN: $Id$
 * @link      
 * @since    1 mars 2014
 */

include 'versions.php';

//internal libraries
if ( !defined('FBT_CORE_LIB') ) {
    define(
        'FBT_CORE_LIB',
        FBT_ROOT . 'lib/fbt/Core'.'/'
    );
}
if ( !defined('FBT_COMMON_LIB') ) {
	define(
			'FBT_COMMON_LIB',
			FBT_ROOT . 'lib/fbt/Common'.'/'
	);
}
//external libraries
if ( !defined('FBT_FBLIBS') ) {
	define(
			'FBT_FBLIBS',
			FBT_ROOT . 'lib/fblibs'.'/'
	);
}
if ( !defined('FBT_SMARTY_PATH') ) {
	define(
			'FBT_SMARTY_PATH',
			FBT_ROOT . 'include/Smarty-' . SMARTY_VERSION . '/'
	);
}



?>