<?php


class MEDates
{
	private $dateDebut;
	private $dateFin;
        
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
		
		if(strlen($this->dateFin) < 1)
		{
			$validation= 'Il faut une date de fin.';
		}
                elseif(strlen($this->dateDebut) < 1)
                {
                    $validation = 'Il faut une date de début.';
                }
		else
		{
			$validation = 'ok';
		}
		
		return $validation;
    }

    /*GETTERS*/
    
    public function getDatefin()
    {
    	return $this->dateFin;
    }
    
    public function getDatedebut()
    {
    	return $this->dateDebut;
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
    
    
    public function setDatefin($date)
    {
        $this->dateFin = $date;
    }
    
    public function setDatedebut($date)
    {
        $this->dateDebut = $date;
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
    