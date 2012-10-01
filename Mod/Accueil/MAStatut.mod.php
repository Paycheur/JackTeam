<?php


class MAStatut
{
	private $auteur;
	private $contenu;
	private $date;
	private $photo;
	private $tag;

	
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
		if(strlen($this->contenu) < 2)
		{
			$validation = 'Le contenu du statut est trop court.';
		}
		
		else
		{
			$validation='ok';
		}
		
		return $validation;
    }

    /*GETTERS*/
    
    public function getAuteur()
    {
    	return $this->auteur;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getContenu()
    {
    	return $this->contenu;
    }
    
    public function getDate()
    {
    	return $this->date;
    }
    
    public function getTag()
    {
    	return $this->tag;
    }
    
    public function getPhoto()
    {
    	return $this->photo;
    }
    
    
   /*SETTERS */
    
    public function setId($id)
    {
        $this->id = (int) $id;
    }
    
    public function setAuteur($id_auteur)
    {
        $this->auteur = $id_auteur;
    }
    
    
    public function setContenu($c)
    {
        $this->contenu = $c;
    }
    
    public function setPhoto($p)
    {
        $this->photo = $p;
    }
    
    
    public function setTag($t)
    {
        $this->tag = $t;
    }
    
    public function setDate($date)
    {
    	$this->date = $date;
    }
        
        
    
}
?>
    