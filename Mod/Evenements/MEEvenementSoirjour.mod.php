<?php


class MEEvenementSoirjour extends MEEvenement
{
	private $heure; 
	private $prixEvent;
        private $lieu;
        private $apporter;
        private $type;

	
	
    public function __construct($titre, $presentation, $id_auteur, $dateEvent, $date_post, $id, $resume, $heure, $prixEvent, $lieu, $apporter, $type)
    {
		$this->heure= $heure;
		$this->prixEvent = $prixEvent;
		$this->lieu = $lieu;
                $this->apporter = $apporter;
                $this->type = $type;
		parent::__construct(array(
			'id' => $id,
			'titre' => $titre,
			'presentation' => $presentation,
			'id_auteur' => $id_auteur,
			'date_event' => $dateEvent,
			'date_post' => $date_post,
			'resume' => $resume,
			));

    }
    
    public function isValide()
    {
    	if(strlen($this->titre) < 200 && strlen($this->description) < 2000)
	{	
		$validation=true;
	}
					
	else
	{
		$validation = 'L\'un des champs est trop long.';
	}
	
	return $validation;
    }

    
    public function getHeure()
    {
    	return $this->heure;
    }
    public function getLieu()
    {
    	return $this->lieu;
    }
    
    public function getPrixEvent()
    {
	return $this->prixEvent;
    }
    
    public function getType()
    {
	return $this->type;
    }
    
    public function getApporter()
    {
	return $this->apporter;
    }
    
    
    
    public function setHeure($h)
    {
        $this->heure = $h;
    }
    
    public function setPrixevent($prix)
    {
        $this->prixEvent = $prix;
    }
    
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }
    
    public function setApporter($t)
    {
        $this->apporter = $t;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    
}
?>
    