<?php


class MGAlbum
{
	private $description;
	private $titre;
	private $id;
	private $auteur;
	private $date;
	private $miniature;
	
	
	public function __construct($valeurs = array())
    {
		if (!empty($valeurs)) // Si on a spŽcifiŽ des valeurs, alors on hydrate l'objet
        $this->hydrate($valeurs);
    }
    
    public function hydrate($donnees)
        {
            foreach ($donnees as $attribut => $valeur)
            {
                $methode = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
                
                if (is_callable(array($this, $methode)))
                {
                    $this->$methode($valeur);
                }
            }
        }
        
    public function isNew()
    {
    	return empty($this->id);
    }
    
    public function isValide()
    {
    	if(empty($_SESSION['pseudo']) OR empty($this->titre) OR empty($this->description))
		{
			$validation=false;
		}
		else
		{
			$validation=true;
		}
		
		return $validation;
    }
    
    public function getDescription()
    {
    	return $this->description;
    }
    
    public function getAuteur()
    {
    	return $this->auteur;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getDate()
    {
    	return $this->date;
    }
    
    public function getMiniature()
    {
    	return $this->miniature;
    }
    
    public function getTitre()
    {
    	return $this->titre;
    }
    
    public function setId($id)
    {
        $this->id = (int) $id;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }
    
    public function setMiniature($miniature)
    {
        $this->miniature = $miniature;
    }
    
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }
    
    public function setDate($date)
    {
        $this->date = $date;
    }
        
        
    
}
?>
    