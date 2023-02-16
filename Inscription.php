<?php 
      session_start();
      include 'database.php'; //connexion à la bdd
      //si j'ai déjà une session
      if(isset($_SESSION['email'])){
            header('Location: Acces.php');
            exit();
      }

?>

<?php    
    
    if (isset($_POST['formulaireInscription'])){
        //trim supprime les espaces en début et fin de chaine 

        $identifiant = htmlspecialchars(strtolower(trim($_POST['identifiant'])));
        $email = htmlspecialchars(strtolower(trim($_POST['email'])));
        $mdp= htmlspecialchars(trim($_POST['mdp']));
    

        //si les variables ne sont pas vide
        if (!empty($email) && !empty($_POST['email2']) && !empty($mdp) && !empty($_POST['mdp2'])) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) { //si l'email est valide, filter_var renvoie les données filtrées
                if ($email == $_POST['email2']){ //si les deux emails se correspondent
                   
                   $reqmail = $bd->prepare('SELECT * from utilisateurs where email = ?');
                   $reqmail->execute([$email]);
                   $mailexist = $reqmail->fetch();
                   if (!$mailexist) {  //si email n'est pas déjà utilisé dans la bdd, on peut continuer le formulaire
                      if (iconv_strlen($mdp,"UTF-8")>= 8){ //si la longueur du mdp est >= 8
                          if ($mdp == $_POST['mdp2']){ //si les deux mdp se correspondent
                            $options = [
                              'cost' => 13,  //on fait le hachage, ici c'est le coût algorithmique
                            ];

                            $hashmdp = password_hash($mdp, PASSWORD_BCRYPT, $options); //création de la clé de hachage 
                         
                            $req = $bd-> prepare("INSERT INTO utilisateurs(identifiant,email,mdp) VALUES (:identifiant,:email,:mdp)");
                            $req->execute(array(
                              'identifiant' => $identifiant,
                              'email' => $email,
                              'mdp' => $hashmdp,            
                            ));
                              header('Location:connexion.php');
                              exit;
                             
                          }// fin if (mdp==mdp2)
                          else {
                              $erreur = "Les mots de passes ne sont correspondent pas.";
                          }
                      }//fin minimum 8 caractères pour le mdp
                      else{
                         $erreur = "Votre mot de passe doit contenir au minimum 8 caractères";
                      }                

                   }//fin if mailexist
                   else {
                        $erreur = "Cette adresse mail est déjà utilisée.";
                   }
                
                }//fin if email = email2 correspondent
                else {
                  $erreur = "Les deux emails ne se correspondent pas.";
                }
            }//fin if filter_var
            else {
                  $erreur = "L'adresse mail est invalide.";
            }
        }//fin du if verification des champs non vide: nom n'est pas vide...
        else {
          $erreur = "Les champs doivent être tous remplis.";
        }
    }//fin du grand if isset
        
?>
  

<html>
    <head>
       <meta charset="utf-8">
        <!-- importer le fichier de style -->
        <link rel="stylesheet" href="Ressources_css/inscription.css" media="screen" type="text/css" />
        <title>Ajout d'un compte</title>
    </head>
    <body>
        <div style='margin-left: 43%; margin-bottom:-150px;'>
               <img src="./img/login.png" alt="Mon logo" style=' width: 250px;
        height:150px;'>
        </div>
        <div id="container">
            <!-- zone d'inscription' -->
            
            <form action="inscription.php" method="POST">
                <h1>Inscription</h1>

                <span><label><b>Identifiant </b></label></span>
                <input type="text" name="identifiant" placeholder="Entrer votre identifiant" required value="<?php if(isset($identifiant)){ echo $identifiant;}?>">

                 <span><label><b>Email</b></label></span>
                 <input type="text" name="email" placeholder="Entrez votre email" required value="<?php if(isset($email)){ echo $email;}?>">

                 <span><label><b>Confirmation Email</b></label></span>
                 <input type="text" name="email2" placeholder="Confirmez votre email" required>

                <br>
                <span><label><b>Mot de passe (minimum 8 caractère) </b></label></span>
                <input type="password" placeholder="Entrer le mot de passe" name="mdp" required>

                <span><label><b>Confirmation du mot de passe </b></label></span>
                <input type="password" name="mdp2" placeholder="Confirmez votre mot de passe" required>

                 <?php if (isset($erreur)) {
                    echo'<p><font color="#A50505">'. $erreur. '</font> </p>';
                }?>

                <input type="submit" id='submit_inscription' value="INSCRIPTION" name="formulaireInscription" href="connexion.php" >
                
            </form>
        </div>
    </body>
