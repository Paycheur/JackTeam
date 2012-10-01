
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

function envoyerSondage(ex, value, idSujet, typeSend) //EX : la variable de requete, value : oui (1) ou non (0), idSujet : l'id du sujet du sondage, typeSend : new ou update
{
  
  var xhr=getXhr();
  var nbPour = document.getElementById(idSujet+"nbMembresPour"+ex).innerHTML;
  var nbContre = document.getElementById(idSujet+"nbMembresContre"+ex).innerHTML;
   xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
		alert("Avis envoyé");
		if(value==1)
		{
		  if(typeSend=="new")
		  {
		    document.getElementById(idSujet+"nbMembresPour"+ex).innerHTML=parseInt(nbPour)+1;
		    document.getElementById(idSujet+"bout"+ex+"Oui").disabled=true;
		  }
		  else
		  {
		    document.getElementById(idSujet+"nbMembresPour"+ex).innerHTML=parseInt(nbPour)+1;
		    document.getElementById(idSujet+"nbMembresContre"+ex).innerHTML=parseInt(nbContre)-1;
		    document.getElementById(idSujet+"bout"+ex+"Oui").disabled=true;
		    document.getElementById(idSujet+"bout"+ex+"Non").disabled=false;
		  }
		  
		}
		else
		{
		  if(typeSend=="new")
		  {
		    document.getElementById(idSujet+"nbMembresContre"+ex).innerHTML=parseInt(nbContre)+1;
		    document.getElementById(idSujet+"bout"+ex+"Non").disabled=true;
		  }
		  else
		  {
		    document.getElementById(idSujet+"nbMembresContre"+ex).innerHTML=parseInt(nbContre)+1;
		    document.getElementById(idSujet+"nbMembresPour"+ex).innerHTML=parseInt(nbPour)-1;
		    document.getElementById(idSujet+"bout"+ex+"Non").disabled=true;
		    document.getElementById(idSujet+"bout"+ex+"Oui").disabled=false;
		  }
		}
	}
    };

  	xhr.open("POST", "../Php/evenements.php?EX=SendSond"+ex, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var data = "value="+value+"&id="+idSujet;
	xhr.send(data);
    
    
}

function envoyerParticipation(ex, idSujet, value) //EX : la variable de requete, value : oui (1) ou non (0), idSujet : l'id du sujet du sondage, typeSend : new ou update
{
  
  var xhr=getXhr();
   xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
		alert("Avis envoyé");
		if(value==2)
		{
		    document.getElementById("msgParticipation").innerHTML="Cool ! Tu participe";
		    document.getElementById("ParticipationOui").disabled=true;
		  
		}
		else if(value==1)
		{
		  document.getElementById("msgParticipation").innerHTML="Peut être que tu participeras...";
		  document.getElementById("ParticipationPe").disabled=true;
		}
		else
		{
		  document.getElementById("msgParticipation").innerHTML="Tu ne participeras pas à l'évènement, sale Juif !";
		  document.getElementById("ParticipationNon").disabled=true;
		}
	}
    };

  	xhr.open("POST", "../Php/evenements.php?EX="+ex, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var data = "value="+value+"&idEvent="+idSujet;
	xhr.send(data);
    
    
}

function envoyerBlocResume(idEvent) //EX : la variable de requete, value : oui (1) ou non (0), idSujet : l'id du sujet du sondage, typeSend : new ou update
{
  
  var xhr=getXhr();
  var content = document.getElementById("insertBlocResume").value;
  
   xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
	  alert("Actualisé");
	  document.getElementById("blocResume").innerHTML=content;
	}
    };

  	xhr.open("POST", "../Php/evenements.php?EX=SendBlocResume", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var data = "idEvent="+idEvent+"&content="+content;
	xhr.send(data);
    
    
}

function envoyerModifDates(idDates, idEvent)
{
  var xhr=getXhr();
  var dateDebut = document.getElementById("formDateDebut").value;
  var dateFin = document.getElementById("formDateFin").value;
  
   xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
	  alert("Modifié !");
	  var queryAll = document.querySelectorAll('#dates'+idDates+' span');
	  queryAll[0].innerHTML=dateDebut;
	  queryAll[1].innerHTML=dateFin;
	  
	  var form = document.getElementById("formAddDates");
	  form.setAttribute("method", "post");
	  form.setAttribute("action", "?EX=SendAddDates&idEvent="+idEvent);
	  
	  var submit = document.getElementById('sendDates');
	  submit.setAttribute("type", "submit");
	  submit.removeAttribute("onclick");
	  
	  document.getElementById("titreFormDates").innerHTML="Proposer des dates";
	  
	  document.getElementById('formDateDebut').value="";
	  document.getElementById('formDateFin').value="";
	}
    };

  	xhr.open("POST", "../Php/evenements.php?EX=SendUpdateDatesV", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var data = "idDates="+idDates+"&dateDebut="+dateDebut+"&dateFin="+dateFin;
	xhr.send(data);
}

function envoyerModifLieu(idLieu, idEvent)
{
  var xhr=getXhr();
  var lieu = document.getElementById("formNomLieu").value;
  var infoPrix = document.getElementById("formInfoPrix").value;
  
   xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
	  alert("Modifié !");
	  var queryAll = document.querySelectorAll('#lieu'+idLieu+' span');
	  queryAll[0].innerHTML=lieu;
	  queryAll[1].innerHTML=infoPrix;
	  
	  var form = document.getElementById("formAddLieu");
	  form.setAttribute("method", "post");
	  form.setAttribute("action", "?EX=SendAddLieu&idEvent="+idEvent);
	  
	  var submit = document.getElementById('sendLieu');
	  submit.setAttribute("type", "submit");
	  submit.removeAttribute("onclick");
	  
	  document.getElementById("titreFormLieu").innerHTML="Proposer un lieu";
	  
	  document.getElementById('formNomLieu').value="";
	  document.getElementById('formInfoPrix').value="";
	}
    };

  	xhr.open("POST", "../Php/evenements.php?EX=SendUpdateLieuV", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var data = "idLieu="+idLieu+"&lieu="+lieu+"&infoPrix="+infoPrix;
	xhr.send(data);
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

  xhr.open("POST", "../Php/evenements.php?EX=SendDeleteCommentaire", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  var data = "idCommentaire=" + idCommentaire;
  xhr.send(data);
}