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
		<h2>Les 10 meilleurs scores</h2>
		<aside>
			<form method="post" action="Accueil.php">
				<?php
					//On affiche le nom de l'utilisateur
					echo '<h3>Bonjour '.$_SESSION['login'].' !</h3>';
				?>
				<input type="submit" value="Se déconnecter" name="deco" />
			</form>
		</aside>
		<main>
			<h1>Les 10 meilleurs scores</h1>
			<h2>Vous voulez en faire partie ? Alors jouez !</h2>
			<table>
				<tr>
					<td>Classement</td>
					<td>Score</td>
					<td>Joueur</td>
					<td>Thème</td>
					<td>Date</td>
				</tr>
			<?php
			//on sélectionne les scores, du plus grand au plus petit, avec les informations reliées
			$scores = $connexion->query("SELECT valeur, joueur, theme, date FROM Pendu_Score ORDER BY 1 DESC");
			$scores->setFetchMode(PDO::FETCH_OBJ);

			// Parcours des lignes contenues dans le tableau résultat
			// On parcourt 10 fois pour afficher les 10 premiers seulement
			$i=1;
			while(($ligne = $scores->fetch()) && ($i<=10)) {
			// Pour chaque ligne issue du résultat de la requête, on l’affiche en mettant tout dans un tableau
				echo '<tr><td>'.$i.'</td>';
				echo '<td>'.$ligne->valeur.'</td>';
				echo '<td>'.$ligne->joueur.'</td>';
				echo '<td>'.$ligne->theme.'</td>';
				echo '<td>'.$ligne->date.'</td></tr>';
				$i+=1;
			}
			?>
			</table>
		</main>
	</body>
</html>