<?php
	//FICHIERS INCLUS
	include_once("connexion.inc");
	
	//CONNEXION SESSION UTILISATEUR
	if(!isset($_SESSION['login'])) //si l'utilisateur ne s'est pas connecté
	{
		if(isset($_POST['soumission']))
		{
			//comme pour la page d'accueil, on vérifie si le login et le mot de passe sont bons
			$mdp = $_POST['mdp'];
			$login = $_POST['login'];
			$req = $connexion->prepare("SELECT login FROM Pendu_Joueur WHERE login = :login AND mdp = :mdp");
			$req->execute(array('login' => $login, 'mdp' => $mdp));
			$resultat = $req->fetch();

			if(!$resultat)
			{
				// si non, on affiche un message
				$msg = 'Mauvais identifiant ou mot de passe !';
			}
			else
			{
				// si oui, on redirige
				session_start();
				$_SESSION['login'] = $login;
				$_SESSION['mdp'] = $mdp;
				header("Location:AccueilJeu.php");
				exit();
			}
		}
	}
	
	//INSCRIPTION
	if(isset($_POST['inscription']))
	{

		// Insertion dans la base de données des informations du joueur
		$requete = "insert into Pendu_Joueur values('".$_POST['login']."','".$_POST['mdp']."','".$_POST['mail']."')";
		$reponse = $connexion->query($requete);
		$reponse->closeCursor();
		// Quand il s'est inscrit, on lui permet d'aller jouer sans avoir à se connecter : il est connecté directement à l'inscription
		session_start();
		$_SESSION['login'] = $_POST["login"];
		$_SESSION['mdp'] = $_POST["mdp"];
		header("Location:AccueilJeu.php");
		exit();
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
		<form method="post" action="Inscription.php" id="connecte">
			<fieldset>
			<legend>Inscris-toi pour jouer !</legend>
				<p>Nom d'utilisateur: <input type="text" name="login" /></p>
				<p>Mot de passe : <input type="password" name="mdp" /></p>
				<p>Mail : <input type="text" name="mail" /></p>
				<p><input type="submit" value="S'inscrire" name="inscription" /></p>
			</fieldset>
		</form>
		</main>
	</body>
</html>