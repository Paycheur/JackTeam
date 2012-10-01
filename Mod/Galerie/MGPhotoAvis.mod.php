<?php
class MGPhotoAvis extends Connexion
{
	private $id_photo;
	
	public function __construct($id_photo)
	{
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
		$this->id_photo=bdd($id_photo);
	}
	
	public function verifDeja_note() //est-ce que le membre a dŽjˆ notŽ la photo ?
	{
		$reponse= $this->connexion->prepare('SELECT id_membre FROM galerie_photos_notes WHERE id_photo= :id_photo');	
		$reponse->execute(array(
			'id_photo' => $this->id_photo,
			));
			
		$deja_note=false;
		while($donnees = $reponse->fetch())
		{
			if($_SESSION['id'] == $donnees['id_membre']) //savoir si le membre a dŽjˆ notŽ la photo ou pas
			{
				$deja_note= true;
			}
		}
		$reponse->closeCursor();
	
		return $deja_note;
	}
	
	public function addAvis()
	{
		$req=$this->connexion->prepare('INSERT INTO galerie_photos_notes(id_membre, id_photo, note) VALUES(:id_membre, :id_photo, :note)');
			$req->execute(array(
				'id_membre' => intval($_POST['idMembre']),
				'id_photo' => intval($_POST['idPhoto']),
				'note' => intval($_POST['avis']),
		));
		
		$req->closeCursor();
	}
	
	public function combienDePersonneAime()
	{
		//Avoir le nombre de personne qui aime la photo
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM galerie_photos_notes WHERE id_photo= :id_photo AND note = 1');	
		$reponse->execute(array(
			'id_photo' => $this->id_photo,
			));
		$donnees = $reponse->fetch();
	
		$nbpersonneaime=$donnees['nbpersonne'];
		
		$reponse->closeCursor();
		
		return $nbpersonneaime;
	}
	

					
	public function nomsPersonnesAime()
	{
		//rŽcupŽrer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM galerie_photos_notes WHERE id_photo= :id_photo AND note = 1');	
	$reponse->execute(array(
			'id_photo' => $this->id_photo,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas aprs le pseudo	
	while($donnees = $reponse->fetch())
	{
		
		$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
		$membre->execute(array(
			'id_membre' => $donnees['id_membre'],
			));
		
		while($pseudo = $membre->fetch())
		{
			if($i < $this->combienDePersonneAime()) //virgule ?
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>, '; 
			}
			else
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>';
			}
			
			$i++;
		}
		$membre->closeCursor();
	}
	$reponse->closeCursor();
	
	return $membres;
	}
	
	public function combienDePersonneAimePas()
	{
		//Avoir le nombre de personne qui aime la photo
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM galerie_photos_notes WHERE id_photo= :id_photo AND note = 0');	
		$reponse->execute(array(
			'id_photo' => $this->id_photo,
			));
		$donnees = $reponse->fetch();
		
		
		$nbpersonneaimepas=$donnees['nbpersonne'];
		$reponse->closeCursor();
		
		return $nbpersonneaimepas;
	}
	

					
	public function nomsPersonnesAimePas()
	{
		//rŽcupŽrer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM galerie_photos_notes WHERE id_photo= :id_photo AND note = 0');	
	$reponse->execute(array(
			'id_photo' => $this->id_photo,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas aprs le pseudo
	while($donnees = $reponse->fetch())
	{
		
		$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
		$membre->execute(array(
			'id_membre' => $donnees['id_membre'],
			));
		
		while($pseudo = $membre->fetch())
		{
			if($i < $this->combienDePersonneAimePas()) //virgule ?
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>, '; 
			}
			else
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>';
			}
			
			$i++;
		}
		$membre->closeCursor();
	}
	
	$reponse->closeCursor();
	
	return $membres;
	}
}
?>