<?php
 /**
 * ua.class.php
 *
 * PHP version 5
 *
 * Class which gets browser information
 *
 * @author    Fabrice BREBION <fabrice@brebion.info>
 * @version   SVN: $Id$
 * @link      
 * @since    1 mars 2014
 */

class ua {
	/**
	 *
	 * @PARAM 	
	 */
	private $_UA_=array();
	private $ua;
	private $browsers=array("firefox", "msie", "opera", "safari", "chrome");
	private $systems=array("windows", "linux", "mac");
	function __construct() {
		$this->ua=strtolower($_SERVER['HTTP_USER_AGENT']);
	}
	
	private function get_user_agent() {
		$user_agent="unknown";
		$user_agent_version="0";
		$user_os="unknown";
		foreach ($this->browsers as $browser) {
			if (preg_match("#($browser)[/ ]([0-9.]*)#", $this->ua, $match)) {
				$user_agent = $match[1] ;
				$user_agent_version = $match[2] ;
				break;
			}
		}
		foreach ($this->systems as $os) {
			if (strstr($ua,$os)!=FALSE) {
				$user_os=$os;
				break;
			}
		}
		$this->_UA_=array("UA" => $user_agent,"VER" => $user_agent_version,"OS" =>$user_os);
	}
	
	private function get_ip() {
		$this->_UA_['IP']=$_SERVER['REMOTE_ADDR'];
	}
	
	public function get_UA_() {
		return $_UA_;
	}
}


?>