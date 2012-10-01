jQuery.extend(jQuery.validator.messages, {
  required: "Ce champ est obligatoire.",
  remote: "votre message",
  email: "Ce champ doit être un email valide.",
  url: "votre message",
  date: "votre message",
  dateISO: "votre message",
  number: "votre message",
  digits: "votre message",
  creditcard: "votre message",
  equalTo: "Les mots de passe doivent être identiques.",
  accept: "votre message",
  maxlength: jQuery.validator.format("{0} caractéres maximum."),
  minlength: jQuery.validator.format("{0} caractéres minimum."),
  rangelength: jQuery.validator.format("votre message  entre {0} et {1} caractéres."),
  range: jQuery.validator.format("votre message  entre {0} et {1}."),
  max: jQuery.validator.format("votre message  inférieur ou égal à {0}."),
  min: jQuery.validator.format("votre message  supérieur ou égal à {0}.")
});


jQuery(document).ready(function() {
   jQuery("#formInscription").validate({
      rules: {
         "nom":{
            "required": true,
            "minlength": 2,
            "maxlength": 30
         },
         "prenom": {
            "required": true,
            "minlength": 2,
            "maxlength": 30
         },
         "pseudo": {
            "required": true,
            "minlength": 3,
            "maxlength": 20
         },
         "email": {
            "required" : true,
            "email": true
         },
         "mdp": {
            "required" : true,
            "minlength": 6,
            "maxlength": 30
         },
         "mdp_c": {
            "required" : true,
            "equalTo" : "#mdp",
            "minlength": 6,
            "maxlength": 30
         }
      }
  })
});

$(document).ready(function(){
      $("#formInscription").validate();
    });