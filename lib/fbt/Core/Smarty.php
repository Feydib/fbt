<?php
 /**
 * Smarty.php
 *
 * PHP version 5
 *
 * This file is part of FreeBetTournament Project
 *
 * @author    Fabrice BREBION <fabrice@brebion.info>
 * @version   SVN: $Id$
 * @link      
 * @since    2 mars 2014
 */

/**********
 * Load Smarty
*/
require_once(FBT_SMARTY_PATH . 'Smarty.class.php');

class Smarty_FBT extends Smarty {

	function Smarty_FBT() {

		// Constructeur de la classe.
		// Appelé automatiquement à l'instanciation de la classe.

		$this->__construct();

		$this->template_dir = FBT_ROOT . 'www/templates/';
		$this->compile_dir = FBT_ROOT . 'www/templates_c/';
		$this->config_dir = FBT_ROOT . 'www/configs/';
		$this->cache_dir = FBT_ROOT . 'www/cache/';
		$this->caching = true;
		$this->assign('app_name', 'FBT');
	}

}

?>