?php
if(isset($_POST) && !empty($_POST))
{
    $regmail = "#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#";
    $regnom = "#^[a-zA-ZÀ-ÿ\s\'-]{1,15} [a-zA-ZÀ-ÿ\s\'-]{1,15}$#";

	if(empty($_POST['email']))
		$erreur['email'] = "Veuillez rentrer une adresse email.";
	else if(!preg_match($regmail, $_POST['email']))
		$erreur['email'] = "Veuillez rentrer une adresse email valide.";

	if(empty($_POST['nom']))
		$erreur['nom'] = "Veuillez rentrer un nom.";
	else if(!preg_match($regnom, $_POST['nom']))
		$erreur['nom'] = "Veuillez rentrer un nom valide.";
	if(empty($_POST['projet']))
		$erreur['projet'] = "Veuillez rentrer une description de votre projet.";
	if(empty($_POST['g-recaptcha-response']))
		$erreur['robot'] = "Veuillez confirmer que vous n'êtes pas un robot.";

	
	if(empty($erreur))
	{
		session_start();
		if(isset($_SESSION['lastEmail']) && ((time() - $_SESSION['lastEmail']) < 30))
			$erreur['mail'] = "Merci d'attendre un moment avant d'envoyer un nouveau mail.";
		else
		{
			$_SESSION['lastEmail'] = time();
	
			require '../assets/mail/PHPMailerAutoload.php';
	
			$mail = new PHPMailer();
			$mail->addAddress('webinpres@gmail.com', 'WebInpres Formulaire');
			$mail->addAddress('f.cardoen@me.com', 'Florent Cardoen');
			$mail->Subject = 'Demande de projet';
	
			$body = "<table>
				<tr><th>Email</th><td>".$_POST['email']."</td></tr>
				<tr><th>Nom prénom</th><td>".$_POST['nom']."</td></tr>
				<tr><th>Téléphone</th><td>".$_POST['telephone']."</td></tr>
				<tr><th>Projet</th><td>".$_POST['projet']."</td></tr>
			</table>";
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true;  // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = 465; 
			$mail->Username = 'webinpres@gmail.com';  
			$mail->Password = 'WebInpres(1337)'; 	
			$mail->CharSet = 'UTF-8';
			$mail->isHTML();
		    $mail->Body = $body;
   			$mail->addReplyTo($_POST['email'], $_POST['nom']);
			$mail->setFrom($_POST['email'], $_POST['nom']);

			if(!$mail->Send()) 
				$erreur['mail'] = "Le message n'est pas partis pour une raison inconnue.";
			else 
			{
				$info['mail'] = "Merci de nous avoir contacté.";
				unset($_POST);
			}
		}
	}
		
}



?>
