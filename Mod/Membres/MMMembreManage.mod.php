<?php
class MMMembreManage extends Connexion
{
	
	private $confirmation;
	private $error;
	
	private $album = array();
	
	
	public function __construct()
    {
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
	
	
	public function save(MMMembre $membre)
    {

    	if ($membre->isValide() === true) //si les principales infos du formulaire sont valides
    	{
        	if($membre->isNew())
			{
				if($this->pseudoExiste($membre->getPseudo()))
				{
					$this->add($membre);
				}
				else
				{
					$this->error = 'Ce pseudo existe déjà.';
				}
				
			}
			else
			{
				$this->update($membre);
			}
			
        }
        else
        {
				$this->error=$membre->isValide();
        }
    }
    
    public function pseudoExiste($pseudo)
    {
    	$req = $this->connexion->query('SELECT id FROM membres WHERE pseudo=\''. bdd($pseudo) . '\'');
	$existe = $req->rowcount();
	if($existe<1)
	{
		return true;  
	}
	else
	{
		return false;
	}
    }

        
    public function add(MMMembre $membre)
    {

        $reponse = $this->connexion->prepare('INSERT INTO membres(pseudo, password, nom, prenom, email, etat, groupe, dateInsc) VALUES(:pseudo, :password, :nom, :prenom, :email, :etat, :groupe, :dateInsc)');
		$reponse->execute(array(
			'pseudo' => bdd($membre->getPseudo()),
			'password' => md5($membre->getPassword()),
			'nom' => bdd($membre->getNom()),
			'prenom' => bdd($membre->getPrenom()),
			'email' => bdd($membre->getEmail()),
			'etat' => bdd($membre->getEtat()),
			'groupe' => bdd($membre->getGroupe()),
			'dateInsc' => bdd($membre->getDateInsc()),
			));
		
		$this->confirmation = 'Votre inscription a bien été prise en compte.';
    }
    
	public function update(MMMembre $membre)
    {

        $reponse = $this->connexion->prepare('UPDATE membres SET pseudo= :pseudo, password = :password, nom = :nom, prenom = :prenom, email = :email, photo = :photo, dateNaiss = :dateNaiss WHERE id = :idMembre');
		$reponse->execute(array(
			'pseudo' => bdd($membre->getPseudo()),
			'password' => md5($membre->getPassword()),
			'nom' => bdd($membre->getNom()),
			'prenom' => bdd($membre->getPrenom()),
			'email' => bdd($membre->getEmail()),
			'photo' => bdd($membre->getPhoto()),
			'dateNaiss' => bdd($membre->getDateNaiss()),
			'idMembre' => bdd($membre->getId())
			));
		
		$this->confirmation = 'Les informations du profil ont été modifiées.';
    }
    
    public function connexion()
    {
		$pseudo = bdd($_POST['CPseudo']);
		$mdp = bdd($_POST['CPassword']);
		$mdp_md5 = md5($mdp);	// on hache le mdp par sécurité
	
		$req = $this->connexion->prepare('SELECT password, pseudo, etat, groupe, id, photo FROM membres WHERE pseudo=:pseudo');
		$req->execute(array(
			'pseudo' => $pseudo,
		));
		$donnees = $req->fetch();
		if($donnees['password'] == $mdp_md5 AND $donnees['etat']==1)
		{
			$_SESSION['pseudo']=$donnees['pseudo']; // on créé la session
			$_SESSION['groupe']=$donnees['groupe'];
			$_SESSION['id']=$donnees['id'];
			$_SESSION['photo']=$donnees['photo'];
			
			
			if($_POST['connect_auto']==true)
			{
				setcookie('idUser',''. $donnees['id'] .'', time() + 62*24*3600, null, null, false, true);
			}
				
			return true;
		}
		else
		{
			$this->error='Erreur de connexion.';
		}
        
    }
    
	public function connexionAuto($idMembre)
    {
		//Connexion grace au cookie de connexion automatique
		$req = $this->connexion->prepare('SELECT password, pseudo, etat, groupe, id, photo FROM membres WHERE id=:id');
		$req->execute(array(
			'id' => $idMembre,
		));
		$donnees = $req->fetch();
		if($donnees['pseudo'] != '')
		{
			$_SESSION['pseudo']=$donnees['pseudo']; // on créé la session
			$_SESSION['groupe']=$donnees['groupe'];
			$_SESSION['id']=$donnees['id'];
			$_SESSION['photo']=$donnees['photo'];
			
			
			if($_POST['connect_auto']==true)
			{
				setcookie('idUser',''. $donnees['id'] .'', time() + 62*24*3600, null, null, false, true);
			}
				
			return true;
		}
		else
		{
			$this->error='Erreur de connexion.';
		}
        
    }
    
    public function selectMembre($idMembre)
    {
    	$req = $this->connexion->prepare('SELECT nom, prenom, pseudo, photo, dateNaiss, email, password, etat, groupe, dateInsc FROM membres WHERE id=:id');
		$req->execute(array(
			'id' => $idMembre,
		));
		$donnees = $req->fetch();
		$membre = '';
		if($donnees['pseudo'] != '')
		{
			
			$membre = new MMMembre(array(
                'id' => $idMembre,
                'nom' => $donnees['nom'],
                'prenom' => $donnees['prenom'],
                'pseudo' => $donnees['pseudo'],
                'photo' => $donnees['photo'],
				'dateNaiss' => $donnees['dateNaiss'],
				'email' => $donnees['email'],
				'password' => $donnees['password'],
				'etat' => $donnees['etat'],
				'groupe' => $donnees['groupe'],
				'dateInsc' => $donnees['dateInsc']
                ));
		}
		else
		{
			$this->error='Erreur inconnue.';
		}
		
		return $membre;
    }

    
    public function deconnexion()
    {
		session_unset();
		session_destroy();
	  
		setcookie('id', NULL, -1);
    }
    
    public function getListePseudo()
    {
		$req = $this->connexion->prepare('SELECT pseudo, id FROM membres');
		$req->execute();
		
		$i=0;
		while($donnees = $req->fetch())
		{
			$membres[$i]['pseudo']=$donnees['pseudo'];
			$membres[$i]['id']=$donnees['id'];
			$i++;
		}
		
		return $membres;
    }
    
	public function verifPassword($password_actuel = '', $password, $password_verif)
	{
		if($password_actuel != '')
		{
			$req = $this->connexion->prepare('SELECT password FROM membres WHERE id=:id');
			$req->execute(array(
				'id' => $idMembre,
			));
			$donnees = $req->fetch();
			if(md5($password_actuel) == md5($donnees['password']))
			{
				if($password == $password_verif)
				{
					return true;
				}
				else
				{
					$this->error='Les deux mots de passe ne sont pas identiques.';
				}
			}
			else
			{
				$this->error='Le mot de passe actuel n\'est pas correct.';
			}
		}
		else
		{
			if($password == $password_verif)
			{
				return true;
			}
			else
			{
				$this->error='Les deux mots de passe ne sont pas identiques.';
			}
		}
	}
	
	public function getListePhotoProfil($idMembre)
	{
		$req = $this->connexion->prepare('SELECT photo FROM membres_photo_profil WHERE idMembre=:id');
		$req->execute(array(
			'id' => $idMembre,
		));
		$tab = array();
		while($donnees = $req->fetch())
		{
			$tab[] = $donnees['photo'];
		}

		return $tab;
	}
	
	public function addPhotoProfil()
	{
		if($_FILES['photo_upload']['error'] == 0) 
		{
			if($_FILES['photo_upload']['size'] < 3145728 )
			{
				$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' ); // on liste les extensions valides dans un tableau
	
				$extension_upload = strtolower(  substr(  strrchr($_FILES['photo_upload']['name'], '.')  ,1)  );
				if( in_array($extension_upload,$extensions_valides) ) 
				{
					$nom_fichier = valideChaine($_FILES['photo_upload']['name']);
					$chemin = '../Upload/Profil/' . $nom_fichier; 
					$resultat = move_uploaded_file($_FILES['photo_upload']['tmp_name'],$chemin);
					if ($resultat)
					{		
									//redimenssioner l'image pour l'adapter au site.	
						$source= '../Upload/Profil/';
						fctredimimage(700,700,$source,$nom_fichier,$source,$nom_fichier); //fonction de redimenssionement
						
						$reponse = $this->connexion->prepare('INSERT INTO membres_photo_profil(photo, idMembre, date) VALUES(:photo, :idMembre, NOW())');
						$reponse->execute(array(
							'photo' => bdd($chemin),
							'idMembre' => $_SESSION['id'],
							));			
						return $chemin;
					}
				}
				else
				{
					$this->error = 'L\'extension de la photo n\'est pas valide.';
				}
			}
			else
			{
				$this->error = 'La photo est trop lourde.';
			}
		}
		else
		{
			$this->error = 'Erreur lors de l\'upload de la photo.';
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