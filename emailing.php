<?php
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmail($email, $name, $flag, $propertyName, $host,$code)
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
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                    <h2 style='color: #28a745;'>Booking Approved</h2>
                    <p>Thank you <b style='color: #007bff;'>$name</b> for booking with us,</p>
                    <p>Wishing you a pleasant stay at <b style='color: #6c757d;'>$propertyName</b> hosted by <b>$host</b>!</p>
                    <p>Enjoy!</p>
                </div>";
            $mail->AltBody = 'Thank you for booking with us. Wishing you a pleasant stay. Enjoy!';
        } elseif ($flag == 0) {
            $mail->Subject = 'Booking Declined';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                    <h2 style='color: #dc3545;'>Booking Declined</h2>
                    <p>Dear <b style='color: #007bff;'>$name</b>,</p>
                    <p>We regret to inform you that your booking for <b style='color: #6c757d;'>$propertyName</b> has been declined.</p>
                    <p>Please feel free to reach out to us for further assistance.</p>
                </div>";
            $mail->AltBody = 'Dear ' . $name . ', we regret to inform you that your booking has been declined. Please feel free to reach out to us for further assistance.';
        } elseif ($flag == 2) {
            $mail->Subject = 'Successful registration';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                    <h2 style='color: #28a745;'>Welcome!</h2>
                    <p>Hello <b style='color: #007bff;'>$name</b>,</p>
                    <p>Thank you for becoming a member!</p>
                    <p>Enjoy!</p>
                </div>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        } elseif ($flag == 3) {
            $mail->Subject = 'Payment code';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                    <h2 style='color: #ffc107;'>Payment Verification Code</h2>
                    <p>Dear <b style='color: #007bff;'>$name</b>,</p>
                    <p>Your payment verification code is:</p>
                    <p style='font-size: 20px; font-weight: bold; color: #dc3545;'>$code</p>
                    <p>Please copy it and return to the payment confirmation page!</p>
                    <p>Enjoy!</p>
                </div>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        }
        
        $mail->send();
        if ($flag == 1) {
            header("Location: hostPendingBookings.php");
        } elseif ($flag == 0) {
            header("Location: hostPendingBookings.php");

        } elseif ($flag == 2) {
            header("Location: logIn.php");
        }
        exit();
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}