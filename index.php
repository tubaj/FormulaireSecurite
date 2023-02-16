<?php 
    include 'database.php';
	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
    session_start();
    $req = $bd->prepare("SELECT identifiant FROM utilisateurs where email = :email");
    $req ->bindValue(':email',$_SESSION['email']);  
    $req->execute();
    $res = $req->fetch(PDO::FETCH_ASSOC);
    $identifiant = $res['identifiant'];

	if(!isset($identifiant)){
		header("Location: connexion.php");
		exit(); 
	}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Connecté</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="Ressources_css/index.css" media="screen" type="text/css" />
</head>
<body>
  
</div>
 <div id="container">     
    
 
            <p> Vous êtes bien connecté <p style='font-weight: bold; font-size: 20px;'><?php echo $res['identifiant']?></p></p>
            <form action="Deconnexion.php" method="POST">
               	 <input type="submit" id='submit_deco' value='DECONNEXION' >    
            </form>
           
        </div>

</div>
</body>
</html>



