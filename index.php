<?php
 /**
 * index.php
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

/**********
 * global variables
 */
define(eol, "<br>");

//define fbt's root directory
if ( !defined('FBT_ROOT') ) {
	define('FBT_ROOT', __DIR__ .'/');
}

/**********
 * Display errors (set to 1 if you want to display errors)
*/
ini_set( 'display_errors' , 1);
error_reporting(E_ALL);

/**********
 * Path definition
 */
require_once ('./config/paths.php');

/**********
 * Database definition
*/
require_once FBT_ROOT . 'config/db.php';


/**********
 * Load classes
*/
//Core classes
array_walk( glob(FBT_CORE_LIB . '*.php') , create_function('$v,$i', 'return require_once($v);') );

//fblibs classes
array_walk( glob(FBT_FBLIBS . '*.php') , create_function('$v,$i', 'return require_once($v);') );


/**********
 * Si variables définies par URL, on les récupère, sinon, on les initialise
*/
$menu=( !empty($_GET['menu']) ) ? $_GET['menu'] : 'welcome' ;

/**********
 * Test si cookie existant, sinon, on initialise les variables
*/
if ( empty($_COOKIE['login']) ) {
	$login = '';
	$identity = '';
	$identity_info = '<a href="fbt.php?menu=registration">Inscrivez-vous</a>';
	$login_logout = '<a href="fbt.php?menu=login">Connexion</a>';
}
else {
	if ( $menu == "logout" ) {
		// On détruit les cookies
		setcookie("login[id]" , '' , time()-1);
		setcookie("login[name]" , '' , time()-1);
		setcookie("login[firstname]" , '' , time()-1);
		setcookie("login[authenticated]" , FALSE , time()-1);
		// on recharge la page pour afficher l'information
		echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
	}
	else {
		$login=$_COOKIE['login'];
		$identity=$login['firstname']." " . $login['name'];
		$identity_info='Identifiant : ' . $identity;
		$login_logout='<a href="fbt.php?menu=logout">Déconnexion</a>';
	}
}







/********************************
 * TESTS
 */

$smarty = new Smarty_FBT();
//$smarty->assign('name','Ned');
//$smarty->display('index.tpl');

$country = new country("France", "fr", "Europe");
$country->get();

/*
$team1 = new team("test", "test", "EUROPE");
$team1->win(3,2);

echo ($team1->getpts());
*/

?>