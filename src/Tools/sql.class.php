<?php

/**
 * Classe de connexion et requêtes sur une base de donnée MySQL
 * 
 * <p> Décrire l'utilité de cette classe
 *	- 
 *	- 
 * </p>
 * 
 * @name sql
 * @author Fabrice BREBION <fabrice@brebion.info> 
 * @link 
 * @copyright Fabrice BREBION 2010
 * @version 1.1.0
 * @package
 */

 class MySQL {
 
    /*~*~*~*~*~*~*~*~*~*~*/
    /*  1. propriétés    */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
    * @var (String, resource ...)
    * @desc 
    */
    var $query;
    var $query_result;
 
    /**
    * Définition des variables privées
    *
    * @var $_link	@desc id de connexion SQL
    * @var $SQLNumrows	@desc Nb de lignes retournées
    * @var $SQLQuery	@desc Requête SQL
    * @var $SQLTable	@desc Nom de la table SQL
    * @var $SQLFilters	@desc Tableau contenant la liste des filtres SQL
    * @var $SQLColumns	@desc Liste des colonnes pour l'insertion de valeurs
    *
    *
    *
    */
    private $_link;
    private $_DBSelected;
    private $SQLNumrows;
    private $SQLQuery;
    private $SQLTable;
    private $SQLFilters= array();
    private $SQLOrder = array ('Field'=>'' , 'Way'=>'' );
    private $SQLLimit = array('start'=> false , 'lenght' => false );
    private $Return = 'ROWS';
    private $SQLFields = array();
    private $SQLGroupBy = array();
    private $SQLColumns=array();
    private $SQLInsertValues=array();

    /**
    * Définition des variables publiques
    *
    * @$SQLExtractionFields     @ Liste des champs à extraire. Défaut -> '*'
    * 
    *
    */
    public $SQLExtractionFields='*';
    public $Last_ID='';
    public $SQLFilterOp='AND';
    
   
    /*~*~*~*~*~*~*~*~*~*~*/
    /*  2. méthodes      */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
    * Constructeur
    * 
    * <p>création de l'instance de la classe</p>
    * 
    * @name MySQL::__construct()
    * @param SQLHost
    * @param SQLUser
    * @param SQLPassword
    * @param SQLBase
    * @return void 
    */
    public function __construct($SQLHost,$SQLUser,$SQLPassword,$SQLBase) {
		$this->_link = @mysql_connect($SQLHost,$SQLUser,$SQLPassword) or die("Unable to connect to server : ".mysql_error());
		$this->_DBSelected = @mysql_select_db($SQLBase,$this->_link) or die("Unable to select database : ".mysql_error());
    } 
    
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
    /*  2.1 méthodes privées   */
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/

    private function BuildSQLQuery() {
		// Debut de la requete 
		$SQLQuery = 'SELECT ';
		// Liste des champs a extraire 
		$SQLQuery .= $this->SQLExtractionFields;//implode(',',$this->SQLExtractionFields);
		// Nom de la table 
		$SQLQuery .= ' FROM '.$this->SQLTable;
		// Ajout des clauses de conditions si nécessaire 
		if( sizeof( $this->SQLFilters ) > 0 ) {
	    	$EachFilter = array() ;
	    	// Continuation de la mise en forme de la requete 
	    	$SQLQuery .= ' WHERE ' ;
	    	// On met en forme les filtres 
	    	foreach( $this->SQLFilters as $Filter ) {
			// On ajoute le filtre (Si $Op est Between, on ajoute le 2ème argument)
			$EachFilter[] = ($Filter['op']!=' BETWEEN ')?$Filter['field'].$Filter['op'].$Filter['arg1']:$Filter['field'].$Filter['op'].$Filter['arg1']." AND ".$Filter['arg2'] ;
	    	}
	    	// On colle les filtres avec du implode 
	    	$SQLQuery .= implode(' '.$this->SQLFilterOp.' ',$EachFilter) ;
		}
		// On rajoute une clause eventuelle d'ordre 
		if( strlen($this->SQLOrder['Field']) > 0 ) {
	    	// On rajoute la clause 
	    	$SQLQuery .= ' ORDER BY '.$this->SQLOrder['Field'] ;
	    	// Si le sens est précisé on le rajoute 
	    	if( strlen($this->SQLOrder['Way']) > 0 ) {
			$SQLQuery .= ' '.$this->SQLOrder['Way'] ;
	    	}
		}
		// On vérifie l'attribut LIMIT 
		if( count($this->SQLLimit) > 0) {
	    	if ( $this->SQLLimit['lenght'] != false && $this->SQLLimit['start'] != false ) {
			// LIMIT complet 
			$SQLQuery .= ' LIMIT '.$this->SQLLimit['start'].','.$this->SQLLimit['lenght'] ;
	    	}
	    	elseif( $this->SQLLimit['lenght'] != false && $this->SQLLimit['start'] == false ) {
			// LIMIT semi-complet 
			$SQLQuery .= ' LIMIT '.$this->SQLLimit['lenght'] ;
	    	}
		}
    	// On vérifie l'attribut GROUP BY
		if( count($this->SQLGroupBy) > 0) {
        	// On rajoute le groupement
        	$SQLQuery .= ' GROUP BY '.$this->SQLGroupBy['Column'];
    	}

		// Requete SQL Complete 
		$return = $SQLQuery ;
		return $return ;
    }

    private function sqlquery($req) {
		return mysql_query($req);
    }

    private function BuildSQLInsert() {
    	// Debut de la requete 
		$SQLInsert = 'INSERT INTO ';
		// Nom de la table 
		$SQLInsert .= $this->SQLTable;
		// Liste des champs a inclure
		$SQLInsert .= '('.implode(',',$this->SQLColumns).') ';
		// Valeures
		$SQLInsert .= 'VALUES ('.implode(',',$this->SQLInsertValues).')';
		$return = $SQLInsert;
		//renvoi de la commande
		return $return; 
    }
    
    private function BuildSQLUpdate() {
    	/*UPDATE [LOW_PRIORITY] [IGNORE] nom_de_table
		SET nom_colonne1=expr1 [, nom_colonne2=expr2, ...]
		[WHERE where_definition]
		[ORDER BY ...]
		[LIMIT #]*/
    	// Debut de la requête 
		$SQLUpdate = 'UPDATE ';
		// Nom de la table 
		$SQLUpdate .= $this->SQLTable;
		$SQLUpdate .= ' SET ';
		// Colonnes=Valeures
		// On utilise array combine sur les tableaux colonnes/valeurs qu'on place dans un 3ème array.
		$update_columns=array_combine($this->SQLColumns,$this->SQLInsertValues);
		foreach ($update_columns as $keys=>$values) {
			$SQLUpdate .= $keys.'='.$values.',';
		}
		// On suprimme la dernière ',' qui se trouve en dernière position
		$SQLUpdate = substr($SQLUpdate,0,strlen($SQLUpdate)-1);
		if( sizeof( $this->SQLFilters ) > 0 ) {
	    	$EachFilter = array() ;
	    	// Continuation de la mise en forme de la requete 
	    	$SQLUpdate .= ' WHERE ' ;
	    	// On met en forme les filtres 
	    	foreach( $this->SQLFilters as $Filter ) {
			// On ajoute le filtre (Si $Op est Between, on ajoute le 2ème argument)
			$EachFilter[] = ($Filter['op']!=' BETWEEN ')?$Filter['field'].$Filter['op'].$Filter['arg1']:$Filter['field'].$Filter['op'].$Filter['arg1']." AND ".$Filter['arg2'] ;
	    	}
	    	// On colle les filtres avec du implode 
	    	$SQLUpdate .= implode(' AND ',$EachFilter) ;
		}
		$return = $SQLUpdate;
		//renvoi de la commande
		return $return; 
    }
    

    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
    /*  2.2 méthodes publiques */
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/

	/* 
	* Séléction et vérification de la table SQL
	*/
    public function table($TblName) {
		if(preg_match("#^[[:alnum:]]+$#",$TblName)) {
		    // Nom de table uniquement alphanumérique 
		    //$this->LogEvent('Table SQL non alphanumerique');
		    // Renvoi faux 
		    $return = false ;
		}
		else {
		    // On enregistre le nom de la table dans la variable
		    $this->SQLTable = strtolower($TblName) ;
		    // Renvoi vrai 
		    $return = true ;
		}
		return $return ;
    }

	/* 
	* Définition du filtre SQL
	*/
    public function SetFilter($Field,$Op,$Arg1,$Arg2="") {
		$return = true;
    	// Operateurs acceptés 
		$AcceptedOperators = array('>','<','<=','>=','=','!=','LIKE','BETWEEN') ;
		// Si $Arg1 et $Arg2 ne sont pas des nombres on met des guillemets
		(!preg_match("#^[0-9]+$#",$Arg1))?$PutFilterValue1 = '"'.$Arg1.'"' : $PutFilterValue1 = $Arg1 ;
		(!preg_match("#^[0-9]+$#",$Arg2))?$PutFilterValue2 = '"'.$Arg2.'"' : $PutFilterValue2 = $Arg2 ;
    	// Si $Op est 'LIKE' ou 'BETWEEN', on insère un espace de chaque coté
    	(in_array($Op,array("LIKE","BETWEEN")))?$PutOp=' '.$Op.' ':$PutOp=$Op;
		// Si l'opérateur n'est pas reconnu on ne retourne rien
		( in_array($Op,$AcceptedOperators) ) ? $this->SQLFilters[] = array('field'=>$Field, 'op'=>$PutOp, 'arg1'=>$PutFilterValue1, 'arg2'=>$PutFilterValue2): $return = false ;
		return $return ;
    }

	/* 
	* Définition du tri SQL
	*/
    public function SetOrder($Field,$Way) {
		// Sens acceptés 
		$AcceptedWay = array('ASC','DESC') ;
		// Si le sens n'est pas reconnu, pas de tri
		(in_array($Way,$AcceptedWay))?$this->SQLOrder = array('Field'=>$Field,'Way'=>$Way):$return = false ;
		return $return ;
    }

	/* 
	* Définition de la limite de portée de l'interrogation SQL
	*/
    public function SetLimit($Start,$Lenght) {
		// N'accepte que des nombres
		// Si ce n'est pas un nombre, pas de limite
		((preg_match("#[0-9]+#",$Start))&&(preg_match("#[0-9]+#",$Lenght)))?$this->SQLLimit = array('start'=>$Start,'lenght'=>$Lenght):$return = false ;
		return $return ;
    }

	/* 
	* Définition du groupement SQL
	*/
    public function SetGroupBy($Column) {
		if(ereg("^[:alnum:]+$",$Column)) {
	    	// Nom de table uniquement alphanumérique 
	    	// Renvoi faux 
	    	$return = false ;
		}
		else {
	    	// On enregistre le nom de la table dans la variable
	    	$this->SQLGroupBy['Column'] = $Column ;
	    	// Renvoi vrai 
	    	$return = true ;
		}
		return $return ;
    }

    public function Query() {
		$SQLQuery=$this->BuildSQLQuery();
		return $SQLQuery;
    }

    public function Insert($table,$columns,$values) {
    	$this->table($table);
    	$this->SQLColumns=$columns;
    	$this->SQLInsertValues=$values;
    	//TODO Vérifier si array columns et values ont le même nombre d'éléments
    	$SQLInsert=$this->BuildSQLInsert();
    	$this->insert_result=@mysql_query($SQLInsert);
    	//Si insertion correcte, on retourne la valeur du dernier ID sinon on retourne FALSE
    	if ($this->insert_result == 1) {
	    	$return=$this->GetLastID();
    	}
    	else $return=FALSE;
    	//$return=$SQLInsert;
    	return $return;
    }

    public function Update($table,$columns,$values) {
    	$this->table($table);
    	$this->SQLColumns=$columns;
    	$this->SQLInsertValues=$values;
    	//TODO Vérifier si array columns et values ont le même nombre d'éléments
    	$SQLUpdate=$this->BuildSQLUpdate();
    	$this->update_result=@mysql_query($SQLUpdate);
    	$return=$this->update_result;
    	//$return = $SQLUpdate;
    	return $return;
    }

    public function NumRows($SQLQuery) {
		$this->query_result=mysql_query($SQLQuery);
		$this->numrows=@mysql_numrows($this->query_result);
		$return=$this->numrows;
		return $return;
    }

    public function FieldList() {
    	while ($i < mysql_num_fields($this->query_result)) {
    	    $this->SQLFields[$i]=mysql_field_name($this->query_result, $i);
    	    $i++;
    	}
    	$return=$this->SQLFields;
    	return $return;
    }

    public function Result($i,$FieldName) {
		$return=mysql_result($this->query_result,$i,$FieldName);
		return $return;
    }

    public function GetLastID() {
    	$return=@mysql_insert_id($this->_link);
    	return $return;
    }
    
	/* 
	* Suppression des filtres SQL
	*/
    public function ClearFilter() {
    	unset ($this->SQLFilters);
    	unset ($this->SQLGroupBy);
    }

    
    /**
    * Destructeur
    * 
    * <p>Destruction de l'instance de classe</p>
    * 
    * @name MySQL::__destruct()
    * @param nom du premier paramètre
    * @param nom du second paramètre
    * @param etc ...
    * @return void
    */
    public function __destruct() {
		@mysql_close($this->link);
    }
 }

?>
