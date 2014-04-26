<?php
 /**
 * country.php
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

class country {
    /*~*~*~*~*~*~*~*~*~*~*/
    /*  1. Properties    */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
    * @var (String, resource ...)
    * @desc 
    */
	
	private $sql_tb = 'FBTCountries';
	
	/**
	 * Private variables definition
	 *
	 * @var  (String, resource ...)                  @desc
	 */
	
	
	
    /**
    * Protected variables definition
    * 
    * @var  (String, resource ...)                  @desc	
    */

	protected $name;
	protected $iso;
	protected $continent;
	protected $sqlcon;
	
    /**
    * Public variables definition
    * 
    * @var  (String, resource ...)                  @desc	
    */
   
    /*~*~*~*~*~*~*~*~*~*~*/
    /*  2. Methodes      */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
    * Constructor
    * 
    * <p>Class instance build</p>
    * 
    * @name team::__construct()
    * @param 
    * @param 
    * @param 
    * @param 
    * @return void 
    */
    public function __construct($name,$iso,$cont) {
    	$this->name = $name;
    	$this->iso = $iso;
    	$this->continent = $cont;
    	/**********
    	 * Initialisation de la connexion a la base de donnée
    	*/
    	$this->sqlcon = new MySQL(HOST,USER,PASSWD,DB);
    }
    
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
    /*  2.1 Private methodes   */
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/

	
	/*~*~*~*~*~*~*~*~*~*~*~*~*~*/
	/*  2.2 Public methodes   */
	/*~*~*~*~*~*~*~*~*~*~*~*~*~*/

    /**
     * create
     *
     * <p>create a new country into a database</p>
     *
     * @name country::create()
     * @param void
     */
    public function create() {
    
    }
    
    /**
     * delete
     *
     * <p>delete a country</p>
     *
     * @name country::delete()
     * @param void
     */
    public function delete() {
    
    }
    
    /**
     * modify
     *
     * <p>modify a country into a database</p>
     *
     * @name country::modify()
     * @param void
     */
    public function modify() {
    
    }

    /**
     * get
     *
     * <p>get a country from SQL database</p>
     *
     * @name country::modify()
     * @param void
     */
    public function get() {
    	$this->sqlcon->Table($this->sql_tb);
    	$this->sqlcon->ClearFilter();
    	$this->sqlcon->SetFilter("name", "=", $this->name);
    	echo $this->sqlcon->Query();
    	//$SQL_NumRow = $this->sqlcon->NumRows($SQL_Query);
    	 
    }
    
    
	
	/**
	 * Destructor
	 *
	 * <p>Class instance destruct</p>
	 *
	 * @name team::__destruct()
	 * @return void
	 */
	public function __destruct() {
		//echo ("__destruct");
	}
	
}

?>