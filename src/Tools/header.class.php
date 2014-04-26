<?php
/**
 * Classe de construction du header d'une page HTML
 * 
 * @author Fabrice BREBION
 * @name header
 * @version 1.0
 *
 */
class header
{
	/*~*~*~*~*~*~*~*~*~*~*/
    /*  1. propriétés    */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
     * @var $_DOCTYPE_ Doctype version (default HTML 4.01 TRANS)
     * @var $_CHARSET_ Charset to use (default UTF-8)
     * @var	$_DESCRIPTION_ Web page description (default empty)
     * @var $_AUTHOR_ Web page Author (default empty)
     * @var	$_TITLE_ Web page Title (default empty)
     * @var $_SHEETS_ Web page style sheets
     * @var $_SCRIPTS_ Web page scripts
	 */
	
    public $_DOCTYPE_=array(type=>"html",version=>"trans");
    public $_CHARSET_="UTF-8";
    public $_DESCRIPTION_="";
    public $_AUTHOR_="";
    public $_TITLE_="";
    public $_SHEETS_=array();
    public $_SCRIPTS_=array();
	
    private	$html4strict="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">";
	private	$html4trans="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
	private	$xhtml1strict="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
	private	$xhtml1trans="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	private	$xhtml1_1="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">";
	private	$html_lang="<html lang=\"fr\">";
	private	$xhtml_lang="<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"fr\" xml:lang=\"fr\">";
    
    
    /*~*~*~*~*~*~*~*~*~*~*/
    /*  2. méthodes      */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
     * Constructeur
     * 
     * <p>création de l'instance de la classe</p>
     * 
     * @name header::__construct()
     * @return void 
     */
    public function __construct() {
		
	}
	
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
    /*  2.1 privates methods   */
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
	
	/**
	 * This function constructs doctype chain
	 * 
	 * @name header::DTD_Insert($dtd)
	 * @param array $dtd - type=>[html,xhtml] and version=>[strict,trans]
	 * @return string (web page doctype chain)
	 * 
	 */
	private function DTD_Insert($dtd) {
	
		switch ($dtd[type]) {
			case 'html':
				$wp_doctype=($dtd[version]=="strict")?$this->html4strict:$this->html4trans;
				$wp_lang=$this->html_lang;
				break;
			case 'xhtml';
				$wp_doctype=($dtd[version]=="strict")?$this->xhtml1strict:$this->xhtml1trans;	
				$wp_lang=$this->xhtml_lang;
			break;
		}
		$wp_doctype.="\n";
		$wp_doctype.=$wp_lang."\n";
		return $wp_doctype;
	}

	/**
	 * This function construct web page meta data (charset, description, author and title)
	 * @name header::Meta_Insert($charset,$desc,$author,$title)
	 * @param string $charset - charset to use (UTF-8, ISO8859-1, etc.)
	 * @param string $desc - web page description
	 * @param string $author - web page author
	 * @param string $title - web page title
	 * @return string (web page meta data)
	 */
	private function Meta_Insert($charset,$desc,$author,$title) {
		$wp_http_equiv="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />";
		$wp_description="<meta name=\"description\" content=\"$desc\" />";
		$wp_author="<meta name=\"author\" content=\"$author\" />";
		$wp_title="<title>$title</title>";
		
		$wp_meta=$wp_http_equiv."\n";
		$wp_meta.=$wp_description."\n";
		$wp_meta.=$wp_author."\n";
		$wp_meta.=$wp_title."\n";
		return $wp_meta;
	}

	/**
	 * This function construct style sheets
	 * @name header::Stylesheet($sheets)
	 * @param array $sheets - this array contains href=>link to css file and media=>[screen,print,all]
	 * @return string (web page style sheet)
	 */
	private function Stylesheet($sheets) {
		$wp_style="";
		foreach ($sheets as $sheet) {
			$wp_style.="<link rel=\"stylesheet\" href=\"$sheet[href]\" type=\"text/css\"  media=\"$sheet[media]\" />\n";
		}
		return $wp_style;
	}

	/**
	 * This function construct scripts declaration
	 * @name header::Scripts($scripts)
	 * @param array $scripts - this array contains type=>script type and href=>link script file
	 * @return string (web page scripts declaration)
	 */
	private function Script($scripts) {
		$wp_script="";
		foreach ($scripts as $script) {
			$wp_script.="<script type=\"$script[type]\" src=\"$script[href]\"></script>\n";
		}
		return $wp_script;
	}
	
	private function Favicon() {

	}
	
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
    /*  2.1 publics methods    */
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
	
	/**
	 * This function construct web page header
	 * @name header::header()
	 * @return string (web page header)
	 */
	public function header() {
		// Définition Doctype
		$wp_header=$this->DTD_Insert($this->_DOCTYPE_);
	
		// Balise HEAD
		$wp_header.="<head>\n";

		// Définition des metadata
		$wp_header.=$this->Meta_Insert($this->_CHARSET_,$this->_DESCRIPTION_,$this->_AUTHOR_,$this->_TITLE_);

		// Définition des feuilles de style
		$wp_header.=$this->Stylesheet($this->_SHEETS_);

		// Définition des feuilles de style
		$wp_header.=$this->Script($this->_SCRIPTS_);
		
		// Fermeture de la balise HEAD
		$wp_header.="</head>\n";
		
		return $wp_header;
		
	}
	
	public function __destruct () {
		
	}
}


?>
