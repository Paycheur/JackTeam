<?php
/*function valideChaine($chaineNonValide)
		{
  		$chaineNonValide = preg_replace('`\s+`', '_', trim($chaineNonValide));
  		$chaineNonValide = str_replace("'", "_", $chaineNonValide);
  		$chaineNonValide = preg_replace('`_+`', '_', trim($chaineNonValide));
                //strtr()
  		return ($chaineValide);
		}*/
function valideChaine($badstring)
{
    $pattern = Array("é", "è", "ê", "ç", "à", "â", "î", "ï", "ù", "ô");
    $rep_pat = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o");
    $cleaned= str_replace($pattern, $rep_pat, $badstring);
    $cleaned = preg_replace('`\s+`', '_', trim($cleaned));
  	$cleaned = str_replace("'", "_", $cleaned);
  	$cleaned = preg_replace('`_+`', '_', trim($cleaned));
    return $cleaned;
}
                
?>