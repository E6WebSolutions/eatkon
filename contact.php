<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$nameErr = $emailErr = $genderErr = $subjectErr = "";
$name = $email = $gender = $message = $subject = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$errors = [];

	if (!empty($_POST)) {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['message'];
		$subject = $_POST['subject'];
		if (empty($name)) {
			$errors[] = 'Name is empty';
		}

		if (empty($email)) {
			$errors[] = 'Email is empty';
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Email is invalid';
		}

		if (empty($message)) {
			$errors[] = 'Message is empty';
		}
		if (!empty($errors)) {
			$allErrors = join('<br/>', $errors);
			$errorMessage = "<p style='color: red;'>{$allErrors}</p>";
		}
		if (empty($errors)) {
			try {
				//Server settings
				$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
				$mail->SMTPAuth = true;                                   //Enable SMTP authentication
				$mail->Username = 'info@eatkon.com';                     //SMTP username
				$mail->Password = 'Eatkon@2021';                               //SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
				$mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

				//Recipients
				$mail->setFrom('from@example.com', 'Eatkon');
				$mail->addAddress('info@eatkon.com');               //Name is optional


				//Content
				$mail->isHTML(true);                                  //Set email format to HTML
				$mail->Subject = 'Contact Details: ' . $name;
				$mail->Body = "Email: " . $email . "<br>Subject: " . $subject . "<br>Message:" . $message;


				$mail->send();

				header('Location: contact.html');

			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		} else {
			$allErrors = join('<br/>', $errors);
			$errorMessage = "<p style='color: red;'>{$allErrors}</p>";
		}
	}
}else{
	header('Location: /contact.html');
}
?>

?>