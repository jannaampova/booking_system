<?php
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmail($email, $name, $flag, $propertyName, $host)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'webprojecttj@gmail.com';
        $mail->Password = 'arzh mctp sgap jjkm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('webprojecttj@gmail.com', 'TJ EasyStay');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        if ($flag == 1) {
            $mail->Subject = 'Approved booking';
            $mail->Body = "Thank you <b>$name</b> for booking with us,<br>Wishing you a pleasant stay at <b>$propertyName</b> hosted BY $host!<br>Enjoy!";
            $mail->AltBody = 'Thank you for booking with us. Wishing you a pleasant stay. Enjoy!';
        } elseif ($flag == 0) {
            $mail->Subject = 'Booking Declined';
            $mail->Body = "Dear <b>$name</b>,<br>We regret to inform you that your booking for <b>$propertyName</b> has been declined.<br>Please feel free to reach out to us for further assistance.";
            $mail->AltBody = 'Dear ' . $name . ', we regret to inform you that your booking has been declined. Please feel free to reach out to us for further assistance.';
        } elseif ($flag == 2) {
            $mail->Subject = 'Successful registration';
            $mail->Body = "WELCOME <b>$name</b>,<br> Thank you for becoming a member!<br>Enjoy!</b>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        }elseif ($flag==3){
            $mail->Subject = 'Payment code';
            $mail->Body = "Dear <b>$name</b>,<br> Your payment verification code is:<br>CODE<br>Please copy it and return to the payment confirmation page!<br>Enjoy!";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        }
        $mail->send();
        if ($flag == 1) {
            header("Location: " . $_SERVER['dashboards/host/hostPendingBookings.php']);
        } elseif ($flag == 0) {
            header("Location: " . $_SERVER['dashboards/host/hostPendingBookings.php']);

        } elseif ($flag == 2) {
            header("Location: logIn.php");
        }
        exit();
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}