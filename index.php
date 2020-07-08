<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création Wescape Pass </title>
</head>
<body>

<?php
   include 'navbar.html';
    $t = new DateTime();
    $date = $t->format('Y-m-d');
?> 

 <select form="PassForm" name="valeur">
  <option value="Euros">€</option>
  <option value="Pourcent">%</option>
</select>
<form method="POST" id="PassForm" action="">
    <input type="number" placeholder="Montant" name="montant"/> <br/>
    <input type="date" min="<?php echo $date ?>"  value="<?php echo $date ?>" name="date"  /> <br/>
    <textarea rows="4" cols="50" name="email" placeholder="Séparer chaque email par un saut à la ligne"></textarea> <br/>
    <input type="number" placeholder="Nombre de pass" name="nombrePass"> <br/>
    <input type="text" placeholder="Nom du lot" name="nomLot"> <br/>
    <input type="submit" name ="envoiPass" value="Enregistrer"> <br/>
</form> 

<?php 
    // Connection BDD
    $bdd = new PDO('mysql:host=;dbname=', '', '');
    // include fonction pour generer le code aléatoire.
    include 'random.php';
    // format date
    $dateValidit = date('d/m/Y', strtotime($_POST['date']));
    $date = $t->format('d/m/Y');

    if(isset($_POST['envoiPass']))
    {
        if( !empty($_POST['valeur']) AND !empty($_POST['montant']) AND !empty($_POST['email']) AND !empty($_POST['nombrePass']) AND !empty($_POST['nomLot']) AND !empty($_POST['date']))
            {
                //Recuperation des mails dans le text area
                $mail = $_POST['email'];
                $arrayOfMail = explode("\n", $mail);

                // insert nom du lot dans la table lots 
                $insertLot = $bdd->prepare("INSERT INTO lots(nom) VALUES (?)");
                $insertLot->execute(array($_POST['nomLot']));

                foreach ($arrayOfMail as $oneMail) {
                    for ($i = 0; $i < $_POST['nombrePass']; $i++) {
                        $code = generateRandomString();

                        $insert = $bdd->prepare("INSERT INTO pass(montant, valeur, email, code, date_validit, dateDebut, lots_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

                        $LotsId = $bdd->prepare("SELECT lots.id  FROM lots INNER JOIN pass  ON  lots.id = pass.lots_id");

                        var_dump($LotsId);

                        $insert->execute(array($_POST['montant'], $_POST['valeur'], $oneMail, $code, $dateValidit, $date, $LotsId));

                        
                    }
                }
               
                // $requete = $bdd->query('SELECT lots.id FROM lots INNER JOIN pass ON lots.id=pass.lots_id');
                // var_dump($requete);

                // $jointure = "SELECT lots_id FROM pass LEFT JOIN lots ON pass.lots_id=lots.id"

            }
    }

    
?>
    
</body>
</html>