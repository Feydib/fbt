<?php
 /**
 * team.php
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

class team extends country {
    /*~*~*~*~*~*~*~*~*~*~*/
    /*  1. Properties    */
    /*~*~*~*~*~*~*~*~*~*~*/
    
    /**
    * @var (String, resource ...)
    * @desc 
    */
 
    /**
    * Private variables definition
    * 
    *
    */

	private $played,$win,$draw,$lost,$gf,$ga,$gav;
	private $pts=0;
	
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
    	parent::__construct($name,$iso,$cont);
    } 
    
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/
    /*  2.1 Private methodes   */
    /*~*~*~*~*~*~*~*~*~*~*~*~*~*/

    /**
     * updategav
     *
     * <p>Update Goal average</p>
     *
     * @name team::updategav($gf,$ga)
     * @param 
     */
	
	private function updategav ($gf,$ga) {
		$this->gf += $gf ;
		$this->ga += $ga ;
		$this->gav = $this->gf - $this->ga ;
	}

	/*~*~*~*~*~*~*~*~*~*~*~*~*~*/
	/*  2.2 Public methodes   */
	/*~*~*~*~*~*~*~*~*~*~*~*~*~*/
	
	/**
	 * win
	 * <p>Add a win</p>
	 *
	 * @name team::win()
	 * @param $gf (Integer) - Goal for
	 * @param $ga (Integer) - Goal against
	 */
	
	public function win($gf,$ga) {
		if ($gf < $ga) {
			return -1 ;
		}
		$this->pts += 3 ;
		$this->updategav($gf,$ga);
	}
	
	public function lose($gf,$ga) {
		if ($gf > $ga) {
			return -1 ;
		}
		$this->updategav($gf,$ga);
		return 0 ;
	}
	
	public function draw($gf,$gf) {
		if ($gf !== $ga) {
			return -1;
		}
		$this->pts++ ;
		$this->updategav($gf,$ga);
		return 0;
	}
	
	public function getpts() {
		return $this->pts;
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