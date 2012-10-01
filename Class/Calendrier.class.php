<?php

class Calendrier {

	//---------------------------------------------------------------------------------------------------------------//
	//--                                               PROPRIETES                                                  --//
	//---------------------------------------------------------------------------------------------------------------//

	/**
	 * Variables concernant le format de la date
	 *
	 * @var		boolean		$zeros		A true, on met les z�ros devant mois ou jour s'ils sont plus petit que 10
	 * @var		array		$format		L'ordre de la date, soit l'ann�e (a), le mois (m) et le jour (j)
	 * @var		string		$sep		Le s�parateur entre l'ann�e, le mois et le jour
	 */
	var $zeros = true;
	var $format = array('j', 'm', 'a');
	var $sep = '-';
	
	/**
	 * Propri�t�s concernant les ann�es dipsonibles dans le calendrier
	 *
	 * @var		integer		$aMoins		Le nombre d'ann�es de moins que celle actuelle
	 * @var		integer		$aPlus		Le nombre d'ann�es de plus que celle actuelle
	 */
	var $aMoins = 0;
	var $aPlus = 3;

	/**
	 * Propri�t�s � renseigner si $popup est � true
	 *
	 * Si $popup est � false, c'est que le calendrier va s'afficher dans la m�me page. Il faut
	 * donc sp�cifier le nom du formulaire, le nom du champ ainsi que celui de la page. Pour
	 * customiser un peu l'affichage, on peut jouer sur la largeur et les marges.
	 *
	 * @var		boolean		$popup			A true, affichera le calendrier sous forme de popup
	 * @var		string		$largeurCal		La largeur du calendrier (sp�cifier l'unit� px, %, etc)
	 * @var		string		$margesCal		Les marges du calendrier (genre 15px 10px 0 10px)
	 * @var		string		$form			Le nom du formulaire
	 * @var		string		$champ			Le nom du champ
	 * @var		string		$page			Le nom de la page
	 */
	var $popup = true;
	var $largeurCal = '100%';
	var $margesCal = 0;
	var $form = null;
	var $champ = null;
	var $page = null;
	
	/**
	 * Propri�t�s diverses et vari�es
	 *
	 * @var		string		$titre		Le titre de la popup
	 * @var		string		$css		Le chemin et le nom du fichier de css du calendrier
	 * @var		string		$css		Le chemin et le nom du fichier javascript du calendrier
	 * @var		array		$nomj		Le nom des jours
	 * @var		array		$nomm		Le nom des mois
	 * @var		integer		$mois		Le mois choisi � afficher
	 * @var		integer		$annee		L'ann�e choisie � afficher
	 * @var		integer		$jour		Le jour choisi � afficher
	 */
	var $titre = 'Calendrier';
	var $css = '../Css/calendrier.css';
	var $js = '../Js/Lib/calendrier.js';
	var $nomj = array ('Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa');
	var $nomm = array(
		'Janvier',
		'F&eacute;vrier',
		'Mars',
		'Avril',
		'Mai',
		'Juin',
		'Juillet',
		'Ao&ucirc;t',
		'Septembre',
		'Octobre',
		'Novembre',
		'D&eacute;cembre'
	);
	var $mois = null;
	var $annee = null;
	var $jour = null;

	/**
	 * Propri�t�s concernant le style d'affichage du calendrier
	 *
	 * Par exemple, le style "fr" (fran�ais) commencera par lundi, alors que le style "en" (anglais)
	 * commencera lui par dimanche.
	 *
	 * @var		array		$stylesDispo	Tableau de string repr�sentant les styles disponibles
	 * @var		string		$style			Le style d'affichage du calendrier
	 */
	var $stylesDispo = array('fr', 'en');
	var $style = 'fr';

	/**
	 * Propri�t� qui permet de dire que les dates sont choisies et que c'est comme �a, voil� tout.
	 *
	 * Utilis�e dans le seul cas o� on a un set_date_defaut(). En effet, dans ce cas, lorsqu'on
	 * change de mois ou d'ann�e, il faut pouvoir lui dire de ne pas prendre en consid�ration la
	 * date par d�faut, mais bien celle qu'on re�oit via le formulaire. Si vous n'utilisez pas la
	 * fonction auto_set_date(), vous ne devrez pas oublier de mettre manuellement la valeur de
	 * cette propri�t� � TRUE.
	 *
	 * @var		boolean		$dateChoisie	A TRUE, la fonction set_date_defaut est inactive
	 */
	var $dateChoisie = false;


