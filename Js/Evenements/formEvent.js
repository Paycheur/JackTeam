jQuery.extend(jQuery.validator.messages, {
  required: "Ce champ est obligatoire.",
  remote: "votre message",
  email: "votre message",
  url: "votre message",
  date: "Ce champ doit être de type : AAAA-MM-JJ",
  dateISO: "votre message",
  number: "Ce champ doit être un nombre.",
  digits: "Ce champs doit être de type numérique.",
  creditcard: "votre message",
  equalTo: "votre message",
  accept: "votre message",
  maxlength: jQuery.validator.format("{0} caractéres maximum."),
  minlength: jQuery.validator.format("{0} caractéres minimum."),
  rangelength: jQuery.validator.format("votre message  entre {0} et {1} caractéres."),
  range: jQuery.validator.format("votre message  entre {0} et {1}."),
  max: jQuery.validator.format("votre message  inférieur ou égal à {0}."),
  min: jQuery.validator.format("votre message  supérieur ou égal à {0}.")
});


jQuery(document).ready(function() {
   jQuery("#formAddAnniv").validate({
      rules: {
         "titre":{
            "required": true,
            "minlength": 2,
            "maxlength": 40
         },
         "presentation": {
            "required": true,
            "minlength": 20,
            "maxlength": 1000
         },
         "date": {
            "required": true
         }
      }
  })
});

$(document).ready(function(){
      $("#formAddAnniv").validate();
    });

//PARTIE LISTE CADEAUX

$().ready(function() {
  $("button.LienDetailsCadeaux").bind('click', function(e) {
			
  if($("button.LienDetailsCadeaux").text() == 'Afficher les détails')
  {
      $("ul.details_cadeau").show("slow");
      $('button.LienDetailsCadeaux').text('Cacher les détails');
  }
  else if($("button.LienDetailsCadeaux").text() == 'Cacher les détails')
  {
      $("ul.details_cadeau").hide("slow");
      $('button.LienDetailsCadeaux').text('Afficher les détails');
  }
  });

//Ci dessous la fonction qui va permettre de cacher ou visualiser les formulaires de proposition
$("button.showFormsPropositions").bind('click', function(e) {
  if($('button.showFormsPropositions').text() == "Ajouter une proposition")
  {
    var textResume = $('p#blocResume').text();
    $('div#blocAddPropositions').show("slow");
    $('button.showFormsPropositions').text('Cacher les formulaires');
  }
  else if($('a.showFormsPropositions').text() == "Cacher les formulaires")
  {
    $('div#blocAddPropositions').hide("slow");
    $('button.showFormsPropositions').text('Ajouter une proposition');
  }
  });

$("img.imgModifier").bind('click', function(e) {
  $('div#blocAddPropositions').show("slow");
    $('button.showFormsPropositions').text('Cacher les formulaires');
});
//PARTIE BLOC RESUME

$("button.modifBlocResume").bind('click', function(e) {
  if($('button.modifBlocResume').text() == "Modifier")
  {
    var textResume = $('p#blocResume').text();
    $('textarea#insertBlocResume').show("slow");
    $('textarea#insertBlocResume').text(textResume);
    $('button.modifBlocResume').text('Valider');
    var idEvent = $('button.modifBlocResume').attr('idEvent');
    $('button.modifBlocResume').attr("onclick", "envoyerBlocResume("+idEvent+")")
    $('p#blocResume').hide("slow");
  }
  else if($('button.modifBlocResume').text() == "Valider")
  {
    $('textarea#insertBlocResume').hide("slow");
    $('button.modifBlocResume').text('Modifier');
    $('p#blocResume').show("slow");
  }
  });
})

//FORM ADD CADEAU
jQuery(document).ready(function() {
   jQuery("#formAddCadeau").validate({
      rules: {
         "titre":{
            "required": true,
            "minlength": 2,
            "maxlength": 40
         },
         "description": {
            "required": true,
            "minlength": 5,
            "maxlength": 500
         },
         "prix": {
            "required": true,
            "number": true
         }
      }
  })
});

$(document).ready(function(){
      $("#formAddCadeau").validate();
    });

//DELETE CADEAU

function showDeleteFormCadeau(idEvent, idCadeau) //lorsqu'on clique sur "supprimer un cadeau"
{
      var msg= 'Etes vous sur de vouloir supprimer le cadeau ? <form action="?EX=SendDeleteCadeau&idEvent='+idEvent+'&idCadeau='+idCadeau+'" method="post"><input type="submit" name="oui" value="Oui" />  <input type="button" onclick="hideDeleteFormCadeau('+idCadeau+')" value="Non" /></form>';
      document.getElementById("affichageMsgDelete_"+idCadeau).innerHTML=msg;
}

