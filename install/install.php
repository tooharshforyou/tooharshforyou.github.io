<?php
    function alertBox($message, $icon = "", $type = "") {
        return "<div class=\"alertMsg $type\"><span>$icon</span> $message <a class=\"alert-close\" href=\"#\">x</a></div>";
    }
	
	function encryptIt($value) {
		// The encodeKey MUST match the decodeKey
		$encodeKey = '0z%E4!3I1C#5y@9&qTx@swGn@78ePqViI1C#5y@';
		$encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($encodeKey), $value, MCRYPT_MODE_CBC, md5(md5($encodeKey))));
		return($encoded);
	}

	$msgBox = '';

	$step = 'check';
	$phpbtn = $mysqlibtn = $mcryptbtn = '';
	
	// Check for PHP Version & MySQLi
	if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
		$phpversion = PHP_VERSION;
		$phpcheck = '<i class="fa fa-check text-success"></i> PASS';
		$phpbtn = 'true';
	} else {
		$phpversion = 'You need to have PHP Version 5.3 or higher Installed to run Fess Up.';
		$phpcheck = '<i class="fa fa-times text-danger"></i> FAIL';
	}
	if (function_exists('mysqli_connect')) {
		$mysqliver = '<i class="fa fa-check text-success"></i> PASS';
		$mysqlibtn = 'true';
	} else {
		$mysqliver = '<i class="fa fa-times text-danger"></i> FAIL';
	}
	if (function_exists('mcrypt_module_open')) {
		$hasmcrypt = '<i class="fa fa-check text-success"></i> PASS';
		$mcryptbtn = 'true';
	} else {
		$hasmcrypt = '<i class="fa fa-times text-danger"></i> FAIL';
	}

	if(isset($_POST['submit']) && $_POST['submit'] == 'nextStep') {
		$step = '1';
		$file = false;
	}
	
	if(isset($_POST['submit']) && $_POST['submit'] == 'On to Step 2') {
        // Validation
        if($_POST['dbhost'] == '') {
			$msgBox = alertBox("Please enter in your Host name. This is usually 'localhost'.", "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['dbuser'] == '') {
			$msgBox = alertBox("Please enter the username for the database.", "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['dbname'] == '') {
			$msgBox = alertBox("Please enter the database name.", "<i class='fa fa-times-circle'></i>", "danger");
        } else {
			$dbhost = $_POST['dbhost'];
			$dbuser = $_POST['dbuser'];
			$dbpass = $_POST['dbpass'];
			$dbname = $_POST['dbname'];
			$timezone = $_POST['timezone'];

            $str ="<?php
error_reporting(0);
ini_set('display_errors', '0');

date_default_timezone_set('".$timezone."');

$"."dbhost = '".$dbhost."';
$"."dbuser = '".$dbuser."';
$"."dbpass = '".$dbpass."';
$"."dbname = '".$dbname."';

".file_get_contents('config.txt')."
?>";
            if (!file_put_contents('../config.php', $str)) {
                $no_perm = true;
            }
        }
    }
	
	if (is_file('../config.php')) {
		include ('../config.php');

		if (!$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname)) {
            $step = '1';
            $file = true;
        } else {
			if (mysqli_connect_errno()) {
                $step = '1';
            } else {
				$sql = file_get_contents('install.sql');
				if (!$sql){
					die ('Error opening file');
				}
				mysqli_multi_query($mysqli, $sql) or die('-1' . mysqli_error());
				$step = '2';
			}
		}

		if(isset($_POST['submit']) && $_POST['submit'] == 'Complete Install') {
			include ('../config.php');

			// Settings Validations
			if($_POST['installUrl'] == "") {
				$msgBox = alertBox("Please enter the Installation URL (include the trailing slash).", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['siteName'] == "") {
				$msgBox = alertBox("Please enter a Site Name.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['siteEmail'] == "") {
				$msgBox = alertBox("Please enter the main site reply-to Email address.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['uploadPath'] == "") {
				$msgBox = alertBox("Please enter the folder location where Confession Uploads will be saved.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['fileTypesAllowed'] == "") {
				$msgBox = alertBox("Please enter the allowed File Type Extensions.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['adsPath'] == "") {
				$msgBox = alertBox("Please enter the folder location where Advertisement images will be saved.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['adTypesAllowed'] == "") {
				$msgBox = alertBox("Please enter the allowed Advertisement File Type Extensions.", "<i class='fa fa-times-circle'></i>", "danger");
			}
			// Main Admin Account Validations
			else if($_POST['userEmail'] == '') {
				$msgBox = alertBox("Please enter a valid email for the Primary Admin. Email addresses are used as your account login.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['password'] == '') {
				$msgBox = alertBox("Please enter a password for the Primary Admin's Account.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['r-password'] == '') {
				$msgBox = alertBox("Please re-enter the password for the Primary Admin's Account.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['password'] != $_POST['r-password']) {
				$msgBox = alertBox("The password for the Primary Admin's Account does not match.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['userFirst'] == '') {
				$msgBox = alertBox("Please enter the Primary Admin's First Name.", "<i class='fa fa-times-circle'></i>", "danger");
			} else if($_POST['userLast'] == '') {
				$msgBox = alertBox("Please enter the Primary Admin's Last Name.", "<i class='fa fa-times-circle'></i>", "danger");
			} else {
				$installUrl = $mysqli->real_escape_string($_POST['installUrl']);
				$siteName = $mysqli->real_escape_string($_POST['siteName']);
				$siteEmail = $mysqli->real_escape_string($_POST['siteEmail']);
				$uploadPath = $mysqli->real_escape_string($_POST['uploadPath']);
				$fileTypesAllowed = $mysqli->real_escape_string($_POST['fileTypesAllowed']);
				$adsPath = $mysqli->real_escape_string($_POST['adsPath']);
				$adTypesAllowed = $mysqli->real_escape_string($_POST['adTypesAllowed']);
				$aboutUs = '
&lt;h2&gt;About Fess Up&lt;/h2&gt;
&lt;p class=&quot;lead&quot;&gt;Confess yourself &amp;mdash; Don’t repress yourself.&lt;/p&gt;
&lt;p class=&quot;lead&quot;&gt;Say out loud what you’ve been keeping inside. Say the whole truth and nothing but the truth. Tell your confessions to total strangers without any fear. Go ahead, take the leap.&lt;/p&gt;

&lt;hr /&gt;

&lt;h5&gt;Want to Advertise with us?&lt;/h5&gt;
&lt;p class=&quot;lead&quot;&gt;Get your ad seen on Fess Up. Create an account, and then contact us about your ad requirements. We would love to work with you and get you the best exposure possible on Fess Up.&lt;/p&gt;

&lt;hr /&gt;

&lt;h5&gt;Have a Question?&lt;/h5&gt;
&lt;p class=&quot;lead&quot;&gt;If you have any questions or concerns, or just want to tell us what a great time you have had on Fess Up, please let us know. We would love to hear from you.&lt;/p&gt;
				';
				$siteRules = '
&lt;h2 class=&quot;mb-20&quot;&gt;Fess Up Rules of Conduct&lt;/h2&gt;

&lt;h5&gt;Your Privacy&lt;/h5&gt;
&lt;p&gt;Your anonymity is of primary importance to us. We do not sell information about you to anyone or any organization for any reason whatsoever.&lt;/p&gt;

&lt;h5&gt;Confession Submissions&lt;/h5&gt;
&lt;p&gt;
	All materials submitted, posted or shown by users of Fess Up becomes, on submission, the property of Fess Up and is free of any claims of proprietary
	or personal rights. Fess Up reserves the present and future right to post, publish or otherwise disseminate these materials on this site or in any existing
	or future media as the sole copyright holder and owner of all publication and ownership rights without possible claims of ownership from any parties that have used this website and/or its services.
&lt;/p&gt;

&lt;h5&gt;Conditions of Use&lt;/h5&gt;
&lt;p&gt;
	You agree that you shall not use Fess Up in any manner that violates any applicable law, regulation, or term of this Agreement. Specifically, you agree that you will not
	(I.) access or attempt to access any account that you are not authorized to access, (II.) modify or attempt to modify Fess Up in any manner or form, (III.) copy, distribute,
	or create derivative works based on Fess Up, (IV.) exploit Fess Up in any unauthorized way whatsoever, including but not limited to, by trespass
	or burdening network capacity, or (V.) sub-license, sell, resell, or otherwise convey Fess Up or any elements thereof.
&lt;/p&gt;
&lt;p&gt;When posting Confession content, including pictures or images, to or through Fess Up, you agree that you shall not:&lt;/p&gt;
&lt;ul&gt;
	&lt;li&gt;Post content that includes personally-identifying information of yourself or another person.&lt;/li&gt;
	&lt;li&gt;Post images that are sexually explicit or pornographic.&lt;/li&gt;
	&lt;li&gt;Post content that harasses or advocates harassment of another person, or promotes racism, bigotry, hatred or physical harm of any kind against any group or individual.&lt;/li&gt;
	&lt;li&gt;Post content that you know is false or misleading.&lt;/li&gt;
	&lt;li&gt;Post content that promotes illegal activities or conduct that is abusive, threatening, obscene, defamatory or libellous.&lt;/li&gt;
	&lt;li&gt;Post content that violates, or promotes the violation of, the intellectual property rights of any third party.&lt;/li&gt;
	&lt;li&gt;Impersonate any individual.&lt;/li&gt;
&lt;/ul&gt;
&lt;p&gt;We reserve the right, in our sole discretion without notice or liability, to review, edit, and/or remove content posted to Fess Up.&lt;/p&gt;
&lt;p&gt;
	We reserve the right at any time to modify or discontinue, temporarily or permanently, Fess Up (or any part thereof) with or without notice. We will not be liable
	to you or to any third party for such modification or discontinuation.
&lt;/p&gt;

&lt;h5&gt;General Disclaimer&lt;/h5&gt;
&lt;p&gt;
	Fess Up reserves the right to review all confessions and comments on the site but does not represent or endorse the accuracy or reliability of any of such content.
	Fess Up reserves that right to edit or delete any post. The information and opinions expressed on the site are not necessarily those of Fess Up or
	its affiliated or related entities or content providers and Fess Up makes no representations or warranties regarding that information or those opinions. Furthermore,
	neither Fess Up nor its affiliated or related entities or its content providers are responsible or liable to any person or entity whatsoever (including, without limitation,
	persons who may use or rely on such data/materials or to whom such data/materials may be furnished) for any loss, damage (whether actual, consequential, punitive or otherwise), injury, claim,
	liability or other cause of any kind or character whatsoever based upon or resulting from any information or opinions provided on Fess Up Web Site.
&lt;/p&gt;
&lt;p&gt;Fess Up reserves the right to employ IP blocking or any other means to ban anyone we deem to be offensive and or inappropriate from selected parts or the whole of this website.&lt;/p&gt;
&lt;p&gt;
	By using this site, you signify your assent to the Fess Up Rules of Conduct. If you do not agree to these terms, please do not use our site. Your continued use of the
	Fess Up website following the posting of changes to these terms will mean you accept those changes.
&lt;/p&gt;
				';

				// Add data to the siteSettings Table
				$stmt = $mysqli->prepare("
									INSERT INTO
										sitesettings(
											installUrl,
											siteName,
											siteEmail,
											uploadPath,
											fileTypesAllowed,
											adsPath,
											adTypesAllowed,
											aboutUs,
											siteRules
										) VALUES (
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?,
											?
										)");
				$stmt->bind_param('sssssssss',
										$installUrl,
										$siteName,
										$siteEmail,
										$uploadPath,
										$fileTypesAllowed,
										$adsPath,
										$adTypesAllowed,
										$aboutUs,
										$siteRules
				);
				$stmt->execute();
				$stmt->close();
				
				$userEmail = $mysqli->real_escape_string($_POST['userEmail']);
				$password = $mysqli->real_escape_string($_POST['password']);
				$userFirst = $mysqli->real_escape_string($_POST['userFirst']);
				$userLast = $mysqli->real_escape_string($_POST['userLast']);
				$isAdmin = $isActive = '1';
				$joinDate = date("Y-m-d H:i:s");

				// Encrypt Password
				$newPassword = encryptIt($password);

				// Add the new Admin Account
				$stmt = $mysqli->prepare("
									INSERT INTO
										users(
											userEmail,
											password,
											userFirst,
											userLast,
											isAdmin,
											joinDate,
											isActive
										) VALUES (
											?,
											?,
											?,
											?,
											?,
											?,
											?
										)");
				$stmt->bind_param('sssssss',
									$userEmail,
									$newPassword,
									$userFirst,
									$userLast,
									$isAdmin,
									$joinDate,
									$isActive
				);
				$stmt->execute();
				$stmt->close();

                if (is_file('../config.php')) {
					include ('../config.php');

                    // Get Settings Data
                    $settingsql  = "SELECT installUrl, siteName, siteEmail FROM sitesettings";
                    $settingres = mysqli_query($mysqli, $settingsql) or die('-2' . mysqli_error());
                    $set = mysqli_fetch_assoc($settingres);

                    // Get Admin Data
                    $adminsql  = "SELECT userEmail FROM users";
                    $adminres = mysqli_query($mysqli, $adminsql) or die('-3' . mysqli_error());
                    $admin = mysqli_fetch_assoc($adminres);

                    //Email out a confirmation
                    $siteName = $set['siteName'];
                    $siteEmail = $set['siteEmail'];
                    $installUrl = $set['installUrl'];
                    $userEmail = $admin['userEmail'];

                    $bodyText = "Congratulations, Fess Up has been successfully installed.

Your Admin Account details:
-------------------------------------
Login: ".$userEmail."
Password: The password you set up during Installation


For security reasons and to stop any possible re-installations please,
DELETE or RENAME the \"install\" folder, otherwise you will not be able
to log in as Administrator.

You can log in to your Admin account at ".$installUrl."
after the install folder has been taken care of.

If you lose or forget your password, you can use the \"Reset Password\"
link located at ".$installUrl."

Thank you,
".$siteName."

This email was automatically generated.";

                    $subject = 'Fess Up Installation Successful';
                    $emailBody = $bodyText;

                    $mail = mail($userEmail, $subject, $emailBody,
                    "From: ".$siteName." <".$siteEmail.">\r\n"
                    ."Reply-To: ".$siteEmail."\r\n"
                    ."X-Mailer: PHP/" . phpversion());
                }

				$step = '3';
			}
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Fess Up &middot; Installation &amp; Setup</title>

	<link rel="stylesheet" type="text/css" href="../css/fonts.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-min.css">
	<link rel="stylesheet" type="text/css" href="../css/custom.css">
	<link rel="stylesheet" type="text/css" href="../css/fessup.css">
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.css" />
	<style type="text/css">
		.navbar { min-height: 110px; }
		#main-container { padding-top: 0; }
	</style>

	<!--[if lt IE 9]>
		<script src="../js/html5shiv.js"></script>
		<script src="../js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<div class="navbar navbar-inverse">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="install.php"><img src="../images/logo.png" alt="Logo"></a>
			</div>
		</div>
	</div>

	<section id="main-container">
		<div class="container">
			<?php if ($step == 'check') { ?>

			<h3 class="text-center">Installing Fess Up is easy.<br />Four steps and less then 5 minutes. Ready?</h3>
			<div class="panel panel-primary">
				<div class="panel-heading">Server Configuration Check</div>
				<div class="panel-body">
					<table class="table table-condensed">
						<tbody>
							<tr class="primary">
								<th>PHP Version</th>
								<th>Your Version</th>
								<th class="text-right">Pass / Fail</th>
							</tr>
							<tr>
								<td>V.5+ Required</td>
								<td><?php echo $phpversion; ?></td>
								<td class="text-right"><?php echo $phpcheck; ?></td>
							</tr>
						</tbody>
					</table>

					<table class="table table-condensed">
						<tr>
							<th>MySQLi Installed</th>
							<th class="text-right">Pass / Fail</th>
						</tr>
						<tr>
							<td>MySQLi Check</td>
							<td class="text-right"><?php echo $mysqliver; ?></td>
						</tr>
					</table>
					
					<table class="table table-condensed">
						<tr>
							<th>mcrypt_encrypt Installed</th>
							<th class="text-right">Pass / Fail</th>
						</tr>
						<tr>
							<td>mcrypt_encrypt Check</td>
							<td class="text-right"><?php echo $mysqliver; ?></td>
						</tr>
					</table>
					<span class="pull-right">
						<?php if (($phpbtn != '') || ($mysqlibtn != '') || ($mcryptbtn != '')) { ?>
							<form action="" method="post">
								<button type="input" name="submit" value="nextStep" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square"></i> Start the Installation</button>
							</form>
						<?php } ?>
					</span>
				</div>
			</div>

			<?php } else if ($step == '1') { ?>

			<h3 class="text-center">Installing Fess Up is easy.<br />Four steps and less then 5 minutes. Ready?</h3>
			<?php if ($msgBox) { echo $msgBox; } ?>

			<div class="panel panel-primary">
				<div class="panel-heading">Step 1 <i class="fa fa-long-arrow-right"></i> Configure Database &amp Time Zone</div>
				<div class="panel-body">
					<p class="lead">Please type in your database information &amp; select a Time Zone.</p>

					<?php if (isset($no_perm)) { ?>

					<script type="text/javascript">
						function select_all(obj) {
							var text_val = eval(obj);
							text_val.focus();
							text_val.select();
						}
					</script>
					<p class="lead">
						You haven't the permissions to create a new file. Please manually create a file named <strong>config.php</strong> in the root
						directory and copy the text from the box below.<br />
						Once it's created, <a href="install.php">refresh this page</a>.
					</p>
					<textarea name="configStr" id="configStr" onClick="select_all(this);" cols="58" rows="6"><?php echo $str; ?></textarea>

					<?php } elseif (!$file) { ?>
						<form action="" method="post" class="mt-10">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="dbhost">Host Name</label>
										<input type="text" class="form-control" name="dbhost" value="localhost" />
										<span class="help-block">Usually 'localhost'. Check with your Host Provider.</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="dbname">Database Name</label>
										<input type="text" class="form-control" name="dbname" value="<?php echo isset($_POST['dbname']) ? $_POST['dbname'] : '' ?>" />
										<span class="help-block">The Database Name.</span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="dbuser">Database Username</label>
										<input type="text" class="form-control" name="dbuser" value="<?php echo isset($_POST['dbuser']) ? $_POST['dbuser'] : '' ?>" />
										<span class="help-block">The User allowed to connect to the Database.</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="dbpass">Database User Password</label>
										<input type="text" class="form-control" name="dbpass" value="<?php echo isset($_POST['dbpass']) ? $_POST['dbpass'] : '' ?>" />
										<span class="help-block">The Password for the User allowed to connect to the Database.</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="timezone">Select Time Zone</label>
								<select class="form-control" name="timezone">
									<option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
									<option value="America/Adak">(GMT-10:00) Hawaii-Aleutian</option>
									<option value="Etc/GMT+10">(GMT-10:00) Hawaii</option>
									<option value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</option>
									<option value="Pacific/Gambier">(GMT-09:00) Gambier Islands</option>
									<option value="America/Anchorage">(GMT-09:00) Alaska</option>
									<option value="America/Ensenada">(GMT-08:00) Tijuana, Baja California</option>
									<option value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</option>
									<option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
									<option value="America/Denver">(GMT-07:00) Mountain Time (US & Canada)</option>
									<option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
									<option value="America/Dawson_Creek">(GMT-07:00) Arizona</option>
									<option value="America/Belize">(GMT-06:00) Saskatchewan, Central America</option>
									<option value="America/Cancun">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
									<option value="Chile/EasterIsland">(GMT-06:00) Easter Island</option>
									<option value="America/Chicago">(GMT-06:00) Central Time (US & Canada)</option>
									<option value="America/New_York" selected>(GMT-05:00) Eastern Time (US & Canada)</option>
									<option value="America/Havana">(GMT-05:00) Cuba</option>
									<option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
									<option value="America/Caracas">(GMT-04:30) Caracas</option>
									<option value="America/Santiago">(GMT-04:00) Santiago</option>
									<option value="America/La_Paz">(GMT-04:00) La Paz</option>
									<option value="Atlantic/Stanley">(GMT-04:00) Faukland Islands</option>
									<option value="America/Campo_Grande">(GMT-04:00) Brazil</option>
									<option value="America/Goose_Bay">(GMT-04:00) Atlantic Time (Goose Bay)</option>
									<option value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</option>
									<option value="America/St_Johns">(GMT-03:30) Newfoundland</option>
									<option value="America/Araguaina">(GMT-03:00) UTC-3</option>
									<option value="America/Montevideo">(GMT-03:00) Montevideo</option>
									<option value="America/Miquelon">(GMT-03:00) Miquelon, St. Pierre</option>
									<option value="America/Godthab">(GMT-03:00) Greenland</option>
									<option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</option>
									<option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
									<option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
									<option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
									<option value="Atlantic/Azores">(GMT-01:00) Azores</option>
									<option value="Europe/Belfast">(GMT) Greenwich Mean Time : Belfast</option>
									<option value="Europe/Dublin">(GMT) Greenwich Mean Time : Dublin</option>
									<option value="Europe/Lisbon">(GMT) Greenwich Mean Time : Lisbon</option>
									<option value="Europe/London">(GMT) Greenwich Mean Time : London</option>
									<option value="Africa/Abidjan">(GMT) Monrovia, Reykjavik</option>
									<option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
									<option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
									<option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
									<option value="Africa/Algiers">(GMT+01:00) West Central Africa</option>
									<option value="Africa/Windhoek">(GMT+01:00) Windhoek</option>
									<option value="Asia/Beirut">(GMT+02:00) Beirut</option>
									<option value="Africa/Cairo">(GMT+02:00) Cairo</option>
									<option value="Asia/Gaza">(GMT+02:00) Gaza</option>
									<option value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria</option>
									<option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
									<option value="Europe/Minsk">(GMT+02:00) Minsk</option>
									<option value="Asia/Damascus">(GMT+02:00) Syria</option>
									<option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
									<option value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</option>
									<option value="Asia/Tehran">(GMT+03:30) Tehran</option>
									<option value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</option>
									<option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
									<option value="Asia/Kabul">(GMT+04:30) Kabul</option>
									<option value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>
									<option value="Asia/Tashkent">(GMT+05:00) Tashkent</option>
									<option value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
									<option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
									<option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
									<option value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</option>
									<option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
									<option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
									<option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
									<option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
									<option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
									<option value="Australia/Perth">(GMT+08:00) Perth</option>
									<option value="Australia/Eucla">(GMT+08:45) Eucla</option>
									<option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
									<option value="Asia/Seoul">(GMT+09:00) Seoul</option>
									<option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
									<option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
									<option value="Australia/Darwin">(GMT+09:30) Darwin</option>
									<option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
									<option value="Australia/Hobart">(GMT+10:00) Hobart</option>
									<option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
									<option value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</option>
									<option value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</option>
									<option value="Asia/Magadan">(GMT+11:00) Magadan</option>
									<option value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</option>
									<option value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka</option>
									<option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
									<option value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
									<option value="Pacific/Chatham">(GMT+12:45) Chatham Islands</option>
									<option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
									<option value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</option>
								</select>
							</div>
							<span class="pull-right">
								<button type="input" name="submit" value="On to Step 2" class="btn btn-success btn-lg btn-icon mt-10"><i class="fa fa-check-square"></i> On to Step 2</button>
							</span>
						</form>
					<?php } else { ?>
						<div class="alertMsg danger">
							<i class='fa fa-times-circle'></i> Your database information is incorrect. Please delete the generated <strong>config.php</strong> file and then <a href="install.php">refresh this page</a>.
						</div>
					<?php } ?>

					<?php
					} else if ($step == '2') {

						include('../config.php');
						$isSetup = '';

						// Check for Data
						if ($result = $mysqli->query("SELECT * FROM sitesettings LIMIT 1")) {
							if ($obj = $result->fetch_object()) {
								$isSetup = 'true';
							}
							$result->close();
						}

						if($isSetup == '') {

						// Get the install URL
						$siteURL = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
						$installURL = str_replace("install/install.php", "", $siteURL);
					?>
						<h3 class="text-center">Installing Fess Up is easy.<br />Four steps and less then 5 minutes. Keep Going!</h3>
						<?php if ($msgBox) { echo $msgBox; } ?>

						<div class="alertMsg success">
							<i class='fa fa-check'></i> Your database has been correctly configured.
						</div>

						<form action="" method="post">
							<div class="panel panel-primary">
								<div class="panel-heading">Step 2 <i class="fa fa-long-arrow-right"></i> Global Settings</div>
								<div class="panel-body">
									<p class="lead">Now please take a few minutes and complete the information below in order to finish installing Fess Up.</p>

									<div class="settingsNote highlight"></div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="installUrl">Installation URL</label>
												<input type="text" class="form-control" name="installUrl" value="<?php echo $installURL; ?>" />
												<span class="help-block">Used in Notification emails &amp; Avatars. Must include the trailing slash. Change the default value if it is not correct.</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="siteName">Site Name</label>
												<input type="text" class="form-control" name="siteName" value="<?php echo isset($_POST['siteName']) ? $_POST['siteName'] : ''; ?>" />
												<span class="help-block">ie. Fess Up (Appears at the top of the browser, the header logo, in the footer and in other headings throughout the site).</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="siteEmail">Site Email</label>
												<input type="text" class="form-control" name="siteEmail" value="<?php echo isset($_POST['siteEmail']) ? $_POST['siteEmail'] : ''; ?>" />
												<span class="help-block">Used in email notifications as the "from/reply to" email address.</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="uploadPath">Confession Uploads Path</label>
												<input type="text" class="form-control" name="uploadPath" value="uploads/" />
												<span class="help-block">Where Confession Images upload to. Must include the trailing slash (ie. uploads/).</span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="adsPath">Advertisement Uploads Folder</label>
												<input type="text" class="form-control" name="adsPath" value="ads/" />
												<span class="help-block">Where Advertisement images upload to. Must include the trailing slash (ie. ads/).</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="fileTypesAllowed">Allowed File Types</label>
												<input type="text" class="form-control" name="fileTypesAllowed" value="gif,jpg,jpeg,png,tiff,tif,zip,rar,pdf,doc,docx,txt,xls,csv" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="adTypesAllowed">Allowed Advertisement File Types</label>
												<input type="text" class="form-control" name="adTypesAllowed" value="jpg,jpeg,png,svg" />
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="panel panel-info">
								<div class="panel-heading">Step 2 <i class="fa fa-long-arrow-right"></i> Primary Admin Account</div>
								<div class="panel-body">
									<p class="lead">Finally, set up the Primary Admin Account.</p>

									<div class="form-group">
										<label for="userEmail">Administrator's Email</label>
										<input type="text" class="form-control" name="userEmail" value="<?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : ''; ?>" />
										<span class="help-block">Your email address is also used for your Account log In.</span>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="userFirst">Administrator's First Name</label>
												<input type="text" class="form-control" name="userFirst" value="<?php echo isset($_POST['userFirst']) ? $_POST['userFirst'] : ''; ?>" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="userLast">Administrator's Last Name</label>
												<input type="text" class="form-control" name="userLast" value="<?php echo isset($_POST['userLast']) ? $_POST['userLast'] : ''; ?>" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="password">Administrator's Password</label>
												<input type="text" class="form-control" name="password" value="" />
												<span class="help-block">Type a Password for your Account.</span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="r-password">Re-type Administrator's Password</label>
												<input type="text" class="form-control" name="r-password" value="" />
												<span class="help-block">Please type your desired Password again. Passwords MUST Match.</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<span class="pull-right">
								<button type="input" name="submit" value="Complete Install" class="btn btn-success btn-lg btn-icon mt-10 mb-20"><i class="fa fa-check-square"></i> Complete Install</button>
							</span>
						</form>
						<div class="clearfix"></div>

						<?php } else { ?>
							<h3 class="text-center">Fess Up Installation Complete</h3>
							<div class="panel panel-primary">
								<div class="panel-heading">Step 3 <i class="fa fa-long-arrow-right"></i> Ready to get Started?</div>
								<div class="panel-body">
									<div class="alertMsg info mt-10">
										<i class='fa fa-info-circle'></i> Whoops! Looks like the <strong>"install"</strong> folder is still there!
									</div>
									<p class="lead">
										For security reasons and to stop any possible re-installations please, <strong>DELETE or RENAME</strong> the "install" folder,
										otherwise you will not be able to log in as Administrator.
									</p>
									<div class="alertMsg warning mt-10">
										<i class="fa fa-times-circle"></i> Please <strong>DELETE or RENAME</strong> the "install" folder.
									</div>
									<a href="../index.php" class="btn btn-lg btn-info btn-icon mt-20"><i class="fa fa-sign-in"></i> Log In</a>
								</div>
							</div>
						<?php } ?>


					<?php } else { ?>

						<h3 class="text-center">Fess Up Installation Complete</h3>
						<div class="alertMsg success">
							<i class='fa fa-check'></i> Fess Up was successfully installed.
						</div>

						<div class="panel panel-primary">
							<div class="panel-heading">Step 3 <i class="fa fa-long-arrow-right"></i> Ready to get Started?</div>
							<div class="panel-body">
								<p class="lead">
									For security reasons and to stop any possible re-installations please, <strong>DELETE or RENAME</strong> the "install" folder,
									otherwise you will not be able to log in as Administrator.
									<br />
									A confirmation email has been sent to the email address you supplied for the Primary Administrator.
								</p>
								<div class="alertMsg warning mt-10">
									<i class="fa fa-times-circle"></i> You must <strong>DELETE or RENAME</strong> the "install" folder.
								</div>
								<a href="../index.php" class="btn btn-lg btn-info btn-icon btn-icon mt-20"><i class="fa fa-sign-in"></i> Log In</a>
							</div>
						</div>

					<?php } ?>
				</div>
			</div>

		</div>
	</section>
</body>
</html>
