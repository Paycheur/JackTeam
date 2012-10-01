<?php

define ('ICONE_PAGE', '../Img/bdd.png');
define ('CSS_PAGE', '../Css/galerie.css');
define ('JS_PAGE', '../Js/Galerie/galerie.js');
define ('CONTROLER', '../Php/galerie.php');

define('BD_NAME', 'jack');
define('BD_LOGIN', 'root');
define('BD_PASSWORD', '');

require ('../Lib/tools.lib.php');
require ('../Lib/pagination.lib.php'); //systme de page dans la liste des photos
require ('../Lib/redimImage.lib.php'); //redimensionnement des photo lors de l'upload
require ('../Lib/valideChaine.lib.php'); //pour verifier les liens (notamment pour l'upload de photo)

?>