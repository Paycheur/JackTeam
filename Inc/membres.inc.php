<?php

define ('ICONE_PAGE', '../Img/bdd.png');
define ('CSS_PAGE', '../Css/index.css');
define ('JS_PAGE', '../Js/Membres/membres.js');
define ('CONTROLER', '../Php/membres.php');

define('BD_NAME', 'jack');
define('BD_LOGIN', 'root');
define('BD_PASSWORD', '');

require ('../Lib/tools.lib.php');
require ('../Lib/redimImage.lib.php'); //redimensionnement des photo lors de l'upload
require ('../Lib/valideChaine.lib.php'); //pour verifier les liens (notamment pour l'upload de photo)
?>