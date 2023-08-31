<?php

/* RUN each line separatley BEFOREHAND to get COMPOSER
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

php composer-setup.php

php -r "unlink('composer-setup.php');"

php composer.phar require phpmailer/phpmailer
*/

// LET THE CODING BEGIN!!

// ERROR HANDELING =========== 
// TURN ON WHEN IN DEV =========
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// ////////////

// GMAIL ===========
// If you've enabled 2-Step Verification for your Gmail account, you'll need to use an "App Password" instead of your regular password. Go to your Google Account settings, under "Security", there's an option for "App passwords". Generate a password there and use that password in your script.
// /////////

// for manual download of phpMailer use this
// require 'path_to/PHPMailerAutoload.php';

// us used composer to download PHPmailer use this
require 'vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Configuration settings
$recipient_email = "youremail.com"; // Replace with your own email
$subject = "New Form Submission from Missouri Native";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $phone = strip_tags(trim($_POST["phone"]));
    $email = strip_tags(trim($_POST["email"]));
    $message_body = strip_tags(trim($_POST["message"]));

    if (empty($name) || empty($phone) || empty($email) || empty($message_body)) {
        echo "Please fill all fields in the form!";
        exit;
    }
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: " . $email);
        echo "There was an issue with your submission. Please check your inputs and try again.";
        exit;
    }
    // Validate phone format
if (!preg_match('/^\(?\d{3}\)?[-. ]?\d{3}[-. ]?\d{4}$/', $phone)) {
    echo "There was an issue with your submission. Please check your inputs and try again.";
    exit;
}

    $mail = new PHPMailer;

    // Enable verbose debug output (use 2 in dev and 0 in production)
    // $mail->SMTPDebug = 2; 
    $mail->SMTPDebug = 0;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'youremail.gmail.com';  // Your Gmail address
    $mail->Password = 'yourPassword';        // Your Gmail password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom($email, $name);                   // The email submitted in the form
    $mail->addAddress($recipient_email);             // Your email where you want to receive the emails

    $mail->Subject = $subject;
    $mail->Body    = "Name: $name\nPhone: $phone\nEmail: $email\nMessage: $message_body";

    if (!$mail->send()) {
        echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Thank you for contacting us! We will get back to you soon.";
    }
} else {
    echo "Form not submitted yet!";
}

?>