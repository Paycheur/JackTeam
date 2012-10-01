<?php
class MGPhotoManage extends Connexion
{
	
	private $error;
	private $confirmation;
	
	private $photos = array();
	
	public function __construct()
	{
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
	}
	
	public function save(MGPhoto $photo) //sauvegarder la photo : nouvelle ou modifié ? valide ou pas ?
    {
        if ($photo->isValide()=='ok')
    	{
        	$photo->isNew() ? $this->add($photo) : $this->modifier($photo);
        }
        else
        {
            $this->error=$photo->isValide();
        }
    }
    
    public function photoExiste($id_photo, $id_album) //la photo existe ?
    {
    	$reponse = $this->connexion->prepare('SELECT id FROM galerie_photos WHERE id = :id AND id_album= :id_album');
		$reponse->execute(array(
			'id' => $id_photo,
			'id_album' => $id_album,
			));
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
    
    public function add(MGPhoto $photo) //ajouter une photo
    {
        $reponse = $this->connexion->prepare('INSERT INTO galerie_photos(titre, description, photo, date, miniature, id_album, id_auteur) VALUES(:titre, :description, :photo, :date, :miniature, :id_album, :id_auteur)');
		$reponse->execute(array(
			'titre' => bdd($photo->getTitre()),
			'description' => bdd($photo->getDescription()),
			'photo' => bdd($photo->getPhotoUrl()),
			'miniature' => bdd($photo->getMiniature()),
			'id_album' => bdd($photo->getAlbum()),
			'id_auteur' => bdd($photo->getAuteur()),
			'date' => time(),
			));
		 $reponse->closeCursor();
		 
		$this->confirmation = 'La photo a bien été envoyée<br />';
    }
    
    public function modifier(MGPhoto $photo) // sauvegarde d'une photo modifié
	{
		
		$reponse = $this->connexion->prepare('UPDATE galerie_photos SET titre = :titre WHERE id = :id'); 
		$reponse->execute(array(
			'titre' => bdd($photo->getTitre()),
			'id' => bdd($photo->getId()),
			));
		
		$reponse->closeCursor();
		
		$this->confirmation = 'La photo a bien été modifiée<br />';
	}
    
    public function getUnique($id) //on récupère une photo
    {
    	$reponse = $this->connexion->prepare('SELECT titre, description, id_auteur, id, date, photo, miniature FROM galerie_photos WHERE id = :id');
		$reponse->execute(array('id' => $id));
		$donnees = $reponse->fetch(); 
			
		$reponse_membre = $this->connexion->prepare('SELECT pseudo FROM membre WHERE id= :id_membre'); //on récupère le membre selon l'id_membre
		$reponse_membre->execute(array(
			'id_membre' => $donnees['id_auteur'],
			));
		$donnees_membre = $reponse_membre->fetch();
		
		if($donnees)
		{	
			
			$photo = new MGPhoto(array(
                'id' => $donnees['id'],
                'auteur' => affichage($donnees_membre['pseudo']),
                'titre' => affichage($donnees['titre']),
                'description' => affichage($donnees['description']),
                'date' => $donnees['date'],
                'photoUrl' => $donnees['photo'],
                'miniature' => $donnees['miniature'],
                ));
		}
		else
		{
			$this->error = 'La photo n\'existe pas';
		}
		
		$reponse->closeCursor();

		return $photo;
	}
    
	public function tailleValide($taille)
    {
    	if($taille < 3145728) 
		{
			return true;
		}
		else
		{
			$this->error = 'La taille de la photo n\'est pas valide. 3mo max.';
			return false;
		}
    }
    
    public function extensionValide($fichier)
    {
    	$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // on liste les extensions valides dans un tableau

		$extension_upload = strtolower(  substr(  strrchr($_FILES[$fichier]['name'], '.')  ,1)  );
		if( in_array($extension_upload,$extensions_valides) ) 
		{
			return true;
		}
		else
		{
			$this->error = 'L\'extension de la photo n\'est pas valide.';
			return false;
			
		}
    }
    
    public function upload_redim($photo, $nom_image) //redimenssionnement de la photo
    {

		$chemin = '../Upload/Galerie/' . $nom_image; 
		$resultat = move_uploaded_file($_FILES[$photo]['tmp_name'],$chemin);
		if ($resultat)
		{		
						//redimenssioner l'image pour l'adapter au site.	
			$source= '../Upload/Galerie/';
			fctredimimage(700,700,$source,$nom_image,$source,$nom_image); //fonction de redimenssionement
						
			$source_mini= '../Upload/Galerie/Mini/'; //création de la miniature
			fctredimimage(150,150,$source_mini,$nom_image,$source,$nom_image); 
						
			return true;
		}
		else
		{	
			$this->error = "Transfert Echoué";
			return false;
		}
    }
    
    public function image_suivante() //fonction pour avoir l'image suivante de celle qu'on a selectionné
	{
	
	if(ctype_digit($_GET['id'])) // L'id est un entier ?
	{
		$id= $_GET['id']+1;
	}
	else
	{
		$this->error= 'L\'image choisie n\'existe pas';
	}

	$reponse = $this->connexion->prepare('SELECT MAX(id) AS id_max FROM galerie_photos WHERE id_album = :id_album');
	$reponse->execute(array('id_album' => $_GET['album']));
	$donnees = $reponse->fetch(); 
	$id_max=$donnees['id_max'];
	
	$reponse->closeCursor();
	
	$findeboucle=false;
	while($findeboucle==false)
	{

		$reponse = $this->connexion->prepare('SELECT titre, photo, description, date, id FROM galerie_photos WHERE id = :id AND id_album = :id_album');
		$reponse->execute(array('id' => $id, 'id_album' => $_GET['album']));
		$donnees = $reponse->fetch(); 
		if($donnees==true)
		{
			$findeboucle=true;
			$lien='?EX=ShowPhoto&album='.$_GET['album'].'&id='.$id;
		}
		else if(($id-1)==$id_max)
		{
			$findeboucle=true;
		}
		else
		{
			$id++;
		}
	}
	$reponse->closeCursor();
	return $lien;
	}

public function image_precedente()//fonction pour avoir l'image précédente de celle qu'on a selectionné
{
	if(ctype_digit($_GET['id'])) // L'id est un entier ?
	{
		$id= $_GET['id']-1;
	}
	else
	{
		$this->error= 'L\'image choisie n\'existe pas';
	}
	

	$reponse = $this->connexion->prepare('SELECT MIN(id) AS id_min FROM galerie_photos WHERE id_album = :id_album');
		$reponse->execute(array('id_album' => $_GET['album']));
	$donnees = $reponse->fetch(); 
	$id_min=$donnees['id_min'];
	
	$reponse->closeCursor();
	
	$findeboucle=false;
	while($findeboucle==false)
	{

		$reponse = $this->connexion->prepare('SELECT titre, photo, description, date, id FROM galerie_photos WHERE id = :id AND id_album = :id_album');
		$reponse->execute(array('id' => $id, 'id_album' => $_GET['album']));
		$donnees = $reponse->fetch(); 
		if($donnees==true)
		{
			$findeboucle=true;
			$lien='?EX=ShowPhoto&album='.$_GET['album'].'&id='.$id;
		}
		else if(($id+1)==$id_min)
		{
			$findeboucle=true;
		}
		else
		{
			$id=$id-1;
		}
	}
	$reponse->closeCursor();
	
	return $lien;
}

	public function delete($idPhoto, $idMembre) //supprimer une image
	{
		if(ctype_digit($idPhoto))
		{
			//$membre va permettre de verifier si le commentaire appartient bien au membre qui veut le supprimer.
			$membre = $this->connexion->prepare('SELECT id_auteur FROM galerie_photos WHERE id=:id');
			$membre->execute(array(
				'id' => bdd($idPhoto),
			));
		
			$donnees = $membre->fetch();
			if($idMembre == $donnees['id_auteur']) //le test pour voir si le membre est le bon.
			{
				//supprimer physiquement la photo
				$reponse = $this->connexion->prepare('SELECT photo, miniature FROM galerie_photos WHERE id= :id');
				$reponse->execute(array(
					'id' => bdd($idPhoto),
					));
				$donnees = $reponse->fetch(); 
			
				$image=$donnees['photo'];
				$miniature=$donnees['miniature'];
			
				unlink(''.$image.'');
				unlink(''.$miniature.'');
			
				$reponse->closeCursor();
				
				//supprimer les commentaires
				$reponse = $this->connexion->prepare('DELETE FROM galerie_photos_com WHERE id_photo= :id_photo');
				$reponse->execute(array(
					'id_photo' => bdd($idPhoto),
					));
					
				$reponse->closeCursor();
				
				//supprimer le champ de la photo
				$reponse = $this->connexion->prepare('DELETE FROM galerie_photos WHERE id= :id');
				$reponse->execute(array(
					'id' => bdd($idPhoto),
					));
			
				$reponse->closeCursor();
			
				$this->confirmation = 'La photo a bien été supprimée';
			}
			else
			{
				$this->error = 'Vous n\'avez pas le droit de supprimer cette photo.';
			}
		}
		else
		{
			$this->error = 'Cette photo n\'existe pas.';
		}
	}
    
    public function getListe($album, $premier, $nb_par_page)
    {
		$reponse = $this->connexion->prepare('SELECT miniature, photo, id FROM galerie_photos WHERE id_album=:id_album ORDER BY id LIMIT ' . $premier . ', ' . $nb_par_page . '');
		$reponse->execute(array(
			'id_album' => (int) $album,
			));			
			
		while ($donnees = $reponse->fetch()) 
		{
			
			$this->photos[] = new MGPhoto(array(
				'miniature' => $donnees['miniature'],
				'photoUrl' => $donnees['photo'],
				'id' => $donnees['id'],
				));
				
		}
			
		$reponse->closeCursor();	
			
		return $this->photos;
	}
	
	public function verifEtAjoutPhoto()
	{
		$nbr_fichier = $_POST['nbPhoto'];
		if($nbr_fichier>5) //sécurité
		{
			$nbr_fichier=0;
		}
		elseif($nbr_fichier<=0)
		{
			$nbr_fichier=0;
		}
		$x=1;
		while( $x <= $nbr_fichier )
		{
			$f='image_'.$x;
			if($_FILES[$f]['error'] == 0) 
			{
				if($this->tailleValide($_FILES[$f]['size']))
				{
					if($this->extensionValide($f))
					{
						$nom_fichier = valideChaine($_FILES[$f]['name']);
						if($this->upload_redim($f, $nom_fichier))
						{
							
		   					$photo_url = '../Upload/Galerie/' . $nom_fichier; //création des adresses
							$miniature = '../Upload/Galerie/Mini/' . $nom_fichier;
							$titre='titre_'.$x;
							$description='description_'.$x;
							$photo = new MGPhoto(array(
								'titre' => $_POST[$titre],
								'description' => $_POST[$description],
								'photoUrl' => $photo_url,
								'miniature' => $miniature,
								'album' => $_GET['album'],
								'auteur' => $_SESSION['id'],
								));
							if($photo->isValide())
							{
								$this->save($photo);
							}
							else
							{
								$this->setError('Un des champs est trop long <a href=\"javascript:history.back()\">Retour</a>');
							}
						}	
					}
				}
				
				
			}
			else
			{
			
				if($_FILES[$f]['error'] == UPLOAD_ERR_INI_SIZE)
				{
					$this->setError('Fichier dépassant la taille');
				}
				if($_FILES[$f]['error'] == UPLOAD_ERR_FORM_SIZE)
				{
					$this->setError('Fichier dépassant la taille max proposé par le formulaire');
				}
				if($_FILES[$f]['error'] == UPLOAD_ERR_PARTIAL)
				{
					$this->setError('Fichier transféré partiellement');
				}
				if($_FILES[$f]['error'] == UPLOAD_ERR_NO_FILE)
				{
					$this->setError('Fichier manquant <a href=\"javascript:history.back()\">Retour</a>');
				}
			}
			$x++;
		}
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