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

    $bdd = new PDO('mysql:host=;dbname=', '', '');
    include 'random.php';
    $dateValidit = date('d/m/Y', strtotime($_POST['date']));
    $date = $t->format('d/m/Y');
   
    if(isset($_POST['envoiPass']))
    {
        if( !empty($_POST['valeur']) AND !empty($_POST['montant']) AND !empty($_POST['email']) AND !empty($_POST['nombrePass']) AND !empty($_POST['nomLot']) AND !empty($_POST['date']))
            {
                $mail = $_POST['email'];
                $arrayOfMail = explode("\n", $mail);

                foreach ($arrayOfMail as $oneMail) {
                    for ($i = 0; $i < $_POST['nombrePass']; $i++) {
                        $code = generateRandomString();

                        $insert = $bdd->prepare("INSERT INTO pass(montant, valeur, email, code, date_validit, dateDebut) VALUES (?, ?, ?, ?, ?, ?)");
                        $insert->execute(array($_POST['montant'], $_POST['valeur'], $oneMail, $code, $dateValidit, $date, ));

                        
                    }
                }
                $insertLot = $bdd->prepare("INSERT INTO lots(nom) VALUES (?)");
                $insertLot->execute(array($_POST['nomLot']));



            }
    }

    
?>
    
</body>
</html>