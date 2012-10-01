<?php


class MMMembre
{
	private $id;
        private $pseudo;
        private $password;
        private $photo;
        private $groupe;
        private $etat;
	private $nom;
	private $prenom;
	private $email;
	private $dateNaiss;
	private $dateInsc;
	
	
    public function __construct($valeurs = array())
    {
		if (!empty($valeurs)) // Si on a spécifié des valeurs, alors on hydrate l'objet
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
    
    public function isValide() //return TRUE ou un tableau error[]
    {
    							
    	if(!empty($this->pseudo) AND !empty($this->password) AND !empty($this->email))												//champ remplis ???
		{
			if(strlen($this->pseudo)>3 AND strlen($this->pseudo)<15) 													//pseudo de taille corecte ???
			{
				if(preg_match("#[A-Za-z0-9_-]#",$this->pseudo))													//caractère pseudo ok ???
				{
					if(strlen($this->pseudo)>=6)																	// confirmation du mdp ok ???
					{
						if(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#",$this->email))			// caractère email ok ???
						{
	
							return true;				// appel de la fonction ajout membre	
						}
						else
						{
							$error= 'l\'email est incorecte.';
						}
					}
					else
					{
						$error = 'Le mot de passe doit faire au mois 6 caractères.';
					}
				}
				else
				{
					$error = 'Le pseudo contient des caractères interdit.<br/> Seuls ces caractères sont autorisés : A-Z, a-z, 0-9, -, _.';
				}
			}
			else
			{
				$error = 'La taille du pseudo doit être compris entre 4 et 15 caractères.';
			}
		}
		else
		{
			$error = 'Vous devez remplir tout les champs.';
		}
	
		return $error;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getPseudo()
    {
    	return $this->pseudo;
    }
    
    public function getPassword()
    {
    	return $this->password;
    }
    
    public function getNom()
    {
    	return $this->nom;
    }
    
    public function getPrenom()
    {
    	return $this->prenom;
    }
    
    public function getEmail()
    {
    	return $this->email;
    }
    
    public function getPhoto()
    {
    	return $this->photo;
    }
    
    public function getGroupe()
    {
    	return $this->groupe;
    }
    
    public function getEtat()
    {
    	return $this->etat;
    }
    
    public function getDateNaiss()
    {
    	return $this->dateNaiss;
    }
    
    public function getDateInsc()
    {
    	return $this->dateInsc;
    }
    
    
    public function setId($id)
    {
        $this->id = (int) $id;
    }
    
    public function setPseudo($p)
    {
        $this->pseudo = $p;
    }
    
    public function setPassword($pwd)
    {
        $this->password = $pwd;
    }
    
    public function setNom($n)
    {
        $this->nom = $n;
    }
    
    public function setPrenom($n)
    {
        $this->prenom = $n;
    }
    
    public function setEmail($e)
    {
        $this->email = $e;
    }
    
    public function setPhoto($e)
    {
        $this->photo = $e;
    }
    
    public function setGroupe($e)
    {
        $this->groupe = $e;
    }
    
    public function setEtat($e)
    {
        $this->etat = $e;
    }
    
    public function setDateNaiss($e)
    {
        $this->dateNaiss = $e;
    }
    
    public function setDateInsc($e)
    {
        $this->dateInsc = $e;
    }
    
        
        
    
}
?>
    