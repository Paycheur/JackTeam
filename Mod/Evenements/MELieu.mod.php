<?php


class MELieu
{
	private $lieu;
	private $infoPrix;
        
	private $id_auteur;
	private $id_event;

	
	private $id;
	
	
	public function __construct($valeurs = array())
    {
		if (!empty($valeurs)) // Si on a spécifié des valeurs, alors on hydrate l'objet
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
		
		if(strlen($this->lieu) < 1)
		{
			$validation= 'Il faut renseigner le lieu.';
		}
		else
		{
			$validation = 'ok';
		}
		
		return $validation;
    }

    /*GETTERS*/
    
    public function getLieu()
    {
    	return $this->lieu;
    }
    
    public function getInfoprix()
    {
    	return $this->infoPrix;
    }
    
    public function getAuteur()
    {
    	return $this->id_auteur;
    }
    
    
    public function getIdevent()
    {
    	return $this->id_event;
    }
    
     public function getId()
    {
    	return $this->id;
    }
    
    
   /*SETTERS */
    
    
    public function setIdauteur($id_auteur)
    {
        $this->id_auteur = $id_auteur;
    }
    
    
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }
    
    public function setInfoprix($prix)
    {
        $this->infoPrix = $prix;
    }
    
    public function setIdevent($id_event)
    {
    	$this->id_event = $id_event;
    }
    
    public function setId($id)
    {
        $this->id = (int) $id;
    }
        
        
    
}
?>
    