<?php
	$toggleOpen = '';
	$msgDiv = '';
	
	// Check if Moderation is On
	$moderated = $set['moderation'];
	if ($moderated == '1') { $isOn = $onText; } else { $isOn = $offText; }
	
	// Check if Profanity Filter is On
	$filterProfanity = $set['useFilter'];
	if ($filterProfanity == '1') { $filtered = $onText; } else { $filtered = $offText; }
	
	// Check if Uploads are Enabled in the Site Settings
	$uploadsEnabled = $set['allowUploads'];
	
	// Get the Max Upload Size allowed
    $maxUpload = (int)(ini_get('upload_max_filesize'));

	// Get the Uploads Folder from the Site Settings
	$uploadsDir = $set['uploadPath'];
	
	// Get the Ad Images Folder from the Site Settings
	$adsPath = $set['adsPath'];

	// Get the File Types allowed
	$fileExt = $set['fileTypesAllowed'];
	$allowed = preg_replace('/,/', ', ', $fileExt);
	$ftypes = array($fileExt);
	$ftypes_data = explode( ',', $fileExt );

	// New Confession
    if (isset($_POST['submit']) && $_POST['submit'] == 'confess') {
		// Validation
        if($_POST['confessText'] == "") {
            $msgDiv = alertBox($confessTextReq, "<i class='fa fa-times-circle'></i>", "danger");
			$toggleOpen = ' in';
        } else if($_POST['answer'] == "") {
            $msgDiv = alertBox($captchaCodeReq, "<i class='fa fa-times-circle'></i>", "danger");
			$toggleOpen = ' in';
        } else if ($_POST['hole'] != '') {
			$msgDiv = alertBox($confessErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
			$_POST['firstName'] = $_POST['confessText'] = $_POST['answer'] = '';
		} else {
			if(strtolower($_POST['answer']) == $_SESSION['thecode']) {
				$hasImage = '0';
				if ($_POST['firstName'] == '') {
					$firstName = null;
				} else {
					$firstName = $mysqli->real_escape_string($_POST['firstName']);
				}
				$confessText = htmlspecialchars($_POST['confessText']);
				if ($uploadsEnabled == '1') {
					if (!empty($_FILES['image']['name'])) {
						$hasImage = '1';
					}
				}
				$postDate = date("Y-m-d H:i:s");
				$postIp = $_SERVER['REMOTE_ADDR'];
				
				// Moderation Check
				if ($moderated == '1') {
					$isActive = '0';
					$confPosted = $confessSavedMsg1;
				} else {
					$isActive = '1';
					$confPosted = $confessSavedMsg2;
				}
				
				// Check if the poster is a logged in user
				if (isset($_SESSION['userId'])) {
					$user = $_SESSION['userId'];
				} else {
					$user = '0';
				}
				
				if ($hasImage != '0') {
					// Check file type
					$ext = substr(strrchr(basename($_FILES['image']['name']), '.'), 1);
					if (!in_array($ext, $ftypes_data)) {
						$msgDiv = alertBox($invalidImgTypeMsg, "<i class='fa fa-times-circle'></i>", "danger");
					} else {
						// Replace any spaces with an underscore
						// And set to all lower-case
						$newName = str_replace('.', '-', $postIp);
						$fileNewName = strtolower($newName);

						// Set the upload path
						$uploadTo = $uploadsDir;
						$fileUrl = basename($_FILES['image']['name']);

						// Get the files original Ext
						$extension = end(explode(".", $fileUrl));

						// Generate a random string to append to the file's name
						$imgString=md5(uniqid(rand()));
						$appendName=substr($imgString, 0, 8);

						// Set the files name to the name set in the form
						// And add the original Ext
						$newfilename = $fileNewName.'-'.$appendName.'.'.$extension;
						$movePath = $uploadTo.'/'.$newfilename;
						
						// Save the Confession
						$stmt = $mysqli->prepare("
											INSERT INTO
												confessions(
													userId,
													firstName,
													confessText,
													postDate,
													hasImage,
													isActive,
													postIp
												) VALUES (
													?,
													?,
													?,
													?,
													?,
													?,
													?
												)
						");
						$stmt->bind_param('sssssss',
							$user,
							$firstName,
							$confessText,
							$postDate,
							$hasImage,
							$isActive,
							$postIp
						);
						$stmt->execute();
						$stmt->close();
						
						// Get The New Confession ID
						$newId = "SELECT confessId FROM confessions WHERE postDate = '".$postDate."' AND postIp = '".$postIp."'";
						$theId = mysqli_query($mysqli, $newId) or die('-59'.mysqli_error());
						$a = mysqli_fetch_assoc($theId);
						$confessionId = $a['confessId'];
						
						// Upload the Image
						$stmt = $mysqli->prepare("
											INSERT INTO
												uploads(
													confessId,
													uploadUrl,
													uploadDate
												) VALUES (
													?,
													?,
													?
												)
						");
						$stmt->bind_param('sss',
							$confessionId,
							$newfilename,
							$uploadDate
						);

						if (move_uploaded_file($_FILES['image']['tmp_name'], $movePath)) {
							$stmt->execute();
							$msgDiv = alertBox($confPosted, "<i class='fa fa-check-square'></i>", "success");
							// Clear the Form of values
							$_POST['firstName'] = $_POST['confessText'] = $_POST['answer'] = '';
							$stmt->close();
						}
					}
				} else {
					// Save the Confession
						$stmt = $mysqli->prepare("
										INSERT INTO
											confessions(
												userId,
												firstName,
												confessText,
												postDate,
												hasImage,
												isActive,
												postIp
											) VALUES (
												?,
												?,
												?,
												?,
												?,
												?,
												?
											)
					");
					$stmt->bind_param('sssssss',
						$user,
						$firstName,
						$confessText,
						$postDate,
						$hasImage,
						$isActive,
						$postIp
					);
					$stmt->execute();
					$msgDiv = alertBox($confPosted, "<i class='fa fa-check-square'></i>", "success");
					// Clear the Form of values
					$_POST['firstName'] = $_POST['confessText'] = $_POST['answer'] = '';
					$stmt->close();
				}
			} else {
				$msgDiv = alertBox($captchaErrorMsg, "<i class='fa fa-warning'></i>", "warning");
				$toggleOpen = ' in';
			}
		}
	}
	
	// Subscription Sign Up
	if (isset($_POST['submit']) && $_POST['submit'] == 'subscribe') {
		// User Validations
		if($_POST['subscribeEmail'] == '') {
			$msgBox = alertBox($emailAddyReq, "<i class='fa fa-times-circle'></i>", "danger");
		// Black Hole Trap to help reduce bot registrations
		} else if($_POST['noName'] != '') {
			$msgBox = alertBox($subscribeErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
			$_POST['subscribeEmail'] = '';
		} else {
			// Set some variables
			$dupEmail = '';
			$subscribeEmail = $mysqli->real_escape_string($_POST['subscribeEmail']);

			// Check for Duplicate email
			$check = $mysqli->query("SELECT 'X' FROM mailinglist WHERE emailAddress = '".$subscribeEmail."'");
			if ($check->num_rows) {
				$dupEmail = 'true';
			}

			// If duplicates are found
			if ($dupEmail != '') {
				$msgBox = alertBox($dupSubscribeMsg, "<i class='fa fa-times-circle'></i>", "danger");
				$_POST['subscribeEmail'] = '';
			} else {
				// Add the New Subscription
				$signupDate = date("Y-m-d H:i:s");
				$hash = md5(rand(0,1000));
				$isActive = '0';
				$signupIp = $_SERVER['REMOTE_ADDR'];

				$stmt = $mysqli->prepare("
									INSERT INTO
										mailinglist(
											emailAddress,
											signupDate,
											hash,
											isActive,
											signupIp
										) VALUES (
											?,
											?,
											?,
											?,
											?
										)");
				$stmt->bind_param('sssss',
					$subscribeEmail,
					$signupDate,
					$hash,
					$isActive,
					$signupIp
				);
				$stmt->execute();

				// Send out the email in HTML
				$installUrl = $set['installUrl'];
				$siteName = $set['siteName'];
				$siteEmail = $set['siteEmail'];

				$subject = $subscribeEmailSubject;
				$message = '<html><body>';
				$message .= '<h3>'.$subject.'</h3>';
				$message .= '<p>'.$subscribeEmail1.'<br>'.$installUrl.'subscribe.php?email='.$subscribeEmail.'&hash='.$hash.'</p>';
				$message .= '<hr>';
				$message .= '<p>'.$subscribeEmail2.' '.$siteName.'.</p>';
				$message .= '<p>'.$subscribeEmail3.'<br>'.$siteName.'</p>';
				$message .= '</body></html>';
				$headers = "From: ".$siteName." <".$siteEmail.">\r\n";
				$headers .= "Reply-To: ".$siteEmail."\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				if (mail($subscribeEmail, $subject, $message, $headers)) {
					$msgBox = alertBox($subscribeConfMsg, "<i class='fa fa-check-square'></i>", "success");
					// Clear the Form of values
					$_POST['subscribeEmail'] = '';
				}
				$stmt->close();
			}
		}
	}
	
	// User Log In Form
	if (isset($_POST['submit']) && $_POST['submit'] == 'signIn') {
		if($_POST['userEmail'] == '') {
			$msgBox = alertBox($accountEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['password'] == '') {
			$msgBox = alertBox($accountPasswordReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			// Check if the User account has been activated
			$userEmail = (isset($_POST['userEmail'])) ? $mysqli->real_escape_string($_POST['userEmail']) : '';
			$check = $mysqli->query("SELECT isActive FROM users WHERE userEmail = '".$userEmail."'");
			$row = mysqli_fetch_assoc($check);

			// If the account is active - allow the login
			if ($row['isActive'] == '1') {
				$userEmail = $mysqli->real_escape_string($_POST['userEmail']);
				$password = encryptIt($_POST['password']);

				if($stmt = $mysqli -> prepare("
										SELECT
											userId,
											isAdmin,
											userEmail,
											userFirst,
											userLast
										FROM
											users
										WHERE
											userEmail = ? AND password = ?
				"))	{
					$stmt -> bind_param("ss",
										$userEmail,
										$password
					);
					$stmt -> execute();
					$stmt -> bind_result(
								$userId,
								$isAdmin,
								$userEmail,
								$userFirst,
								$userLast
					);
					$stmt -> fetch();
					$stmt -> close();

					if (!empty($userId)) {
						session_start();
							$_SESSION["userId"]		= $userId;
							$_SESSION["isAdmin"]	= $isAdmin;
							$_SESSION["userEmail"] 	= $userEmail;
							$_SESSION["userFirst"] 	= $userFirst;
							$_SESSION["userLast"] 	= $userLast;
						header('Location: page.php');
					} else {
						$msgBox = alertBox($loginFailedMsg, "<i class='fa fa-times-circle'></i>", "danger");
					}
				}

				// Update Last Visited Date for User
				$lastVisited = date("Y-m-d H:i:s");
				$sqlStmt = $mysqli->prepare("
										UPDATE
											users
										SET
											lastVisited = ?
										WHERE
											userId = ?
				");
				$sqlStmt->bind_param('ss',
								   $lastVisited,
								   $userId
				);
				$sqlStmt->execute();
				$sqlStmt->close();

			} else if ($row['isActive'] == '0') {
				// If the account is not active, show a message
				$msgBox = alertBox($inactiveAccMsg, "<i class='fa fa-warning'></i>", "warning");
			} else {
				// No account found
				$msgBox = alertBox($noAccMsg, "<i class='fa fa-times-circle'></i>", "danger");
			}
		}
	}
	
	// Reset Account Password Form
	if (isset($_POST['submit']) && $_POST['submit'] == 'resetPass') {
		// Set the email address
		$theEmail = (isset($_POST['emailAddy'])) ? $mysqli->real_escape_string($_POST['emailAddy']) : '';

		// Validation
		if ($_POST['emailAddy'] == "") {
			$msgBox = alertBox($accountEmailReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else {
			$query = "SELECT userEmail FROM users WHERE userEmail = ?";
			$stmt = $mysqli->prepare($query);
			$stmt->bind_param("s",$theEmail);
			$stmt->execute();
			$stmt->bind_result($userEmail);
			$stmt->store_result();
			$numrows = $stmt->num_rows();

			if ($numrows == 1){
				// Generate a RANDOM Hash for a password
				$randomPassword = uniqid(rand());

				// Take the first 8 digits and use them as the password we intend to email the Employee
				$emailPassword = substr($randomPassword, 0, 8);

				// Encrypt $emailPassword for the database
				$newpassword = encryptIt($emailPassword);

				//update password in db
				$updatesql = "UPDATE users SET password = ? WHERE userEmail = ?";
				$update = $mysqli->prepare($updatesql);
				$update->bind_param("ss",
										$newpassword,
										$theEmail
									);
				$update->execute();

				// Send out the email in HTML
				$installUrl = $set['installUrl'];
				$siteName = $set['siteName'];
				$siteEmail = $set['siteEmail'];

				$subject = $resetPassemailSubject;
				$message = '<html><body>';
				$message .= '<h3>'.$subject.'</h3>';
				$message .= '<p>'.$resetPasswordEmail1.'</p>';
				$message .= '<hr>';
				$message .= '<p>'.$emailPassword.'</p>';
				$message .= '<hr>';
				$message .= '<p>'.$resetPasswordEmail2.'</p>';
				$message .= '<p>'.$resetPasswordEmail3.' '.$installUrl.'</p>';
				$message .= '<p>'.$subscribeEmail3.'<br>'.$siteName.'</p>';
				$message .= '</body></html>';
				$headers = "From: ".$siteName." <".$siteEmail.">\r\n";
				$headers .= "Reply-To: ".$siteEmail."\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				if (mail($theEmail, $subject, $message, $headers)) {
					$msgBox = alertBox($passwordResetMsg, "<i class='fa fa-check-square-o'></i>", "success");
					$isReset = 'true';
					$stmt->close();
				}
			} else {
				// No account found
				$msgBox = alertBox($noAccFoundMsg, "<i class='fa fa-warning'></i>", "warning");
			}
		}
	}
	
	// Create a New Account
	if (isset($_POST['submit']) && $_POST['submit'] == 'signUp') {
		// User Validations
		if($_POST['userEmail'] == '') {
			$msgBox = alertBox($emailAddyReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['password'] == '') {
			$msgBox = alertBox($newPasswordReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if($_POST['password'] != $_POST['passwordr']) {
			$msgBox = alertBox($passNotMatchMsg, "<i class='fa fa-times-circle'></i>", "danger");
		// Black Hole Trap to help reduce bot registrations
		} else if($_POST['noAnswer'] != '') {
			$msgBox = alertBox($newAccErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
			$_POST['userEmail'] = '';
		} else {
			// Set some variables
			$dupEmail = '';
			$userEmail = $mysqli->real_escape_string($_POST['userEmail']);

			// Check for Duplicate email
			$check = $mysqli->query("SELECT 'X' FROM users WHERE userEmail = '".$userEmail."'");
			if ($check->num_rows) {
				$dupEmail = 'true';
			}

			// If duplicates are found
			if ($dupEmail != '') {
				$msgBox = alertBox($dupAccMsg, "<i class='fa fa-times-circle'></i>", "danger");
				$_POST['userEmail'] = '';
			} else {
				// Add the New Account
				$password = encryptIt($_POST['password']);
				$joinDate = date("Y-m-d H:i:s");
				$hash = md5(rand(0,1000));
				$isActive = '0';
				$signupIp = $_SERVER['REMOTE_ADDR'];

				$stmt = $mysqli->prepare("
									INSERT INTO
										users(
											userEmail,
											password,
											joinDate,
											isActive,
											hash
										) VALUES (
											?,
											?,
											?,
											?,
											?
										)");
				$stmt->bind_param('sssss',
					$userEmail,
					$password,
					$joinDate,
					$isActive,
					$hash
				);
				$stmt->execute();

				// Send out the email in HTML
				$installUrl = $set['installUrl'];
				$siteName = $set['siteName'];
				$siteEmail = $set['siteEmail'];
				$newPass = $mysqli->real_escape_string($_POST['password']);

				$subject = $newAccEmailSubject;
				$message = '<html><body>';
				$message .= '<h3>'.$subject.'</h3>';
				$message .= '<p>'.$newAccEmail1.'</p>';
				$message .= '<hr>';
				$message .= '<p>'.$newAccEmail2.' '.$newPass.'</p>';
				$message .= '<p>'.$newAccEmail3.'<br>'.$installUrl.'activate.php?userEmail='.$userEmail.'&hash='.$hash.'</p>';
				$message .= '<hr>';
				$message .= '<p>'.$newAccEmail4.'</p>';
				$message .= '<p>'.$newAccEmail5.' '.$installUrl.'</p>';
				$message .= '<p>'.$subscribeEmail3.'<br>'.$siteName.'</p>';
				$message .= '</body></html>';
				$headers = "From: ".$siteName." <".$siteEmail.">\r\n";
				$headers .= "Reply-To: ".$siteEmail."\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				if (mail($userEmail, $subject, $message, $headers)) {
					$msgBox = alertBox($newAccCreatedMsg, "<i class='fa fa-check-square'></i>", "success");
					// Clear the Form of values
					$_POST['userEmail'] = '';
				}
				$stmt->close();
			}
		}
	}
	
	if (isset($_SESSION['userId'])) {
		// Keep some User data available
		$uid 				= $_SESSION['userId'];
		$uemail 			= $_SESSION['userEmail'];
		$fullName 			= $_SESSION['userFirst'].' '.$_SESSION['userLast'];
		if (isset($_SESSION['isAdmin'])) {
			$admin			= $_SESSION['isAdmin'];
		} else {
			$admin			= '0';
		}
	} else {
		$uid = '';
		$admin = '';
		$uemail = '';
		$fullName = '';
		$admin = '';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo $set['siteName']; ?> &middot; <?php echo $siteHeaderTitle; ?></title>
		<meta name="description" content="<?php echo $siteHeaderDescrip; ?>">
		<meta name="keywords" content="<?php echo $siteHeaderKeywords; ?>">

		<link rel="stylesheet" type="text/css" href="css/fonts.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-min.css">
		<link rel="stylesheet" type="text/css" href="css/datatables.css">
		<link rel="stylesheet" type="text/css" href="css/custom.css">
		<?php if (isset($addCss)) { echo $addCss; } ?>
		<link rel="stylesheet" type="text/css" href="css/fessup.css">
		<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />

		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>
	
	<body>
	<div id="fullscreen">
		<a href="#" class="close-overlay"><i class="fa fa-times"></i></a>
		<div class="form-wrap">
			<div class="signin-form">
				<h1><?php echo $set['siteName']; ?> <?php echo $memberSignInText; ?></h1>
				<form action="" method="post">
					<div class="form-group">
						<label for="userEmail"><?php echo $emailAddyField; ?></label>
						<input type="email" class="form-control" required="" name="userEmail" value="" />
					</div>
					<div class="form-group">
						<label for="password"><?php echo $accPasswordField; ?></label>
						<small class="pull-right"><a href="#" class="password-btn"><i class="fa fa-lock"></i> <?php echo $lostPasswordText; ?></a></small>
						<input type="password" class="form-control" required="" name="password" value="" />
					</div>
					<p class="text-center">
						<button type="input" name="submit" value="signIn" class="btn btn-success btn-lg btn-icon"><i class="fa fa-sign-in"></i> <?php echo $signInBtn; ?></button>
						<?php if ($set['allowRegistrations'] == '1') { ?>
							<button type="button" class="btn btn-info btn-lg btn-icon signup-btn"><i class="fa fa-unlock-alt"></i> <?php echo $createNewAccBtn; ?></button>
						<?php } ?>
					</p>
				</form>
			</div>
			<?php if ($set['allowRegistrations'] == '1') { ?>
				<div class="signup-form">
					<h1><?php echo $createNewAccModal; ?></h1>
					<form action="" method="post">
						<div class="form-group">
							<label for="userEmail"><?php echo $emailAddyField; ?></label>
							<input type="text" class="form-control" required="" name="userEmail" value="" />
							<span class="help-block"><?php echo $validEmailAddyQuip; ?></span>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="password"><?php echo $passwordField; ?></label>
									<input type="text" class="form-control" required="" name="password" value="" />
									<span class="help-block"><?php echo $passwordFieldQuip; ?></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="passwordr"><?php echo $repeatPasswordField1; ?></label>
									<input type="text" class="form-control" required="" name="passwordr" value="" />
									<span class="help-block"><?php echo $repeatPasswordQuip; ?></span>
								</div>
							</div>
						</div>
						<div class="row mb-10">
							<div class="col-md-3">
								<p class="text-right mt-15"><img src="includes/captcha.php" id="captcha" data-toggle="tooltip" data-placement="top" title="<?php echo $captchaCodeTooltip; ?>" /></p>
							</div>
							<div class="col-md-9">
								<label for="captchaanswer"><?php echo $captchaCodeField; ?></label>
								<input type="text" class="form-control" required="" maxlength="6" name="captchaanswer" value="" />
							</div>
						</div>
						<p class="text-center">
							<input type="hidden" name="noAnswer" />
							<button type="input" name="submit" value="signUp" class="btn btn-success btn-lg btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $createAccBtn; ?></button>
							<button type="button" class="btn btn-info btn-lg btn-icon-alt signin-btn"><?php echo $haveAccSignInBtn; ?> <i class="fa fa-long-arrow-right"></i></button>
							<button type="button" class="btn btn-primary btn-lg why-btn"><i class="fa fa-question-circle"></i></button>
						</p>
					</form>
				</div>
			<?php } ?>
			
			<div class="password-form">
				<h1 class="head1"><?php echo $resetPassModal; ?></h1>
				<form action="" method="post">
					<div class="form-group">
						<label for="emailAddy"><?php echo $emailAddyField; ?></label>
						<input type="email" class="form-control" required="" name="emailAddy" value="" />
						<span class="help-block"><?php echo $emailAddyResetQuip; ?></span>
					</div>
					<p class="text-center">
						<button type="input" name="submit" value="resetPass" class="btn btn-success btn-lg btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $resetPassModal; ?></button>
						<button type="button" class="btn btn-info btn-lg btn-icon-alt signin-btn"><?php echo $allSetSignInBtn; ?> <i class="fa fa-long-arrow-right"></i></button>
					</p>
				</form>
			</div>
			
			<?php if ($set['allowRegistrations'] == '1') { ?>
				<div class="whyDiv">
					<h1 class="head1"><?php echo $whySignUpModal; ?></h1>
					<div class="blockquote-box blockquote-info clearfix">
						<div class="square pull-left">
							<span class="fa fa-edit faIcon-lg"></span>
						</div>
						<h4><?php echo $whyText1; ?></h4>
						<p><?php echo $whyText2; ?></p>
					</div>
					<div class="blockquote-box blockquote-primary clearfix">
						<div class="square pull-left">
							<span class="fa fa-envelope-o faIcon-lg"></span>
						</div>
						<h4><?php echo $whyText3; ?></h4>
						<p><?php echo $whyText4; ?></p>
					</div>
					<div class="blockquote-box blockquote-success clearfix">
						<div class="square pull-left">
							<span class="fa fa-money faIcon-lg"></span>
						</div>
						<h4><?php echo $whyText5; ?></h4>
						<p><?php echo $whyText6; ?></p>
					</div>
					<p class="text-center mt-20">
						<button type="button" class="btn btn-primary btn-lg btn-icon-alt signup-btn"><?php echo $signMeUpBtn; ?> <i class="fa fa-long-arrow-right"></i></button>
						<button type="button" class="btn btn-warning btn-lg btn-icon signin-btn"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
					</p>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only"><?php echo $toggleNavText; ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="<?php echo $set['siteName']; ?>"></a>
			</div>

			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $confessionsNavLink; ?> <i class="fa fa-angle-down"></i></a>
						<ul class="dropdown-menu">
							<li><a href="index.php?view=<?php echo $viewNavLinkNewest; ?>"><?php echo $newestNavLink; ?></a></li>
							<li><a href="index.php?view=<?php echo $viewNavLinkOldest; ?>"><?php echo $oldestNavLink; ?></a></li>
							<li><a href="index.php?view=<?php echo $viewNavLinkPopular; ?>"><?php echo $popularNavLink; ?></a></li>
							<li><a href="index.php?view=<?php echo $viewNavLinkLikes; ?>"><?php echo $likesNavLink; ?></a></li>
							<li><a href="index.php?view=<?php echo $viewNavLinkDislikes; ?>"><?php echo $dislikesNavLink; ?></a></li>
							<li><a href="index.php?view=<?php echo $viewNavLinkRandom; ?>"><?php echo $randomNavLink; ?></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $aboutNavLink; ?> <i class="fa fa-angle-down"></i></a>
						<ul class="dropdown-menu">
							<li><a href="page.php?page=<?php echo $pageNavLinkAbout; ?>"><?php echo $aboutNavLink1; ?></a></li>
							<li><a href="page.php?page=<?php echo $pageNavLinkRules; ?>"><?php echo $rocNavLink; ?></a></li>
						</ul>
					</li>
					<?php
						if ($uid == '') {
							if ($set['allowRegistrations'] == '1') { ?>
								<li class="sign-up signinup"><a href="#"><span class="white"> <?php echo $signInUpNavLink; ?></span></a></li>
							<?php } else { ?>
								<li class="sign-up signinup"><a href="#"><span class="white"> <?php echo $signInNavLink; ?></span></a></li>
					<?php
							}
						} else {
					?>
						<li><a href="page.php?page=myProfile"><?php echo $myProfileNavLink; ?></a></li>
						<?php if($admin == '1') { ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $manageNavLink; ?> <i class="fa fa-angle-down"></i></a>
								<ul class="dropdown-menu">
									<li><a href="page.php?page=confessions"><?php echo $confessionsNavLink; ?></a></li>
									<li><a href="page.php?page=comments"><?php echo $commentsNavLink; ?></a></li>
									<li><a href="page.php?page=subscriptions"><?php echo $subscriptionsNavLink; ?></a></li>
									<li><a href="page.php?page=users"><?php echo $usersNavLink; ?></a></li>
									<li><a href="page.php?page=advertising"><?php echo $advertisingNavLink; ?></a></li>
									<li><a href="page.php?page=siteSettings"><?php echo $settingsNavLink; ?></a></li>
								</ul>
							</li>
						<?php } ?>
						<li class="sign-up"><a data-toggle="modal" href="#signOut"><span class="white"> <?php echo $signOutNavLink; ?></span></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="signOut" tabindex="-1" role="dialog" aria-labelledby="signOutLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p class="lead"><?php echo $fullName.$signOutConf; ?></p>
				</div>
				<div class="modal-footer">
					<a href="index.php?action=logout" class="btn btn-success btn-icon-alt"><?php echo $signOutNavLink; ?> <i class="fa fa-sign-out"></i></a>
					<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> <?php echo $cancelBtn; ?></button>
				</div>
			</div>
		</div>
	</div>

	<section id="page-title">
		<div class="container">
			<div class="row">
				<div class="col-md-12 title togglePanel">
					<div class="row">
						<div class="col-md-10">
							<h2><?php echo $confessFormTitle; ?></h2>
						</div>
						<div class="col-md-2">
							<div class="panel-toggle">
								<a href="#" class="btn btn-confess btn-icon pull-right" id="confessToggle" data-perform="panel-collapse"><i class="fa fa-comment-o"></i> <?php echo $fessUpBtn; ?></a>
							</div>
						</div>
					</div>

					<div class="panel-wrapper collapse<?php echo $toggleOpen; ?>">
						<div class="panel-body fessup-form">
							<?php if ($msgDiv) { echo $msgDiv; } ?>
							<form action="" method="post" enctype="multipart/form-data" class="confessForm">
								<div class="form_row">
									<textarea class="phslidedown" name="confessText" required="" rows="6" placeholder="<?php echo $confessPlaceholder; ?>"><?php echo isset($_POST['confessText']) ? $_POST['confessText'] : ''; ?></textarea>
								</div>
								<div class="row mt-10">
									<div class="col-md-4">
										<input class="phslidedown" type="text" name="firstName" placeholder="<?php echo $firstNamePlaceholder; ?>" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : ''; ?>" />
									</div>
									<div class="col-md-4">
										<?php if ($uploadsEnabled == '1') { ?>
											<span id="filename"><?php echo $includeImgText; ?></span>
											<label for="file-upload">
												<?php echo $browseBtn; ?>
												<input type="file" name="image" id="file-upload">
											</label>
										<?php } ?>
									</div>
									<div class="col-md-4">
										<div class="row">
											<div class="col-md-4">
												<img src="includes/captcha.php" id="captcha" data-toggle="tooltip" data-placement="left" title="<?php echo $captchaCodeTooltip; ?>" />
											</div>
											<div class="col-md-8">
												<input class="phslidedown" name="answer" type="text" required="" maxlength="6" placeholder="<?php echo $captchaCodeTooltip; ?>" />
											</div>
										</div>
									</div>
								</div>
								<p><?php echo $confessQuip1; ?>
								<?php if ($uploadsEnabled == '1') { ?>
									<?php echo $confessQuip2; ?>
								<?php } ?>
								<?php echo $confessQuip3; ?> <strong><?php echo $isOn; ?></strong>. <?php echo $confessQuip4; ?> <strong><?php echo $filtered; ?></strong>.</p>
								<input type="hidden" name="hole" id="hole" />
								<button type="input" name="submit" value="confess" class="btn btn-inverse btn-lg btn-icon mt-10"><i class="fa fa-check-square-o"></i> <?php echo $shareConfessBtn; ?></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>