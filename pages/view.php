<?php
	$confId = $_GET['confession'];
	$viewIp = $_SERVER['REMOTE_ADDR'];
	
	// Get the Full Page URL
	$pageURL = (isset($_SERVER['HTTPS']) ? "https" : "http")."//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	$count = 0;
	$hasViewed = '';
	$msgBox = '';
	
	// Get the File Uploads Folder from the Site Settings
	$uploadsDir = $set['uploadPath'];

	// Check if Moderation is On
	$moderated = $set['moderation'];
	
	// Check if Profanity Filter is On
	$filterProfanity = $set['useFilter'];

	$chkViews = mysqli_query($mysqli,"SELECT 'X' FROM views WHERE confessId = ".$confId." AND viewIp = '".$viewIp."' LIMIT 1");
	$hasView = mysqli_num_rows($chkViews);
	if ($hasView == 0) { $hasViewed = '0'; }
	$viewDate = date("Y-m-d H:i:s");

	if ($hasViewed == '0') {
		$stmt = $mysqli->prepare("
							INSERT INTO
								views(
									confessId,
									viewIp,
									viewDate
								) VALUES (
									?,
									?,
									?
								)
		");
		$stmt->bind_param('sss',
							$confId,
							$viewIp,
							$viewDate
		);
		$stmt->execute();
	}

	// Add New Comment
    if (isset($_POST['submit']) && $_POST['submit'] == 'addComment') {
        // Validation
		if($_POST['commentText'] == "") {
            $msgBox = alertBox($commentsReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if($_POST['answer'] == "") {
            $msgBox = alertBox($captchaCodeReq, "<i class='fa fa-times-circle'></i>", "danger");
        } else if ($_POST['hole'] != '') {
			$msgBox = alertBox($commentsErrorMsg, "<i class='fa fa-times-circle'></i>", "danger");
			$_POST['firstName'] = $_POST['commentText'] = $_POST['answer'] = '';
		} else {
			$commentText = htmlentities($_POST['commentText']);
			$usersId = $mysqli->real_escape_string($_POST['usersId']);
			if ($_POST['firstName'] == '') {
				$firstName = null;
			} else {
				$firstName = $mysqli->real_escape_string($_POST['firstName']);
			}
			$commentDate = date("Y-m-d H:i:s");

			// Moderation Check
			if ($moderated == '1') { $isActive = '0'; } else { $isActive = '1'; }
			
			// Check if the poster is a logged in user
			if (isset($_SESSION['userId'])) {
				$user = $_SESSION['userId'];
			} else {
				$user = '0';
			}

			if(strtolower($_POST['answer']) == $_SESSION['thecode']) {
				$stmt = $mysqli->prepare("
									INSERT INTO
										comments(
											confessId,
											userId,
											firstName,
											comments,
											commentDate,
											isActive,
											commentIp
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
									$confId,
									$user,
									$firstName,
									$commentText,
									$commentDate,
									$isActive,
									$viewIp
				);
				$stmt->execute();

				if ($moderated == '1') {
					$msgBox = alertBox($commentsSavedMsg1, "<i class='fa fa-check-square'></i>", "success");
				} else {
					$msgBox = alertBox($commentsSavedMsg2, "<i class='fa fa-check-square'></i>", "success");
				}
				// Clear the Form of values
				$_POST['firstName'] = $_POST['commentText'] = $_POST['answer'] = '';
				$stmt->close();
			} else {
				$msgBox = alertBox($captchaErrorMsg, "<i class='fa fa-warning'></i>", "warning");
			}
			
			// If the confession is posted by a user
			if ($usersId != '0') {
				$uemail = "SELECT userEmail, recEmails FROM users WHERE userId = ".$usersId;
				$remail = mysqli_query($mysqli, $uemail) or die('-1' . mysqli_error());
				$e = mysqli_fetch_assoc($remail);
				$userEmail = $e['userEmail'];
				$recEmails = $e['recEmails'];
				
				// If the users has opted in to receive notifications
				if ($recEmails == '1') {
					// Send out the email in HTML
					$installUrl = $set['installUrl'];
					$siteName = $set['siteName'];
					$siteEmail = $set['siteEmail'];

					$subject = $newCommentEmailSubject;
					$message = '<html><body>';
					$message .= '<h3>'.$subject.'</h3>';
					$message .= '<hr>';
					$message .= '<p>'.nl2br($commentText).'</p>';
					$message .= '<hr>';
					$message .= '<p>'.$newCommentEmail1.' '.$pageURL.'</p>';
					$message .= '<p>'.$subscribeEmail3.'<br>'.$siteName.'</p>';
					$message .= '</body></html>';
					$headers = "From: ".$siteName." <".$siteEmail.">\r\n";
					$headers .= "Reply-To: ".$siteEmail."\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

					mail($userEmail, $subject, $message, $headers);
				}
			}
		}
	}

	// Get Confession
	$select = "SELECT
					confessId,
					userId,
					(IFNULL(firstName, '')) AS firstName,
					confessText,
					DATE_FORMAT(postDate,'%b %d %Y %h:%i %p') AS postDate,
					hasImage,
					isActive,
					(SELECT COUNT(*) FROM views WHERE views.confessId = confessions.confessId ) as totalViews,
					(SELECT COUNT(*) FROM likes WHERE likes.confessId = confessions.confessId ) as totalLikes,
					(SELECT COUNT(*) FROM dislikes WHERE dislikes.confessId = confessions.confessId ) as totalDislikes
				FROM
					confessions
				WHERE confessId = ".$confId;
	$res = mysqli_query($mysqli, $select) or die('-1' . mysqli_error());
	$row = mysqli_fetch_assoc($res);

	if ($row['totalViews'] == '1') { $viewText = $singleViewText; } else { $viewText = $multipleViewsText; }
	$shareURL = $set['installUrl'].'page.php?page=view&confession='.$row['confessId'];
	$googleURL = $set['installUrl'];

	// Get Comments
	$qry = "SELECT
				commentId,
				confessId,
				(IFNULL(firstName, '')) AS fName,
				comments,
				DATE_FORMAT(commentDate,'%b %d %Y %h:%i %p') AS commentDate,
				isActive
			FROM
				comments
			WHERE
				confessId = ".$confId." AND
				isActive = 1
			ORDER BY commentId DESC";
	$results = mysqli_query($mysqli, $qry) or die('-2'.mysqli_error());

	include('includes/header.php');
?>
	<section id="main-container">
		<div class="container">
			<?php if ($msgBox) { echo $msgBox; } ?>

			<div class="confessbox">
				<div class="confession">
					<?php
						if ($row['hasImage'] == '1') {
							// Get Image
							$sqlStmt = "SELECT uploadId, confessId, uploadUrl FROM uploads WHERE confessId = ".$confId;
							$sqlres = mysqli_query($mysqli, $sqlStmt) or die('-2'.mysqli_error());
							$col = mysqli_fetch_assoc($sqlres);
							
							//Get File Extension
							$ext = substr(strrchr($col['uploadUrl'],'.'), 1);
							$imgExts = array('gif', 'GIF', 'jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'tiff', 'TIFF', 'tif', 'TIF', 'bmp', 'BMP');
							
							if (in_array($ext, $imgExts)) {
								echo '<p class="mb-20"><img alt="'.$confImageAlt.'" src="'.$uploadsDir.$col['uploadUrl'].'" class="img-responsive" /></p>';
							}
						}
					?>
					<p>
						<i class="fa fa-quote-left"></i>
						<?php
							if ($filterProfanity == '1') {
								echo nl2br(htmlspecialchars(filterwords($row['confessText'])));
							} else {
								echo nl2br(htmlspecialchars($row['confessText']));
							}
						?>
						<i class="fa fa-quote-right"></i>
					</p>
					<input type="hidden" id="confessId" name="confessId_<?php echo $count; ?>" value="<?php echo $row['confessId']; ?>" />
					<div class="confession-footer">
						<div class="likes">
							<span class="label label-confess first liked">
								<a href="" id="likeIt<?php echo $row['confessId']; ?>" class="likeIt_<?php echo $count; ?> text-success">
									<i class="fa fa-smile-o"></i> <span id="likesVal_<?php echo $count; ?>"><?php echo $row['totalLikes']; ?></span>
								</a>
							</span>
						</div>
						<div class="dislikes">
							<span class="label label-confess disliked">
								<a href="" id="dislikeIt<?php echo $row['confessId']; ?>" class="dislike_<?php echo $count; ?> text-danger">
									<span id="dislikesVal_<?php echo $count; ?>"><?php echo $row['totalDislikes']; ?></span> <i class="fa fa-frown-o"></i>
								</a>
							</span>
						</div>
						<span class="label label-confess"><?php echo timeago($row['postDate']); ?></span>
						<span class="label label-confess last"><?php echo $row['totalViews'].' '.$viewText; ?></span>
						<a href="https://twitter.com/intent/tweet?text=<?php echo $set['siteName']; ?>%20Confession:%20<?php echo htmlspecialchars(ellipsis($row['confessText'],65)); ?>%20&url=<?php echo $shareURL; ?>" class="btn btn-tw btn-sm" target="_blank" data-toggle="tooltip" data-placement="top" title="<?php echo $twitterShareTooltip; ?>">
							<i class="fa fa-twitter"></i>
						</a>
						<a href="https://plus.google.com/share?url=<?php echo $googleURL; ?>" class="btn btn-gp btn-sm" target="_blank" data-toggle="tooltip" data-placement="top" title="<?php echo $googleShareTooltip; ?>">
							<i class="fa fa-google-plus"></i>
						</a>
						<span class="label label-confess last hasVoted text-danger"><strong><?php echo $onlyVoteOnceText; ?></strong></span>
						<div class="comments">
							<?php if ($row['firstName'] != '') { ?>
								<span class="label label-confess last"><?php echo $postedByText.' '.clean($row['firstName']); ?></span>
							<?php } else { ?>
								<span class="label label-confess last"><?php echo $postedByAnon; ?></span>
							<?php } ?>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>

			<?php if(mysqli_num_rows($results) > 0) { ?>
				<hr />
				<div class="commentbox">
					<?php while ($rows = mysqli_fetch_assoc($results)) { ?>
						<div class="comment">
							<p>
								<?php
									if ($filterProfanity == '1') {
										echo nl2br(clean(filterwords($rows['comments'])));
									} else {
										echo nl2br(clean($rows['comments']));
									}
								?>
							</p>
							<?php if ($rows['fName'] != '') { ?>
								<span class="label label-comments"><?php echo clean($rows['fName']).' '.$commentedText.' '.timeago($rows['commentDate']); ?></span>
							<?php } else { ?>
								<span class="label label-comments"><?php echo $anonCommented.' '.timeago($rows['commentDate']); ?></span>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>

			<hr />

			<form action="" method="post" class="comment-form mt-30">
				<div class="form-group">
					<textarea class="form-control" name="commentText" id="commentText" rows="4" required="" placeholder="<?php echo $addCommentsField; ?>"><?php echo isset($_POST['commentText']) ? $_POST['commentText'] : ''; ?></textarea>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<input type="text" class="form-control" name="firstName" placeholder="<?php echo $firstNamePlaceholder; ?>" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : ''; ?>">
						</div>
					</div>
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								<img src="includes/captcha.php" id="captcha" data-toggle="tooltip" data-placement="left" class="pull-right" title="<?php echo $captchaCodeTooltip; ?>" />
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<input type="text" class="form-control" name="answer" required="" maxlength="6" placeholder="<?php echo $captchaCodeTooltip; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<p><?php echo $commentsQuip1; ?><strong><?php echo $isOn; ?></strong>. <?php echo $commentsQuip2; ?> <strong><?php echo $filtered; ?></strong>.</p>
					</div>
					<div class="col-md-4">
						<input type="hidden" name="hole" id="hole" />
						<input type="hidden" name="usersId" value="<?php echo $row['userId']; ?>" />
						<button type="input" name="submit" value="addComment" class="btn btn-fessup btn-lg pull-right btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveCommentsBtn; ?></button>
					</div>
				</div>
			</form>

		</div>
	</section>