function hideDeleteFormCadeau(idCadeau) 
{
      var msg= '';
      document.getElementById("affichageMsgDelete_"+idCadeau).innerHTML=msg;
}

//DELETE EVENT

function showDeleteFormEvent(idEvent, type) //lorsqu'on clique sur "supprimer un cadeau"
{
      var msg= 'Etes vous sur de vouloir supprimer l\'évenement ? <form action="?EX=SendDeleteEvent&type='+type+'&idEvent='+idEvent+'" method="post"><input type="submit" name="oui" value="Oui" />  <input type="button" onclick="hideDeleteFormEvent('+idEvent+')" value="Non" /></form>';
      document.getElementById("affichageMsgDelete_"+idEvent).innerHTML=msg;
}

function hideDeleteFormEvent(idEvent) 
{
      var msg= '';
      document.getElementById("affichageMsgDelete_"+idEvent).innerHTML=msg;
}

//FORM ADD VACANCE

$().ready(function() {
  $("input#dateOui").bind('click', function(e) {		
      $("div#definitionDate").show("slow");
  });
  $("input#dateNon").bind('click', function(e) {		
      $("div#definitionDate").hide("slow");
  });
  $("input#lieuOui").bind('click', function(e) {		
      $("div#definitionLieu").show("slow");
  });
  $("input#lieuNon").bind('click', function(e) {		
      $("div#definitionLieu").hide("slow");
  });
  });



jQuery(document).ready(function() {
   jQuery("#formAddVacance").validate({
      rules: {
         "titre":{
            "required": true,
            "minlength": 2,
            "maxlength": 40
         },
         "presentation": {
            "required": true,
            "minlength": 5,
            "maxlength": 500
         },
         "dateDebut":{
            "required": true,
            "minlength": 10
         },
         "lieu":{
            "required": true,
            "minlength" : 2,
            "maxlength" : 100
         }
      }
  })
   jQuery("#formAddSoirjour").validate({
      rules: {
         "titre":{
            "required": true,
            "minlength": 2,
            "maxlength": 40
         },
         "presentation": {
            "required": true,
            "minlength": 5,
            "maxlength": 500
         },
         "date":{
            "required": true,
            "minlength": 10
         },
         "heure":{
            "required": true,
            "minlength" : 2,
            "maxlength" : 20
         },
         "lieu":{
            "required": true,
            "minlength" : 2,
            "maxlength" : 100
         },
         "lieuDefini":{
            "required": true
         },
         "type":{
            "required": true
         }
      }
  })
   jQuery("#formAddDates").validate({
      rules: {
         "dateDebut":{
            "required": true,
            "minlength": 10
         },
        "dateFin":{
            "required": true,
            "minlength": 10
         }
      }
  })
   jQuery("#formAddLieu").validate({
      rules: {
         "lieu":{
            "required": true,
            "minlength": 3,
            "maxlength": 100
         },
         "infoPrix":{
            "maxlength": 80
         }
      }
  })
});

$(document).ready(function(){
      $("#formAddVacance").validate();
      $("#formAddSoirjour").validate();
      $("#formAddDates").validate();
      $("#formAddLieu").validate();
    });

//MODIFIER UNE PROPOSITION DE DATE

function modifierPropositionDatesV(idDates, idEvent)
{
  var queryAll = document.querySelectorAll('#dates'+idDates+' span');
  var dateDebut = queryAll[0].innerHTML;
  var dateFin = queryAll[1].innerHTML;
  
  document.getElementById('formDateDebut').value=dateDebut;
  document.getElementById('formDateFin').value=dateFin;
  var form = document.getElementById("formAddDates");
  form.removeAttribute("action");
  form.removeAttribute("method");
  
  var submit = document.getElementById('sendDates');
  submit.setAttribute("type", "button");
  submit.setAttribute("onclick", "envoyerModifDates("+idDates+", "+idEvent+")");
  
  document.getElementById("titreFormDates").innerHTML="MODIFICATION";
}

//MODIFIER UNE PROPOSITION DE LIEU

function modifierPropositionLieuV(idLieu, idEvent)
{
  var queryAll = document.querySelectorAll('#lieu'+idLieu+' span');
  var lieu = queryAll[0].innerHTML;
  var infoPrix = queryAll[1].innerHTML;
  
  document.getElementById('formNomLieu').value=lieu;
  document.getElementById('formInfoPrix').value=infoPrix;
  var form = document.getElementById("formAddLieu");
  form.removeAttribute("action");
  form.removeAttribute("method");
  
  var submit = document.getElementById('sendLieu');
  submit.setAttribute("type", "button");
  submit.setAttribute("onclick", "envoyerModifLieu("+idLieu+", "+idEvent+")");
  
  document.getElementById("titreFormLieu").innerHTML="MODIFICATION";
}

//DELETE COMMENTAIRE
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

