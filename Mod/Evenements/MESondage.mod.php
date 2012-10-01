<?php
class MESondage extends Connexion
{
	private $column;
	private $table;
	private $id;
	
	public function __construct($table, $column, $id)
	{
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
		$this->column=$column;
		$this->table=$table;
		$this->id=$id;
	}
	
	public function verifDeja_fait() //est-ce que le membre a d�j� not� la cadeau ?
	{
		$reponse= $this->connexion->prepare('SELECT id_membre, sond FROM '.$this->table.' WHERE '.$this->column.'= :id');	
		$reponse->execute(array(
			'id' => $this->id,
			));
		$deja_fait=2; //rien ne peut etre �gal a 2 car OUI=1 et NON=0
		while($donnees = $reponse->fetch())
		{

			if($_SESSION['id'] == $donnees['id_membre']) //savoir si le membre a d�j� not� la cadeau ou pas
			{
				$deja_fait= $donnees['sond'];
			}
		}
		$reponse->closeCursor();
	
		return $deja_fait;
	}
	
	public function add($sond, $id_membre)
	{
		$reponse= $this->connexion->prepare('SELECT * FROM '.$this->table.' WHERE id_membre=:id_membre AND '.$this->column.'=:id');
		$reponse->execute(array(
			'id_membre' => $id_membre,
			'id' => $this->id,
		));
		$donnees = $reponse->fetch();
		
		if($donnees)
		{
			$req2=$this->connexion->prepare('UPDATE '.$this->table.' SET sond = :sond WHERE id_membre = :id_membre AND '.$this->column.'= :id');
			$req2->execute(array(
				'id_membre' => intval($id_membre),
				'sond' => intval($sond),
				'id' => intval($this->id),
				));
		}
		else
		{
			$req2=$this->connexion->prepare('INSERT INTO '.$this->table.'(id_membre, '.$this->column.', sond) VALUES(:id_membre, :id, :sond)');
			$req2->execute(array(
			'id_membre' => intval($id_membre),
			'sond' => intval($sond),
			'id' => intval($this->id),
			));
		}
		
		$reponse->closeCursor();
		$req2->closeCursor();
		
	}
	
	public function combienDePersonnePour()
	{
		//Avoir le nombre de personne qui aime la cadeau
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM '.$this->table.' WHERE '.$this->column.'= :id AND sond = 1');	
		$reponse->execute(array(
			'id' => $this->id,
			));
		$donnees = $reponse->fetch();
	
		$nbpersonnepour=$donnees['nbpersonne'];
		
		$reponse->closeCursor();
		
		return $nbpersonnepour;
	}
	

					
	public function nomsPersonnesPour()
	{
		//r�cup�rer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM '.$this->table.' WHERE '.$this->column.'= :id AND sond = 1');	
	$reponse->execute(array(
			'id' => $this->id,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas apr�s le pseudo
	$membres ='';	
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
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM '.$this->table.' WHERE '.$this->column.'= :id AND sond = 0');	
		$reponse->execute(array(
			'id' => $this->id,
			));
		$donnees = $reponse->fetch();
		
		
		$nbpersonnecontre=$donnees['nbpersonne'];
		$reponse->closeCursor();
		
		return $nbpersonnecontre;
	}
	

					
	public function nomsPersonnesContre()
	{
		//r�cup�rer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM '.$this->table.' WHERE '.$this->column.'= :id AND sond = 0');	
	$reponse->execute(array(
			'id' => $this->id,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas apr�s le pseudo
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