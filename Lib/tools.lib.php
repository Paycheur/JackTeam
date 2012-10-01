<?php

function __autoload ($class)
{
  switch ($class[0].$class[1])
  {
	case 'C'.$class[1] : require_once ('../Class/'.$class.'.class.php');   break;
  	case 'V'.$class[1] : require_once ('../View/'.$class.'.view.php'); break;
  	case 'MG' : require_once ('../Mod/Galerie/'.$class.'.mod.php');   break;
  	case 'MM' : require_once('../Mod/Membres/'.$class.'.mod.php'); break;
	case 'ME' : require_once('../Mod/Evenements/'.$class.'.mod.php'); break;
	case 'MA' : require_once('../Mod/Accueil/'.$class.'.mod.php'); break;
  }

  return;

}

 
function debug ($val)
{
  echo '<pre>'; print_r ($val); echo '</pre>';
  
  return;

}



function br2nl($text) //Enleve les <br /> du au nl2br()
{
	return  str_replace("<br />", "", $text);
}

// DonnÃÂ©es entrantes
function bdd($content)
{
	// On regarde si le type de content est un nombre entier (int)
	if(ctype_digit($content))
	{
		$content = intval($content);
	}
	// Pour tous les autres types
	else
	{
	    //ajouter fonction pour enlever espace et tabulation

		$content = addslashes($content);
		$content = addcslashes($content, '%_');
	}
		
	return $content;

}

// DonnÃÂ©es sortantes
function html($content)
{
	return htmlentities($content);
}

function affichage($content)
{
	$content = trim(stripslashes(nl2br(htmlspecialchars($content))));
    return $content;
}

function affichage_form($content)
{
	$content = trim(stripslashes(br2nl(htmlspecialchars($content))));
    return $content;
}

function Tronquer_Texte($texte, $longeur_max)
{
	if (strlen($texte) > $longeur_max)
	{
		$texte = substr($texte, 0, $longeur_max);
		$dernier_espace = strrpos($texte," ");
		$texte = substr($texte, 0, $dernier_espace)."...";
	}

return $texte;
}

function remplacer_date($date) // Permet de mettre les mois de la date en entier..
{

if(date('m', $date) == 1) $mois = "Janvier";
if(date('m', $date) == 2) $mois = "Fevrier";
if(date('m', $date) == 3) $mois = "Mars";
if(date('m', $date) == 4) $mois = "Avril";
if(date('m', $date) == 5) $mois = "Mai";
if(date('m', $date) == 6) $mois = "Juin";
if(date('m', $date) == 7) $mois = "Juillet";
if(date('m', $date) == 8) $mois = "Aout";
if(date('m', $date) == 9) $mois = "Septembre";
if(date('m', $date) == 10) $mois = "Octobre";
if(date('m', $date) == 11) $mois = "Novembre";
if(date('m', $date) == 12) $mois = "Decembre";

$date_complete =  date('d', $date) . ' ' . $mois . ' ' . date('Y', $date).' à  '.date('H', $date).'h'.date('i', $date);
return $date_complete;
}


function redimage($img_src,$dst_w,$dst_h) 
{
// Lit les dimensions de l'image
	$size = GetImageSize($img_src);  
	$src_w = $size[0]; 
	$src_h = $size[1];
// Teste les dimensions tenant dans la zone
	$test_h = round(($dst_w / $src_w) * $src_h);
	$test_w = round(($dst_h / $src_h) * $src_w);
	// Si Height final non prÃÂ©cisÃÂ© (0)
if(!$dst_h) $dst_h = $test_h;
// Sinon si Width final non prÃÂ©cisÃÂ© (0)
elseif(!$dst_w) $dst_w = $test_w;
// Sinon teste quel redimensionnement tient dans la zone
elseif($test_h>$dst_h) $dst_w = $test_w;
else $dst_h = $test_h;

// Affiche les dimensions optimales
return "WIDTH=".$dst_w." HEIGHT=".$dst_h;
}

// Fonction fil d'Ariane
function fil_ariane($titres, $liens)
{
	$baseUrl = 'http://'.$_SERVER['HTTP_HOST']; //l'url de base
	$chemin = explode("/", substr($_SERVER['PHP_SELF'], 1));
	
	$nb_liens=count($liens); 
	
	$nb_lien_php=0;
	
	for($i=0; $i<$nb_liens; $i++)
	{
		$lien=$liens[$i];
		
		
		if($chemin[1]==$lien) //$chemin[1] car il y aura qu'une seule page ÃÂ  chaque fois (ex : jack.team.com/galerie.php)
		{
			$Url .= '/jackbuilding/'.$lien; //faudra supprimer jackbuilding quand on mettra en ligne
			$chaine .= '<a href=' . $Url . '>||' . $titres[$i] . '||</a> > ||'; 
			
		}
		
		
		if(isset($_GET[$lien]))
		{
				if($nb_lien_php==0) //permet de gÃÂ©rer les variables du lien entre celle qui a un "?" et celles qui ont un "&" devant.
				{
					$Url .= '?'.$lien.'='.$_GET[$lien];
					$chaine .= '<a href=' . $Url . '>||' . $titres[$i] . '||</a> > ||'; 
				}
				else
				{
					$Url .= '&'.$lien.'='.$_GET[$lien];
					$chaine .= '<a href=' . $Url . '>||' . $titres[$i] . '||</a> > ||';
				} 
				$nb_lien_php++;
		}
		
		
	}
	$dernier = explode("||", substr($chaine, 0)); //on met dans un tableau tout ce qui a entre les ||
	$nb_total= count($dernier); //on regarde le nombre total de lien
	
	//On veut maintenant pouvoir supprimer le dernier lien et mettre du simple texte.
	
	for($i=0; $i<$nb_total-2; $i++) //Pourquoi -2 ? parce que ca marche.
	{
		if($i==$nb_total-2) //Pour supprimer "</a>"
		{
			$fil = $fil;
		}
		elseif($i==$nb_total-4) //Pour supprimer "<a href=""Ã¢ÂÂ¦>" !! Pourquoi -4 ? bah je sais pas mais en tout cas ca marche aussi
		{
			$fil = $fil;
		}
		else
		{
		$fil .=$dernier[$i];
		}
	}
	$retour = '<div class="rapide_nav"><a href=' . $baseUrl . '>' . $titres[0] . '</a> > '.$fil.'</div>';
	return $retour;
}
?>