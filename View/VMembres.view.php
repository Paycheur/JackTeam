<?php
class VMembres
{
  /**
   * Constructeur
   * 
   * @access public
   * 
   * @return none
   */
  public function __construct() {return;}

  /**
   * Destructeur
   * 
   * @access public
   * 
   * @return none
   */
  public function __destruct() {return;}

  
public function ShowFormInscription($_value)
{
    if($_value==null) 
    {
	$nom='';
	$prenom='';
	$email='';
    }
    else //si il y a une Žrreur lors de la soumission du formulaire.
    {
	$nom=$_value->getNom();
	$prenom=$_value->getPrenom();
	$email=$_value->getEmail();
    }
    echo <<<HERE
    <form method="post" action="membres.php?EX=SendInscription" id="formInscription" enctype="multipart/form-data"> 
	<p style="text-align:center;color:#636363;margin:0px;">
	    <label for="nom">Nom</label> : <input type="text" id="nom" name="nom" value="{$nom}" size="40" /><br/>
	    <label for="prenom">Prenom</label> : <input type="text" id="prenom" name="prenom" value="{$prenom}" size="40" /><br/>
	    <label for="email">Email</label> : <input type="text" id="email" name="email" value="{$email}" size="60" /><br/>
	    <label for="pseudo">Pseudo</label> : <input type="text" id="pseudo" name="pseudo" value="" size="20" /><br/>
	    <label for="mdp">Mot de passe : </label><input type="password" name="mdp" id="mdp" size="20"/><br/>
	    <label for="mdp">Confirmer le mot de passe : </label><input type="password" name="mdp_c" size="20"/><br/> 
	    <input type="submit" name="SendInscription" value="Inscription" />			
	</p> 
    </form>
HERE;
}
    


}
?>