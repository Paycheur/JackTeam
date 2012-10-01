<?php


class MECommentaire
{
	private $commentaire;
	private $id;
	private $auteur;
	private $date;
	private $idEvent;
	
	
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
    	if(strlen($this->commentaire) < 2000)
		{
			if(strlen($this->commentaire) < 1)
			{
				$validation= 'Vous ne pouvez pas poster un commentaire vide';
			}
			else
			{	
				$validation= 'ok';
			}
		}
					
		else
		{
			$validation = 'Le commentaire est trop long';
		}
		
		return $validation;
    }

    
    public function getAuteur()
    {
    	return $this->auteur;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getIdevent()
    {
    	return $this->idEvent;
    }
    
    public function getDate()
    {
    	return $this->date;
    }
    
    public function getCommentaire()
    {
    	return $this->commentaire;
    }
    
    public function getLien()
    {
    	return $this->lien;
    }
    
    
    public function setId($id)
    {
        $this->id = (int) $id;
    }
    
    public function setIdevent($idEvent)
    {
        $this->idEvent = $idEvent;
    }
    
    
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }
    
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }
    
    
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    public function setLien($lien)
    {
    	$this->lien = $lien;
    }
        
        
    
}
?>
    