	//---------------------------------------------------------------------------------------------------------------//
	//--                                               CONSTRUCTEURS                                               --//
	//---------------------------------------------------------------------------------------------------------------//

	/**
	 * Assigne le nom du formulaire et du champ et r�cup�re la date de maintenant
	 *
	 * @param	string		$form		Le nom du formulaire
	 * @param	string		$champ		Le nom du champ
	 */
	function Calendrier($form, $champ){
		$this->form = $form;
		$this->champ = $champ;
		$page = '?frm='.$form.'&ch='.$champ;
		$this->page = $page;
	}


	//---------------------------------------------------------------------------------------------------------------//
	//--                                            FONCTIONS PUBLIQUES                                            --//
	//---------------------------------------------------------------------------------------------------------------//

	/**
	 * Change le style d'affichage du calendrier
	 *
	 * Un style fr commencera par lundi, alors qu'un style "en" d�butera par dimanche
	 *
	 * @param	string		$form		Le type d'affichage (fr ou en)
	 */
	function set_style($style){
		$tab = $this->stylesDispo;
		if (in_array($style, $tab)){
			$this->style = $style;
		}else{
			$this->style = 'fr';
		}
	}

	/**
	 * Set le nom de la page qu'il faut reloader
	 *
	 * A utiliser dans le seul cas o� le calendrier est inclu dans une page (donc pas une
	 * popup pour lui tout seul) et qu'il y a des autres param�tres en GET.
	 *
	 * @param	string		$page		Le nom de la page � reloader
	 */
	function set_page($page){
		$tab = explode('?', $page);
		if (count($tab) > 1){
			$cc = '&';
		}else{
			$cc = '?';
		}
		$page .= $cc.'frm='.$this->form.'&ch='.$this->champ;
		$this->page = $page;
	}

	/**
	 * Affiche ou non les z�ros devant jours et mois < 10
	 *
	 * @param	boolean		$zeros		A true, affiche les z�ros devant jours et mois < 10
	 */
	function set_zeros($zeros){
		$this->zeros = $zeros;
	}

	/**
	 * Fixe le format de la date � avoir dans le champ de formulaire
	 *
	 * L'ordre (ann�e, mois, jour) peut �tre fourni dans un tableau (array("a", "m", "j")) ou
	 * dans une string ("amj") ou ("a,m,j") �ventuellement.
	 *
	 * @param	array|string	$format		L'ordre d'affichage des ann�es, mois et jours
	 * @param	string			$sep		La s�paration entre ann�es, mois et jours
	 * @return	boolean						True en cas de succ�s, false sinon
	 */
	function set_format($format, $sep){
		if (!is_array($format)){
			if (strlen($format) == 3){
				$tmp = array();
				for ($i = 0; $i < strlen($format); $i++){
					$tmp[] = strtolower($format[$i]);
				}
				$format = $tmp;
			}else{
				return false;
			}
		}else{
			if (count($format) == 3){
				for ($i = 0; $i < count($format); $i++){
					$format[$i] = strtolower($format[$i]);
				}
			}else{
				return false;
			}
		}
		$this->format = $format;
		$this->sep = $sep;
		return true;
	}

	/**
	 * D�termine le mois et l'ann�e en cours
	 *
	 * Par d�faut, prend les valeurs d'aujourd'hui.
	 *
	 * @param	integer		$mois		Le num�ro du mois (1 = janvier)
	 * @param	integer		$annee		L'ann�e en 4 chiffres
	 */
	function set_date($mois, $annee){
		$this->mois = $mois;
		$this->annee = $annee;
	}

