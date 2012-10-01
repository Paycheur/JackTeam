<?php


class MEEvenementVacance extends MEEvenement
{
	private $dateFin; //la date du debut de l'évenement est dans date_event.
	private $prixEvent;
        private $lieu;
	
	private $selec_termine=false;

	
	
	public function __construct($titre, $presentation, $id_auteur, $dateDebut, $date_post, $id, $resume, $prixEvent, $dateFin, $lieu)
    {
		$this->dateFin = $dateFin;
		$this->prixEvent = $prixEvent;
		$this->lieu = $lieu;
		parent::__construct(array(
			'id' => $id,
			'titre' => $titre,
			'presentation' => $presentation,
			'id_auteur' => $id_auteur,
			'date_event' => $dateDebut,
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

    
    public function getDatefin()
    {
    	return $this->dateFin;
    }
    public function getLieu()
    {
    	return $this->lieu;
    }
    
    public function getPrixEvent()
    {
	return $this->prixEvent;
    }
    
    
    
    public function setDatefin($date)
    {
        $this->dateFin = $date;
    }
    
    public function setPrixevent($prix)
    {
        $this->prixEvent = $prix;
    }
    
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }
    
    
}
?>
    