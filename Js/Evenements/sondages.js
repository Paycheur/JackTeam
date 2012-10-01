
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

function envoyerSondage(ex, value, idSujet, typeSend, nomBout) //EX : la variable de requete, value : oui (1) ou non (0), idSujet : l'id du sujet du sondage, typeSend : new ou update
{
  
  var xhr=getXhr();
  var nbPour = document.getElementById(idSujet+"nbMembresPour").innerHTML;
  var nbContre = document.getElementById(idSujet+"nbMembresContre").innerHTML;
   xhr.onreadystatechange = function() 
    { 
    	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) //on test pour voir si tout est près (open, send...)
    	{ 
		alert("Avis envoyé");
		if(value==1)
		{
		  if(typeSend=="new")
		  {
		    document.getElementById(idSujet+"nbMembresPour").innerHTML=parseInt(nbPour)+1;
		    document.getElementById(idSujet+"bout"+nomBout+"Oui").disabled=true;
		  }
		  else
		  {
		    document.getElementById(idSujet+"nbMembresPour").innerHTML=parseInt(nbPour)+1;
		    document.getElementById(idSujet+"nbMembresContre").innerHTML=parseInt(nbContre)-1;
		    document.getElementById(idSujet+"bout"+nomBout+"Oui").disabled=true;
		    document.getElementById(idSujet+"bout"+nomBout+"Non").disabled=false;
		  }
		  
		}
		else
		{
		  if(typeSend=="new")
		  {
		    document.getElementById(idSujet+"nbMembresContre").innerHTML=parseInt(nbContre)+1;
		    document.getElementById(idSujet+"bout"+nomBout+"Non").disabled=true;
		  }
		  else
		  {
		    document.getElementById(idSujet+"nbMembresContre").innerHTML=parseInt(nbContre)+1;
		    document.getElementById(idSujet+"nbMembresPour").innerHTML=parseInt(nbPour)-1;
		    document.getElementById(idSujet+"bout"+nomBout+"Non").disabled=true;
		    document.getElementById(idSujet+"bout"+nomBout+"Oui").disabled=false;
		  }
		}
	}
    };

  	xhr.open("POST", "../Php/evenements.php?EX="+ex, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var data = "value="+value+"&id="+idSujet;
	xhr.send(data);
    
    
}