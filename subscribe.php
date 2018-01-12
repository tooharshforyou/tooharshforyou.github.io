<?php
	// Check if install.php is present
	if(is_dir('install')) {
		header("Location: install/install.php");
	} else {
		// Start a session if it is not all ready started
		if (!isset($_SESSION)) session_start();

		// Access DB Info
		include('config.php');

		// Get Settings Data
		include ('includes/settings.php');
		$set = mysqli_fetch_assoc($setRes);

		// Set Localization
		$local = $set['localization'];
		switch ($local) {
			case 'ar':		include ('language/ar.php');		break;
			case 'bg':		include ('language/bg.php');		break;
			case 'ce':		include ('language/ce.php');		break;
			case 'cs':		include ('language/cs.php');		break;
			case 'da':		include ('language/da.php');		break;
			case 'en':		include ('language/en.php');		break;
			case 'en-ca':	include ('language/en-ca.php');		break;
			case 'en-gb':	include ('language/en-gb.php');		break;
			case 'es':		include ('language/es.php');		break;
			case 'fr':		include ('language/fr.php');		break;
			case 'hr':		include ('language/hr.php');		break;
			case 'hu':		include ('language/hu.php');		break;
			case 'hy':		include ('language/hy.php');		break;
			case 'id':		include ('language/id.php');		break;
			case 'it':		include ('language/it.php');		break;
			case 'ja':		include ('language/ja.php');		break;
			case 'ko':		include ('language/ko.php');		break;
			case 'nl':		include ('language/nl.php');		break;
			case 'pt':		include ('language/pt.php');		break;
			case 'ro':		include ('language/ro.php');		break;
			case 'sv':		include ('language/sv.php');		break;
			case 'th':		include ('language/th.php');		break;
			case 'vi':		include ('language/vi.php');		break;
			case 'yue':		include ('language/yue.php');		break;
		}

		// Include Functions
		include('includes/functions.php');
		
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
		
		$activeSubscribe = '';
		$nowActive = '';
		$msgBox = '';
		
		if (isset($_GET['email']) && !empty($_GET['email'])) {
			// Set some variables
			$email = $mysqli->real_escape_string($_GET['email']);

			// Check to see if there is an account that matches the link
			$check1 = $mysqli->query("SELECT
										emailAddress,
										isActive
									FROM
										mailinglist
									WHERE
										emailAddress = '".$email."' AND
										isActive = 0
			");
			$match = mysqli_num_rows($check1);
			
			// Check if Subscription has all ready been activated
			$check2 = $mysqli->query("SELECT 'X' FROM mailinglist WHERE emailAddress = '".$email."' AND isActive = 1");
			if ($check2->num_rows) {
				$activeSubscribe = 'true';
			}

			// Match found, update the User's Subscription to active
			if ($match > 0) {
				$isActive = '1';

				$stmt = $mysqli->prepare("
									UPDATE
										mailinglist
									SET
										isActive = ?
									WHERE
										emailAddress = ?");
				$stmt->bind_param('ss',
								   $isActive,
								   $email);
				$stmt->execute();
				$nowActive = 'true';
				$stmt->close();
			}
		}
		
		include('includes/header.php');
?>
		<section id="main-container">
			<div class="container">
				<h3><?php echo $subscribePageHeader; ?></h3>
				<?php if ($msgBox) { echo $msgBox; } ?>
				
				<?php
					// The Subscription has been activated
					if ($nowActive != '') {
				?>
						<p class="lead"><?php echo $subscribeText1; ?></p>
						<div class="alertMsg success">
							<i class="fa fa-check-square-o"></i> <?php echo $subscribeText2; ?>
						</div>
				<?php
					// An email match was found and has all ready been activated
					} else if ($activeSubscribe != '') {
				?>
						<p class="lead"><?php echo $subscribeText3; ?></p>
						<div class="alertMsg success">
							<i class="fa fa-check-square-o"></i> <?php echo $subscribeText4; ?>
						</div>
				<?php
					// An email match was not found/or the
					// user tried to directly access this page
					} else {
				?>
						<p class="lead"><?php echo $subscribeText5; ?></p>
						<div class="alertMsg danger">
							<i class="fa fa-minus-circle"></i> <?php echo $activateText6; ?>
						</div>
				<?php
					}
				?>
			</div>
		</section>
<?php
		include('includes/footer.php');
	}
?>