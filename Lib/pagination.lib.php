<?php
function page($nb_par_page, $page_cible, $base_cible)
{

	$page = $_GET['page'];
	$page_preced = $page - 1 ;
	$page_suiv = $page + 1;
	
	
	$connexion = new PDO('mysql:host=localhost;dbname=jack', 'root', ''); // on se connecte �� la bdd
	$req = $connexion->query('SELECT COUNT(*) AS nb_rentree FROM ' . $base_cible . ''); // on compte le nombre de membre
	$donnees = $req->fetch();
	$nb_rentree = $donnees['nb_rentree'];
	$nb_page = ceil($nb_rentree/$nb_par_page); // calcul du nbr de page qu'il faut
	$derniere_page_moins_1 = $nb_page - 1;	
	
	if($page != 1) // afficher pr̩c̩dent
	{
	echo '<a href="' . $page_cible . '&page=' . $page_preced . '">Pr̩c̩dent</a> ';
	}
		if ($nb_page < 7 + ($nb_par_page * 2))	// ̤a fait bcp mais on verra qd on aura bcp de page, on r̩glera les coeff
		{	
			for ($i = 1 ; $i <= $nb_page ; $i++)
			{
				if($i==$page)
				{
				echo('- ' . $i . ' ');
				}
				else
				{
				echo '- <a href="' . $page_cible . '&page=' . $i . '">' . $i . '</a> ';
				}
			}
		}
		else if($nb_page > 5 + ($nb_par_page * 2))	
		{
		
			if($page < 1 + ($nb_par_page * 2))		
			{
				for ($i = 1; $i < 4 + ($nb_par_page * 2); $i++)
				{
					if ($i == $page)
					{
					echo('- ' . $i . ' ');
					}
					else
					{
					echo('- <a href="' . $page_cible . '&page=' . $i . '">' . $i . '</a> ');					
					}
				}
				echo('- ... ');
				echo('- <a href="' . $page_cible . '&page=' . $derniere_page_moins_1 . '">' . $derniere_page_moins_1 . '</a> ');
				echo('- <a href="' . $page_cible . '&page=' . $nb_page . '">' . $nb_page . '</a> ');
			}
			
			else if($nb_page - ($nb_par_page * 2) > $page AND $page > ($nb_par_page * 2))
			{
				echo('- <a href="' . $page_cible . '&page=1">1</a> ');
				echo('- <a href="' . $page_cible . '&page=2">2</a> ');
				echo('- ... ');
				for ($i = $page - $nb_par_page; $i <= $page + $nb_par_page; $i++)
				{
					if ($i == $page)
					{
					echo('- ' . $i . ' ');
					}
					else
					{
					echo('- <a href="' . $page_cible . '&page=' . $i .'">' . $i . '</a> ');
					}						
				}
				echo('- ... ');
				echo('- <a href="' . $page_cible . '&page=' . $derniere_page_moins_1 . '">' . $derniere_page_moins_1 . '</a> ');
				echo('- <a href="' . $page_cible . '&page=' . $nb_page . '">' . $nb_page . '</a> ');
			}
			
			else
			{
				echo('- <a href="' . $page_cible . '&page=1">1</a> ');
				echo('- <a href="' . $page_cible . '&page=2">2</a> ');
				echo('- ... ');
				for ($i = $nb_page - (2 + ($nb_par_page * 2)); $i <= $nb_page; $i++)
				{
					if ($i == $page)
					{
						echo('- ' . $i . ' ');
					}
					else
					{
						echo('- <a href="' . $page_cible . '&page=' . $i .'">' . $i . '</a> ');	
					}
				}
			}
		}
		echo(' -'); // ̤a fait jolie	
	if($page != $nb_page) // afficher suivant
	{
	echo ' <a href="' . $page_cible . '&page=' . $page_suiv . '">Suivant</a> ';
	}
}
?>