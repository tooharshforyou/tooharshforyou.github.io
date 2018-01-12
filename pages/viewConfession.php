<?php
	$confessId = $_GET['confessId'];
	$datePicker = 'true';
	$jsFile = 'viewConfession';
	$msgBox = '';

	// Get the File Uploads Folder from the Site Settings
	$uploadsDir = $set['uploadPath'];

	// Remove Upload from Confession
    if (isset($_POST['submit']) && $_POST['submit'] == 'removeUpload') {
		$uploadId = $mysqli->real_escape_string($_POST['uploadId']);
		$uploadUrl = $mysqli->real_escape_string($_POST['uploadUrl']);

		$filePath = $uploadsDir.$uploadUrl;
		// Delete the image from the server
		if (file_exists($filePath)) {
			unlink($filePath);

			// Update the Confession record
			$hasImage = '0';
			$stmt = $mysqli->prepare("
								UPDATE
									confessions
								SET
									hasImage = ?
								WHERE
									confessId = ?");
			$stmt->bind_param('ss',
							   $hasImage,
							   $confessId);
			$stmt->execute();
			$stmt->close();

			// Delete the Upload record
			$stmt = $mysqli->prepare("DELETE FROM uploads WHERE uploadId = ?");
			$stmt->bind_param('s', $uploadId);
			$stmt->execute();
			$msgBox = alertBox($imageDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
			$stmt->close();
		} else {
			$msgBox = alertBox($imageDeleteErrorMsg, "<i class='fa fa-warning'></i>", "warning");
		}
	}

	// Approve Confession
    if (isset($_POST['submit']) && $_POST['submit'] == 'approve') {
		// Update the Confession record
		$isActive = '1';
		$stmt = $mysqli->prepare("UPDATE confessions SET isActive = ? WHERE confessId = ?");
		$stmt->bind_param('ss', $isActive, $confessId);
		$stmt->execute();
		$msgBox = alertBox($confApprovedMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
	}

	// Disable Confession
    if (isset($_POST['submit']) && $_POST['submit'] == 'disable') {
		// Update the Confession record
		$isActive = '0';
		$stmt = $mysqli->prepare("UPDATE confessions SET isActive = ? WHERE confessId = ?");
		$stmt->bind_param('ss', $isActive, $confessId);
		$stmt->execute();
		$msgBox = alertBox($confDisabledMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
	}

	// Edit Confession
    if (isset($_POST['submit']) && $_POST['submit'] == 'editConfession') {
		$fname = $mysqli->real_escape_string($_POST['firstName']);
		$confessText = htmlspecialchars($_POST['confessText']);
		$postDate = $mysqli->real_escape_string($_POST['postDate']);
		$editTime = date("H:i:s");
		$newDate = $postDate.' '.$editTime;

		if ($fname == '') {
			$firstName = NULL;
		} else {
			$firstName = $mysqli->real_escape_string($_POST['firstName']);
		}


		$stmt = $mysqli->prepare("UPDATE
									confessions
								SET
									firstName = ?,
									confessText = ?,
									postDate = ?
								WHERE
									confessId = ?"
		);
		$stmt->bind_param('ssss',
							$firstName,
							$confessText,
							$newDate,
							$confessId
		);
		$stmt->execute();
		$msgBox = alertBox($myConfUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
	}
	
	// Approve Comment
    if (isset($_POST['submit']) && $_POST['submit'] == 'approveComment') {
		$commentId = $mysqli->real_escape_string($_POST['commentId']);
		$isActive = '1';
		$stmt = $mysqli->prepare("UPDATE comments SET isActive = ? WHERE commentId = ?");
		$stmt->bind_param('ss', $isActive, $commentId);
		$stmt->execute();
		$msgBox = alertBox($commentApprovedMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
	}

	// Disable Comment
    if (isset($_POST['submit']) && $_POST['submit'] == 'disableComment') {
		$commentId = $mysqli->real_escape_string($_POST['commentId']);
		$isActive = '0';
		$stmt = $mysqli->prepare("UPDATE comments SET isActive = ? WHERE commentId = ?");
		$stmt->bind_param('ss', $isActive, $commentId);
		$stmt->execute();
		$msgBox = alertBox($commentDisabledMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
	}
	
	// Delete Comment
	if (isset($_POST['submit']) && $_POST['submit'] == 'deleteComment') {
		$deleteId = $mysqli->real_escape_string($_POST['deleteId']);
		$stmt = $mysqli->prepare("DELETE FROM comments WHERE commentId = ?");
		$stmt->bind_param('s', $deleteId);
		$stmt->execute();
		$msgBox = alertBox($commentDeletedMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
	}

	// Edit Comment
    if (isset($_POST['submit']) && $_POST['submit'] == 'editComment') {
		$commentId = $mysqli->real_escape_string($_POST['commentId']);
		$comments = htmlspecialchars($_POST['comments']);

		$stmt = $mysqli->prepare("UPDATE
									comments
								SET
									comments = ?
								WHERE
									commentId = ?"
		);
		$stmt->bind_param('ss',
							$comments,
							$commentId
		);
		$stmt->execute();
		$msgBox = alertBox($myCommentUpdatedMsg, "<i class='fa fa-check-square'></i>", "success");
		$stmt->close();
	}

	// Get Data
	$sqlStmt = "SELECT
					confessId,
					(IFNULL(firstName, '')) AS firstName,
					confessText,
					DATE_FORMAT(postDate,'%Y-%m-%d') AS postDate,
					hasImage,
					isActive,
					postIp,
					(SELECT COUNT(*) FROM views WHERE views.confessId = confessions.confessId ) as totalViews,
					(SELECT COUNT(*) FROM likes WHERE likes.confessId = confessions.confessId ) as totalLikes,
					(SELECT COUNT(*) FROM dislikes WHERE dislikes.confessId = confessions.confessId ) as totalDislikes
				FROM confessions
				WHERE confessId = ".$confessId;
	$res = mysqli_query($mysqli, $sqlStmt) or die('-1' . mysqli_error());
	$row = mysqli_fetch_assoc($res);
	if ($row['firstName'] == '') { $firstName = $anonymousText; } else { $firstName = clean($row['firstName']); }
	
	// Get Comments
	$qry = "SELECT
				commentId,
				confessId,
				(IFNULL(firstName, '')) AS fName,
				comments,
				DATE_FORMAT(commentDate,'%b %d %Y') AS commentDate,
				isActive,
				commentIp
			FROM
				comments
			WHERE confessId = ".$confessId."
			ORDER BY commentId DESC";
	$results = mysqli_query($mysqli, $qry) or die('-2'.mysqli_error());

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
			<h3 class="mb-20"><?php echo $viewModifyConfPageHead; ?></h3>
			<?php if ($msgBox) { echo $msgBox; } ?>

			<form action="" method="post">
				<?php
					if ($row['hasImage'] == '1') {
						// Get Image
						$sqlStmt = "SELECT uploadId, confessId, uploadUrl FROM uploads WHERE confessId = ".$row['confessId'];
						$sqlres = mysqli_query($mysqli, $sqlStmt) or die('-2'.mysqli_error());
						$col = mysqli_fetch_assoc($sqlres);

						//Get File Extension
						$ext = substr(strrchr($col['uploadUrl'],'.'), 1);
						$imgExts = array('gif', 'GIF', 'jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'tiff', 'TIFF', 'tif', 'TIF', 'bmp', 'BMP');

						if (in_array($ext, $imgExts)) {
							echo '<p class="mb-20"><img alt="'.$confImageAlt.'" src="'.$uploadsDir.$col['uploadUrl'].'" class="img-responsive" /></p>';
						}
				?>
						<input type="hidden" name="uploadId" value="<?php echo $col['uploadId']; ?>" />
						<input type="hidden" name="uploadUrl" value="<?php echo $col['uploadUrl']; ?>" />
						<a data-toggle="modal" href="#removeUpload" class="btn btn-danger btn-icon"><i class="fa fa-times"></i> <?php echo $deleteUploadLink; ?></a>
				<?php } ?>

				<div class="row mt-20 mb-10">
					<div class="col-md-3">
						<ul class="list-group">
							<li class="list-group-item"><strong><?php echo $totalViewsText; ?></strong> <?php echo $row['totalViews']; ?></li>
						</ul>
					</div>
					<div class="col-md-3">
						<ul class="list-group">
							<li class="list-group-item"><strong><?php echo $totalLikesText; ?></strong> <?php echo $row['totalLikes']; ?></li>
						</ul>
					</div>
					<div class="col-md-3">
						<ul class="list-group">
							<li class="list-group-item"><strong><?php echo $totalDislikesText; ?></strong> <?php echo $row['totalDislikes']; ?></li>
						</ul>
					</div>
					<div class="col-md-3">
						<ul class="list-group">
							<li class="list-group-item"><strong><?php echo $viewPostersIpText; ?></strong> <?php echo $row['postIp']; ?></li>
						</ul>
					</div>
				</div>
				<div class="form-group">
					<label for="confessText"><?php echo $confessPlaceholder; ?></label>
					<textarea class="form-control" required="" name="confessText" rows="4"><?php echo htmlspecialchars($row['confessText']); ?></textarea>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="postDate"><?php echo $datePostedText; ?></label>
							<input type="text" class="form-control" required="" name="postDate" id="postDate" value="<?php echo $row['postDate']; ?>" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="firstName"><?php echo $postedByText; ?></label>
							<input type="text" class="form-control" name="firstName" value="<?php echo clean($row['firstName']); ?>" />
						</div>
					</div>
				</div>

				<button type="input" name="submit" value="editConfession" class="btn btn-success btn-lg btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
				<span class="pull-right">
					<?php if ($row['isActive'] == '0') { ?>
						<button type="input" name="submit" value="approve" class="btn btn-primary btn-lg btn-icon"><i class="fa fa-check"></i> <?php echo $approveConfTooltip; ?></button>
					<?php } else { ?>
						<button type="input" name="submit" value="disable" class="btn btn-warning btn-lg btn-icon"><i class="fa fa-ban"></i> <?php echo $disableConfTooltip; ?></button>
					<?php } ?>
				</span>
			</form>

			<hr class="mt-20" />

			<h3><?php echo $confCommentsTitle; ?></h3>
			<?php if(mysqli_num_rows($results) < 1) { ?>
				<div class="alertMsg default no-margin">
					<i class="fa fa-warning"></i> <?php echo $noConfCommentsFound; ?>
				</div>
			<?php } else { ?>
				<table id="commentData" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php echo $commentText; ?></th>
							<th class="text-center"><?php echo $postedByText; ?></th>
							<th class="text-center"><?php echo $datePostedText; ?></th>
							<th class="text-center"><?php echo $postersIpText; ?></th>
							<th><?php echo $actionsText; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							while ($rows = mysqli_fetch_assoc($results)) {
								if ($rows['fName'] == '') { $fName = $anonymousText; } else { $fName = clean($rows['fName']); }
								if ($rows['isActive'] == '1') {
									$action = '
												<form action="" method="post" class="tableForm">
													<input type="hidden" name="commentId" value="'.$rows['commentId'].'" />
													<span data-toggle="tooltip" data-placement="left" title="'.$disableCommentTooltip.'">
														<button type="input" name="submit" value="disableComment" class="label label-warning"><i class="fa fa-ban"></i></button>
													</span>
												</form>
											  ';
								} else {
									$action = '
												<form action="" method="post" class="tableForm">
													<input type="hidden" name="commentId" value="'.$rows['commentId'].'" />
													<span data-toggle="tooltip" data-placement="left" title="'.$approveCommentTooltip.'">
														<button type="input" name="submit" value="approveComment" class="label label-success"><i class="fa fa-check"></i></button>
													</span>
												</form>
											  ';
								}
						?>
							<tr>
								<td>
									<span class="text-muted popover-icon" data-toggle="popover" data-placement="top" data-content="<?php echo htmlspecialchars_decode($rows['comments']); ?>"><i class="fa fa-quote-left"></i></span>
									<?php echo htmlspecialchars_decode(ellipsis($rows['comments'], 50)); ?>
								</td>
								<td class="text-center"><?php echo $fName; ?></td>
								<td class="text-center"><?php echo $rows['commentDate']; ?></td>
								<td class="text-center"><?php echo $rows['commentIp']; ?></td>
								<td>
									<?php echo $action; ?>
									<span data-toggle="tooltip" data-placement="left" title="<?php echo $editCommentModal; ?>">
										<a data-toggle="modal" href="#editComment<?php echo $rows['commentId']; ?>" class="label label-primary"><i class="fa fa-edit"></i></a>
									</span>
									<span data-toggle="tooltip" data-placement="left" title="<?php echo $deleteCommentText; ?>">
										<a data-toggle="modal" href="#deleteComment<?php echo $rows['commentId']; ?>" class="label label-danger"><i class="fa fa-times"></i></a>
									</span>
								</td>
							</tr>
							
							<div class="modal fade" id="editComment<?php echo $rows['commentId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
											<h4 class="modal-title"><?php echo $editCommentModal; ?></h4>
										</div>
										<form action="" method="post">
											<div class="modal-body">
												<div class="form-group">
													<label for="comments"><?php echo $commentsField; ?></label>
													<textarea class="form-control" required="" name="comments" rows="4"><?php echo htmlspecialchars_decode($rows['comments']); ?></textarea>
												</div>
											</div>
											<div class="modal-footer">
												<input name="commentId" type="hidden" value="<?php echo $rows['commentId']; ?>" />
												<button type="input" name="submit" value="editComment" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $saveChangesBtn; ?></button>
												<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							<div class="modal fade" id="deleteComment<?php echo $rows['commentId']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post">
											<div class="modal-body">
												<p class="lead"><?php echo $deleteCommentConf; ?></p>
											</div>
											<div class="modal-footer">
												<input name="deleteId" type="hidden" value="<?php echo $rows['commentId']; ?>" />
												<button type="input" name="submit" value="deleteComment" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $deleteCommentBtn; ?></button>
												<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</section>

	<div class="modal fade" id="removeUpload" tabindex="-1" role="dialog" aria-labelledby="removeUpload" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="" method="post">
					<div class="modal-body">
						<p class="lead"><?php echo $deleteConfUploadImage; ?></p>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="uploadId" value="<?php echo $col['uploadId']; ?>" />
						<input type="hidden" name="uploadUrl" value="<?php echo $col['uploadUrl']; ?>" />
						<button type="input" name="submit" value="removeUpload" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> <?php echo $deleteUploadedImgBtn; ?></button>
						<button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> <?php echo $cancelBtn; ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php } ?>