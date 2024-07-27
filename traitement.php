<?php

header('Content-Type: text/html; charset=UTF-8');

$nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
$telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
$depart = htmlspecialchars($_POST['lieu_depart'], ENT_QUOTES, 'UTF-8');
$arrive = htmlspecialchars($_POST['lieu_arrivee'], ENT_QUOTES, 'UTF-8');
$passagers = htmlspecialchars($_POST['passagers'], ENT_QUOTES, 'UTF-8');
$bagages = htmlspecialchars($_POST['bagages'], ENT_QUOTES, 'UTF-8');

$message = "NOM : $nom\n";
$message .= "TÉLÉPHONE : $telephone\n";
$message .= "DÉPART : $depart\n";
$message .= "ARRIVÉE : $arrive\n";
$message .= "PASSAGERS : $passagers\n";
$message .= "BAGAGES : $bagages\n";

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
    $mail->addAddress('asc.driver@outlook.com');
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

