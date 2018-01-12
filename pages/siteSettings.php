<?php
	$msgBox = '';

	// Update Global Site Settings
    if (isset($_POST['submit']) && $_POST['submit'] == 'updateSettings') {
        // Validation
		if($_POST['installUrl'] == "") {
            $msgBox = alertBox($installUrlMsg, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['siteName'] == "") {
            $msgBox = alertBox($siteNameMsg, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['siteEmail'] == "") {
            $msgBox = alertBox($siteEmalMsg, "<i class='fa fa-times-circle'></i>", "danger");
        } else if(($_POST['allowUploads'] == "1") && ($_POST['uploadPath'] == "")) {
			$msgBox = alertBox($uploadsPathReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if(($_POST['allowUploads'] == "1") && ($_POST['fileTypesAllowed'] == "")) {
			$msgBox = alertBox($uploadFileTypesReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if(($_POST['enableAds'] == "1") && ($_POST['adsPath'] == "")) {
			$msgBox = alertBox($adPathReq, "<i class='fa fa-times-circle'></i>", "danger");
		} else if(($_POST['enableAds'] == "1") && ($_POST['adTypesAllowed'] == "")) {
			$msgBox = alertBox($adsFileTypesReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else {
			// Add the trailing slash if there is not one
			$installUrl = $mysqli->real_escape_string($_POST['installUrl']);
			$uploadPath = $mysqli->real_escape_string($_POST['uploadPath']);
			$adsPath = $mysqli->real_escape_string($_POST['adsPath']);
			if(substr($installUrl, -1) != '/') { $install = $installUrl.'/'; } else { $install = $installUrl; }
			if(substr($uploadPath, -1) != '/') { $uploadsDir = $uploadPath.'/'; } else { $uploadsDir = $uploadPath; }
			if(substr($adsPath, -1) != '/') { $adsDir = $adsPath.'/'; } else { $adsDir = $adsPath; }

			$localization = $mysqli->real_escape_string($_POST['localization']);
			$siteName = $mysqli->real_escape_string($_POST['siteName']);
			$siteEmail = $mysqli->real_escape_string($_POST['siteEmail']);
			$analyticsCode = htmlspecialchars($_POST['analyticsCode']);
			$fileTypesAllowed = $mysqli->real_escape_string($_POST['fileTypesAllowed']);
			$adTypesAllowed = $mysqli->real_escape_string($_POST['adTypesAllowed']);
			$moderation = $mysqli->real_escape_string($_POST['moderation']);
			$useFilter = $mysqli->real_escape_string($_POST['useFilter']);
			$allowRegistrations = $mysqli->real_escape_string($_POST['allowRegistrations']);
			$allowUploads = $mysqli->real_escape_string($_POST['allowUploads']);
			$enableAds = $mysqli->real_escape_string($_POST['enableAds']);
			$aboutUs = htmlspecialchars($_POST['aboutUs']);
			$siteRules = htmlspecialchars($_POST['siteRules']);

            $stmt = $mysqli->prepare("
                                UPDATE
                                    sitesettings
                                SET
									installUrl = ?,
									localization = ?,
									siteName = ?,
									analyticsCode = ?,
									siteEmail = ?,
									uploadPath = ?,
									fileTypesAllowed = ?,
									adsPath = ?,
									adTypesAllowed = ?,
									moderation = ?,
									useFilter = ?,
									allowRegistrations = ?,
									allowUploads = ?,
									enableAds = ?,
									aboutUs = ?,
									siteRules = ?
			");
            $stmt->bind_param('ssssssssssssssss',
								   $install,
								   $localization,
								   $siteName,
								   $analyticsCode,
								   $siteEmail,
								   $uploadsDir,
								   $fileTypesAllowed,
								   $adsDir,
								   $adTypesAllowed,
								   $moderation,
								   $useFilter,
								   $allowRegistrations,
								   $allowUploads,
								   $enableAds,
								   $aboutUs,
								   $siteRules
			);
            $stmt->execute();
			$msgBox = alertBox($siteSettingsSavedMsg, "<i class='fa fa-check-square'></i>", "success");
            $stmt->close();
		}
	}

	// Get Data
	$sqlStmt = "SELECT * FROM sitesettings";
	$res = mysqli_query($mysqli, $sqlStmt) or die('-1' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

	if ($row['localization'] == 'ar') { $ar = 'selected'; } else { $ar = ''; }
	if ($row['localization'] == 'bg') { $bg = 'selected'; } else { $bg = ''; }
	if ($row['localization'] == 'ce') { $ce = 'selected'; } else { $ce = ''; }
	if ($row['localization'] == 'cs') { $cs = 'selected'; } else { $cs = ''; }
	if ($row['localization'] == 'da') { $da = 'selected'; } else { $da = ''; }
	if ($row['localization'] == 'en') { $en = 'selected'; } else { $en = ''; }
	if ($row['localization'] == 'en-ca') { $en_ca = 'selected'; } else { $en_ca = ''; }
	if ($row['localization'] == 'en-gb') { $en_gb = 'selected'; } else { $en_gb = ''; }
	if ($row['localization'] == 'es') { $es = 'selected'; } else { $es = ''; }
	if ($row['localization'] == 'fr') { $fr = 'selected'; } else { $fr = ''; }
	if ($row['localization'] == 'ge') { $ge = 'selected'; } else { $ge = ''; }
	if ($row['localization'] == 'hr') { $hr = 'selected'; } else { $hr = ''; }
	if ($row['localization'] == 'hu') { $hu = 'selected'; } else { $hu = ''; }
	if ($row['localization'] == 'hy') { $hy = 'selected'; } else { $hy = ''; }
	if ($row['localization'] == 'id') { $id = 'selected'; } else { $id = ''; }
	if ($row['localization'] == 'it') { $it = 'selected'; } else { $it = ''; }
	if ($row['localization'] == 'ja') { $ja = 'selected'; } else { $ja = ''; }
	if ($row['localization'] == 'ko') { $ko = 'selected'; } else { $ko = ''; }
	if ($row['localization'] == 'nl') { $nl = 'selected'; } else { $nl = ''; }
	if ($row['localization'] == 'pt') { $pt = 'selected'; } else { $pt = ''; }
	if ($row['localization'] == 'ro') { $ro = 'selected'; } else { $ro = ''; }
	if ($row['localization'] == 'sv') { $sv = 'selected'; } else { $sv = ''; }
	if ($row['localization'] == 'th') { $th = 'selected'; } else { $th = ''; }
	if ($row['localization'] == 'vi') { $vi = 'selected'; } else { $vi = ''; }
	if ($row['localization'] == 'yue') { $yue = 'selected'; } else { $yue = ''; }

	if ($row['moderation'] == '1') { $moderation = 'selected'; } else { $moderation = ''; }
	if ($row['useFilter'] == '1') { $useFilter = 'selected'; } else { $useFilter = ''; }
	if ($row['allowRegistrations'] == '1') { $allowReg = 'selected'; } else { $allowReg = ''; }
	if ($row['allowUploads'] == '1') { $allowUploads = 'selected'; } else { $allowUploads = ''; }
	if ($row['enableAds'] == '1') { $enableAds = 'selected'; } else { $enableAds = ''; }

	include('includes/header.php');

	if ($admin != '1') {
?>
	<section id="main-container">
		<div class="container">
			<h3><?php echo $accessErrorHeader; ?></h3>
			<div class="alertMsg danger no-margin">
				<i class="fa fa-warning"></i> <?php echo $permissionDenied; ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<section id="main-container">
		<div class="container">			
			<h3 class="mb-20"><?php echo $siteSettingsPageTitle; ?></h3>
			<?php if ($msgBox) { echo $msgBox; } ?>
			
			<form action="" method="post">
				<h5><?php echo $appSettingsTitle; ?></h5>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="installUrl"><?php echo $installUrlField; ?></label>
							<input type="text" class="form-control" required="" name="installUrl" value="<?php echo $row['installUrl']; ?>" />
							<span class="help-block"><?php echo $installUrlHelper; ?></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="localization"><?php echo $localizationField; ?></label>
							<select class="form-control" name="localization">
								<option value="ar" <?php echo $ar; ?>><?php echo $optionArabic; ?> &mdash; ar.php</option>
								<option value="bg" <?php echo $bg; ?>><?php echo $optionBulgarian; ?> &mdash; bg.php</option>
								<option value="ce" <?php echo $ce; ?>><?php echo $optionChechen; ?> &mdash; ce.php</option>
								<option value="cs" <?php echo $cs; ?>><?php echo $optionCzech; ?> &mdash; cs.php</option>
								<option value="da" <?php echo $da; ?>><?php echo $optionDanish; ?> &mdash; da.php</option>
								<option value="en" <?php echo $en; ?>><?php echo $optionEnglish; ?> &mdash; en.php</option>
								<option value="en-ca" <?php echo $en_ca; ?>><?php echo $optionCanadianEnglish; ?> &mdash; en-ca.php</option>
								<option value="en-gb" <?php echo $en_gb; ?>><?php echo $optionBritishEnglish; ?> &mdash; en-gb.php</option>
								<option value="es" <?php echo $es; ?>><?php echo $optionEspanol; ?> &mdash; es.php</option>
								<option value="fr" <?php echo $fr; ?>><?php echo $optionFrench; ?> &mdash; fr.php</option>
								<option value="ge" <?php echo $ge; ?>><?php echo $optionGerman; ?> &mdash; ge.php</option>
								<option value="hr" <?php echo $hr; ?>><?php echo $optionCroatian; ?> &mdash; hr.php</option>
								<option value="hu" <?php echo $hu; ?>><?php echo $optionHungarian; ?> &mdash; hu.php</option>
								<option value="hy" <?php echo $hy; ?>><?php echo $optionArmenian; ?> &mdash; hy.php</option>
								<option value="id" <?php echo $id; ?>><?php echo $optionIndonesian; ?> &mdash; id.php</option>
								<option value="it" <?php echo $it; ?>><?php echo $optionItalian; ?> &mdash; it.php</option>
								<option value="ja" <?php echo $ja; ?>><?php echo $optionJapanese; ?> &mdash; ja.php</option>
								<option value="ko" <?php echo $ko; ?>><?php echo $optionKorean; ?> &mdash; ko.php</option>
								<option value="nl" <?php echo $nl; ?>><?php echo $optionDutch; ?> &mdash; nl.php</option>
								<option value="pt" <?php echo $pt; ?>><?php echo $optionPortuguese; ?> &mdash; pt.php</option>
								<option value="ro" <?php echo $ro; ?>><?php echo $optionRomanian; ?> &mdash; ro.php</option>
								<option value="sv" <?php echo $sv; ?>><?php echo $optionSwedish; ?> &mdash; sv.php</option>
								<option value="th" <?php echo $th; ?>><?php echo $optionThai; ?> &mdash; th.php</option>
								<option value="vi" <?php echo $vi; ?>><?php echo $optionVietnamese; ?> &mdash; vi.php</option>
								<option value="yue" <?php echo $yue; ?>><?php echo $optionCantonese; ?> &mdash; yue.php</option>
							</select>
							<span class="help-block"><?php echo $localizationHelper; ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="siteName"><?php echo $siteNameField; ?></label>
							<input type="text" class="form-control" required="" name="siteName" value="<?php echo clean($row['siteName']); ?>" />
							<span class="help-block"><?php echo $siteNameHelper; ?></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="siteEmail"><?php echo $siteEmailField; ?></label>
							<input type="text" class="form-control" required="" name="siteEmail" value="<?php echo clean($row['siteEmail']); ?>" />
							<span class="help-block"><?php echo $siteEmailHelper; ?></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="analyticsCode"><?php echo $googleCodeField; ?></label>
					<textarea class="form-control" name="analyticsCode" rows="4"><?php echo htmlspecialchars_decode($row['analyticsCode']); ?></textarea>
				</div>
				<h5><?php echo $confessionSettingsTitle; ?></h5>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="allowUploads"><?php echo $allowUploadsField; ?></label>
							<select class="form-control" name="allowUploads">
								<option value="0"><?php echo $noBtn; ?></option>
								<option value="1" <?php echo $allowUploads; ?>><?php echo $yesBtn; ?></option>
							</select>
							<span class="help-block"><?php echo $allowUploadsFieldHelp; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="uploadPath"><?php echo $uploadsPathField; ?></label>
							<input type="text" class="form-control" required="" name="uploadPath" value="<?php echo clean($row['uploadPath']); ?>" />
							<span class="help-block"><?php echo $uploadsPathFieldHelp; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="fileTypesAllowed"><?php echo $uploadFileTypesField; ?></label>
							<input type="text" class="form-control" required="" name="fileTypesAllowed" value="<?php echo clean($row['fileTypesAllowed']); ?>" />
							<span class="help-block"><?php echo $uploadFileTypesFieldHelp; ?></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="moderation"><?php echo $moderationField; ?></label>
							<select class="form-control" name="moderation">
								<option value="0"><?php echo $noBtn; ?></option>
								<option value="1" <?php echo $moderation; ?>><?php echo $yesBtn; ?></option>
							</select>
							<span class="help-block"><?php echo $moderationFieldHelp; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="useFilter"><?php echo $profanityField; ?></label>
							<select class="form-control" name="useFilter">
								<option value="0"><?php echo $noBtn; ?></option>
								<option value="1" <?php echo $useFilter; ?>><?php echo $yesBtn; ?></option>
							</select>
							<span class="help-block"><?php echo $profanityFieldHelp; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="allowRegistrations"><?php echo $selfRegField; ?></label>
							<select class="form-control" name="allowRegistrations">
								<option value="0"><?php echo $noBtn; ?></option>
								<option value="1" <?php echo $allowReg; ?>><?php echo $yesBtn; ?></option>
							</select>
							<span class="help-block"><?php echo $selfRegFieldHelp; ?></span>
						</div>
					</div>
				</div>
				<h5><?php echo $advertisingTitle; ?></h5>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="enableAds"><?php echo $adField; ?></label>
							<select class="form-control" name="enableAds">
								<option value="0"><?php echo $noBtn; ?></option>
								<option value="1" <?php echo $enableAds; ?>><?php echo $yesBtn; ?></option>
							</select>
							<span class="help-block"><?php echo $adFieldHelp; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="adsPath"><?php echo $adsPathField; ?></label>
							<input type="text" class="form-control" required="" name="adsPath" value="<?php echo clean($row['adsPath']); ?>" />
							<span class="help-block"><?php echo $adsPathFieldHelp; ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="adTypesAllowed"><?php echo $adFileTypesField; ?></label>
							<input type="text" class="form-control" required="" name="adTypesAllowed" value="<?php echo clean($row['adTypesAllowed']); ?>" />
							<span class="help-block"><?php echo $adFileTypesFieldHelp; ?></span>
						</div>
					</div>
				</div>
				<h5><?php echo $aboutUsTitle; ?></h5>
				<div class="form-group">
					<label for="aboutUs"><?php echo $aboutUsField; ?></label>
					<textarea class="form-control" name="aboutUs" rows="14"><?php echo htmlspecialchars_decode($row['aboutUs']); ?></textarea>
					<span class="help-block"><?php echo $aboutUsFieldHelp; ?></span>
				</div>
				<h5><?php echo $rocTitle; ?></h5>
				<div class="form-group">
					<label for="siteRules"><?php echo $rocField; ?></label>
					<textarea class="form-control" name="siteRules" rows="14"><?php echo htmlspecialchars_decode($row['siteRules']); ?></textarea>
					<span class="help-block"><?php echo $rocFieldHelp; ?></span>
				</div>
				<button type="input" name="submit" value="updateSettings" class="btn btn-success btn-lg btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveSettingsBtn; ?></button>
			</form>
		</div>
	</div>
<?php } ?>