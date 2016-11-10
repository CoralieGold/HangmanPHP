<?php
	//SESSION UTILISATEUR
	session_start(); //on démarre une session
	if(!isset($_SESSION['login'])) //si l'utilisateur ne s'est pas connect
	{
		//il est redirigé vers la page de connexion
		header("Location:Accueil.php");
		exit();
	}
			
	//DECONNEXION SESSION
	if(isset($_POST['deco']))
	{
		//l'utilisateur est arrivé à la page membre, on peut donc fermer la session
		//cela permet l'utilisateur d'être déconnecté automatiquement
		session_destroy();
	}
	
	//FICHIERS INCLUS
	include_once("connexion.inc");
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		
		<link rel="stylesheet" href="style/jeu.css">
		<link rel="shortcut icon" href="images/favicon.ico">

        <title>10 meilleurs scores</title>
	</head>
	<body>

		<header>
			<a href="#" id="logo"></a>
			
			<nav>
				<ul>
					<a href="AccueilJeu.php"><li>Accueil</li></a>
					<a href="Score.php"><li>Scores</li></a>
					<a href="Theme.php"><li>Thème</li></a>
					<a href="Contact.php"><li>Contact</li></a>
				</ul>
			</nav>
		</header>
		<aside>
			<form method="post" action="Accueil.php">
				<?php
					echo '<h3>Bonjour '.$_SESSION['login'].' !</h3>';
				?>
				<input type="submit" value="Se déconnecter" name="deco" />
			</form>
		</aside>
		<main>
			<h1>Contact</h1>
			<h2>Ce jeu a été fait par © Coralie Goldbaum - 2015</h2>
			<p>Il s'agit d'un TP fait lors d'un cours de PHP.<br />
			Si vous voulez voir mes créations, vous pouvez aller visiter mon <a href="http://coraliegoldbaum.fr/" target="_BLANK">portofolio</a>.</p>
		</main>
	</body>
</html>