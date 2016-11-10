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
		header("Location:Accueil.php");
		exit();
	}
	
	//FICHIERS INCLUS
	include_once("connexion.inc");
	
	//FONCTIONS
	function motMyst($connexion)
	{
		$nbMot=4;
		//on choisi un nombre entre 0 et 4 (correspond à 5 mots)
		$ind = rand(0, $nbMot);
		//selon le thème choisi, on fait une requete différente pour afficher le mot aléatoire
		if(strcmp($_SESSION['theme'],'london') == 0)
		{
			$mots = $connexion->query("SELECT mot FROM Pendu_Mot WHERE theme = 'London'");
			$mots2 = $connexion->query("SELECT mot FROM Pendu_Mot WHERE theme = 'London' LIMIT ". $ind . ",1");
		}
		else if(strcmp($_SESSION['theme'],'timburton') == 0)
		{
			$mots = $connexion->query("SELECT mot FROM Pendu_Mot WHERE theme = 'TimBurton'");
			$mots2 = $connexion->query("SELECT mot FROM Pendu_Mot WHERE theme = 'TimBurton' LIMIT ". $ind . ",1");
		}
		else if(strcmp($_SESSION['theme'],'musique') == 0)
		{
			$mots = $connexion->query("SELECT mot FROM Pendu_Mot WHERE theme = 'Musique'");
			$mots2 = $connexion->query("SELECT mot FROM Pendu_Mot WHERE theme = 'Musique' LIMIT ". $ind . ",1");
		}
		
		while($ligne = $mots->fetch())
		{
			$nbMot+=1;					
		}
		$mots->closeCursor();
		//identifiant aléatoire du mot
		
		//on met le mot en majuscules et on le renvoie
		while($ligne = $mots2->fetch())
		{
			$motMystere = strtoupper($ligne['mot']);
			return $motMystere;			
		}
	}
	
	//on affiche le mot visible par l'utilisateur en utilisant des étoiles
	function motUtil($connexion, $motMystere)
	{
		$motUtilisateur = "";
		//on récupère la taille du mot mystère
		$taille = strlen($motMystere);
		//et on complète le mot visible par l'utilisateur
		for($i = 0; $i < $taille; $i ++)
		{
			$motUtilisateur = $motUtilisateur . "*";
		}
		return $motUtilisateur;
	}

	//on met la lettre en majuscule si elle ne l'est pas
	//on remplace l'étoile si la lettre correspond à celle du mot mystère
	//si il n'y a plus d'étoiles dans le mot, l'utilisateur a gagné, on appelle la méthode gagner
	function cherche($connexion,$motMystere,$motUtilisateur,$lettreUtilisateur)
	{
		$lettre = strtoupper($lettreUtilisateur);
		$taille = strlen($motMystere);
		$gagne = 0;
		for($i = 0; $i < $taille; $i ++)
		{
			if($motMystere[$i] == $lettre)
			{
				$motUtilisateur[$i] =  str_replace('*', $lettre, $motUtilisateur[$i]);
			}
			if($motUtilisateur[$i] != "*")
			{
				$gagne += 1;
			}
		}
		if($gagne == $taille)
		{
			gagner($connexion,$motMystere,$motUtilisateur,$lettreUtilisateur,$gagne);
		}
		return $motUtilisateur;
	}
	
	//on met un score de base à 1000 et à chaque erreur, on enlève 100 : 1000 de score si l'utilisateur a tout bon et 0 s'il a perdu
	//on affiche le score, on lui demande s'il veut rejouer et on met le score dans la base de données
	function gagner($connexion,$motMystere,$motUtilisateur,$lettreUtilisateur,$gagne)
	{
		$score = 1000 - $_SESSION['test']*100;
		echo "<p>Vous avez gagné !<br/></p>";
		echo "<h3>Votre score est de ".$score." !</h3><br />";
		echo '<a href="Theme.php">Rejouer</a><br />';
		$date = date_create()->format('Y-m-d H:i:s');
		$requete = "insert into Pendu_Score values('".$date."','".$_SESSION['login']."','".$_SESSION['theme']."',".$score.")";
		$reponse = $connexion->query($requete);
		$reponse->closeCursor();
	}
	
	function perdre($connexion)
	{
		//on affiche que l'utilisateur a perdu et on lui montre quel était le mot mystère
		//on affiche un lien pour rejouer
		echo "<p>Vous avez perdu !<br/> Le mot était ".$_SESSION["motMystere"]." !</p>";
		echo '<br /><a href="Theme.php">Rejouer</a><br />';
	}
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="shortcut icon" href="images/favicon.ico">
		<?php
			//selon le thème choisi, on met un css différent
			if(strcmp($_SESSION['theme'],'london') == 0)
			{
				echo '<link rel="stylesheet" href="style/london.css">';
			}
			else if(strcmp($_SESSION['theme'],'timburton') == 0)
			{
				echo '<link rel="stylesheet" href="style/timburton.css">';
			}
			else if(strcmp($_SESSION['theme'],'musique') == 0)
			{
				echo '<link rel="stylesheet" href="style/musique.css">';
			}
		?>
        <title>Jeu</title>
	</head>
	<body onload="dessine(0);">
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
			<form method="post" action="Jeu.php">
				<?php
					echo '<h3>Bonjour '.$_SESSION['login'].' !</h3>';
				?>
				<input type="submit" value="Se déconnecter" name="deco" />
			</form>
		</aside>
		<main>
		<h1>Jouez !</h1>
		<h2><?php echo $_SESSION['theme']; ?></h2>
			<script>
				//fonction qui dessine le pendu
				function dessine(erreur) 
				{
					//on récupère le canvas
					var c = document.getElementById("mon_canvas");
					var ctx = c.getContext("2d");
					ctx.strokeStyle = "white";
					//selon le numéro de l'erreur, on appelle une fonction
					switch(erreur)
					{
						case 1 :
						dessine1();
						break;
						case 2 :
						dessine2();
						break;
						case 3 :
						dessine3();
						break;
						case 4 :
						dessine4();
						break;
						case 5 :
						dessine5();
						break;
						case 6 :
						dessine6();
						break;
						case 7 :
						dessine7();
						break;
						case 8 :
						dessine8();
						break;
						case 9 :
						dessine9();
						break;
						case 10 :
						dessine10();
						break;
						default :
						break;
					}
					//chaque fonction appelée dessine une partie et appelle les fonctions précédentes
					//par exemple : la tête ne sera pas dessinée toute seule, il y aura aussi la corde et les barres

					//barre verticale
					function dessine1()
					{
						ctx.beginPath();
						ctx.moveTo(2,2);
						ctx.lineTo(2,200);
						ctx.stroke();
					}
					
					//barre horizontale
					function dessine2()
					{
						dessine1();
						ctx.beginPath();
						ctx.moveTo(175,2);
						ctx.lineTo(2,2);
						ctx.stroke();
					}
					
					//corde
					function dessine3()
					{
						dessine2();
						ctx.beginPath();
						ctx.moveTo(100,2);
						ctx.lineTo(100,25);
						ctx.stroke();
					}
					
					//tête
					function dessine4()
					{
						dessine3();
						ctx.beginPath();
						ctx.beginPath();
						ctx.arc(100,50,25,0,Math.PI*2,true);
						ctx.stroke();
					}
					
					//cou
					function dessine5()
					{
						dessine4();
						ctx.beginPath();
						ctx.moveTo(100,75);
						ctx.lineTo(100,100);
						ctx.stroke();
					}
					
					//bras 1
					function dessine6()
					{
						dessine5();
						ctx.beginPath();
						ctx.moveTo(100,100);
						ctx.lineTo(50,100);
						ctx.stroke();
					}
					
					//bras 2
					function dessine7()
					{
						dessine6();
						ctx.beginPath();
						ctx.moveTo(100,100);
						ctx.lineTo(150,100);
						ctx.stroke();
					}
					
					//corps
					function dessine8()
					{
						dessine7();
						ctx.beginPath();
						ctx.moveTo(100,100);
						ctx.lineTo(100,150);
						ctx.stroke();
					}
					
					//jambe 1
					function dessine9()
					{
						dessine8();
						ctx.beginPath();
						ctx.moveTo(100,150);
						ctx.lineTo(50,150);
						ctx.stroke();

					}
					
					//jambe 2
					function dessine10()
					{
						dessine9();
						ctx.beginPath();
						ctx.moveTo(100,150);
						ctx.lineTo(150,150);
						ctx.stroke();
					}
				}
			</script>
			<form method="post">
				<input type="submit" value="A" name="jouer"/>
				<input type="submit" value="B" name="jouer"/>
				<input type="submit" value="C" name="jouer"/>
				<input type="submit" value="D" name="jouer"/>
				<input type="submit" value="E" name="jouer"/>
				<input type="submit" value="F" name="jouer"/>
				<input type="submit" value="G" name="jouer"/>
				<input type="submit" value="H" name="jouer"/>
				<input type="submit" value="I" name="jouer"/>
				<input type="submit" value="J" name="jouer"/>
				<input type="submit" value="K" name="jouer"/>
				<input type="submit" value="L" name="jouer"/>
				<input type="submit" value="M" name="jouer"/>
				<input type="submit" value="N" name="jouer"/>
				<input type="submit" value="O" name="jouer"/>
				<input type="submit" value="P" name="jouer"/>
				<input type="submit" value="Q" name="jouer"/>
				<input type="submit" value="R" name="jouer"/>
				<input type="submit" value="S" name="jouer"/>
				<input type="submit" value="T" name="jouer"/>
				<input type="submit" value="U" name="jouer"/>
				<input type="submit" value="V" name="jouer"/>
				<input type="submit" value="W" name="jouer"/>
				<input type="submit" value="X" name="jouer"/>
				<input type="submit" value="Y" name="jouer"/>
				<input type="submit" value="Z" name="jouer"/>
			</form>
			<canvas id="mon_canvas" width="200" height="175"></canvas>
			<?php
				//si la partie n'a pas commencé, on initialise le mot mystère et le mot utilisateur, puis on affiche ce dernier
				if(!isset($_SESSION['motMystere']))
				{
					$_SESSION['motMystere'] = motMyst($connexion);
					$_SESSION['motUtilisateur'] = motUtil($connexion, $_SESSION['motMystere']);
					echo "<p class='mot'>".$_SESSION['motUtilisateur']."</p>";
					$_SESSION['test'] = 0;
				}
				// a chaque fois qu'une lettre est sélectionnée, on la récupère pour la réutiliser dans les fonctions
				//on sauvegarde le mot utilisateur avant de le réactualiser après la recherche des lettres
				//si le nouveau mot correspond à l'ancien, cela veut dire que la lettre est fausse
				//on ajoute donc un essai
				//si le pendu n'est pas fini, on le dessine, sinon on appelle la fonction perdre
				if(isset($_POST['jouer']))
				{
					$lettre = $_POST['jouer'];
					$motDepart = $_SESSION['motUtilisateur'];
					$_SESSION['motUtilisateur'] = cherche($connexion,$_SESSION['motMystere'],$_SESSION['motUtilisateur'],$lettre);
					echo "<p class='mot'>".$_SESSION['motUtilisateur']."</p>";
					if($motDepart == $_SESSION['motUtilisateur'])
					{
						$_SESSION['test'] += 1;
					}
					if($_SESSION['test'] < 10)
					{
						echo "<script>dessine(".$_SESSION['test'].")</script>";
					}
					if($_SESSION['test'] >= 10)
					{
						perdre($connexion);
					}
				}
			?>
		</main>
	</body>
</html>
