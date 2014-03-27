<?php
/**********
* On récupère tous les champs du formulaire
* et on les place dans un array MyForm en fonction
* du formulaire d'entrée
*
*/
$MyForm = array ();
if (!empty($_POST['form'])) {
	$form=$_POST['form'];
	unset ($_POST['form']);
}
switch ($form) {
	//cas du formulaire de login
	case "login" :
		$user_mail=$_POST['user_mail'];
		$user_password=$_POST['user_password'];
		$remember_me=$_POST['remember_me'];
		$expiration=(empty($remember_me))?0:time() + 31536000;
		$login_id=verif_login($user_mail,$user_password);
		if ($login_id['authenticated']) {
			// Si Login OK, on renseigne le cookie
			setcookie("login[id]",$login_id['id_player'],$expiration);
			setcookie("login[firstname]",$login_id['first'],$expiration);
			setcookie("login[name]",$login_id['last'],$expiration);
			setcookie("login[authenticated]",TRUE,$expiration);
			// on recharge la page pour afficher l'information
			echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
		}
		else {
			$result="Echec d'identification";
		}
		break;
	//cas du formulaire d'inscription
	case "registration" :
		foreach ($_POST as $key => $value ){
			if ($value !== '') {
				// Récupération de champs de confirmation du mot de passe pour vérification
				if (in_array($key, array('confirm'))) {
					$confirm='"'.md5($value).'"';
				}
				elseif (in_array($key, array('password'))) {
					$MyForm['player'][$key]='"'.md5($value).'"';
				}
				else {
					$MyForm['player'][$key]='"'.$value.'"';
				}
			}
			else {
				$result="Tous les champs sont obligatoires";
				unset ($MyForm);
				break;
			}
		}
		break;
		
}

/**********
 * Traitement des formulaires
 * 1/ On récupère le tableau MyForm et on crée la requête SQL
 * 2/ On vérifie la validité des informations avant insertion dans la table
 * 3/ On envoie un mail
*/
if (!empty($MyForm) && $form == "registration") {
	// Vérification de la validité des champs
	if(!preg_match("#^[^&$\#!:.;,?/]{2,20}+$#", $MyForm['player']['first'])) {
		$message = "Votre prénom doit comporter entre 2 et 20 caractères<br />";
	}
	elseif (!preg_match("#^[^&$\#!:.;,?/]{2,20}+$#", $MyForm['player']['last'])) {
		$message = "Votre nom doit comporter entre 2 et 20 caractères<br />";
	}
	elseif(!preg_match("#^[^&$\#!:.;?,/]{6,}+$#", $MyForm['player']['password'])) {
		$message = "Votre mot de passe doit comporter au moins 6 caractères";
	}
	elseif($MyForm['player']['password'] != $confirm) {
		$message = "Vous avez saisi 2 mots de passe différents";
	}
	elseif(!preg_match("#[a-z1-9.-_]+@[a-z1-9.-]{2,}\.[a-z]{2,4}#",$MyForm['player']['mail'])) {
		$message = "Votre adresse e-mail n'est pas valide";
	}
	else {
		// Connexion à la base de données
		$SQLConnect=new MySQL(host,user,passwd,db);
		$SQLConnect->table(tbl_players);
		$SQLConnect->SQLExtractionFields="first,last,mail";

		// Vérification de l'unicité du nom d'utilisateur et de l'adresse e-mail
		$SQLConnect->SetFilter("first", "=", str_replace('"','',$MyForm['player']["first"]));
		$SQLConnect->SetFilter("last", "=", str_replace('"','',$MyForm['player']["last"]));
		$SQL_User_Query=$SQLConnect->Query();
		$SQL_User_NumRow=$SQLConnect->NumRows($SQL_User_Query);
		$SQLConnect->ClearFilter();
		$SQLConnect->SetFilter("last", "=", str_replace('"','',$MyForm['player']["mail"]));
		$SQL_Mail_Query=$SQLConnect->Query();
		$SQL_Mail_NumRow=$SQLConnect->NumRows($SQL_Mail_Query);
		if ($SQL_User_NumRow > 0) {
			$message="L'utilisateur ".$MyForm['player']['first']." ".$MyForm['player']['last']." existe déjà";
		}
		elseif ($SQL_Mail_NumRow > 0) {
			$message="Le mail ".$MyForm['player']['mail']." existe déjà";
		}
		else {
			// Création du compte utilisateur
			$columns=array_keys($MyForm['player']);
			$values=array_values($MyForm['player']);
			$SQLInsert=$SQLConnect->Insert(tbl_players, $columns, $values);
			$result=$SQLInsert;
				
			// Si une erreur survient
			if(!$result) {
				$message = "Erreur d'accès à la base de données lors de la création du compte utilisateur";
			}
			else {
				$result = "Success";
				// Envoi du mail d'activation
				/*					$sujet = "Activation de votre compte utilisateur";

				$message = "Pour valider votre inscription, merci de cliquer sur le lien suivant :\n";
				$message .= "http://" . $_SERVER["SERVER_NAME"];
				$message .= "/activer-compte-utilisateur.php?id=" . mysql_insert_id();
				$message .= "&clef=" . $clef_activation;

				// Si une erreur survient
				if(!@mail($_POST["TB_Adresse_Email"], $sujet, $message))
				{
				$message = "Une erreur est survenue lors de l'envoi du mail d'activation<br />\n";
				$message .= "Veuillez contacter l'administrateur afin d'activer votre compte";
				}
				else
				{
					
				// Message de confirmation
				$message = "Votre compte utilisateur a correctement été créé<br />\n";
				$message .= "Un email vient de vous être envoyer afin de l'activer";
					
				// On masque le formulaire
				$masquer_formulaire = true;
				*/

			}
		}
	}
}
?>