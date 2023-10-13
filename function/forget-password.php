<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';


$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'khatiphatjomsri@gmail.com';
    $mail->Password = "bana qxmf jbez fona";

    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";

    $mail->setFrom('khatiphatjomsri@gmail.com', 'CARE MY SKIN');
    $mail->addAddress('khatiphatjomsri@gmail.com', 'Test Send Email');

    $mail->isHTML(true);
    $mail->Subject = 'Kate Katipad555';
    $mail->Body = 'This is the HTML message body <b>in bold!</b>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error : {$mail->ErrorInfo}";
}
?>