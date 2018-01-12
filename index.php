<?php
	// Check if install.php is present
	if(is_dir('install')) {
		header("Location: install/install.php");
	} else {
		if(!isset($_SESSION)) session_start();

		// Logout
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
			if ($action == 'logout') {
				session_destroy();
				header('Location: index.php');
			}
		}

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

		$msgBox = '';

		include('includes/header.php');

		// Get the Page URL
		$pageURL = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$homePage = 'true';
		$count = 0;
		if (isset($_GET['view'])) { $view = $_GET['view']; } else { $view = ''; }

		if (isset($_GET['view'])) {
			if ($view == $viewNavLinkNewest) {
				$select = "SELECT
								confessId,
								(IFNULL(firstName, '')) AS firstName,
								confessText,
								DATE_FORMAT(postDate,'%b %d %Y %h:%i %p') AS postDate,
								hasImage,
								UNIX_TIMESTAMP(postDate) AS orderDate,
								isActive,
								(SELECT COUNT(*) FROM views WHERE views.confessId = confessions.confessId ) as totalViews,
								(SELECT COUNT(*) FROM likes WHERE likes.confessId = confessions.confessId ) as totalLikes,
								(SELECT COUNT(*) FROM dislikes WHERE dislikes.confessId = confessions.confessId ) as totalDislikes
							FROM
								confessions
						WHERE isActive = 1 ORDER BY orderDate DESC";
				$res = mysqli_query($mysqli, $select) or die('-1' . mysqli_error());
			} else if ($view == $viewNavLinkOldest) {
				$select = "SELECT
								confessId,
								(IFNULL(firstName, '')) AS firstName,
								confessText,
								DATE_FORMAT(postDate,'%b %d %Y %h:%i %p') AS postDate,
								hasImage,
								UNIX_TIMESTAMP(postDate) AS orderDate,
								isActive,
								(SELECT COUNT(*) FROM views WHERE views.confessId = confessions.confessId ) as totalViews,
								(SELECT COUNT(*) FROM likes WHERE likes.confessId = confessions.confessId ) as totalLikes,
								(SELECT COUNT(*) FROM dislikes WHERE dislikes.confessId = confessions.confessId ) as totalDislikes
							FROM
								confessions
						WHERE isActive = 1 ORDER BY orderDate ASC";
				$res = mysqli_query($mysqli, $select) or die('-2' . mysqli_error());
			} else if ($view == $viewNavLinkPopular) {
				$select = "SELECT
								confessions.confessId,
								(IFNULL(confessions.firstName, '')) AS firstName,
								confessions.confessText,
								DATE_FORMAT(confessions.postDate,'%b %d %Y %h:%i %p') AS postDate,
								hasImage,
								UNIX_TIMESTAMP(confessions.postDate) AS orderDate,
								confessions.isActive,
								(SELECT COUNT(*) FROM views WHERE views.confessId = confessions.confessId ) as totalViews,
								(SELECT COUNT(*) FROM likes WHERE likes.confessId = confessions.confessId ) as totalLikes,
								(SELECT COUNT(*) FROM dislikes WHERE dislikes.confessId = confessions.confessId ) as totalDislikes
							FROM
								confessions
							WHERE isActive = 1
							ORDER BY totalViews DESC, orderDate DESC";
				$res = mysqli_query($mysqli, $select) or die('-3' . mysqli_error());
			} else if ($view == $viewNavLinkLikes) {
				$select = "SELECT
								confessions.confessId,
								(IFNULL(confessions.firstName, '')) AS firstName,
								confessions.confessText,
								DATE_FORMAT(confessions.postDate,'%b %d %Y %h:%i %p') AS postDate,
								hasImage,
								UNIX_TIMESTAMP(confessions.postDate) AS orderDate,
								confessions.isActive,
								(SELECT COUNT(*) FROM views WHERE views.confessId = confessions.confessId ) as totalViews,
								(SELECT COUNT(*) FROM likes WHERE likes.confessId = confessions.confessId ) as totalLikes,
								(SELECT COUNT(*) FROM dislikes WHERE dislikes.confessId = confessions.confessId ) as totalDislikes
							FROM
								confessions
							WHERE isActive = 1
							ORDER BY totalLikes DESC, orderDate DESC";
				$res = mysqli_query($mysqli, $select) or die('-4' . mysqli_error());
			} else if ($view == $viewNavLinkDislikes) {
				$select = "SELECT
								confessions.confessId,
								(IFNULL(confessions.firstName, '')) AS firstName,
								confessions.confessText,
								DATE_FORMAT(confessions.postDate,'%b %d %Y %h:%i %p') AS postDate,
								hasImage,
								UNIX_TIMESTAMP(confessions.postDate) AS orderDate,
								confessions.isActive,
								(SELECT COUNT(*) FROM views WHERE views.confessId = confessions.confessId ) as totalViews,
								(SELECT COUNT(*) FROM likes WHERE likes.confessId = confessions.confessId ) as totalLikes,
								(SELECT COUNT(*) FROM dislikes WHERE dislikes.confessId = confessions.confessId ) as totalDislikes
							FROM
								confessions
							WHERE isActive = 1
							ORDER BY totalDislikes DESC, orderDate DESC";
				$res = mysqli_query($mysqli, $select) or die('-5' . mysqli_error());
			} else if ($view == $viewNavLinkRandom) {
				$select = "SELECT
								confessId,
								(IFNULL(firstName, '')) AS firstName,
								confessText,
								DATE_FORMAT(postDate,'%b %d %Y %h:%i %p') AS postDate,
								hasImage,
								UNIX_TIMESTAMP(postDate) AS orderDate,
								isActive,
								(SELECT COUNT(*) FROM views WHERE views.confessId = confessions.confessId ) as totalViews,
								(SELECT COUNT(*) FROM likes WHERE likes.confessId = confessions.confessId ) as totalLikes,
								(SELECT COUNT(*) FROM dislikes WHERE dislikes.confessId = confessions.confessId ) as totalDislikes
							FROM
								confessions
							WHERE isActive = 1 ORDER BY RAND()";
				$res = mysqli_query($mysqli, $select) or die('-6' . mysqli_error());
			}
		} else {
			$select = "SELECT
							confessId,
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
						WHERE isActive = 1
						ORDER BY confessId DESC";
			$res = mysqli_query($mysqli, $select) or die('-7' . mysqli_error());
		}

		// Get Ad Data
		$ads  = "SELECT
					adId, adType, adImage,
					adTitle, adText, adUrl,
					adStartDate, adEndDate, isActive
				FROM
					ads
				WHERE
					(isActive = 1 OR
					adStartDate <= DATE_SUB(CURDATE(),INTERVAL 0 DAY) AND
					adEndDate >= DATE_SUB(CURDATE(),INTERVAL 0 DAY)) AND
					adType = 1
				ORDER BY RAND()
				LIMIT 1";
		$adres = mysqli_query($mysqli, $ads) or die('-8' . mysqli_error());