	/**
	 * D�termine le mois et l'ann�e en cours
	 *
	 * Par d�faut, prend les valeurs d'aujourd'hui. En fonction de la variable d'environnement
	 * $_POST. Le tableau doit en tout cas contenir ces valeurs :
	 *		- $_POST['change_date']	: true ou false
	 *		- $_POST['mois']		: le num�ro du mois
	 *		- $_POST['annee']		: l'ann�e
	 *
	 * @param	integer		$mois		Le num�ro du mois (1 = janvier)
	 * @return	boolean					False si change_date n'existe pas, true sinon
	 */
	function auto_set_date($_POST){
		if (isset($_POST['change_date'])){
			$this->set_date($_POST['mois'], $_POST['annee']);
			$this->dateChoisie = true;
			return true;
		}
		return false;
	}

	/**
	 * D�termine le mois et l'ann�e voulue
	 *
	 * Tr�s utile si on veut, d�s le 1er chargement, �tre sur une autre date que celle courante.
	 * Change les variables globales des mois et ann�es. Si la fonction auto_set_date a �t�
	 * effectu�e, et surtout que la propri�t� $dateChoisie est � TRUE, le traitement ne se fera
	 * pas.
	 *
	 * @param	string		$date_defaut	La date courante au format sp�cifi� par set_format()
	 * @return	integer						Retourne -1 si le traitement n'a pas �t� effectu� volontairement
	 */
	function set_date_defaut($date_defaut){
		$mois = false;
		$annee = false;
		$jour = false;
		if (!$this->dateChoisie){
			$temps = explode($this->sep, $date_defaut);
			for ($i = 0; $i < count($this->format); $i++){
				switch ($this->format[$i]){
					case 'a':
						$annee = $temps[$i];
						break;
					case 'm':
						$mois = $temps[$i];
						break;
					case 'j':
						$jour = $temps[$i];
				}
			}
			if (!$mois || !$annee || !$jour) return false;
			$this->set_date($mois, $annee);
			$this->jour = $jour;
		}else{
			return -1;
		}
	}

	/**
	 * Assigne le spectre d'ann�es � prendre en compte dans le calendrier
	 *
	 * @param	integer		$aMoins		Le nb d'ann�es en moins que la courante
	 * @param	integer		$aPlus		Le nb d'ann�es en plus que la courante
	 */
	function set_annees($aMoins, $aPlus){
		$this->aMoins = $aMoins;
		//+1 parce que l'ann�e courante est comprise dedans
		$this->aPlus = $aPlus + 1;
	}

	/**
	 * Sauve le chemin et le nom du fichier de css
	 *
	 * @param	string		$chemin		Le chemin et le nom du fichier css
	 */
	function set_css($chemin){
		$this->css = $chemin;
	}

	/**
	 * Sauve le chemin et le nom du fichier javascript
	 *
	 * @param	string		$chemin		Le chemin et le nom du fichier javascript
	 */
	function set_js($chemin){
		$this->js = $chemin;
	}

	/**
	 * Change le titre de la page
	 *
	 * @param	string		$titre		Le titre de la page
	 */
	function set_titre($titre){
		$this->titre = $titre;
	}

	/**
	 * D�termine si l'affichage du calendrier
	 *
	 * S'il est sous forme de popup, il faudra afficher toutes les ent�tes html et ins�rer
	 * tous les fichiers css et js. S'il est contenu dans une page o� il y a d'autres
	 * choses, il n'y a plus besoin des balises d'ent�tes.
	 *
	 * @param	boolean		$estPop		A true, affiche dans une popup, � false, non
	 */
	function set_popup($estPop){
		$this->popup = $estPop;
	}

	/**
	 * Change le style du div qui englobe le calendrier
	 *
	 * On ne peut jouer que sur la largeur et/ou les marges.
	 *
	 * @param	string		$largeur	La largeur du calendrier au format css
	 * @param	string		$marges		Les marges du calendrier au format css
	 */
	function set_position($largeur, $marges){
		$this->largeurCal = $largeur;
		$this->margesCal = $marges;
	}

