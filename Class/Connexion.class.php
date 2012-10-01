<?php
abstract class Connexion
{
	protected $connexion;
	
	public function __construct($dbName, $login, $password)
    {
        $this->connexion = new PDO('mysql:dbname='.$dbName, $login, $password);

    }
}
?>
