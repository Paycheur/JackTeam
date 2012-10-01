<?php

/**
 * Int�rieur du fichier php contenant le seul calendrier. On r�cup�re en GET les valeurs
 * repr�sentant le nom du formulaire et le nom du champ de la date.
 */
$frm = $_GET['frm'];
$chm = $_GET['ch'];
if (isset($_GET['defaut']))
	$dat = $_GET['defaut'];
else
	$date = false;

/**
 * On cr�� un nouveau calendrier, on r�cup�re la date � afficher (par d�faut, le calendrier
 * affiche le mois en cours de l'ann�e en cours). Les valeurs de POST sont transmises au
 * moment o� on change le SELECT des mois ou celui des ann�es. Finalement, on affiche le
 * calendrier.
 */

include('../Class/Calendrier.class.php');

$cal = new Calendrier($frm, $chm);
$cal->set_titre('Jack Calendar');
$cal->auto_set_date($_POST);
if ($date)
	$cal->set_date_defaut($date);
$cal->affiche();

?>