	/**
	 * R�cup�re la string pour ouvrir dans une popup
	 *
	 * @param	string		$page		La page dans laquelle s'ouvrira le calendrier
	 * @param	integer		$l			La largeur de la popup
	 * @param	integer		$h			La hauteur de la popup
	 * @return	string					La string d'ouverture de popup
	 */
	function get_strPopup($page, $l, $h){
		$tab = explode('?', $page);
		if (count($tab) > 1){
			$cc = '&';
		}else{
			$cc = '?';
		}
		$page .= $cc.'frm='.$this->form.'&ch='.$this->champ;
		$str = "window.open('".$page."','calendrier','width=".$l.",height=".$h.",scrollbars=0').focus();";
		return $str;
	}


	//---------------------------------------------------------------------------------------------------------------//
	//--                                     FONCTIONS AFFICHAGE PUBLIQUES                                         --//
	//---------------------------------------------------------------------------------------------------------------//

	/**
	 * Affiche le calendrier dans la page
	 */
	function affiche(){
		$ajd = getdate();
		$mois = $ajd['mon'];
		$annee = $ajd['year'];
		if (isset($this->mois)){
			$mois = $this->mois;
		}
		if (isset($this->annee)){
			$annee = $this->annee;
		}
		if ($this->popup){
			$this->_affiche_entetes($this->titre, $this->css, $this->js);
			$this->_affiche_calendrier($this->largeurCal, $this->margesCal, $this->page, $this->nomm, $this->aMoins, $this->aPlus, $ajd, $this->style);
			$this->_affiche_js($this->form, $this->champ, $this->zeros, $this->sep, $this->popup, $this->format, $mois, $annee);
			$this->_affiche_basPage();
		}else{
			$this->_affiche_calendrier($this->largeurCal, $this->margesCal, $this->page, $this->nomm, $this->aMoins, $this->aPlus, $ajd, $this->style);
			$this->_affiche_js($this->form, $this->champ, $this->zeros, $this->sep, $this->popup, $this->format, $mois, $annee);
			$this->affiche_js();
		}
	}

	/**
	 * Affiche l'insertion du fichier de javascript
	 *
	 * Utile seulement dans le cas o� le calendrier n'est pas sous forme de popup, que la page h�te
	 * n'a pas de fichier js inclu dans le <head> et/ou que les fonctions js du calendrier n'ont
	 * pas �t� copi�es-coll�es dans le fichier js de la page
	 */
	function affiche_js(){
		echo '<script type="text/javascript" src="'.$this->js.'"></script>'."\n";
	}


	//---------------------------------------------------------------------------------------------------------------//
	//--                                       FONCTIONS AFFICHAGE PRIVEES                                         --//
	//---------------------------------------------------------------------------------------------------------------//

	/**
	 * Affiche les ent�tes html
	 *
	 * Il s'agit concr�tement des balises html, head, title, link, et ouverture de body
	 *
	 * @param	string		$titre		Le titre de la page
	 * @param	string		$css		Le lien vers la feuille de style
	 * @param	string		$js			Le lien vers le fichier javascript
	 */
	function _affiche_entetes($titre, $css, $js){
		echo "<html>\n";
		echo "<head>\n";
		echo "\t<title>".$titre."</title>\n";
		echo "\t".'<link rel="stylesheet" type="text/css" href="'.$css.'"/>'."\n";
		echo "\t".'<script type="text/javascript" src="'.$js.'"></script>'."\n";
		echo "</head>\n\n";
		echo "<body>\n\n";
	}

	/**
	 * Affiche les fins de balises html
	 *
	 * Il s'agit concr�tement des balises /html et /body
	 */
	function _affiche_basPage(){
		echo "</body>\n";
		echo "</html>\n";
	}

