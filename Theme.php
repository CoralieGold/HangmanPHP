<?php
	//permet de redémarrer une session n'importe où dans le fichier
	ob_start();
	//SESSION UTILISATEUR
	session_start(); //on démarre une session
	if(!isset($_SESSION['login'])) //si l'utilisateur ne s'est pas connecté
	{
		//il est redirigé vers la page de connexion
		header("Location:Accueil.php");
		exit();
	}
	else
	{
		//s'il est connecté, on le reconnecte afin de redémarrer un nouveau jeu si il y en avait un déjà en cours
		$mdp = $_SESSION['mdp'];
		$login = $_SESSION['login'];
		session_destroy();
		session_start();
		$_SESSION['login'] = $login;
		$_SESSION['mdp'] = $mdp;
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
	
	//selon le thème sélectionné par l'utilisateur, la variable de session pour le thème contient un thème différent
	$_SESSION['theme'] = "";
	if((isset($_POST['london'])) || (isset($_POST['timburton'])) || (isset($_POST['musique'])))
	{	
		if(isset($_POST['london']))
		{
			$_SESSION['theme'] = "london";
		}
		else if(isset($_POST['timburton']))
		{
			$_SESSION['theme'] = "timburton";
		}
		else if(isset($_POST['musique']))
		{
			$_SESSION['theme'] = "musique";
		}
		//on redirige vers la page de jeu une fois que le thème est choisi
		header('Location:Jeu.php');
		exit();
	}
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="style/jeu.css">
		<link rel="shortcut icon" href="images/favicon.ico">

        <title>Thème</title>
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
			<h1>Les thèmes du Pendu en PHP :</h1>
			<h2>Choisissez un thème pour commencez à jouer, en cliquant dessus.</h2>
			<form method="post" action="Theme.php">
				<input type="submit" value="Tim Burton" name="timburton"></a>
				<input type="submit" value="London" name="london">
				<input type="submit" value="Musique" name="musique">
			</form>
		</main>
	</body>
</html>
<?php
ob_end_flush();
?>