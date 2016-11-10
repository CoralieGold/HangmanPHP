<?php
	//FICHIERS INCLUS
	include_once("connexion.inc");
	
	//CONNEXION SESSION UTILISATEUR
	if(!isset($_SESSION['login'])) //si l'utilisateur ne s'est pas connecté
	{
		if(isset($_POST['soumission']))
		{
			//si l'utilisateur a cliqué sur le bouton pour se connecter, alors
			$mdp = $_POST['mdp'];
			$login = $_POST['login'];
			//on vérifie que le login et le mot de passe correspondent à une association aussi présente dans la base de données
			$req = $connexion->prepare("SELECT login FROM Pendu_Joueur WHERE login = :login AND mdp = :mdp");
			$req->execute(array('login' => $login, 'mdp' => $mdp));
			$resultat = $req->fetch();

			if(!$resultat)
			{
				//si ce n'est pas le cas, on affiche un message d'erreur
				$msg = 'Mauvais identifiant ou mot de passe !';
			}
			else
			{
				//si c'est bon, on démarre une session et on va sur la page d'accueil du jeu
				session_start();
				$_SESSION['login'] = $login;
				$_SESSION['mdp'] = $mdp;
				header("Location:AccueilJeu.php");
				exit();
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/jeu.css">
		<link rel="shortcut icon" href="images/favicon.ico">
		
		<title>Jeu PHP</title>
	</head>
	<body>
		<aside>
			<form method="post" action="Accueil.php" id="connecte">
				<fieldset>
					<legend>Connecte-toi pour jouer !</legend>
						<p>Utilisateur : <input type="text" name="login" /></p>
						<p>Mot de passe : <input type="password" name="mdp" /></p>
						<p><input type="submit" value="Se connecter" name="soumission" /></p>
						<?php
								if(isset($msg))
								{
									//on affiche dans le formulaire le message d'erreur si l'utilisateur n'a pas pu se connecter
									echo $msg;
								}
						?>
				</fieldset>
				<a href="Inscription.php">S'inscrire</a>	
			</form>

		</aside>
		<header>
			<a href="#" id="logo"></a>
			<nav>
				<ul>
					<a href="Accueil.php"><li>Accueil</li></a>
					<a href="Score.php"><li>Scores</li></a>
					<a href="Theme.php"><li>Thème</li></a>
					<a href="Contact.php"><li>Contact</li></a>
				</ul>
			</nav>
		</header>
		<main>
			<h1>Bienvenue sur le Pendu en PHP !</h1>
			<h2>Connectez-vous ou inscrivez-vous !</h2>
			<p>Pour essayer ce jeu du pendu, il faut s'inscrire <a href="Inscription.php">ici</a>, ou se connecter si vous avez déjà un compte.<br />
			Cela permet de profiter de l'intégralité du site, en plus du jeu lui-même.<br /></p>
		</main>
	</body>
</html>