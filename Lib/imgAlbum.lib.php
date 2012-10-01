<?php
// On defini le header
header('Content-type: ' .image_type_to_mime_type(IMAGETYPE_JPEG));

$source = imagecreatefromjpeg($_GET['name']); // La photo est la source
$destination = imagecreatetruecolor(400, 200); // On crŽe la miniature vide

// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
$largeur_source = imagesx($source);
$hauteur_source = imagesy($source);
$largeur_destination = imagesx($destination);
$hauteur_destination = imagesy($destination);

// On crŽe la miniature
if($hauteur_source > $largeur_source)
{
    imagecopy($destination, $source, 0, 0, 60, 120, 460, 320);
}
else
{
    imagecopy($destination, $source, 0, 0, 100, 100, 500, 300);
}


// Le chemin vers le fichier de sauvegarde n'est pas dŽfini, le flux brut de l'image sera affichŽ directement.
imagejpeg($destination);
?>