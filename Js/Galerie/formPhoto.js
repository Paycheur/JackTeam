function nbFormAddPhoto(nombre)
{
  var allForm;
  
  for(var i=1; i<=nombre; i++)
  {
      var image='image_'+i;
      var titre='titre_'+i;
      var description='description_'+i;
			
      
      
     var form = '<label for="'+image+'">Image (jpg, png | max. 1Mo):</label><br /><input type="file" name="'+image+'" id="'+image+'" /><br /><label for="'+titre+'">Titre de la photo (max 50 caractères):</label><br /><input type="text" name="'+titre+'" value="" id="'+titre+'" /><br /><label for="'+description+'">Description de votre fichier :</label><br /><textarea name="'+description+'" id="'+description+'" cols="40" rows="4"></textarea><br />';
     
     if(i==1)
      {
        allForm=form;
      }
      else
      {
        allForm=allForm+form;
      }
     
  }
  
  document.getElementById("AffichageForm").innerHTML=allForm;
  
  
  
  var inputNombre = document.createElement("input");
  inputNombre.setAttribute("type", "hidden");
  inputNombre.setAttribute("name", "nbPhoto");
  inputNombre.setAttribute("value", nombre);
  
  var inputSubmit = document.createElement("input");
  inputSubmit.setAttribute("type", "submit");
  inputSubmit.setAttribute("name", "SendAddPhoto");
  inputSubmit.setAttribute("value", "Envoyer");
  
  var element = document.getElementById("AffichageForm");
  
  var parent = element.parentNode;
  parent.appendChild(inputNombre);
  parent.appendChild(inputSubmit);
}

var contenuDescriptionPhoto; //variable qui va servir à stocker ce qui avait dans le contenu de la description avant de cliquer sur Modifier.
//Comme ca on pourra le restaurer facilement

function updateInfosPhoto(idPhoto)
{
  var blocDescriptionPhoto=document.getElementById("contenuDescriptionPhoto");
  
  contenuDescriptionPhoto=blocDescriptionPhoto.innerHTML;
  
  
  var letitre=document.getElementById("titrePhoto").innerHTML;
  
  blocDescriptionPhoto.innerHTML='';
  
  var labelTitre = document.createElement("label");
  labelTitre.setAttribute("for", "titre");
  labelTitre.innerHTML="Titre : ";
  
  var inputTitre = document.createElement("input");
  inputTitre.setAttribute("type", "text");
  inputTitre.setAttribute("name", "titre");
  inputTitre.setAttribute("id", "titre");
  inputTitre.setAttribute("size", "30");
  inputTitre.setAttribute("value", letitre);
  
  
   var inputId = document.createElement("input");
  inputId.setAttribute("type", "hidden");
  inputId.setAttribute("name", "id");
  inputId.setAttribute("id", "id");
  inputId.setAttribute("value", idPhoto);
  
  var buttonAnnuler = document.createElement("button");
  buttonAnnuler.innerHTML="Annuler";
  buttonAnnuler.setAttribute("onclick", "annulerModifPhoto()");
  
  var buttonEnvoyer = document.createElement("button");
  buttonEnvoyer.innerHTML="Envoyer";
  buttonEnvoyer.setAttribute("onclick", "envoyerModifPhoto()");
  
  blocDescriptionPhoto.appendChild(labelTitre);
  blocDescriptionPhoto.appendChild(inputTitre);
  blocDescriptionPhoto.appendChild(inputId);
  blocDescriptionPhoto.appendChild(buttonAnnuler);
  blocDescriptionPhoto.appendChild(buttonEnvoyer);
  
}

function annulerModifPhoto()
{
  var blocDescriptionPhoto=document.getElementById("contenuDescriptionPhoto");
  
  
  blocDescriptionPhoto.innerHTML=contenuDescriptionPhoto;
}

function getXhr()
{
  var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject) 
	{ 
		if (window.ActiveXObject)
		{
			try 
			{
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) 
			{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} 
		else 
		{
			xhr = new XMLHttpRequest(); 
		}
	} 
	else 
	{
		alert("Erreur avec le navigateur.");
		xhr = false;
	}
        
    return xhr;
}

