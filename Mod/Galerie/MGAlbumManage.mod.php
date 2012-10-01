<?php
class MGAlbumManage extends Connexion
{
	
	private $confirmation;
	private $error;
	
	private $album = array();
	
	
	public function __construct()
    {
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
	
	
	public function save(MGAlbum $album)
    {
    	if ($album->isValide())
    	{
        	$album->isNew() ? $this->add($album) : $this->modifier($album);
        }
        else
        {
            $this->error='Veuillez remplir tous les champs';
        }
    }
    
    public function albumExiste($id_album)
    {
    	$reponse = $this->connexion->prepare('SELECT id FROM galerie_albums WHERE id = :id');
		$reponse->execute(array('id' => $id_album));
		$donnees = $reponse->fetch(); 
		if($donnees)
		{
			return true;
		}
		else
		{
			return false;
		}
    }

        
    public function add(MGAlbum $album)
    {

        $reponse = $this->connexion->prepare('INSERT INTO galerie_albums(titre, description, id_auteur, date) VALUES(:titre, :description, :id_auteur, :date)');
		$reponse->execute(array(
			'titre' => bdd($album->getTitre()),
			'description' => bdd($album->getDescription()),
			'id_auteur' => bdd($album->getAuteur()),
			'date' => time(),
			));
		
		$this->confirmation = 'L\'album a bien été envoyé<br />';
    }
        
    public function getUnique($id)
    {
    	$reponse = $this->connexion->prepare('SELECT titre, description, id_auteur, id, date FROM galerie_albums WHERE id = :id');
		$reponse->execute(array('id' => $id));
		$donnees = $reponse->fetch(); 
			
		$reponse_membre = $this->connexion->prepare('SELECT pseudo FROM membre WHERE id= :id_membre'); //on récupère le membre selon l'id_membre
		$reponse_membre->execute(array(
			'id_membre' => $donnees['id_auteur'],
			));
		$donnees_membre = $reponse_membre->fetch();
		
		if($donnees)
		{	
			$album = new MGAlbum(array(
                'id' => $donnees['id'],
                'auteur' => $donnees_membre['pseudo'],
                'titre' => affichage_form($donnees['titre']),
                'description' => affichage_form($donnees['description']),
                'date' => $donnees['date'],
                ));
		}
		else
		{
			$this->error = 'L\'album n\'existe pas.';
		}
		
		$reponse->closeCursor();

		return $album;
	}
        
    public function delete($id)
    {
		$reponse = $this->connexion->prepare('DELETE FROM galerie_albums WHERE id = :id');
        $reponse->execute(array(
            'id' => (int) $id,
            ));
        $reponse->closeCursor();
	
		$reponse = $this->connexion->prepare('SELECT image, miniature FROM galerie_photos WHERE id_album = :id'); //selection des images qui sont dans l'album
		$reponse->execute(array('id' => (int) $id));
		$donnees = $reponse->fetch(); 
		if($donnees)
		{
			$image=$donnees['image'];
			$miniature=$donnees['miniature'];
			unlink(''.$image.''); //supprime les fichiers images
			unlink(''.$miniature.'');
	
			$reponse = $this->connexion->prepare('DELETE FROM galerie_photos WHERE id_album=:id');  // Supprime les entrées liées aux images de l'album
			$reponse->execute(array(
				'id' => (int) $id,
				));
		}
			
		$this->confirmation = 'L\'album a bien été supprimé';
	}
               
    public function modifier(MGAlbum $album) // Lorsqu'on envois un album modifié
	{
		$reponse = $this->connexion->prepare('UPDATE galerie_albums SET titre = :titre, description = :description WHERE id = :id'); 
		$reponse->execute(array(
			'titre' => bdd($album->getTitre()),
			'description' => bdd($album->getDescription()),
			'id' => bdd($album->getId()),
			));
		
		$reponse->closeCursor();
		
		$this->confirmation = 'L\'album a bien été modifié<br />';
	}
	
	public function getListe()
    {
		$reponse = $this->connexion->query('SELECT titre, description, id_auteur, id, date FROM galerie_albums ORDER BY id');
			
			
		$nb_albums=0;
		while ($donnees = $reponse->fetch()) 
		{
			$membre= $this->connexion->prepare('SELECT pseudo FROM membre WHERE id= :id_membre');
			$membre->execute(array(
				'id_membre' => $donnees['id_auteur'],
				));
			$pseudo = $membre->fetch();
				
			$img_album = $this->connexion->prepare('SELECT photo FROM galerie_photos WHERE id_album = :id LIMIT 0, 1');
			$img_album->execute(array('id' => $donnees['id']));
			$donnees_img = $img_album->fetch();
				
			$this->album[] = new MGAlbum(array(
				'auteur' => $pseudo['pseudo'],
				'titre' => $donnees['titre'],
				'description' => $donnees['description'],
				'date' => $donnees['date'],
				'miniature' => $donnees_img['photo'],
				'id' => $donnees['id'],
				));
				
		}
			
		$reponse->closeCursor();	
			
		return $this->album;
	}  
	
		
	public function getConfirmation()
	{
		return $this->confirmation;
	}
		
	public function getError()
	{
		return $this->error;
	}
		
	public function setConfirmation($message)
	{
		$this->confirmation=$message;
	}

	public function setError($message)
	{
		$this->error=$message;
	}
}
?>