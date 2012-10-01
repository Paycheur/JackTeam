<?php


class MEEvenementAnniv extends MEEvenement
{
	private $membre_anniv;
	private $prixEvent;
	
	private $selec_termine=false;

	
	
	public function __construct($titre, $presentation, $id_auteur, $date_event, $date_post, $membre_anniv, $id, $prixEvent, $resume)
    {
		$this->membre_anniv = $membre_anniv;
		$this->prixEvent = $prixEvent;
		parent::__construct(array(
			'id' => $id,
			'titre' => $titre,
			'presentation' => $presentation,
			'id_auteur' => $id_auteur,
			'date_event' => $date_event,
			'date_post' => $date_post,
			'resume' => $resume,
			));

    }
    
    public function isValide()
    {
    	if(strlen($this->membre_anniv) < 40 && strlen($this->titre) < 200 && strlen($this->description) < 2000)
	{
		if(strlen($this->membre_anniv) < 1)
		{
			$validation= 'Vous ne pouvez pas poster un cadeau sans membre_anniv';
		}
		else
		{	
			$validation=true;
		}
	}
					
	else
	{
		$validation = 'L\'un des champs est trop long.';
	}
	
	return $validation;
    }

    
    public function getMembreanniv()
    {
    	return $this->membre_anniv;
    }
    
    public function getPrixEvent()
    {
	return $this->prixEvent;
    }
    
    
    
    public function setMembreanniv($membre_anniv)
    {
        $this->membre_anniv = $membre_anniv;
    }
    
    
}
?>
    