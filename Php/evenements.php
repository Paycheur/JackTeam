<?php

header ('Content-Type:text/html; charset=UTF-8');

require ('../Inc/evenements.inc.php');

$EX = isset($_REQUEST['EX']) ? $_REQUEST['EX'] : 'home';

session_start();

//Variables
$page['error']='';
$page['confirmation']='';

// Contrôleur
switch ($EX)
{
  case 'home'   : viewListEvent();   break;
  case 'ShowFormAddEvent' : viewFormAddEvent($_GET['type']); break;
  case 'SendAddEventAnniv' : addEventAnniv(); break;
  case 'ViewEvent' : viewEvent($_GET['idEvent'], $_GET['type']); break; //PAREIL QUE POUR SENDADDCADEAU
  case 'SendSondCadeau' : sendSondCadeau(); break;
  case 'SendValiderCadeau' : sendValiderCadeau(); break;
  case 'SendDevaliderCadeau' : sendDevaliderCadeau(); break;
  case 'SendParticipationEvent' : sendParticipationEvent(); break;
  case 'SendBlocResume' : sendBlocResume(); break;
  case 'SendAddCadeau' : sendAddCadeau(); break; //REVOIR CETTE FONCTION, METTRE LES CONDITIONS DANS LA PARTIE MOD
  case 'ViewFormUpdateEvent' : viewFormUpdateEvent($_GET['type'], $_GET['idEvent']); break;
  case 'SendUpdateEventAnniv' : sendUpdateEventAnniv($_GET['idEvent']); break;
  case 'SendDeleteCadeau' : sendDeleteCadeau($_GET['idCadeau'], $_GET['idEvent']); break;
  case 'SendDeleteEvent' : sendDeleteEvent($_GET['idEvent'], $_GET['type']); break; //FONCTION VALIDE POUR TOUS EVENEMENT
  case 'SendAddEventVacance' : addEventVacance(); break;
  case 'SendAddDates' : sendAddDates($_GET['idEvent']); break;
  case 'SendSondDatesV' : sendSondDatesV(); break;
  case 'SendUpdateDatesV' : sendUpdateDatesV(); break;
  case 'SendAddLieuV' : sendAddLieuV($_GET['idEvent']); break;
  case 'SendSondLieuV' : sendSondLieuV(); break;
  case 'SendUpdateLieuV' : sendUpdateLieuV(); break;
  case 'SendUpdateEventVacance' : sendUpdateEventVacance($_GET['idEvent']); break;
  case 'SendAddCommentaire' : addCommentaire($_GET['idEvent']); break;
  case 'SendDeleteCommentaire' : deleteCommentaire(); break;
  case 'SendAddEventSoirjour' : addEventSoirjour(); break;
  case 'SendAddLieuSJ' : sendAddLieuSJ($_GET['idEvent']); break;
  case 'SendSondLieuSJ' : sendSondLieuSJ(); break;
  case 'SendUpdateLieuSJ' : sendUpdateLieuSJ(); break;
  case 'SendUpdateEventSoirjour' : sendUpdateEventSoirjour($_GET['idEvent']); break;
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


function viewListEvent()
{
  $Manage = new MEEvenementManage();
  $events = $Manage->getListe();
  global $page;

  $page['title'] = 'Liste des évènements';
  $page['class'] = 'VEvenements';
  $page['method'] = 'ShowListEvent';
  $page['arg'] = $events;
}

function viewEvent($idEvent, $type)
{
  if($type=='1')
  {
    viewEventAnniv($idEvent);
  }
  elseif($type=='2')
  {
    viewEventVacance($idEvent); //FAIRE COMME CETTE FONCTION POUR LES AUTRES DU MEME TYPE
  }
  elseif($type=='3')
  {
    viewEventSoirjour($idEvent);
  }
}

function addCommentaire($idEvent)
{
  $Manage = new MECommentaireManage();
  $commentaire = new MECommentaire(array(
    'commentaire' => $_POST['commentaire_photo'],
    'date' => time(),
    'auteur' => $_SESSION['id'],
    'idEvent' => $idEvent,
    ));
  
  $Manage->save($commentaire);
  
  global $page;
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  viewEvent($idEvent, $_GET['type']);
}

function deleteCommentaire() //AJAX
{
  $Manage = new MECommentaireManage();
  $Manage->delete($_POST['idCommentaire'], $_SESSION['id']);

}

function viewFormAddEvent($type)
{
  if($type=='1')
  {
    $nomType="Anniv";
    $args['cal']= new Calendrier("formAddAnniv", "date");
    $Manage = new MMMembreManage();
    $args['membres'] = $Manage->getListePseudo();
  }
  elseif($type=="2")
  {
    $nomType="Vacance";
    $args['calDebut']= new Calendrier("formAddVacance", "dateDebut");
    $args['calFin']= new Calendrier("formAddVacance", "dateFin");
  }
  elseif($type=="3")
  {
    $nomType="Soirjour";
    $args['cal']= new Calendrier("formAddSoirjour", "date");
  }
  


  global $page;

  $page['title'] = 'Créer un évenement';
  $page['class'] = 'VEvenements';
  $page['method'] = 'ShowFormAdd'.$nomType;
  $page['arg'] = $args;
}

function viewFormUpdateEvent($type, $id)
{
  $args=null;
  if($type=='1')
  {
    $nomType='Anniv';
    $ManageEvent = new MEEvenementAnnivManage();
    
    if($ManageEvent->evenementExiste($id)) //vérification si l'évenement existe
    {
      if($ManageEvent->canSee($id)) //est-ce que le membre peut voir cet événement ?
      {
        $args['evenement'] = $ManageEvent->getUnique($id); //récupération d'un événement
        
        $args['cal']= new Calendrier("formAddAnniv", "date");
  
        $Manage = new MMMembreManage();
        $args['membres'] = $Manage->getListePseudo();
      }
    }
  }
  elseif($type=='2')
  {
    $nomType='Vacance';
    $ManageEvent = new MEEvenementVacanceManage();
    if($ManageEvent->evenementExiste($id))
    {
      $args['evenement'] = $ManageEvent->getUnique($id); //récupération d'un événement
      $args['calDebut']= new Calendrier("formAddVacance", "dateDebut");
      $args['calFin']= new Calendrier("formAddVacance", "dateFin");
    }
  }
  elseif($type=='3')
  {
    $nomType='Soirjour';
    $ManageEvent = new MEEvenementSoirjourManage();
    if($ManageEvent->evenementExiste($id))
    {
      $args['evenement'] = $ManageEvent->getUnique($id); //récupération d'un événement
      $args['cal']= new Calendrier("formAddSoirjour", "date");
    }
  }
  
  global $page;
  if($args!=null)
  {
    
    $page['title'] = 'Modifier un évenement';
    $page['class'] = 'VEvenements';
    $page['method'] = 'ShowFormAdd'.$nomType;
    $page['arg'] = $args;
  }
  else
  {
    $page['error'] = $ManageEvent->getError();
    viewListEvent();
  }
  
  
}

function addEventAnniv()
{
  $evenement = new MEEvenementAnniv($_POST ['titre'], $_POST ['presentation'], $_SESSION ['id'], $_POST ['date'], time (), $_POST ['membre_anniv'], 0, 0, 0);
  
  $Manage = new MEEvenementAnnivManage();
  $Manage->save($evenement);
  
  global $page;
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  viewListEvent();
}

function viewEventAnniv($id) //$id => $idEvent
{
  $ManageEvent = new MEEvenementAnnivManage();
  
  global $page;
  
  if($ManageEvent->evenementExiste($id)) //vérification si l'évenement existe
  {
    if($ManageEvent->canSee($id)) //est-ce que le membre peut voir cet événement ?
    {
      $args['evenement'] = $ManageEvent->getUnique($id); //récupération d'un événement
  
      $ManageCadeaux = new MECadeauManage();
      $args['cadeauxNonVal']['tab'] = $ManageCadeaux->getListe($id, 0); //on récupère la liste des cadeaux non validés. C'est un tableau avec des objets de type MECadeau
      
      if($args['cadeauxNonVal']['tab']!=null)
      {
        foreach ( $args['cadeauxNonVal']['tab'] as $cadeau ) //récupération du sondage pour chaque cadeaux non validés
        {
          $args['cadeauxNonVal']['sond'][$cadeau->getId()] = new MECadeauSondage($cadeau->getId());
        }
      }
      
  
      $args['cadeauxVal'] = $ManageCadeaux->getListe($id, 1); //récupération de la liste des cadeaux validés
  
      //Récupération de la participation des membres
      $args['participation']['leMembre'] = $ManageEvent->verifParticipation($id);
      $args['participation']['non'] = $ManageEvent->nomsNonParticipants($id);
      $args['participation']['oui'] = $ManageEvent->nomsParticipants($id);
      $args['participation']['pe'] = $ManageEvent->nomsParticipantsPasSur($id);
      $args['participation']['nbOui'] = $ManageEvent->combienDeParticipants($id);
  
      //récupération commentaire
      $ManageCommentaire= new MECommentaireManage();
      $args['commentaires']=$ManageCommentaire->getListe($id);

      $page['title'] = 'Gestion anniversaire';
      $page['class'] = 'VEvenementsAnniv';
      $page['method'] = 'ShowEvent';
      $page['arg'] = $args;
    }
    else
    {
      $page['error']= $ManageEvent->getError();
      viewListEvent();
    }
    
  }
  else
  {
    $page['error']= $ManageEvent->getError();
    viewListEvent();
  }
  
}

function sendSondCadeau() //AJAX
{
  $Manage = new MECadeauSondage($_POST['id']); //constructeur : idCadeau
  $Manage->add($_POST['value'], $_SESSION['id']);
}

function sendValiderCadeau()
{
  $Manage = new MECadeauManage();
  $Manage->validerCadeau($_GET['idCad'], $_GET['idEvent']); //validation du cadeau selectionné
  
  global $page;
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  viewEventAnniv($_GET['idEvent']);
}

function sendDevaliderCadeau()
{
  $Manage = new MECadeauManage();
  $Manage->devaliderCadeau($_GET['idCad'], $_GET['idEvent']); //validation du cadeau selectionné
  
  global $page;
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  viewEventAnniv($_GET['idEvent']);
}

function sendParticipationEvent() //AJAX
{
  $Manage = new MEEvenementManage();
  $Manage->addParticipation($_POST['idEvent'], $_SESSION['id'], $_POST['value']);
}

function sendBlocResume()
{
  $Manage = new MEEvenementManage();
  $Manage->updateBlocResume($_POST['idEvent'], $_POST['content']);
}

function sendAddCadeau()
{
  $idEvent=$_GET['idEvent'];
  
  $ManageEvent = new MEEvenementAnnivManage();
  global $page;
  
  if($ManageEvent->evenementExiste($idEvent) AND $ManageEvent->isAnniv($idEvent) AND $ManageEvent->canSee($idEvent))
  {
    $ManageCadeau = new MECadeauManage();
    $cadeau = new MECadeau ( array ('titre' => $_POST ['titre'], 'description' => $_POST ['description'], 'prix' => $_POST ['prix'], 'id_auteur' => $_SESSION ['id'], 'id_event' => $_GET ['idEvent'] ) );
  
    $ManageCadeau->save ( $cadeau );
    
    $page['error']= $ManageCadeau->getError();
    $page['confirmation']= $ManageCadeau->getConfirmation();

    viewEventAnniv($idEvent);
  }
  else
  {
    $page['error']= $ManageEvent->getError();
    viewListEvent();
  }
  
}

function sendUpdateEventAnniv($idEvent)
{
  $ManageEvent = new MEEvenementAnnivManage();
  global $page;
  
  $evenement = new MEEvenementAnniv($_POST['titre'], $_POST['presentation'], null, $_POST['date'], null, $_POST['membre_anniv'], $_GET['idEvent'], null, null);
  $ManageEvent->save($evenement);
    
  $page['error']= $ManageEvent->getError();
  $page['confirmation']= $ManageEvent->getConfirmation();

  viewListEvent();
 
}

function sendDeleteCadeau($idCadeau, $idEvent)
{
  $ManageCadeau = new MECadeauManage();
  $ManageCadeau->delete($idCadeau);

  global $page;
  $page['error']= $ManageCadeau->getError();
  $page['confirmation']= $ManageCadeau->getConfirmation();
  viewEventAnniv($idEvent);
}

function sendDeleteEvent($idEvent, $type)
{
  global $page;
  if($type=="1") //delete ANNIV
  {
    $Manage= new MEEvenementAnnivManage();
    $Manage->delete($idEvent);
  }
  elseif($type=="2")
  {
    $Manage = new MEEvenementVacanceManage();
    $Manage->delete($idEvent);
  }

  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();

  viewListEvent(); 
  
}

//**********************************************************
//**************GESTION DES EVENEMENTS VACANCES*************
//**********************************************************

function addEventVacance()
{
  if($_POST['dateDefinie']=='oui') //si la date est définie
  {
    $dateDebut = $_POST['dateDebut']; //date de début (obligatoire)
    if($_POST['dateFin']=='') //si la date de fin n'est pas définie (car elle n'est pas obligatoire)
    {
      $dateFin='N/D'; 
    }
    else //si elle est définie....
    {
      $dateFin = $_POST['dateFin'];
    }
    
  }
  else //sinon c'est que les deux ne sont pas définie
  {
    $dateDebut = 'N/D';
    $dateFin = 'N/D';
  }
  if($_POST['lieuDefini']=='oui') //si le lieu est définie
  {
    $lieu = $_POST['lieu'];
  }
  else
  {
    $lieu= 'N/D';
  }
  
  $evenement = new MEEvenementVacance($_POST ['titre'], $_POST ['presentation'], $_SESSION ['id'], $dateDebut, time (), 0, 0, $_POST['prix'], $dateFin, $lieu);
  
  $Manage = new MEEvenementVacanceManage();
  $Manage->save($evenement);
  
  global $page;
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  viewListEvent();
}

function viewEventVacance($idEvent)
{
  $ManageEvent= new MEEvenementVacanceManage();
  global $page;
  
  if($ManageEvent->evenementExiste($idEvent)) //vérification si l'évenement existe
  {
    $args['evenement'] = $ManageEvent->getUnique($idEvent);
    
    $ManageDates = new MEDatesManage();
    $args['proposition']['dates']['tab']= $ManageDates->getListe($idEvent);
    
    if($args['proposition']['dates']['tab']!=null)
      {
        foreach ( $args['proposition']['dates']['tab'] as $dates ) 
        {
          $args['proposition']['dates']['sond'][$dates->getId()] = new MESondage('evenements_vacances_d_sond', 'idDates', $dates->getId());
        }
      }
      
    $ManageLieu = new MELieuManage("evenements_vacances_l");
    $args['proposition']['lieux']['tab']= $ManageLieu->getListe($idEvent);
    if($args['proposition']['lieux']['tab']!=null)
      {
        foreach ( $args['proposition']['lieux']['tab'] as $lieu ) 
        {
          $args['proposition']['lieux']['sond'][$lieu->getId()] = new MESondage('evenements_vacances_l_sond', 'idLieu', $lieu->getId());
        }
      }
    
    //Récupération de la participation des membres
      $args['participation']['leMembre'] = $ManageEvent->verifParticipation($idEvent);
      $args['participation']['non'] = $ManageEvent->nomsNonParticipants($idEvent);
      $args['participation']['oui'] = $ManageEvent->nomsParticipants($idEvent);
      $args['participation']['pe'] = $ManageEvent->nomsParticipantsPasSur($idEvent);
      $args['participation']['nbOui'] = $ManageEvent->combienDeParticipants($idEvent);
      
    //récupération commentaire
    $ManageCommentaire= new MECommentaireManage();
    $args['commentaires']=$ManageCommentaire->getListe($idEvent);
      
    $page['title'] = affichage($args['evenement']->getTitre ());
    $page['class'] = 'VEvenementVacance';
    $page['method'] = 'ShowEvent';
    $page['arg'] = $args;
  }
  else
  {
    $page['error']= $ManageEvent->getError();
    viewListEvent();
  }
  
}

function sendAddDates($idEvent)
{
  
  $ManageEvent = new MEEvenementVacanceManage();
  global $page;
  
  if($ManageEvent->evenementExiste($idEvent) AND $ManageEvent->isVacance($idEvent)) //on vérifie que l'évenement existe bien et qu'il s'agit bien d'un évenement VACANCE avant d'envoyer la proposition de date
  {
    $ManageDates = new MEDatesManage();
    $dates = new MEDates( array ('datefin' => $_POST ['dateFin'], 'datedebut' => $_POST ['dateDebut'], 'id_auteur' => $_SESSION ['id'], 'id_event' => $_GET ['idEvent'] ) );
  
    $ManageDates->save ( $dates );
    
    $page['error']= $ManageDates->getError();
    $page['confirmation']= $ManageDates->getConfirmation();

    viewEventVacance($idEvent);
  }
  else
  {
    $page['error']= $ManageEvent->getError();
    viewListEvent();
  }
  
}

function sendSondDatesV() //AJAX
{
  $Manage = new MESondage('evenements_vacances_d_sond', 'idDates', $_POST['id']); 
  $Manage->add($_POST['value'], $_SESSION['id']);
}

function sendUpdateDatesV() //AJAX
{
  $dates = new MEDates(array ('datefin' => $_POST ['dateFin'], 'datedebut' => $_POST ['dateDebut'], 'id' => $_POST['idDates']));
  $Manage = new MEDatesManage();
  $Manage->save($dates);
  
}

function sendAddLieuV($idEvent)
{
  
  $ManageEvent = new MEEvenementVacanceManage();
  global $page;
  
  if($ManageEvent->evenementExiste($idEvent) AND $ManageEvent->isVacance($idEvent)) //on vérifie que l'évenement existe bien et qu'il s'agit bien d'un évenement VACANCE avant d'envoyer la proposition de lieu
  {
    $ManageLieu = new MELieuManage("evenements_vacances_l");
    $lieu = new MELieu( array ('lieu' => $_POST ['lieu'], 'infoprix' => $_POST ['infoPrix'], 'id_auteur' => $_SESSION ['id'], 'id_event' => $_GET ['idEvent'] ) );
  
    $ManageLieu->save ( $lieu );
    
    $page['error']= $ManageLieu->getError();
    $page['confirmation']= $ManageLieu->getConfirmation();

    viewEventVacance($idEvent);
  }
  else
  {
    $page['error']= $ManageEvent->getError();
    viewListEvent();
  }
  
}

function sendSondLieuV() //AJAX
{
  $Manage = new MESondage('evenements_vacances_l_sond', 'idLieu', $_POST['id']); 
  $Manage->add($_POST['value'], $_SESSION['id']);
}

function sendUpdateLieuV() //AJAX
{
  $lieu = new MELieu(array ('lieu' => $_POST ['lieu'], 'infoprix' => $_POST ['infoPrix'], 'id' => $_POST['idLieu']));
  $Manage = new MELieuManage("evenements_vacances_l");
  $Manage->save($lieu);
  
}


function sendUpdateEventVacance($idEvent)
{
  $ManageEvent = new MEEvenementVacanceManage();
  
  
  if($_POST['dateDefinie']=='oui') //si la date est définie
  {
    $dateDebut = $_POST['dateDebut']; //date de début (obligatoire)
    $dateFin = $_POST['dateFin'];
    
  }
  else //sinon c'est que les deux ne sont pas définie
  {
    $dateDebut = 'N/D';
    $dateFin = 'N/D';
  }
  if($_POST['lieuDefini']=='oui') //si le lieu est définie
  {
    $lieu = $_POST['lieu'];
  }
  else
  {
    $lieu= 'N/D';
  }
  
  $evenement = new MEEvenementVacance($_POST ['titre'], $_POST ['presentation'], $_SESSION ['id'], $dateDebut, time (), $idEvent, 0, $_POST['prix'], $dateFin, $lieu);
  $ManageEvent->save($evenement);
  
  global $page;
  
  $page['error']= $ManageEvent->getError();
  $page['confirmation']= $ManageEvent->getConfirmation();

  viewListEvent();
 
}

//**********************************************************
//**************GESTION DES EVENEMENTS SOIREE/JOURNEE*************
//**********************************************************

function addEventSoirjour()
{
  if($_POST['lieuDefini']=='oui') //si le lieu est définie
  {
    $lieu = $_POST['lieu'];
  }
  else
  {
    $lieu= 'N/D';
  }
  if(strlen($_POST['apporter']) == 0)
  {
    $apporter= 'Rien';
  }
  else
  {
    $apporter=$_POST['apporter'];
  }
  
  $evenement = new MEEvenementSoirjour($_POST ['titre'], $_POST ['presentation'], $_SESSION ['id'], $_POST['date'], time (), 0, 0, $_POST['heure'], $_POST['prix'], $lieu, $apporter, $_POST['type']);
  
  $Manage = new MEEvenementSoirjourManage();
  $Manage->save($evenement);
  
  global $page;
  $page['error']= $Manage->getError();
  $page['confirmation']= $Manage->getConfirmation();
  
  viewListEvent();
}

function viewEventSoirjour($idEvent)
{
  $ManageEvent= new MEEvenementSoirjourManage();
  global $page;
  
  if($ManageEvent->evenementExiste($idEvent)) //vérification si l'évenement existe
  {
    $args['evenement'] = $ManageEvent->getUnique($idEvent);
    
      
    $ManageLieu = new MELieuManage("evenements_soirjour_l");
    $args['proposition']['lieux']['tab']= $ManageLieu->getListe($idEvent);
    if($args['proposition']['lieux']['tab']!=null)
      {
        foreach ( $args['proposition']['lieux']['tab'] as $lieu ) 
        {
          $args['proposition']['lieux']['sond'][$lieu->getId()] = new MESondage('evenements_soirjour_l_sond', 'idLieu', $lieu->getId());
        }
      }
    
    //Récupération de la participation des membres
      $args['participation']['leMembre'] = $ManageEvent->verifParticipation($idEvent);
      $args['participation']['non'] = $ManageEvent->nomsNonParticipants($idEvent);
      $args['participation']['oui'] = $ManageEvent->nomsParticipants($idEvent);
      $args['participation']['pe'] = $ManageEvent->nomsParticipantsPasSur($idEvent);
      $args['participation']['nbOui'] = $ManageEvent->combienDeParticipants($idEvent);
      
    //récupération commentaire
    $ManageCommentaire= new MECommentaireManage();
    $args['commentaires']=$ManageCommentaire->getListe($idEvent);
      
    $page['title'] = 'Gestion de soirée / journée';
    $page['class'] = 'VEvenementSoirjour';
    $page['method'] = 'ShowEvent';
    $page['arg'] = $args;
  }
  else
  {
    $page['error']= $ManageEvent->getError();
    viewListEvent();
  }
  
}

function sendAddLieuSJ($idEvent)
{
  
  $ManageEvent = new MEEvenementSoirjourManage();
  global $page;
  
  if($ManageEvent->evenementExiste($idEvent) AND $ManageEvent->isSoirjour($idEvent)) //on vérifie que l'évenement existe bien et qu'il s'agit bien d'un évenement VACANCE avant d'envoyer la proposition de lieu
  {
    $ManageLieu = new MELieuManage("evenements_soirjour_l");
    $lieu = new MELieu( array ('lieu' => $_POST ['lieu'], 'infoprix' => $_POST ['infoPrix'], 'id_auteur' => $_SESSION ['id'], 'id_event' => $_GET ['idEvent'] ) );
  
    $ManageLieu->save ( $lieu );
    
    $page['error']= $ManageLieu->getError();
    $page['confirmation']= $ManageLieu->getConfirmation();

    viewEventSoirjour($idEvent);
  }
  else
  {
    $page['error']= $ManageEvent->getError();
    viewListEvent();
  }
  
}

function sendSondLieuSJ() //AJAX
{
  $Manage = new MESondage('evenements_soirjour_l_sond', 'idLieu', $_POST['id']); 
  $Manage->add($_POST['value'], $_SESSION['id']);
}

function sendUpdateLieuSJ() //AJAX
{
  $lieu = new MELieu(array ('lieu' => $_POST ['lieu'], 'infoprix' => $_POST ['infoPrix'], 'id' => $_POST['idLieu']));
  $Manage = new MELieuManage("evenements_soirjour_l");
  $Manage->save($lieu);
  
}

function sendUpdateEventSoirjour($idEvent)
{
  $ManageEvent = new MEEvenementSoirjourManage();
  
  
  if($_POST['lieuDefini']=='oui') //si le lieu est définie
  {
    $lieu = $_POST['lieu'];
  }
  else
  {
    $lieu= 'N/D';
  }
  
  $evenement = new MEEvenementSoirjour($_POST ['titre'], $_POST ['presentation'], $_SESSION ['id'], $_POST['date'], time (), $idEvent, 0, $_POST['heure'], $_POST['prix'], $lieu, $apporter, $_POST['type']);
  $ManageEvent->save($evenement);
  
  global $page;
  
  $page['error']= $ManageEvent->getError();
  $page['confirmation']= $ManageEvent->getConfirmation();

  viewListEvent();
 
}


?>