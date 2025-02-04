<?php
header('Content-Type: text/html; charset=UTF-8');

function est_numero_valide($numero) {
    return preg_match('/^(06|07|\+336|\+337)\d{8}$/', $numero);
}

function contient_liens($texte) {
    $patternLien = "/https?:\/\/[^\s]+|<a\s+href\s*=\s*['\"]?[^\s>]+['\"]?/i";
    $patternScript = "/<script[^>]*>[\s\S]*?<\/script>/i";
    return preg_match($patternLien, $texte) || preg_match($patternScript, $texte);
}

function contient_cyrillique($texte) {
    return preg_match("/[\p{Cyrillic}]/u", $texte);
}

function est_vide($champ) {
    return !isset($champ) || trim($champ) === '';
}

$nom = htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8');
$telephone = htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8');
$prestation = htmlspecialchars($_POST['prestation'] ?? '', ENT_QUOTES, 'UTF-8');
$depart = htmlspecialchars($_POST['lieu_depart'] ?? '', ENT_QUOTES, 'UTF-8');
$arrive = htmlspecialchars($_POST['lieu_arrivee'] ?? '', ENT_QUOTES, 'UTF-8');
$numero = htmlspecialchars($_POST['numero'] ?? '', ENT_QUOTES, 'UTF-8');
$date = htmlspecialchars($_POST['date'] ?? '', ENT_QUOTES, 'UTF-8');
$heure = htmlspecialchars($_POST['heure'] ?? '', ENT_QUOTES, 'UTF-8');
$passagers = htmlspecialchars($_POST['passagers'] ?? '', ENT_QUOTES, 'UTF-8');
$enfants = htmlspecialchars($_POST['enfants'] ?? '', ENT_QUOTES, 'UTF-8');
$bagages = htmlspecialchars($_POST['bagages'] ?? '', ENT_QUOTES, 'UTF-8');
$sieges_auto = htmlspecialchars($_POST['sieges_auto'] ?? '', ENT_QUOTES, 'UTF-8');
$rehausseur = htmlspecialchars($_POST['rehausseur'] ?? '', ENT_QUOTES, 'UTF-8');
$commentaires = htmlspecialchars($_POST['commentaires'] ?? '', ENT_QUOTES, 'UTF-8');

if (est_vide($nom) || est_vide($telephone) || est_vide($prestation) || est_vide($depart) || est_vide($arrive)) {
    echo "Champs obligatoire.";
    exit();
}

if (!est_numero_valide($telephone)) {
    echo "Veuillez entrer un numéro de téléphone valide.";
    exit();
}

if (contient_liens($commentaires) || contient_cyrillique($commentaires)) {
    echo "Non autorisés.";
    exit();
}

$message = "NOM : $nom\n";
$message .= "TÉLÉPHONE : $telephone\n";
$message .= "PRESTATION : $prestation\n";
$message .= "DÉPART : $depart\n";
$message .= "ARRIVÉE : $arrive\n";
$message .= "NUMÉRO DE VOL/TRAINS : $numero\n";
$message .= "DATE : $date\n";
$message .= "HEURE : $heure\n";
$message .= "ADULTES : $passagers\n";
$message .= "ENFANTS : $enfants\n";
$message .= "BAGAGES : $bagages\n";
$message .= "SIÈGES AUTO : $sieges_auto\n";
$message .= "RÉHAUSSEUR : $rehausseur\n";
$message .= "COMMENTAIRES : $commentaires\n";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.ionos.fr';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@webprime.fr';
    $mail->Password   = 'Allamlyly912!';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('contact@webprime.fr', 'MD-VTC');
    $mail->addAddress('mdvtc@orange.fr');
    $mail->addAddress('webprime91@hotmail.com');

    $mail->CharSet = 'UTF-8'; 
    $mail->isHTML(true);
    $mail->Subject = 'Formulaire';
    $mail->Body    = nl2br($message);
    $mail->AltBody = $message;

    $mail->send();

    header('Location: https://chauffeur-prive-paris-idf.fr/');
    exit();
} catch (Exception $e) {
    echo "Message non envoyé. Erreur Mailer: {$mail->ErrorInfo}";
}
?>

