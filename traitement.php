<?php
ob_start(); // Démarrer la mise en mémoire tampon pour éviter l'affichage de texte

header('Content-Type: text/html; charset=UTF-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Vérifier que la requête est bien en POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Accès non autorisé !");
}

function est_numero_valide($numero) {
    return preg_match('/^(06|07|\+336|\+337)\d{8}$/', $numero);
}

function est_email_valide($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function contient_liens($texte) {
    $patternLien = "/https?:\/\/\S+|<a\s+href\s*=\s*['\"]?\S+['\"]?/i";
    $patternScript = "/<script[^>]*>[\s\S]*?<\/script>/i";
    return preg_match($patternLien, $texte) || preg_match($patternScript, $texte);
}

function est_vide($champ) {
    return !isset($champ) || trim($champ) === '';
}

$nom = htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8');
$telephone = htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8');
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

if (est_vide($nom) || est_vide($telephone) || est_vide($email) || est_vide($prestation) || est_vide($depart) || est_vide($arrive)) {
    die("Champs obligatoires manquants.");
}

if (!est_numero_valide($telephone)) {
    die("Veuillez entrer un numéro de téléphone valide.");
}

if (!est_email_valide($email)) {
    die("Veuillez entrer une adresse email valide.");
}

if (contient_liens($commentaires)) {
    die("Les liens ne sont pas autorisés dans les commentaires.");
}

$message = "NOM : $nom\n";
$message .= "TÉLÉPHONE : $telephone\n";
$message .= "EMAIL : $email\n";
$message .= "PRESTATION : $prestation\n";
$message .= "DÉPART : $depart\n";
$message .= "ARRIVÉE : $arrive\n";
$message .= "DATE : $date\n";
$message .= "HEURE : $heure\n";
$message .= "ADULTES : $passagers\n";
$message .= "ENFANTS : $enfants\n";
$message .= "BAGAGES : $bagages\n";
$message .= "SIEGE AUTO : $sieges_auto\n";
$message .= "REHAUSSEUR : $rehausseur\n";
$message .= "COMMENTAIRES : $commentaires\n";

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.ionos.fr';
    $mail->SMTPAuth = true;
    $mail->Username = 'contact@webprime.fr';
    $mail->Password = 'Allamlyly912!';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->SMTPDebug = 0;

    $mail->setFrom('contact@webprime.fr', 'MD-VTC');
    $mail->addAddress('mdvtc@orange.fr');
    $mail->addAddress('webprime91@hotmail.com');

    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    $mail->Subject = 'Nouveau formulaire reçu';
    $mail->Body = nl2br($message);
    $mail->AltBody = $message;

    if (!$mail->send()) {
        die("Erreur d'envoi de l'email d'administration : " . $mail->ErrorInfo);
    }

    $mailClient = new PHPMailer(true);
    $mailClient->isSMTP();
    $mailClient->Host = 'smtp.ionos.fr';
    $mailClient->SMTPAuth = true;
    $mailClient->Username = 'contact@webprime.fr';
    $mailClient->Password = 'Allamlyly912!';
    $mailClient->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mailClient->Port = 465;
    $mailClient->SMTPDebug = 0;

    $mailClient->setFrom('contact@webprime.fr', 'MD-VTC');
    $mailClient->addAddress($email);
    $mailClient->CharSet = 'UTF-8';
    $mailClient->isHTML(true);
    $mailClient->Subject = 'Confirmation de votre demande';
    $mailClient->Body = "Bonjour $nom,<br><br>Nous avons bien reçu votre demande et nous vous répondrons dans les plus brefs délais.<br><br>Merci de nous faire confiance.<br><br>Cordialement,<br><b>MD-VTC</b>";
    $mailClient->AltBody = "Bonjour $nom,\n\nNous avons bien reçu votre demande et nous vous répondrons dans les plus brefs délais.\n\nMerci de nous faire confiance.\n\nCordialement,\nMD-VTC";

    if (!$mailClient->send()) {
        die("Erreur d'envoi de l'email de confirmation : " . $mailClient->ErrorInfo);
    }

    header('Location: https://chauffeur-prive-paris-idf.fr/');
    exit();
} catch (Exception $e) {
    die("Message non envoyé. Erreur : " . $mail->ErrorInfo);
}

ob_end_flush();
?>




