<?php
class MGPhoto
{
	private $id;
	private $description;
	private $titre;
	private $photoUrl;
	private $miniature;
	private $date;
	private $auteur;
	private $album;
	
	
	public function __construct($valeurs = array())
    {
		if (!empty($valeurs)) 
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
    	if(strlen($this->titre) < 250 && strlen($this->description) < 600)
		{
			$validation='ok';
		}
		else
		{
			$validation='Un des champs est trop long';
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
    
    public function getPhotoUrl()
    {
    	return $this->photoUrl;
    }
    
    public function getAlbum()
    {
    	return $this->album;
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
    
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
    }
    
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    public function setAlbum($album)
    {
        $this->album = $album;
    }
}
?>
	