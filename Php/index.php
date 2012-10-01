<?php

header ('Content-Type:text/html; charset=UTF-8');

require ('../Inc/accueil.inc.php');

$EX = isset($_REQUEST['EX']) ? $_REQUEST['EX'] : 'home';

session_start();

//Variables
$page['error']='';
$page['confirmation']='';

// Contr�leur
switch ($EX)
{
  case 'home'   : viewAccueil ();   break;
  case 'SendStatut' : sendStatut(); exit;
  case 'SendCommentaire' : sendCommentaire(); exit;
  case 'AllCommentaires' : allCommentaires(); exit; //Affichage de tous les commentaires d'un statut
  case 'SendInscription' : sendInscription(); break;
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


function viewAccueil()
{
    global $page;
	$Manage = new MAStatutManage();
  	$liste = $Manage->getListe();
  	
    $page['title'] = 'Accueil';
    $page['class'] = 'VAccueil';
    $page['method'] = 'ShowListeStatuts';
    $page['arg'] = $liste;
    
}

function sendStatut()
{
  $statut = new MAStatut(array(
    'auteur' => 50,
    'contenu' => $_POST['message'],
    'date' => time(),
    ));
  
  $Manage = new MAStatutManage();
  $Manage->save($statut);
  
  global $page;
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  //Pour la valeur de retour AJAX
  if($page['error']=='')
  {
  	$tab['reponse']='ok';
  }
  else
  {
  	$tab['reponse']=$page['error'];
  }
  
  echo json_encode($tab);
  
}

function sendCommentaire()
{
  $commentaire = new MACommentaire(array(
    'auteur' => 4,
    'contenu' => $_POST['contenu'],
  	'idStatut' => $_POST['idStatut'],
    'date' => time(),
    ));
  
  $Manage = new MACommentaireManage();
  $Manage->save($commentaire);
  
  global $page;
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  //Pour la valeur de retour AJAX
  if($page['error']=='')
  {
  	$tab['reponse']='ok';
  }
  else
  {
  	$tab['reponse']=$page['error'];
  }
  
  echo json_encode($tab);
  
}

function allCommentaires()
{
	
  $Manage = new MACommentaireManage();
  $allComment = $Manage->getListe($_POST['idStatut']);
  
  global $page;
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  //Pour la valeur de retour AJAX
  if($page['error']=='')
  {
  	$tab['reponse']='ok';
  	foreach($allComment as $com)
  	{
  		$tab['comments'][] = '<p>'.affichage($com->getContenu()).' par '.affichage($com->getAuteur()).' le '.affichage($com->getDate()).'</p>';
  	}
  }
  else
  {
  	$tab['reponse']=$page['error'];
  }
  
  echo json_encode($tab);
  
}


?>