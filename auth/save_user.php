<?
//store the inputs posted from the admin page into variables
$name = $_POST['first_name'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

//assign a variable for validating inputs
$ok = true;

//Validate name is entered
if(empty($name)) {
    header('location: ../pages/join.php?message=name_error');
    $ok = false;
}
//Check email for empty
if($ok == true && empty($username)) {
    header('location: ../pages/join.php?message=email_empty&name=' . $name);
    $ok = false;
}
if ($ok == true && (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $username))) {
    header('location: ../pages/join.php?message=email_error&un=' . urlencode($username));
    $ok = false;
}
//validate that the passwords entered are the same
if ($ok == true && (empty($password)) || ($password != $confirm_password)) {
    header('location: ../pages/join.php?message=password_error&un=' . urlencode($username) . '&name=' . $name);
    $ok = false;
}

//decide if the admin should be saved to the db or not
if ($ok) {
    //connect to the database
    require_once('../db/db-connect.php');
    $connect = connection();

    //hash the password before inserting it into the db - security
    $password = hash('sha512', $password);

    //set up and run the query for ensuring that no duplicate emails or usernames are entered
    $sql = "SELECT * FROM users WHERE username = :username";
    $cmd1 = $connect -> prepare($sql);
    //Add the parameter values for the admin query and execute the sql
    $cmd1 -> bindParam(':username', $username, PDO::PARAM_STR, 50);

    //handle any pdo query errors
    try {
        $cmd1 ->execute();
    }	catch (PDOException $e) {
        header('location: ../pages/join.php?message=signup_error&un=' . urlencode($username));
    }

    //store the number of rows returned in a variable
    $selectCount = $cmd1 -> rowCount();
    if ($selectCount == 0) {
        //Create a unique activation code
        $activation = md5(uniqid(rand(), true));
        $timeStamp = $_SERVER["REQUEST_TIME"];
        //Set up the sql for inserting a new admin
        $sqlInsert = "INSERT INTO users (name, email, username, password, activation, timeStamp) VALUES (trim(:name), trim(:email), trim(:username), :password, :activation, :timeStamp);";
        //handle any pdo query errors
        try {
            //insert the new admin
            $cmd2 = $connect -> prepare($sqlInsert);
            //Add the parameter values for the admin insert and execute the sql
            $cmd2 -> bindParam(':name', $name, PDO::PARAM_STR, 80);
            $cmd2 -> bindParam(':email', $username, PDO::PARAM_STR, 80);
            $cmd2 -> bindParam(':username', $username, PDO::PARAM_STR, 50);
            $cmd2 -> bindParam(':password', $password, PDO::PARAM_STR, 50);
            $cmd2 -> bindParam(':activation', $activation, PDO::PARAM_STR, 50);
            $cmd2 -> bindParam(':timeStamp', $timeStamp, PDO::PARAM_INT);
            $cmd2 ->execute();
        }
        //handle any errors associated with inserting data to the db
        catch (PDOException $e) {
            header('location: ../pages/join.php?message=signup_error&un=' . urlencode($username));
        }
        //assign the number of rows returned from the insert to a variable
        $insertCount = $cmd2 ->rowCount();
        //Send an email request to hadenhiles@gmail.com for verification of
        //the admin if the data successfully inserted to the database
        if ($insertCount == 1) {
            require '../PHPMailer-master/PHPMailerAutoload.php';
            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'hgameinc@gmail.com';                 // SMTP username
            $mail->Password = 'Hockeych7rules';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            $mail->From = 'info@mygames.com';
            $mail->FromName = 'My Games';
//                $mail->addAddress('hadenhiles@gmail.com', 'Haden Hiles');     // Add a recipient
            $mail->addAddress($username);               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'MyGames - Join';
            $mail->Body    = '<img src="http://mygames.moonrockfamily.ca/images/logos/logo2.png" width="320" /><h2>Thank you for Joining MyGames! The one stop website for all your flash games! </h2>';
            $mail->Body .= '<p>To activate your account, please click <a href="http://mygames.moonrockfamily.ca/auth/activate.php?key=' . $activation . '&email=' . urlencode($username) . '">Here.</a></p>';
            $mail->AltBody = '<img src="http://mygames.moonrockfamily.ca/images/logos/logo2.png" width="320" />Thank you for Joining MyGames! \r\nTo activate your account, please click the following link: http://mygames.moonrockfamily.ca/auth/activate.php?email=' . urlencode($username) . '&key=' . $activation;

            if(!$mail->send()) {
                header('location: ../pages/join.php?message=signup_error&un=' . urlencode($username));
            } else {
                header('location: ../pages/join.php?message=signup_success&un=' . urlencode($username));
            }
        } else {
            echo '';
        }
        //disconnect from the db
        if ($connect) {
            $connect = null;
        }
    }
    else {
        header('location: ../pages/join.php?message=un_taken&un=' . urlencode($username));
    }
}