function envoyerModifPhoto() //fonction permettant de modifier le titre et la description de la photo en ajax
{
  
  var xhr=getXhr();
  var titre = document.getElementById("titre").value;
  var id = document.getElementById("id").value;
  
   xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
		alert("Informations modifiées");
                
                var blocTitre = '<em><span id="titrePhoto">'+titre+'</span></em><img src="../Theme/img/bouton_modifier.jpg" onclick="updateInfosPhoto('+id+')" alt="Modifier" />';
                document.getElementById("contenuDescriptionPhoto").innerHTML=blocTitre;
                document.getElementById("optionsDescription").style.display="block";
			
		}
    };

  	xhr.open("POST", "../Php/galerie.php?EX=SendUpdatePhoto", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var data = "titre=" + titre + "&id="+id;
	xhr.send(data);
    
    
}

function envoyerJaimeJaimePas(idPhoto, idMembre, avis)
{
  var xhr=getXhr();
  xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
          alert("Votre avis a été pris en compte");
			
	}
    };

  xhr.open("POST", "../Php/galerie.php?EX=SendAvisPhoto", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  var data = "idPhoto=" + idPhoto +  "&idMembre=" + idMembre + "&avis="+avis;
  xhr.send(data);
  
}

//COMMENTAIRES

jQuery(document).ready(function() {
   jQuery("#formCommentaire").validate({
      rules: {
         "commentaire_photo":{
            "required": true,
            "minlength": 2,
            "maxlength": 400
         }
      }
  })
});

$(document).ready(function(){
      $("#formCommentaire").validate();
    });

function showDeleteFormCommentaire(id) //lorsqu'on clique sur "supprimer un album"
{
      var msg= 'Etes vous sur de vouloir supprimer le commentaire ? <input type="button" name="oui" value="Oui" onclick="supprimerCommentaire('+id+')" />  <input type="button" onclick="hideDeleteFormCommentaire('+id+')" value="Non" /></form>';
      document.getElementById("affichageMsgDelete_"+id).innerHTML=msg;
}

function hideDeleteFormCommentaire(id) //lorsqu'on clique sur "supprimer un album"
{
      var msg= '';
      document.getElementById("affichageMsgDelete_"+id).innerHTML=msg;
}

function supprimerCommentaire(idCommentaire)
{
  var xhr=getXhr();
  xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
          alert("Commentaire supprimé");
          document.getElementById("commentaire_"+idCommentaire).style.display="none";		
	}
    };

  xhr.open("POST", "../Php/galerie.php?EX=SendDeleteCommentaire", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  var data = "idCommentaire=" + idCommentaire;
  xhr.send(data);
}

function showDeleteFormPhoto(id, album) //lorsqu'on clique sur "supprimer un album"
{
      var msg= 'Etes vous sur de vouloir supprimer l\'album ? <form action="?EX=SendDeletePhoto&album='+album+'&id='+id+'" method="post"><input type="submit" name="oui" value="Oui" />  <input type="button" onclick="hideDeleteFormAlbum('+id+')" value="Non" /></form>';
      document.getElementById("affichageMsgDelete_"+id).innerHTML=msg;
}

function hideDeleteFormPhoto(id) //lorsqu'on clique sur "supprimer un album"
{
      var msg= '';
      document.getElementById("affichageMsgDelete_"+id).innerHTML=msg;
}

/*function supprimerPhotos() //FONCTION QUI POURRAIT SERVIR A SUPPRIMER PLUSIEURS PHOTO EN MEME TEMPS, MAIS PAS TROP SECURISE...
{
  var arrayLignes = document.getElementById("tabListePhoto").rows; //on récupère les lignes du tableau
  var nbLignes = arrayLignes.length;//on peut donc appliquer la propriété length


  for(var i=0; i<nbLignes; i++)//on peut directement définir la variable i dans la boucle
  {
  var arrayCellules = arrayLignes[i].cells;
  var nbCells = arrayCellules.length;
  var j = 0;
  while(j < nbCells)
  {
    var sup='<span style="float:right;"><input type="checkbox" /></span>';
    arrayCellules[j].innerHTML=arrayCellules[j].innerHTML+sup;
    j++;
  }
  }
}*/


