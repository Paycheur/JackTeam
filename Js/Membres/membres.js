var src = new Array();
var i=0;

src[i++] = '../Js/Lib/JQuery.js';
src[i++] = '../Js/Lib/JQueryValidate.js';
src[i++] = '../Js/Lib/JQueryNailThumb.js';
src[i++] = '../Js/Lib/JQueryLightbox.js';

src[i++] = '../Js/Membres/formInscription.js';


src[i++] = '../Js/Lib/noty/jquery.noty.js';
src[i++] = '../Js/Lib/noty/layouts/topLeft.js';
src[i++] = '../Js/Lib/noty/themes/default.js';
src[i++] = '../Js/Lib/noty/notifications.js';

src[i++] = '../Js/Lib/modernizr-2.5.3.min.js';
src[i++] = '../Js/Lib/Modal.js';

src[i++] = '../Js/ModalScript.js';
src[i++] = '../Js/PhotoScript.js';

for (var j = 0; j < i; ++j)
{
  document.write('<script type="text/javascript" src="'+src[j]+'"></script>');
}

