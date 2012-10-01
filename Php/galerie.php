<?php

header ('Content-Type:text/html; charset=UTF-8');

require ('../Inc/galerie.inc.php');

$EX = isset($_REQUEST['EX']) ? $_REQUEST['EX'] : 'home';

session_start();

//Variables
$page['error']='';
$page['confirmation']='';

// Contrôleur
switch ($EX)
{
  case 'home'   : listeAlbum ();   break;
  case 'SendAddAlbum' : addAlbum(); break;
  case 'SendUpdateAlbum' : updateAlbum(); break;
  case 'SendDeleteAlbum' : deleteAlbum(); break;
  case 'ShowListePhoto' : listePhoto(); break;
  case 'SendAddPhoto' : addPhoto(); break;
  case 'ShowPhoto' : showPhoto(); break;
  case 'SendUpdatePhoto' : updatePhoto(); break;
  case 'SendAvisPhoto' : addAvisPhoto(); break;
  case 'SendCommentairePhoto' : addCommentaire(); break;
  case 'SendDeleteCommentaire' : deleteCommentaire(); break;
  case 'SendDeletePhoto' : deletePhoto(); break;
}

/**
 * Récupération de la mise en page
 */
require ('../View/main.view.php');

/********* Fonctions de contrôle *********/



/**
 * Affiche le formulaire et le tableau
 * 
 * @return none
 */
function listeAlbum ()
{
  $Manage = new MGAlbumManage();
  $liste = $Manage->getListe();
 
  global $page;

  $page['title'] = 'Liste Album';
  $page['class'] = 'VAlbum';
  $page['method'] = 'ShowList';
  $page['arg'] = $liste;

} // home ()

function addAlbum()
{
  $Manage = new MGAlbumManage();
  
  $album = new MGAlbum(array(
      'titre' => bdd($_POST['titre']),
      'description' => bdd($_POST['description']),
      'auteur' => bdd($_SESSION['id']),
      'date' => time(),
      ));
  
  $Manage->save($album);
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  listeAlbum();
}

function updateAlbum()
{
  $Manage = new MGAlbumManage();
  
  $album = new MGAlbum(array(
      'titre' => bdd($_POST['titre']),
      'description' => bdd($_POST['description']),
      'id' => bdd($_POST['idAlbum']),
      ));
  $Manage->save($album);
  
  global $page;
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  listeAlbum();
}

function deleteAlbum()
{
  $Manage = new MGAlbumManage();
  $Manage->delete($_GET['id']);
  
  global $page;
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  listeAlbum();
  
}

function listePhoto()
{
  
  $Manage = new MGPhotoManage();
  
  $nb_par_page=25;
  
  if(isset($_GET['page'])) 
  {
    if(ctype_digit($_GET['page'])) // L'id est un entier ?
    {
      $num_page = $_GET['page']; // intval met en entier le nbr, sécurité supplementaire
    }
    else
    {
      $page['error']=  'Erreur : La page n\'existe pas.';
    }
  }
  else
  {
    $num_page = 1;
    $_GET['page']=1;
  }
  $premier = ($num_page - 1) * $nb_par_page;	
	
  $liste = $Manage->getListe($_GET['album'], $premier, $nb_par_page);
  
  global $page;

  $page['title'] = 'Liste Photo';
  $page['class'] = 'VPhoto';
  $page['method'] = 'ShowList';
  $page['arg'] = $liste;
}

function addPhoto()
{
  $Manage = new MGPhotoManage();
  
  $Manage->verifEtAjoutPhoto();
  
  global $page;
  
  $page['error']=$Manage->getError();
  
  listePhoto();
}

function showPhoto()
{
  $Manage = new MGPhotoManage();
  $args['photo'] = $Manage->getUnique($_GET['id']);
  
  $args['precedente'] = $Manage->image_precedente();
  $args['suivante'] = $Manage->image_suivante();
  
  $ManageAime = new MGPhotoAvis($_GET['id']);
  $args['aime']['combienAime']=$ManageAime->combienDePersonneAime();
  $args['aime']['nomsAime']=$ManageAime->nomsPersonnesAime();
  $args['aime']['combienAimePas']=$ManageAime->combienDePersonneAimePas();
  $args['aime']['nomsAimePas']=$ManageAime->nomsPersonnesAimePas();
  $args['aime']['dejaNote']=$ManageAime->verifDeja_note(); //la personne a déjà noté cette photo ?
  
  $ManageCommentaire = new MGCommentaireManage();
  $args['commentaire']=$ManageCommentaire->getListe($_GET['id']);
  
  global $page;

  $page['title'] = 'Photo';
  $page['class'] = 'VPhoto';
  $page['method'] = 'ShowPhoto';
  $page['arg'] = $args;
}

function updatePhoto() //AJAX
{
  $Manage = new MGPhotoManage();
  $photo = new MGPhoto(array(
    'titre' => $_POST['titre'],
    'id' => (int) $_POST['id'],
  ));
  $Manage->save($photo);
}

function addAvisPhoto() //AJAX
{
  $Manage = new MGPhotoAvis();
  $Manage->addAvis();
}

function addCommentaire()
{
  $Manage = new MGCommentaireManage();
  $commentaire = new MGCommentaire(array(
    'commentaire' => $_POST['commentaire_photo'],
    'date' => time(),
    'auteur' => $_SESSION['id'],
    'id_photo' => $_GET['id'],
    ));
  
  $Manage->save($commentaire);
  
  global $page;
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  showPhoto();
}

function deleteCommentaire() //AJAX
{
  $Manage = new MGCommentaireManage();
  $Manage->delete($_POST['idCommentaire'], $_SESSION['id']);

}

function deletePhoto()
{
  $Manage = new MGPhotoManage();
  $Manage->delete($_GET['id'], $_SESSION['id']);
  
  global $page;
  
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  listePhoto();
}


?>