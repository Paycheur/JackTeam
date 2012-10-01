<?php
class MECadeauSondage extends Connexion
{
	private $id_cadeau;
	
	public function __construct($id_cadeau)
	{
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
		$this->id_cadeau=$id_cadeau;
	}
	
	public function verifDeja_fait() //est-ce que le membre a déjà noté la cadeau ?
	{
		$reponse= $this->connexion->prepare('SELECT id_membre, sond FROM evenements_cadeau_c_sond WHERE id_cadeau= :id_cadeau');	
		$reponse->execute(array(
			'id_cadeau' => $this->id_cadeau,
			));
			
		$deja_fait=2; //rien ne peut etre égal a 2 car OUI=1 et NON=0
		while($donnees = $reponse->fetch())
		{
			if($_SESSION['id'] == $donnees['id_membre']) //savoir si le membre a déjà noté la cadeau ou pas
			{
				$deja_fait= $donnees['sond'];
			}
		}
		$reponse->closeCursor();
	
		return $deja_fait;
	}
	
	public function add($sond, $id_membre)
	{
		$reponse= $this->connexion->prepare('SELECT * FROM evenements_cadeau_c_sond WHERE id_membre=:id_membre AND id_cadeau=:id_cadeau');
		$reponse->execute(array(
			'id_membre' => $id_membre,
			'id_cadeau' => $this->id_cadeau,
		));
		$donnees = $reponse->fetch();
		
		if($donnees)
		{
			$req2=$this->connexion->prepare('UPDATE evenements_cadeau_c_sond SET sond = :sond WHERE id_membre = :id_membre AND id_cadeau = :id_cadeau');
			$req2->execute(array(
				'id_membre' => intval($id_membre),
				'id_cadeau' => intval($this->id_cadeau),
				'sond' => intval($sond),
				));
		}
		else
		{
			$req2=$this->connexion->prepare('INSERT INTO evenements_cadeau_c_sond(id_membre, id_cadeau, sond) VALUES(:id_membre, :id_cadeau, :sond)');
			$req2->execute(array(
			'id_membre' => intval($id_membre),
			'id_cadeau' => intval($this->id_cadeau),
			'sond' => intval($sond),
			));
		}
		
		$reponse->closeCursor();
		$req2->closeCursor();
		
	}
	
	public function combienDePersonnePour()
	{
		//Avoir le nombre de personne qui aime la cadeau
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM evenements_cadeau_c_sond WHERE id_cadeau= :id_cadeau AND sond = 1');	
		$reponse->execute(array(
			'id_cadeau' => $this->id_cadeau,
			));
		$donnees = $reponse->fetch();
	
		$nbpersonnepour=$donnees['nbpersonne'];
		
		$reponse->closeCursor();
		
		return $nbpersonnepour;
	}
	

					
	public function nomsPersonnesPour()
	{
		//récupérer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM evenements_cadeau_c_sond WHERE id_cadeau= :id_cadeau AND sond = 1');	
	$reponse->execute(array(
			'id_cadeau' => $this->id_cadeau,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas après le pseudo	
	$membres = '';
	while($donnees = $reponse->fetch())
	{
		
		$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
		$membre->execute(array(
			'id_membre' => $donnees['id_membre'],
			));
		
		
		while($pseudo = $membre->fetch())
		{
			if($i < $this->combienDePersonnePour()) //virgule ?
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
	
	public function combienDePersonneContre()
	{
		//Avoir le nombre de personne qui aime la cadeau
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM evenements_cadeau_c_sond WHERE id_cadeau= :id_cadeau AND sond = 0');	
		$reponse->execute(array(
			'id_cadeau' => $this->id_cadeau,
			));
		$donnees = $reponse->fetch();
		
		
		$nbpersonnecontre=$donnees['nbpersonne'];
		$reponse->closeCursor();
		
		return $nbpersonnecontre;
	}
	

					
	public function nomsPersonnesContre()
	{
		//récupérer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM evenements_cadeau_c_sond WHERE id_cadeau= :id_cadeau AND sond = 0');	
	$reponse->execute(array(
			'id_cadeau' => $this->id_cadeau,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas après le pseudo
	$membres = '';
	while($donnees = $reponse->fetch())
	{
		
		$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
		$membre->execute(array(
			'id_membre' => $donnees['id_membre'],
			));
		
		
		while($pseudo = $membre->fetch())
		{
			if($i < $this->combienDePersonneContre()) //virgule ?
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