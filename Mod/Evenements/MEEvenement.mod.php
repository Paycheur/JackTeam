<?php
class MEEvenement
{
	private $titre;
	private $presentation;
	private $date_event;
	private $date_post;
	private $id_auteur;
	private $resume;
	
	private $type_event; //1=anniv,
	
	private $jours_restant; //phrase indiquant le nombre de jours restant
	
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
    	if($this->id == 0)
    	{
		$this->resume='Entrez un résumé de l\'organisation...';
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    
    public function getTitre()
    {
    	return $this->titre;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getPresentation()
    {
    	return $this->presentation;
    }
    
    public function getDateevent()
    {
    	return $this->date_event;
    }
    
    public function getDatepost()
    {
    	return $this->date_post;
    }
    
    public function getIdauteur()
    {
    	return $this->id_auteur;
    }
    
    public function getJoursrestant()
    {
    	return $this->jours_restant;
    }
    
    public function getResume()
    {
	return $this->resume;
    }
    
    public function getTypeevent()
    {
	return $this->type_event;
    }

    
    
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
    
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }
    
    
    public function setDatepost($date_post)
    {
        $this->date_post = $date_post;
    }
    
    public function setDateevent($date_event)
    {
    	$this->date_event = $date_event;
    }
    
    public function setJoursrestant($jours_restant)
    {
    	$this->jours_restant = $jours_restant;
    }
    
    public function setResume($p)
    {
	$this->resume=$p;
    }
    
    public function setTypeevent($type)
    {
	$this->type_event = $type;
    }
}
?>
    