?>
		<section id="main-container">
			<div class="container">
				<?php if ($msgBox) { echo $msgBox; } ?>

				<div class="confessbox">
					<?php
						while ($row = mysqli_fetch_assoc($res)) {
							// Get Total Comments
							$comssql = "SELECT 'X' FROM comments WHERE confessId = ".$row['confessId']." AND isActive = 1";
							$commentstotal = mysqli_query($mysqli, $comssql) or die('-4'.mysqli_error());
							$totComments = mysqli_num_rows($commentstotal);
							if ($totComments == '1') { $comText	= 'Comment'; } else { $comText = 'Comments'; }
							if ($row['totalViews'] == '1') { $viewText = 'View'; } else { $viewText = 'Views'; }
							$shareURL = $set['installUrl'].'page.php?page=view&confession='.$row['confessId'];
					?>
							<div class="confession confHide">
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
									<?php if ($row['hasImage'] != '0') { ?>
										<span class="label label-confess"><i class="fa fa-picture-o img"></i></span>
									<?php } ?>
									<span class="label label-confess last"><?php echo $row['totalViews'].' '.$viewText; ?></span>
									<span class="label label-confess last hasVoted text-danger"><strong><?php echo $onlyVoteOnceText; ?></strong></span>
									<div class="comments">
										<a href="https://twitter.com/intent/tweet?text=<?php echo $set['siteName']; ?>%20Confession:%20<?php echo ellipsis($row['confessText'],65); ?>%20&url=<?php echo $shareURL; ?>" class="btn btn-tw btn-sm" target="_blank" data-toggle="tooltip" data-placement="top" title="<?php echo $twitterShareTooltip; ?>">
											<i class="fa fa-twitter"></i>
										</a>
										<a href="https://plus.google.com/share?url=<?php echo $pageURL; ?>" class="btn btn-gp btn-sm" target="_blank" data-toggle="tooltip" data-placement="top" title="<?php echo $googleShareTooltip; ?>">
											<i class="fa fa-google-plus"></i>
										</a>
										<a href="page.php?page=view&confession=<?php echo $row['confessId']; ?>" class="btn btn-comment btn-sm btn-icon">
											<i class="fa fa-comments"></i> <?php echo $totComments.' '.$comText; ?>
										</a>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
					<?php
						$count++;
						}
					?>
				</div>
				<a href="#" class="btn btn-fessup btn-block" id="loadMore"><?php echo $loadMoreText; ?></a>
				<?php
					if(mysqli_num_rows($adres) > 0) {
						while ($ad = mysqli_fetch_assoc($adres)) {
				?>
							<div class="adText">
								<h3><a href="<?php echo clean($ad['adUrl']); ?>"><?php echo clean($ad['adTitle']); ?> <i class="fa fa-external-link pull-right"></i></a></h3>
								<p><a href="<?php echo clean($ad['adUrl']); ?>"><?php echo nl2br(clean($ad['adText'])); ?></a></p>
								<span class="label label-default"><?php echo $advertisementText; ?></span>
								<div class="clearfix"></div>
							</div>
				<?php
						}
					}
				?>

			</div>
		</div>
<?php
		include('includes/footer.php');
	}
?>