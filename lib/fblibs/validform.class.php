<?php

/**
 * Classe qui permet de valider les champs d'un formulaire
 * 
 * <p> Cette classe valide les points suivants :
 *	- taille du champ
 *	- Valeur du champ
 * </p>
 * 
 * @name validform
 * @author Fabrice BREBION <fbrebion@retis.fr> 
 * @link 
 * @copyright Fabrice BREBION 2010
 * @version 1.0.0
 * @package
 */
 
 class validform {
 
    /*~*~*~*~*~*~*~*~*~*~*/
    /*  1. propriétés    */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
    * @var (String, resource ...)
    * @desc 
    */
    var $fieldname;
    var $value;
    var $type;
    var $taille_min;
    var $taille_max;
    var $madatory;
    var $pattern;
    var $errormsg;
    var $errorcode;
   
    /*~*~*~*~*~*~*~*~*~*~*/
    /*  2. méthodes      */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
    * Constructeur
    * 
    * <p>création de l'instance de la classe</p>
    * 
    * @name validform::__construct()
    * @param nom du premier paramètre
    * @param nom du second paramètre
    * @param etc ...
    * @return void 
    */
    public function __construct() {
	$taille_min=0;
	$taille_max=0;
	$value="";
	$mandatory=false;
	$errorcode=array();
	$errormsg=array();
    } 
    
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
    /*  2.1 méthodes privées   */
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/

	/**
	* Définition des codes d'erreur
	* E000 -> Champs Vide
	* E001 -> Taille du champ inférieur à la taille mini
	* E002 -> Taille du champ supérieur à la taille maxi
	* E003 -> Caractères invalides
	*/

    private function input_size() {
	if (empty($this->value)) {
	    $this->errorcode="E000";
	}
	elseif (strlen($this->value) < $this->taille_min) {
	    $this->errorcode="E001";
	    return false;
	}
	elseif (strlen($this->value) > $this->taille_max) {
	    $this->errorcode="E002";
	    return false;
	}
	else {
	    return true;
	}
    }

    private function input_pattern() {
	if (!empty($this->value)) {
	    if (preg_match($this->pattern, $this->value)) {
		return true;
	    }
	    else {
		$this->errorcode="E003";
		return false;
	    }
	}
    }
    
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
    /*  2.1 méthodes publiques */
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/


    public function valid_field() {
	/* Fonction qui valide un champ en fonction du type de champs passé en paramètre.
	* 
	*/
	switch ($this->type) {
	    case "mail":
		/* Une adresse mail est composée d'un nom, du caractère @ suivi du nom de domaine puis du tld.
		* Ce qui se décompose en expression régulière de la manière suivante
		*/ 
		$this->pattern="#[a-z1-9.-_]+@[a-z1-9.-]{2,}\.[a-z]{2,4}#";
		break;

	    case "ip":
		/* Une adresse IP est composée de 4 champs 3 chiffres séparés par des '.'.
		* Ce qui se décompose en expression régulière de la manière suivante
		*/ 
		$this->pattern="#([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})#";
		break;

	    case "alphanum":
		/* Un champ de type alpha numérique (Société, hostname) contients des caractères alphanumériques, le caractère '-', '_' ou ' '.
		* Ce qui se décompose en expression régulière de la manière suivante
		*/ 
		$this->pattern="#^[^àçéèù&$\#!:.;,?/]+$#";
		break;

	    case "id":
		/* Fonction qui valide un champ de type numérique.
		* Ce qui se décompose en expression régulière de la manière suivante
		*/ 
		$this->pattern="#[0-9]+#";
		break;

	    case "nospace":
		/* Fonction qui interdit les espaces et les caractères spéciaux.
		* Ce qui se décompose en expression régulière de la manière suivante
		*/ 
		$this->pattern="#^[^ àçéèù&$\#!:.;,?/]+$#";
		break;

	    case "textonly":
		/* Un champ de type texte (Nom, Prénom) ne contient que des caractères alphabétiques, le caractère '-' ou ' '.
		* Ce qui se décompose en expression régulière de la manière suivante
		*/ 
		$this->pattern="#^[^àçéèù_&$\#!:.;,?/0-9]+$#";
		break;

	    case "phone":
		/* Fonction qui valide un champ de type téléphone français.
		* Ce qui se décompose en expression régulière de la manière suivante
		*/ 
		$this->pattern="#([0-9]{2})\.([0-9]{2})\.([0-9]{2})\.([0-9]{2})\.([0-9]{2})#";
		break;
	    default:
		break;
	}
	if ($this->mandatory) {
	    if (!$this->input_size()) $this->errormsg=$this->fieldname;
	    elseif (!$this->input_pattern()) $this->errormsg=$this->fieldname;
	}
    }
	
   
    /**
    * Destructeur
    * 
    * <p>Destruction de l'instance de classe</p>
    * 
    * @name validform::__destruct()
    * @param nom du premier paramètre
    * @param nom du second paramètre
    * @param etc ...
    * @return void
    */
    public function __destruct() {
    
    }
 }
?>