	/**
	 * Affiche le javascript de la page
	 *
	 * Concerne toutes les donn�es utilis�es dans les fonctions js du fichier js.
	 *
	 * @param	string		$frm		Le nom du formulaire
	 * @param	string		$chm		Le nom du champ
	 * @param	boolean		$zeros		Affiche ou non le 0 devant jours et mois < 10
	 * @param	string		$sep		Le s�parateur entre ann�e, mois et jour
	 * @param	boolean		$popup		D�termine si on va fermer la popup ou non
	 * @param	array		$ordre		L'ordre de la date (ann�e - mois - jour)
	 * @param	integer		$mois		Le num�ro du mois
	 * @param	integer		$annee		L'ann�e
	 */
	function _affiche_js($frm, $chm, $zeros, $sep, $popup, $ordre, $mois, $annee){
		if ($zeros){
			$zeros = 'true';
		}else{
			$zeros = 'false';
		}
		echo '<script type="text/javascript">'."\n";
		echo "\tvar checkzero = ".$zeros.";\n";
		echo "\t".'var format = "'.$sep.'";'."\n";
		echo "\tvar moisc = ".$mois.";\n";
		echo "\tvar anneec = ".$annee.";\n";
		echo "\t".'var ordre = new Array("'.strtoupper(implode('", "', $ordre)).'");'."\n\n";
		echo "\t/**\n";
		echo "\t * Ins�re la valeur dans le champ et ferme la fen�tre si c'�tait une popup\n";
		echo "\t *\n";
		echo "\t * @param	string	val		La valeur du champ de date\n";
		echo "\t */\n";
		echo "\tfunction finOperation(val){\n";
		if ($popup){
			echo "\t\twindow.opener.document.forms['".$frm."'].elements['".$chm."'].value = val;\n";
			echo "\t\twindow.close();\n";
		}else{
			echo "\t\tdocument.forms['".$frm."'].elements['".$chm."'].value = val;\n";
		}
		echo "\t}\n";
		echo "</script>\n\n";
	}

	/**
	 * Affiche le calendrier
	 *
	 * @param	string		$largeur	La largeur du calendrier
	 * @param	string		$marges		Les �ventuelles marges du calendrier
	 * @param	string		$link		Le nom de la page o� il y a le calendrier avec les valeurs GET
	 * @param	array		$nomm		Le noms des mois
	 * @param	integer		$anneeMin	Le nb d'ann�e en moins que celle actuelle
	 * @param	integer		$anneeMax	Le nb d'ann�e en plus que celle actuelle
	 * @param	array		$ajd		Le tableau getdate() d'aujourd'hui
	 * @param	string		$affichage	Le style d'affichage (fr, en)
	 */
	function _affiche_calendrier($largeur, $marges, $link, $nomm, $anneeMin, $anneeMax, $ajd, $affichage){
		$mois = $ajd['mon'];
		$annee = $ajd['year'];
		$aujourdhui = array($ajd['mday'], $mois, $annee);
		if (isset($this->mois)){
			$mois = $this->mois;
		}
		if (isset($this->annee)){
			$annee = $this->annee;
		}
		list($premierJour, $dernierJour) = $this->_get_ajd($annee, $mois);
		$nomj = $this->nomj;

		//Intervention des marges et largeur, si on est pas dans une popup
		echo '<div id="calendrierEntier" style="width: '.$largeur.'; margin: '.$marges.';">'."\n";

		echo "\t".'<form id="calendrier" method="post" action="'.$link.'">'."\n";
		echo "\t".'<input type="button" class="navcal" value="<<" onClick="changeDate(this.form, false, \'mois\', '.($anneeMax - $anneeMin).')"/>'."\n";
		echo "\t".'<select name="mois" id="mois" onChange="reload(this.form)">'."\n";
		/**
		 * Affichage des mois
		 */
		for ($i = 0; $i < count($nomm); $i++){
			$selected = $this->_get_selected($mois - 1, $i);
			echo "\t\t".'<option value="'.($i + 1).'"'.$selected.'>'.$nomm[$i].'</option>'."\n";
		}
		echo "\t</select>\n";
		echo "\t".'<input type="button" class="navcal" value=">>" onClick="changeDate(this.form, true, \'mois\', '.($anneeMax - $anneeMin).')"/>'."\n";
		echo "\t".'<input type="button" class="navcal" value="<<" onClick="changeDate(this.form, false, \'annee\', '.($anneeMax - $anneeMin).')"/>'."\n";
		echo "\t".'<select name="annee" id="annee" onChange="reload(this.form)">'."\n";
		/**
		 * Affichage des ann�es
		 */
		for ($i = $ajd["year"] - $anneeMin; $i < $ajd["year"] + $anneeMax; $i++){
			$selected = $this->_get_selected($annee, $i);
			echo "\t\t".'<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n";
		}
		echo "\t</select>\n";
		echo "\t".'<input type="button" class="navcal" value=">>" onClick="changeDate(this.form, true, \'annee\', '.($anneeMax - $anneeMin).')"/>'."\n";
		echo "\t".'<input type="hidden" name="change_date" value="1"/>'."\n";
		echo "\t</form>\n\n";
		echo "\t".'<table id="calendar">'."\n";
		echo "\t\t<tr>\n";
		/**
		 * Affichage du nom des jours
		 */
		for ($jour = 0; $jour < 7; $jour++){
			$classe = $this->_get_classe($jour, 1, $affichage);
			echo "\t\t\t<th".$classe.">".$nomj[$jour]."</th>\n";
		}
		echo "\t\t<tr>\n";
		/**
		 * Affichage des cellules vides en d�but de mois, s'il y en a
		 */
		for ($prems = 0; $prems < $premierJour; $prems++){
			$classe = $this->_get_classe($prems, 2, $affichage);
			echo "\t\t\t<td".$classe.">&nbsp;</td>\n";
		}
		/**
		 * Affichage des jours du mois
		 */
		$cptJour = 0;
		for ($jour = 1; $jour <= $dernierJour; $jour++){
			$classe = $this->_get_classeJour($aujourdhui, $annee, $mois, $jour, $cptJour, $premierJour, $nomj, $prems, $affichage);
			$cptJour++;
			echo "\t\t\t".'<td'.$classe.'><a href="#" onClick="submitDate('.$jour.')">'.$jour.'</a></td>'."\n";
			if (is_int(($jour + $prems) / 7)){
				$cptJour = 0;
				echo "\t\t</tr>\n";
				if ($jour < $dernierJour){
					echo "\t\t<tr>\n";
				}
			}
		}
		/**
		 * Affichage des cellules vides en fin de mois, s'il y en a
		 */
		if ($cptJour != 0){
			for ($i = 0; $i < (7 - $cptJour); $i++){
				$classe = $this->_get_classeJourReste($i, $cptJour, $affichage);
				echo "\t\t\t<td".$classe.">&nbsp;</td>\n";
			}
			echo "\t\t</tr>\n";
		}
		echo "\t</table>\n";
		echo "</div>\n\n";
	}


