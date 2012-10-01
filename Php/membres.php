<?php

header ('Content-Type:text/html; charset=UTF-8');

require ('../Inc/membres.inc.php');

$EX = isset($_REQUEST['EX']) ? $_REQUEST['EX'] : 'home';

session_start();

//Variables
$page['error']='';
$page['confirmation']='';

// Contr�leur
switch ($EX)
{
  case 'home'   : viewInscription ();   break;
  case 'SendConnexion' : LOG_IN(); break;
  case 'SendInscription' : sendInscription(); break;
  case 'viewProfil' : viewProfil(); break;
  case 'updateProfil' : updateProfil(); break;
  case 'SendUpdateProfil' : sendUpdateProfil(); break;
}

/**
 * R�cup�ration de la mise en page
 */
require ('../View/main.view.php');

/********* Fonctions de contr�le *********/

/**
 * Affiche le formulaire et le tableau
 * 
 * @return none
 */


function viewInscription()
{
  if(!isset($_SESSION['id']) && empty($_SESSION['pseudo']))
  {
    global $page;

    $page['title'] = 'Inscription';
    $page['class'] = 'VMembres';
    $page['method'] = 'ShowFormInscription';
  }
  else
  {
    header('location: index.php');
  }
}

function LOG_IN()
{
  $Manage = new MMMembreManage();
  $test=$Manage->connexion();
  if($test)
  {
  	header('Location:galerie.php');
  }
  else
  {
  	echo 'tamere'.$Manage->getError();
  }
}

function sendInscription()
{
  $membre = new MMMembre(array(
    'pseudo' => $_POST['pseudo'],
    'password' => $_POST['mdp'],
    'nom' => $_POST['nom'],
    'prenom' => $_POST['prenom'],
    'email' => $_POST['email'],
    'etat' => 0,
    'groupe' => 'membre',
    'dateInsc' => time(),
    ));
  
  $Manage = new MMMembreManage();
  $Manage->save($membre);
  
  global $page;
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  $page['arg']=$membre; //va permettre d'afficher ce qu'avait entr� le membre si il y a une �rreur
  
  viewInscription();
  
}

function viewProfil()
{
    global $page;
  
  	$ManageProfil = new MMMembreManage();
  	$infos_profil = $ManageProfil->selectMembre($_SESSION['id']); //pour récupérer toutes les informations du membre
  	
  	$ManageStatut = new MAStatutManage();
  	$statuts = $ManageStatut->getListe($_SESSION['id']);
  	
  	$profil['infos'] = $infos_profil;
  	$profil['statuts']= $statuts;
  	
  	$page['title'] = 'Mon profil';
    $page['class'] = 'VProfil';
    $page['method'] = 'ShowProfil';
    $page['arg']=$profil;
}

function updateProfil()
{
    global $page;
  
  	$ManageProfil = new MMMembreManage();
  	$infos_profil = $ManageProfil->selectMembre($_SESSION['id']); //pour récupérer toutes les informations du membre
	$photos_profil = $ManageProfil->getListePhotoProfil($_SESSION['id']); //récupération de toutes les photos du profil
  	
	$tab['infos'] = $infos_profil;
	$tab['photos'] = $photos_profil;
	
  	$page['title'] = 'Modifier mon profil';
    $page['class'] = 'VProfil';
    $page['method'] = 'UpdateProfil';
    $page['arg']=$tab;
}

function sendUpdateProfil()
{
	global $page;
	
	
	$ManageMembre = new MMMembreManage();
	$membre = $ManageMembre->selectMembre($_SESSION['id']); //récupération du profil du membre actuel
	$membre->setNom($_POST['nom']);
	$membre->setPrenom($_POST['prenom']);
	$membre->setPseudo($_POST['pseudo']);
	if(isset($_FILES['photo_upload']))
	{
		echo 'ok';
		$nom = $ManageMembre->addPhotoProfil();
		$photo = $membre->setPhoto($nom);
	}
	else if(isset($_POST['photo_exist']))
		$photo = $membre->setPhoto($_POST['photo_exist']);
		
	$membre->setEmail($_POST['email']);
	$membre->setDateNaiss($_POST['jour_naiss_membre'].'-'.$_POST['mois_naiss_membre'].'-'.$_POST['annee_naiss_membre']);
	/*if(!empty($_POST['password_actuel']) || !empty($_POST['password_new']) || !empty($_POST['password_new_verif']))
	{
		if($ManageMembre->verifPassword($_POST['password_actuel'], $_POST['password_new'], $_POST['password_new_verif']))
			$membre->setPassword($_POST['password_new']);
	}*/
	
	var_dump($_FILES);
	$ManageMembre->save($membre);
	
	$page['error']= $ManageMembre->getError();
  	$page['confirmation']= $ManageMembre->getConfirmation();
  	
	if($page['error'] == '')
		viewProfil();
	else 
		updateProfil();
}

?>