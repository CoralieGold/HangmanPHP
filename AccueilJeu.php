<?php
	//SESSION UTILISATEUR
	session_start(); //on démarre une session
	if(!isset($_SESSION['login'])) //si l'utilisateur ne s'est pas connecté
	{
		//il est redirigé vers la page de connexion
		header("Location:Accueil.php");
		exit();
	}
			
	//DECONNEXION SESSION
	if(isset($_POST['deco']))
	{
		//l'utilisateur est arrivé à la page membre, on peut donc fermer la session
		//cela permet à l'utilisateur d'être déconnecté automatiquement
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

        <title>Accueil</title>
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
					//on affiche le nom de l'utilisateur
					echo '<h3>Bonjour '.$_SESSION['login'].' !</h3>';
				?>
				<input type="submit" value="Se déconnecter" name="deco" />
			</form>
		</aside>
		<main>
			<h1>Bienvenue sur le Pendu en PHP !</h1>
			<h2>Choisissez un thème pour commencez à jouer !</h2>
			<p>Le jeu du pendu est un jeu dans lequel vous devez deviner un mot en mettant des lettres au fur et à mesure. <br />
			Si une lettre ne convient pas, un personnage sera pendu petit à petit. Il faut éviter de se tromper de lettre, car si le personnage est 
			totalement pendu, vous perdez !<br />
			Pour jouer, il faut sélectionner un thème <a href="Theme.php">ici</a>. Les mots à deviner seront donc en lien avec le thème choisi.<br />
			Il est aussi possible de voir les scores des autres joueurs <a href="Score.php">ici</a>.<br />
			Et surtout.. Bon jeu !</p>
		</main>
	</body>
</html>