	//---------------------------------------------------------------------------------------------------------------//
	//--                                            FONCTIONS PRIVEES                                              --//
	//---------------------------------------------------------------------------------------------------------------//

	/**
	 * Renvoie la position du 1er et dernier jour du mois
	 *
	 * En fonction du mois et de l'ann�e, retourne exactement le num�ro du jour dans la
	 * semaine. Le tableau de retour est comme suit :
	 *		- $tab[0] : le num�ro du 1er jour
	 *		- $tab[1] : le num�ro du dernier jour
	 *
	 * @param	integer		$annee		L'ann�e choisie
	 * @param	integer		$mois		Le mois choisi
	 * @return	array					Le tableau du 1er et dernier jour du mois
	*/
	function _get_ajd($annee, $mois){
		$affichage = $this->style;
		$nomj = $this->nomj;

		$moisCheck = $mois + 1;
		$anneeCheck = $annee;
		if ($moisCheck > 12){
			$moisCheck = 1;
			$anneeCheck = $annee + 1;
		}

		$dernierJour = strftime('%d', mktime(0, 0, 0, $moisCheck, 0, $anneeCheck));
		$premierJour = date('w', mktime(0, 0, 0, $mois, 1, $annee));

		if ($affichage == 'fr'){
			//On modifie la position du premier jour suivant la disposition des jours qu'on veut
			$origine = 1;
			if ($origine > 7){
				$origine = 7;
			}
			$j = $origine;
			for ($i = 0; $i < count($nomj); $i++){
				if ($j >= count($nomj)){
					$j = 0;
				}
				$temp[] = $nomj[$j];
				$j++;
			}
			//On d�cale le 1er jour en cons�quence
			$premierJour -= $origine;
			if ($premierJour < 0){
				$premierJour = 6 - (abs($premierJour) - 1);
			}
			$this->nomj = $temp;
		}
		return array($premierJour, $dernierJour);
	}

