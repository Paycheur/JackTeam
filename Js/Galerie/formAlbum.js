

jQuery.extend(jQuery.validator.messages, {
  required: "Ce champ est obligatoire.",
  remote: "votre message",
  email: "votre message",
  url: "votre message",
  date: "votre message",
  dateISO: "votre message",
  number: "votre message",
  digits: "votre message",
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
   jQuery("#formAddAlbum").validate({
      rules: {
         "titre":{
            "required": true,
            "minlength": 2,
            "maxlength": 20
         },
         "description": {
            "required": true,
            "minlength": 5,
            "maxlength": 100
         }
      }
  })
});

$(document).ready(function(){
      $("#formAddAlbum").validate();
    });

function updateAlbum(id)
{
      var description = document.getElementById("description"+id).innerHTML;
      var titre = document.getElementById("titre"+id).innerHTML;
      
      document.getElementById("formDescription").value=description;
      document.getElementById("formTitre").value=titre;
      
      var input = document.createElement("input");
      input.setAttribute("type", "hidden");
      input.setAttribute("name", "idAlbum");
      input.setAttribute("value", id);
      
      document.getElementById("submitFormAlbum").setAttribute("name", "SendUpdatelbum"); //ne sert à rien
      document.getElementById("formAddAlbum").setAttribute("action", "?EX=SendUpdateAlbum");
      
     var i = document.getElementById("submitFormAlbum");
      var parent = i.parentNode;
      parent.insertBefore(input, i);

      
}

function showDeleteFormAlbum(id) //lorsqu'on clique sur "supprimer un album"
{
      var msg= 'Supprimer ? <form action="?EX=SendDeleteAlbum&id='+id+'" method="post"><input type="submit" name="oui" value="Oui" />  <input type="button" onclick="hideDeleteFormAlbum('+id+')" value="Non" /></form>';
      document.getElementById("affichageMsgDelete_"+id).innerHTML=msg;
}

function hideDeleteFormAlbum(id) //lorsqu'on clique sur "supprimer un album"
{
      var msg= '';
      document.getElementById("affichageMsgDelete_"+id).innerHTML=msg;
}
