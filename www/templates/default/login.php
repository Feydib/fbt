<?php 
echo "<div class=\"layout\"></div>";
echo "<div id=\"pop_login\">";
	echo "<div id=\"formulaire\">";
	echo "<form action=\"euro.php?menu=processing\" method=\"post\">";
	echo "<fieldset>";
	echo "<legend> Identification </legend>";
	echo "<p>";
    	echo "<label for=\"user_mail\">Mail</label><input type=\"text\" id=\"user_mail\" name=\"user_mail\" /><br>";
    	echo "<label for=\"user_password\">Password</label><input type=\"password\" id=\"user_password\" name=\"user_password\" /><br>";
    echo "</p>";
    echo "<p>";
		echo "<input type=\"checkbox\" name=\"remember_me\" /> Se souvenir de moi";
    echo "</p>";
    echo "</fieldset>";
	echo "<p>";
    	echo "<input type=\"hidden\" name=\"form\" value=\"login\"/>";
    	echo "<input type=\"submit\" value=\"OK\" />";
    	echo "<input type=\"reset\" value=\"Annuler\" onclick='javascript : location=\"fbt.php\"'/>";
	echo "</p>";
	echo "</form>";
echo "</div> <!-- #formulaire -->";
echo "</div> <!-- #pop_login -->";
?>