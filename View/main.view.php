<?php

//Si le membre a le cookie ID, c'est qu'il avait coché "resté connecté"
if(!isset($_SESSION['id']) && isset($_COOKIE['id']))
{
	$membreManage = new MMMembreManage();
	$membreManage->connexionAuto($_COOKIE['id']);
}

global $page;
if(isset($page['class']))
	$vpage = new $page['class'];	

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
 <title><?=$page['title']?></title>
 <link rel="icon" type="image/png" href="<?=ICONE_PAGE?>" />
 <link rel="stylesheet" media="screen" type="text/css" title="General" href="../Css/general.css" />
 <style type="text/css">@import url('<?=CSS_PAGE?>');</style>
 <script type="text/javascript" src="<?=JS_PAGE?>"></script>
</head>

<body>
  
<header>
 <div id="headerContent">
  <img src="../img/logo.jpg" alt="JackTeam" id="logo" />

 <nav>
  <ul>
   <li><a href="galerie.php">Photos</a></li>
   <li><a href="evenements.php">Evenements</a></li>
   <li><a href="membres.php">Membres</a></li>
   <li><a href="index.php">Index</a></li>
  </ul>
 </nav>
 
  <div id="blocConnexion">
  <button id="setConnexion">Connexion</button>
 </div>
  
 
 </div>
</header>
<?php 
if(isset($_SESSION['id']) && !empty($_SESSION['pseudo']))
{
	echo <<<HERE
	<p style="position:absolute;top:100px;"><img src="../Upload/Profil/test.jpg" width="30px" height="30px" style="float:left; margin-right:5px;"/><strong>{$_SESSION['pseudo']}</strong> (<a href='membres.php?EX=SendDeconnexion'>Déconnexion</a>)<br />
    <a href="membres.php?EX=viewProfil">Voir mon profil</a></p>
HERE;
}
?>
<!-- ModalBox Connexion -->
    <div id="connexionModal" style="display:none;" class="modal hide fade">  
		<div class="modal-header">  
			<a class="close" data-dismiss="modal">×</a>  
		</div>  
		<div class="modal-body">  
		<form method="post" action="membres.php?EX=SendConnexion" enctype="multipart/form-data"> 
		<fieldset class="bigForm">
		<legend>Connecte toi !</legend>
			<div class="formField">
				<label for="pseudo">Pseudo</label>
				<input type="text" id="pseudo" name="CPseudo" value="" size="10" />
			</div>
			<div class="formField">
				<label for="password">Mot de passe</label>
				<input type="password" name="CPassword" id="motdepasse" size="10"/>
			</div>
			<div class="formField">
				<label for="connect_auto">Rester connecté</label>
				<input type="checkbox" name="connect_auto" id="case" />
			</div>
			<div class="formFieldSubmit">
			    <input type="submit" value="Connexion" />		
			</div>	
		</fieldset>
		</form>              
		</div>  

	</div>
  <!-- /ModalBox Connexion -->
<div id="corp">
 <img src="../Img/photos.png" alt="photos" id="photos" />

<div id="transpa-addStatut">
	<section class="addStatut">
	<form action="#" id="formAddStatut" method="POST">
	<textarea id="statut_message" name="message" placeholder="Ecris ton message..."></textarea>
	<div style="float:right;margin-right:10px;">
	<input type="submit" value="Poster" />
	<button>Plus</button>
	</div>
	</form>
</div>
 <div id="transpa">
  <section>
   <h1><?php echo $page['title']; ?></h1>
   <div id="error"><?php echo $page['error']; ?></div>
   <div id="confirmation"><?php echo $page['confirmation']; ?></div>
   <?php $vpage->$page['method']($page['arg']) ?>
  </section>
 </div>
 
</div>
</body>
</html>