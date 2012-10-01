<?php


class MECadeau
{
	private $titre;
	private $prix;
	private $description;
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
		if(strlen($this->titre) > 50)
		{
			$validation = 'Le titre est trop long';
		}
		
		elseif(strlen($this->titre) < 1)
		{
			$validation= 'Vous ne pouvez pas poster un cadeau sans titre';
		}
		else
		{
			if(strlen($this->description) > 500)
			{
				$validation = 'La description est trop longue.';
				
			}
			else
			{
				if(is_numeric($this->prix))
				{
					$validation= 'ok';
				}
				else
				{
					$validation = 'Le prix doit etre de type numérique.';
				}
				
			}
			
		}
		
		return $validation;
    }

    /*GETTERS*/
    
    public function getTitre()
    {
    	return $this->titre;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getAuteur()
    {
    	return $this->id_auteur;
    }
    
    public function getDescription()
    {
    	return $this->description;
    }
    
    public function getPrix()
    {
    	return $this->prix;
    }
    
    public function getIdevent()
    {
    	return $this->id_event;
    }
    
    
   /*SETTERS */
    
    public function setId($id)
    {
        $this->id = (int) $id;
    }
    
    public function setIdauteur($id_auteur)
    {
        $this->id_auteur = $id_auteur;
    }
    
    
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }
    
    public function setIdevent($id_event)
    {
    	$this->id_event = $id_event;
    }
        
        
    
}
?>
    