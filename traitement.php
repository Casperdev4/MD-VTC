<?php

header('Content-Type: text/html; charset=UTF-8');

$nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
$telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
$depart = htmlspecialchars($_POST['lieu_depart'], ENT_QUOTES, 'UTF-8');
$arrive = htmlspecialchars($_POST['lieu_arrivee'], ENT_QUOTES, 'UTF-8');
$date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
$heure = htmlspecialchars($_POST['heure'], ENT_QUOTES, 'UTF-8');
$passagers = htmlspecialchars($_POST['passagers'], ENT_QUOTES, 'UTF-8');
$enfants = htmlspecialchars($_POST['enfants'], ENT_QUOTES, 'UTF-8');
$bagages = htmlspecialchars($_POST['bagages'], ENT_QUOTES, 'UTF-8');
$sieges_auto = htmlspecialchars($_POST['sieges_auto'], ENT_QUOTES, 'UTF-8');
$commentaires = htmlspecialchars($_POST['commentaires'], ENT_QUOTES, 'UTF-8');

$message = "NOM : $nom\n";
$message .= "TÉLÉPHONE : $telephone\n";
$message .= "DÉPART : $depart\n";
$message .= "ARRIVÉE : $arrive\n";
$message .= "DATE : $date\n";
$message .= "HEURE : $heure\n";
$message .= "ADULTES : $passagers\n";
$message .= "ENFANTS : $enfants\n";
$message .= "BAGAGES : $bagages\n";
$message .= "SIÈGES AUTO : $sieges_auto\n";
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
    $mail->Password   = 'Allamalyjass912!';
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

    header('Location: index.html');
    exit();
} catch (Exception $e) {
    echo "Message non envoyé. Mailer Error: {$mail->ErrorInfo}";
}
?>


