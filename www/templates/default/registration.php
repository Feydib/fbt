<?php

// Récupération de la date
$date=date('Y-m-d G:i:s');
// Génération d'une clé d'activation aléatoire
$key = substr(md5($date),0,15);


/**********
 * Affichage du formulaire
*/

echo "<div class=\"layout\">\n</div>";
echo "<div id=\"pop_inscription\">";
	echo "<div id=\"formulaire\">";
	echo "<form action=\"euro.php?menu=processing\" method=\"post\">";
	echo "<fieldset>";
	echo "<legend> Inscription </legend>";
	echo "<p>";
    	echo "<label for=\"first\">Nom</label><input type=\"text\" id=\"last\" name=\"last\" value=\"\"/><br>";
    	echo "<label for=\"last\">Prénom</label><input type=\"text\" id=\"first\" name=\"first\" value=\"\"/><br>";
    	echo "<label for=\"mail\">Mail</label><input type=\"text\" id=\"mail\" name=\"mail\" value=\"\"/><br>";
    	echo "<label for=\"password\">Password</label><input type=\"password\" id=\"password\" name=\"password\" value=\"\"/><br>";
    	echo "<label for=\"confirm\">Password</label><input type=\"password\" id=\"confirm\" name=\"confirm\" value=\"\"/><br>";
    	echo "</p>";
	echo "</fieldset>";
	
	echo "<p>";
		echo "<input type=\"hidden\" name=\"registration_date\" value=\"$date\" />";
		echo "<input type=\"hidden\" name=\"activation_key\" value=\"$key\" />";
    	echo "<input type=\"hidden\" name=\"form\" value=\"registration\" />";
    	echo "<input type=\"submit\" value=\"S'inscrire\" />";
    	echo "<input type=\"reset\" value=\"Annuler\" onclick='javascript : location=\"euro.php\"'/>";
	echo "</p>";
	echo "</form>";
echo "</div> <!-- #formulaire -->";
echo "</div> <!-- #pop_inscription-->";


?>