	/**
	 * Renvoie une string qui vaut selected ou non, pour un champs SELECT
	 *
	 * @param   integer     $temps      L'ann�e ou le mois choisi
	 * @param   integer     $i          L'annee en cours
	 * @return  string                  La string n�cessaire pour s�lectionner une OPTION
	 */
	function _get_selected($temps, $i){
		$selected = '';
		if ($temps == $i){
			$selected = ' selected="selected"';
		}
		return $selected;
	}

	/**
	 * Renvoie une string repr�sentant l'appel � une classe CSS
	 *
	 * Pour les valeurs par d�faut :
	 *      - 1 : ' class="aut"'
	 *      - 2 : ''
	 *
	 * @param   integer     $jour       Le jour en cours
	 * @param   integer     $index      La valeur par d�faut de la string
	 * @return  string                  La string n�cessaire pour appeller la classe CSS voulue
	 */
	function _get_classe($jour, $index, $mode){
		switch ($index) {
			case 1:
				$classe = ' class="aut"';
				break;
			default:
				$classe = '';
		}
		switch ($mode) {
			case 'en':
				$x1 = 0;
				$x2 = 6;
				break;
			default:
				$x1 = 6;
				$x2 = 5;
		}
		if ($jour == $x1){
			$classe = ' class="dim"';
		}elseif ($jour == $x2){
			$classe = ' class="sam"';
		}
		return $classe;
	}

	/**
	 * D�termine si on est sur un dimanche ou un samedi, � partir du 1er du mois
	 *
	 * @param   array       $ajd            Le jour, mois et ann�e de maintenant
	 * @param   integer     $annee          L'ann�e en cours
	 * @param   integer     $mois           Le mois en cours
	 * @param   integer     $jour           Le jour en cours
	 * @param   integer     $cptJour        Le num�ro du jour en cours de la semaine
	 * @param   integer     $premierJour    Le num�ro du 1er jour (dans la semaine) du mois
	 * @param   array       $nomj           Le tableau des noms des jours
	 * @param   integer     $prems          Le num�ro du dernier jour de la semaine du mois pr�c�dent
	 * @param   string      $mode           Le mode d'affichage du calendrier ("fr" ou "en")
	 * @return  string                      La string n�cessaire pour appeller la classe CSS voulue
	 */
	function _get_classeJour($ajd, $annee, $mois, $jour, $cptJour, $premierJour, $nomj, $prems, $mode){
		$classe = '';
		if ($mode == 'en'){
			if (($cptJour == 0 && $jour > 1) || ($jour == 1 && $premierJour == 0)){
				$classe = ' class="dim"';
			}elseif ($cptJour == 6 || (count($nomj) - $jour == $prems)){
				$classe = ' class="sam"';
			}
		}else{
			if ($cptJour == 6 || (count($nomj) - $jour == $prems)){
				$classe = ' class="dim"';
			}else if ($cptJour == 5 || (count($nomj) - $jour - 1 == $prems)){
				$classe = ' class="sam"';
			}
		}
		if ($jour == $ajd[0] && $mois == $ajd[1] && $annee == $ajd[2]){
			$classe = ' class="ajd"';
		}else{
			if ($jour == $this->jour && $mois == $this->mois && $annee == $this->annee){
				$classe = ' class="cho"';
			}
		}
		return $classe;
	}

	/**
	 * D�termine si on est sur un samedi, lorsqu'on compl�te le tableau
	 *
	 * @param   integer     $i              Le jour en cours
	 * @param   integer     $cptJour        Le num�ro du dernier jour (dans la semaine) du mois
	 * @param   string      $mode           Le mode d'affichage du calendrier ("fr" ou "en")
	 * @return  string                      La string n�cessaire pour appeller la classe CSS voulue
	 */
	function _get_classeJourReste($i, $cptJour, $mode){
		$classe = '';
		if ($mode == 'en'){
			if ($i == (7 - $cptJour) - 1){
				$classe = ' class="sam"';
			}
		}else{
			if ($i == (6 - $cptJour) - 1){
				$classe = ' class="sam"';
			}else if ($i == (7 - $cptJour) - 1){
				$classe = ' class="dim"';
			}
		}
		return $classe;
	}

}

?>
