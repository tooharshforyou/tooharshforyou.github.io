<?php
/*
 * Please note - if you are translating this file, that any quotes that have a backslash (\") need that
 * back slash to escape the extra quotes in the string.
 * Any variables in this file need to stay in the same format.
 *
 * If you have any questions at all about this file, please contact me through my CodeCanyon profile.
 * http://codecanyon.net/user/Luminary
 */

// All Pages - Globals
// --------------------------------------------------------------------------------------------------
$accessErrorHeader			= "Access Error";
$permissionDenied			= "Permission Denied. You can not access this page.";
$pageNotFoundHeader			= "404 Error &mdash; Page Not Found";
$pageNotFoundQuip			= "The resource requested could not be found on this server.";
$loggedInAsMsg				= "Logged in as";
$htmlNotAllowed				= "HTML not allowed &amp; will be saved as plain text.";
$logoutConfirmationMsg		= ", are you sure you want to signout?";
$signOutConfBtn				= "Sign Out";

$cancelBtn					= "Cancel";
$closeBtn					= "Close";
$okBtn						= "OK";
$yesBtn						= "Yes";
$noBtn						= "No";
$saveChangesBtn				= "Save Changes";
$saveBtn					= "Save";
$deleteBtn					= "Delete";
$updateBtn					= "Update";
$selectOption				= "Select...";
$onText						= "On";
$offText					= "Off";

$emailAddressField			= "Email Address";
$passwordField				= "Password";

$emailAddressHelp			= "The email address associated with your account.";

// index.php
// --------------------------------------------------------------------------------------------------
$viewNavLinkNewest			= "newest";
$viewNavLinkOldest			= "oldest";
$viewNavLinkPopular			= "popular";
$viewNavLinkLikes			= "most-likes";
$viewNavLinkDislikes		= "most-dislikes";
$viewNavLinkRandom			= "random";
$pageNavLinkAbout			= "about";
$pageNavLinkRules			= "rules";
$onlyVoteOnceText			= "You can only vote once.";
$twitterShareTooltip		= "Share on Twitter";
$googleShareTooltip			= "Share on Google+";
$loadMoreText				= "Load More Confessions";
$advertisementText			= "Advertisement";

// activate.php
// --------------------------------------------------------------------------------------------------
$activatePageHeader			= "New ".$set['siteName']." Account Activation";
$activateText1				= "Thank you for verifying your email address and activating your account.";
$activateText2				= "Your account has been activated, and you can now log in.";
$activateText3				= "You have all ready verified your email address and activated your account.";
$activateText4				= "Your account has been activated, and you can now log in.";
$activateText5				= "Please check your email for the Account Activation Link.";
$activateText6				= "You cannot directly access this page. please use the link that has been sent to your email.";

// subscribe.php
// --------------------------------------------------------------------------------------------------
$subscribePageHeader		= $set['siteName']." Mailing List Subscription Request Activation";
$subscribeText1				= "Thank you for verifying your email address and activating your Subscription request.";
$subscribeText2				= "Your Subscription has been activated.";
$subscribeText3				= "You have all ready verified your email address and activated your Subscription.";
$subscribeText4				= "Your Subscription has been activated.";
$subscribeText5				= "Please check your email for the Subscription Activation Link.";

// header.php
// --------------------------------------------------------------------------------------------------
$siteHeaderTitle			= "Anonymous Confessions";
$siteHeaderDescrip			= "Anonymous Confessions - Say out loud what you’ve been keeping inside. Say the whole truth and nothing but the truth.
Tell your confessions to total strangers without any fear. Go ahead, take the leap.";
$siteHeaderKeywords			= "Confessions, Anonymous Confessions, Confess, Fess Up";
$memberSignInText			= "Member Sign In";
$emailAddyField				= "Email Address";
$accPasswordField			= "Account Password";
$lostPasswordText			= "Lost your password?";
$signInBtn					= "Sign In";
$createNewAccBtn			= "Create a New Account";
$createNewAccModal			= "Create a New ".$set['siteName']." Account";
$validEmailAddyQuip			= "A Valid Email Address. Your Email Address is also used to log in.";
$passwordField				= "Password";
$passwordFieldQuip			= "Choose a password for your new account.";
$repeatPasswordField1		= "Repeat Password";
$repeatPasswordField		= "Repeat Password. Passwords MUST Match.";
$repeatPasswordQuip			= "Type the password again.";
$captchaCodeTooltip			= "Captcha Code";
$captchaCodeField			= "Captcha Code";
$createAccBtn				= "Create My Account";
$haveAccSignInBtn			= "Have an Account? Sign In";
$resetPassModal				= "Reset You Account Password";
$emailAddyResetQuip			= "The email address associated with your account.";
$allSetSignInBtn			= "All Set? Sign In";
$whySignUpModal				= "Why Sign Up?";
$whyText1					= "Full Confession Control";
$whyText2					= "Update/Edit or Delete your confessions.";
$whyText3					= "Notifications";
$whyText4					= "Opt-in to receive notification emails on responses to your Confessions.";
$whyText5					= "Advertising";
$whyText6					= "Want to Advertise with us? Get started by creating an account.";
$signMeUpBtn				= "Sounds Great! Sign Me Up";
$toggleNavText				= "Toggle navigation";
$confessionsNavLink			= "Confessions";
$newestNavLink				= "Newest";
$oldestNavLink				= "Oldest";
$popularNavLink				= "Popular";
$likesNavLink				= "Most Likes";
$dislikesNavLink			= "Most Dislikes";
$randomNavLink				= "Random";
$aboutNavLink				= "About";
$aboutNavLink1				= "About Fess Up";
$rocNavLink					= "Rules of Conduct";
$signInUpNavLink			= "Sign In/Up";
$signInNavLink				= "Sign In";
$myProfileNavLink			= "My Profile";
$manageNavLink				= "Manage";
$commentsNavLink			= "Comments";
$subscriptionsNavLink		= "Subscriptions";
$usersNavLink				= "Users";
$advertisingNavLink			= "Advertising";
$settingsNavLink			= "Site Settings";
$signOutNavLink				= "Sign Out";
$signOutConf				= ", are you sure you want to Sign Out?";
$confessFormTitle			= "Got Something to Confess? We Would Love to Hear It...";
$fessUpBtn					= "Fess Up";
$confessPlaceholder			= "Confession";
$firstNamePlaceholder		= "First Name";
$includeImgText				= "Include an Image?";
$browseBtn					= "Browse";
$confessQuip1				= "Your First Name is optional.";
$confessQuip2				= "You can Upload an image if you wish.";
$confessQuip3				= "HTML not allowed &amp; will be saved as plain text.<br />Confession Moderation is";
$confessQuip4				= "Profanity Filter is";
$shareConfessBtn			= "Share Confession";
$confessTextReq				= "Please tell us your Confession.";
$captchaCodeReq				= "Please type the Captcha Code.";
$confessErrorMsg			= "Something went wrong, and your Confession could not be shared.";
$confessSavedMsg1			= "Thank you, your Confession has been saved and added to the moderation queue.";
$confessSavedMsg2			= "Thank you, Your Confession has been saved.";
$invalidImgTypeMsg			= "Invalid Image Type, please select a different image to upload.";
$captchaErrorMsg			= "Incorrect Captcha Answer. Please try again.";
$emailAddyReq				= "Your Email Address is required.";
$subscribeErrorMsg			= "Something went wrong, and your Subscription could not be saved.";
$dupSubscribeMsg			= "There is all ready a Subscription registered for that Email Address.";
$accountEmailReq			= "Your Account Email Address is required.";
$accountPasswordReq			= "Your Account Password is required.";
$loginFailedMsg				= "Log In Failed, Please check your entries.";
$inactiveAccMsg				= "Your account is not currently active.";
$noAccMsg					= "No account found for that Email Address/Password.";
$passwordResetMsg			= "Your Password has been reset. Please check your email for your new password, and instructions on how to update your account.";
$noAccFoundMsg				= "Account not found for that email address.";
$newPasswordReq				= "An Account Password is required.";
$passNotMatchMsg			= "Passwords do not match, please check your entries.";
$newAccErrorMsg				= "Something went wrong, and your New Account could not be created.";
$dupAccMsg					= "There is all ready an account registered for that Email Address.";
$newAccCreatedMsg			= "Thank you for creating a New Account. Please check your email for instructions on activating your Account.";

// *******************************
// Subscription Email
// *******************************
$subscribeEmailSubject		= "Thank you for Subscribing to ".$set['siteName'];
$subscribeEmail1			= "You must activate your subscription before you will receive any notifications. Please click (or copy/paste) the following link to activate:";
$subscribeEmail2			= "If you decide not to activate your subscription request, you can disregard this notice and you will not receive any communications from";
$subscribeEmail3			= "Thank you,";
$subscribeConfMsg			= "Thank you for Subscribing. Please check your email for instructions on activating your Subscription.";

// *******************************
// Reset Password Email
// *******************************
$resetPassemailSubject		= "Your ".$set['siteName']." Password has been Reset";
$resetPasswordEmail1		= "Your temporary password is:";
$resetPasswordEmail2		= "Please take the time to change your password to something you can easily remember. You can change your password on your My Profile page after
logging into your account. There you can update your password, as well as your account details.";
$resetPasswordEmail3		= "You can log into your account with your email address and new password at:";

// *******************************
// New Account Email
// *******************************
$newAccEmailSubject			= "Your ".$set['siteName']." Account has been created";
$newAccEmail1				= "Your new Account details:";
$newAccEmail2				= "Username: Your email address<br>Password:";
$newAccEmail3				= "You must activate your account before you will be able to log in. Please click (or copy/paste) the following link to activate your account:";
$newAccEmail4				= "Once you have activated your new account and logged in, please take the time to update your account profile details.";
$newAccEmail5				= "You can log in to your account at";

// footer.php
// --------------------------------------------------------------------------------------------------
$footerQuip					= "Confess It &mdash; Don't Repress It.";
$subscribeHeader			= "Stay updated, Subscribe to our mailing list";
$subscribeBtn				= "Subscribe";
$subscribeQuip				= "Please enter a valid email you can receive notifications on.<br />We value your privacy and would never share or sell your email address to anyone, for any reason.";
$footerLine1				= "&copy; Copyright";
$footerLine2				= "All Rights Reserved. Created by <a href=\"http://codecanyon.net/user/Luminary?ref=Luminary\">Luminary on CodeCanyon.</a>";

// export.php
// --------------------------------------------------------------------------------------------------
$idText						= "ID";
$signUpDateText				= "Sign Up Date";
$ipAddyText					= "IP Address";

// About Us Page
// --------------------------------------------------------------------------------------------------
$subjectField				= "Subject";
$yourNameField				= "Your Name";
$phoneField					= "Phone Number";
$commentsField				= "Comments";
$sendContactBtn				= "Send Contact Request";
$totalConfessionsText		= "Total Confessions:";
$totalCommentsText			= "Total Comments:";
$totalLikesText				= "Total Likes:";
$totalDislikesText			= "Total Dislikes:";
$totalViewsText				= "Total Views:";
$contactSubReq				= "A Subject is required to Contact Us.";
$contactNameReq				= "Please tell us your Name.";
$contactEmailReq			= "Please include a valid Email Address we can contact you at.";
$contactCommentsReq			= "Please let us know why you are Contacting Us.";
$contactErrorMsg			= "Something went wrong, and your Contact Request could not be sent.";

// *******************************
// Contact Us Email
// *******************************
$contactUsEmail1			= "From:";
$contactUsEmail2			= "Reply Email:";
$contactUsEmail3			= "Contact Phone:";
$contactUsMsgSent			= "Your Contact Request has been sent successfully.";

// Site Settings
// --------------------------------------------------------------------------------------------------
$siteSettingsPageTitle		= "Global Site Settings";
$appSettingsTitle			= "Application Settings";
$installUrlField			= "Installation URL";
$installUrlHelper			= "Used in all uploads &amp; email notifications. Must include the trailing slash.";
$localizationField			= "Localization";
$optionArabic				= "Arabic";
$optionBulgarian			= "Bulgarian";
$optionChechen				= "Chechen";
$optionCzech				= "Czech";
$optionDanish				= "Danish";
$optionEnglish				= "English";
$optionCanadianEnglish		= "Canadian English";
$optionBritishEnglish		= "British English";
$optionEspanol				= "Espanol";
$optionFrench				= "French";
$optionGerman				= "German";
$optionCroatian				= "Croatian";
$optionHungarian			= "Hungarian";
$optionArmenian				= "Armenian";
$optionIndonesian			= "Indonesian";
$optionItalian				= "Italian";
$optionJapanese				= "Japanese";
$optionKorean				= "Korean";
$optionDutch				= "Dutch";
$optionPortuguese			= "Portuguese";
$optionRomanian				= "Romanian";
$optionSwedish				= "Swedish";
$optionThai					= "Thai";
$optionVietnamese			= "Vietnamese";
$optionCantonese			= "Cantonese";
$localizationHelper			= "Choose the Language file to use throughout ".$set['siteName'].".";
$siteNameField				= "Site Name";
$siteNameHelper				= "ie. Fess Up (Appears at the top of the browser, the header logo, in the footer and in other headings throughout the site).";
$siteEmailField				= "Site Email";
$siteEmailHelper			= "Used in email notifications as the \"from/reply to\" email address.";
$googleCodeField			= "Google Analytics Code";
$confessionSettingsTitle	= "Confession Settings";
$allowUploadsField			= "Allow Confession Uploads?";
$allowUploadsFieldHelp		= "You can disable the ability to upload files with Confessions.";
$uploadsPathField			= "Uploads Path";
$uploadsPathFieldHelp		= "Where all Confession images upload to. Must include the trailing slash.";
$uploadFileTypesField		= "Upload File Types Allowed";
$uploadFileTypesFieldHelp	= "The file types you allow to be uploaded. NO spaces & each separated by a comma (Format: jpg,jpeg,png).";
$moderationField			= "Enable Moderation?";
$moderationFieldHelp		= "Disabling Moderation will allow all Confessions/Comments to be posted immediately.";
$profanityField				= "Enable Profanity Filter?";
$profanityFieldHelp			= "Set to Yes to enable the Profanity Filter.";
$selfRegField				= "Enable Self-Registrations?";
$selfRegFieldHelp			= "Set to No to disable the ability for anonymous users Creating New Accounts.";
$advertisingTitle			= "Advertising Settings";
$adField					= "Enable Advertising?";
$adFieldHelp				= "You can disable the Advertising Feature.";
$adsPathField				= "Advertising Uploads Path";
$adsPathFieldHelp			= "Where all Advertising images upload to. Must include the trailing slash.";
$adFileTypesField			= "Advertising File Types Allowed";
$adFileTypesFieldHelp		= "The Advertising file types you allow to be uploaded. NO spaces & each separated by a comma (Format: jpg,jpeg,png).";
$aboutUsTitle				= "About Us Page";
$aboutUsField				= "About Us Page HTML";
$aboutUsFieldHelp			= "HTML Allowed Here. Displays on the About Us page.";
$rocTitle					= "Rules of Conduct Page";
$rocField					= "Rules of Conduct Page HTML";
$rocFieldHelp				= "HTML Allowed Here. Displays on the Rules of Conduct page.";
$saveSettingsBtn			= "Save Global Site Settings";
$installUrlMsg				= "The Installation URL is required.";
$siteNameMsg				= "The Site Name is required.";
$siteEmalMsg				= "The Site Email Address is required.";
$uploadsPathReq				= "The Uploads Path is required.";
$uploadFileTypesReq			= "The Upload File Types Allowed is required.";
$adPathReq					= "The Advertising Uploads Path is required.";
$adsFileTypesReq			= "The Advertising Upload File Types Allowed is required.";
$siteSettingsSavedMsg		= "The Global Site Settings have been updated.";

// Advertising
// --------------------------------------------------------------------------------------------------
$advertisingPageHead		= "Advertising";
$advertisementsLink			= "Advertisements";
$newAdLink					= "Add a New Advertisement";
$noAdsFoundMsg				= "No Advertisements found.";
$adTitleText				= "Ad Title";
$adTypeText					= "Type";
$adStatusText				= "Status";
$startDateText				= "Start Date";
$endDateText				= "End Date";
$dateCreatedText			= "Date Created";
$actionsText				= "Actions";
$adTypeSelect1				= "In-line (Text)";
$adTypeSelect2				= "Page Sidebars (Image)";
$adTypeSelect3				= "Page Footers (Image)";
$activeText					= "Active";
$inactiveText				= "Inactive";
$viewAdTooltip				= "View/Update Advertisement";
$deleteAdTooltip			= "Delete Advertisement";
$deleteAdConf				= "Are you sure you want to DELETE this Advertisement?";
$deleteAdBtn				= "Yes, Delete Ad";
$newAdQuip					= "To use a Start Date and/or an End Date, set the Ad as inactive. Advertisements set to Active will be visible regardless of what dates are set.";
$adTitleFieldHelp			= "The title for the Ad. This is visible to all Users.";
$adUrlField					= "Ad URL";
$adUrlFieldHelp				= "The URL the User is taken to when clicking on the ad.";
$adTypeField				= "Ad Type";
$adTypeField1				= "In-line with Confessions (Text ONLY Ads)";
$adTypeField2				= "Page Sidebars (Image ONLY Ads)";
$adTypeField3				= "Page Footers (Image ONLY Ads)";
$adTypeFieldHelp			= "The Ad Type dictates where the Ad will be displayed throughout the site.";
$adStatusField				= "Ad Status";
$adStatusFieldHelp			= "Active Ads will be visible to all Users regardless of what dates are set.";
$startDateTextHelp			= "Leave blank if the Ad does not have a start date.";
$endDateTextHelp			= "Leave blank if the Ad never expires.";
$uploadAdImgField			= "Upload Ad Image";
$uploadAdImgFieldHelp		= "Ad Images are responsive, and will be resized to fit the Ad location (ie. Sidebar, footer etc.). Equal Hight/Width images work best.";
$adTextField				= "Advertisement Text";
$adTextFieldHelp			= "The Advertisement Text to display to the User. Text Ads are only used for In-line with Confessions Ads.";
$saveNewAdBtn				= "Save New Advertisement";
$adTitleReq					= "A Title for the New Ad is required.";
$adUrlReq					= "The Destination URL is required.";
$adTypeReq					= "Please select the Type of Ad you are creating.";
$invalidAdImgMsg			= "Invalid Ad Image Type, please select a different image to upload.";
$newAdSavedMsg1				= "The New Image Advertisement has been uploaded and saved.";
$newAdSavedMsg2				= "The New Text Advertisement has been saved.";
$deleteAdMsg1				= "The Advertisement image and data has been deleted.";
$deleteAdMsg2				= "An error was encountered, and the image and data could not be deleted.";
$deleteAdMsg3				= "The Advertisement has been deleted.";

// Comments
// --------------------------------------------------------------------------------------------------
$commentsPageHead			= "All Comments";
$noCommentsFoundMsg			= "No Comments found.";
$commentText				= "Comment";
$postedByText				= "Posted By";
$datePostedText				= "Date Posted";
$postersIpText				= "Poster's IP";
$anonymousText				= "Anonymous";
$disableCommentTooltip		= "Disable Comment";
$approveCommentTooltip		= "Approve Comment";
$viewConfTooltip			= "View Confession";
$deleteCommentTooltip		= "Delete Comment";
$deleteCommentConf			= "Are you sure you want to DELETE this Comment?";
$deleteCommentBtn			= "Yes, Delete Comment";
$commentApprovedMsg			= "The Comment has been approved and is now publicly visible.";
$commentDisabledMsg			= "The Comment has been disabled and is not publicly visible.";
$commentDeletedMsg			= "The Comment has been permanently deleted.";

// Confessions
// --------------------------------------------------------------------------------------------------
$confPageHead				= "All Confessions";
$noConfFoundMsg				= "No Confessions found.";
$approvedText				= "Approved";
$likesDislikesText			= "Likes / Dislikes";
$viewsText					= "Views";
$disableConfTooltip			= "Disable Confession";
$approveConfTooltip			= "Approve Confession";
$modifyConfTooltip			= "Modify Confession";
$deleteConfTooltip			= "Delete Confession";
$deleteConfConfirmation		= "Are you sure you want to DELETE this Confession?";
$deleteConfBtn				= "Yes, Delete Confession";
$confApprovedMsg			= "The Confession has been approved and is now publicly visible.";
$confDisabledMsg			= "The Confession has been disabled and is not publicly visible.";
$confDeletedMsg				= "The Confession has been permanently deleted.";

// My Profile
// --------------------------------------------------------------------------------------------------
$usersNameText				= "User's Name:";
$usersEmailText				= "User's Email:";
$accTypeText				= "Account Type:";
$accStatusText				= "Account Status:";
$userJoinDateText			= "Join Date:";
$lastSignInText				= "Last Sign In:";
$updateProfileLink			= "Update Account Profile";
$changePassLink				= "Change Account Password";
$lastNameField				= "Last Name";
$recNotificationsField		= "Receive Notification Emails?";
$currentPassField			= "Current Password";
$currentPassFieldHelp		= "Your current password.";
$newPassField				= "New Password";
$newPassFieldHelp			= "Type a new Password for the Account.";
$repeatNewPassField			= "Repeat New Password";
$repeatNewPassFieldHelp		= "Type the new password again. Passwords MUST Match.";
$myConfLink					= "My Confessions";
$myCommentsLink				= "My Comments";
$myConfNoneFound			= "You have not posted any Confessions.";
$postedNameText				= "Posted Name";
$editConfModal				= "Edit Confession";
$editComFirstNameHelp		= "Leave blank for an Anonymous Confession. You do not have to use your real name.";
$myCommentsNoneFound		= "You have not added any Comments.";
$deleteCommentText			= "Delete Comment";
$editCommentModal			= "Edit Comment";
$firstNameReq				= "Your First Name is required.";
$lastNameReq				= "Your Last Name is required.";
$myProfileUpdatedMsg		= "Your Account Profile information has been updated.";
$currentAccPassReq			= "Your current Account Password is required.";
$currentAccPassIncorrectMsg	= "Your current Account Password is incorrect. Please check your entry.";
$newAccPassReq				= "A new Account Password is required.";
$repeatNewPassReq			= "Please repeat the new Account Password.";
$passwordUpdatedMsg			= "Your Account Password has been changed successfully.";
$myConfUpdatedMsg			= "The Confession has been updated.";
$myCommentUpdatedMsg		= "The Comment has been updated.";
$administratorText			= "Administrator";
$userText					= "User";
$activeAccText				= "Active Account";
$inactiveAccText			= "Inactive Account";

// Subscriptions
// --------------------------------------------------------------------------------------------------
$subscriptionsPageHead		= "Mailing List Subscriptions";
$subExportBtn				= "Export Subscription Data to CSV";
$signUpIpText				= "Sign Up IP";
$deactivateSubText			= "Deactivate Subscription";
$activateSubText			= "Activate Subscription";
$deleteSubTooltip			= "Delete Subscription";
$deleteSubConf				= "Are you sure you want to DELETE this Subscription?";
$deleteSubBtn				= "Yes, Delete Subscription";
$subActiveMsg				= "The Subscription has been set to Active.";
$subInactiveMsg				= "The Subscription has been deactivated.";
$subDeletedMsg				= "The Subscription has been permanently deleted.";

// Users
// --------------------------------------------------------------------------------------------------
$usersPageHead				= "All Users";
$newUserLink				= "Add a New User";
$setUserAccActiveField		= "Set the Account as Active?";
$setUserAccActiveFieldHelp	= "Selecting No will require the User to activate the Account via a link sent to the account email address.";
$userEmailAddyField			= "User's Email Address";
$userEmailAddyFieldHelp		= "A valid email address. The new account information will be sent to this address.";
$usersFirstNameField		= "User's First Name";
$usersLastNameField			= "User's Last Name";
$genPasswordTooltip			= "Generate Password";
$showPlainText				= "Show Plain Text";
$hidePlainText				= "Hide Plain Text";
$usersNameTab				= "User's Name";
$accTypeTab					= "Account Type";
$joinDateTab				= "Join Date";
$lastVisitedTab				= "Last Visited";
$deactivateAccTooltip		= "Deactivate Account";
$activateAccTooltip			= "Activate Account";
$viewUserTooltip			= "View User";
$deleteAccTootip			= "Delete Account";
$deleteAccConf				= "Are you sure you want to DELETE this User's Account?";
$deleteAccBtn				= "Yes, Delete the Account";
$userEmailReq				= "The User's Email Address is required.";
$userFirstNameReq			= "The User's First Name is required.";
$userLastNameReq			= "The User's Last Name is required.";
$dupAccRegMsg				= "There is all ready an account registered with that Email Address.";
$newUserAccCreatedMsg1		= "The New User account has been created and has been set to Active.";
$newUserAccCreatedMsg2		= "The New User account has been created and an email has been sent with instructions to activate.";
$userAccActiveMsg			= "The User Account has been set to Active.";
$userAccInactiveMsg			= "The User Account has been deactivated.";
$userAccDeletedMsg			= "The User Account has been permanently deleted.";

// *******************************
// New Account Email
// *******************************
$newAccCreatedSubject		= "A New ".$set['siteName']." User Account has been created";
$newAccCreated1				= "Your new Account details:";
$newAccCreated2				= "Username: Your email address<br>Password:";
$newAccCreated3				= "You must activate your account before you will be able to log in. Please click (or copy/paste) the following link to activate your account:<br>";
$newAccCreated4				= "Once you have activated your new account and logged in, please take the time to update your account profile details.";
$newAccCreated5				= "You can log in to your account at ".$set['siteName'];

// View Confession
// --------------------------------------------------------------------------------------------------
$confImageAlt				= "Confession Image";
$postedByAnon				= "Posted by Anonymous";
$commentedText				= "commented";
$anonCommented				= "Anonymous commented";
$addCommentsField			= "Add Your Comments";
$commentsQuip1				= "Your First Name is optional. All other fields are required. HTML not allowed &amp; will be saved as plain text.<br />Comment Moderation is ";
$commentsQuip2				= "Profanity Filter is";
$saveCommentsBtn			= "Save Comments";
$commentsReq				= "Your Comments are required.";
$commentsErrorMsg			= "Something went wrong, and your Comment could not be saved.";
$commentsSavedMsg1			= "Your Comments have been saved, and are awaiting Moderation.";
$commentsSavedMsg2			= "Your Comments have been saved.";
$singleViewText				= "View";
$multipleViewsText			= "Views";

// *******************************
// New Comment Email
// *******************************
$newCommentEmailSubject		= "Your have received a new comment on one of your Confessions from ".$set['siteName'];
$newCommentEmail1			= "You can view your Confession and the comment at";

// View Ad
// --------------------------------------------------------------------------------------------------
$viewAdPageHead				= "View/Update Advertisement";
$adDetailsLink				= "Ad Details";
$adImageLink				= "Ad Image";
$updateAdLink				= "Update Ad";
$adImageAlt					= "Advertisement Image";
$adTitleText				= "Title:";
$adURLText					= "URL:";
$adTypeText					= "Ad Type:";
$adStartDateText			= "Start Date:";
$adStatusText				= "Status:";
$adEndDateText				= "End Date:";
$adUploadImageQuip1			= "Upload a New Image for this Advertisement";
$adUploadImageQuip2			= "Uploading a New Image will delete/overwrite the current Ad Image.";
$selectAdImageField			= "Select New Ad Image";
$selectAdImageFieldHelp		= "Ad Images are responsive, and will be resized to fit the Ad location (ie. Sidebar, footer etc.). Equal Hight/Width images work best.";
$uploadNewAdImageBtn		= "Upload New Image";
$adDatesQuip				= "To use a Start Date and/or an End Date, set the Advertisement as inactive. Advertisements set to Active will display regardless of what dates are set.";
$adTitleField				= "Ad Title";
$adTitleFieldHelp			= "The title for the Ad. This is visible in text only Ads to all Users.";
$adURLField					= "Ad URL";
$adURLFieldHelp				= "The URL the User is taken to when clicking on the ad.";
$adStatusField				= "Ad Status";
$adStatusFieldHelp			= "Active Ads will be visible to all Users regardless of what dates are set.";
$adStartDateHelp			= "Leave blank if the Ad does not have a start date.";
$adEndDateHelp				= "Leave blank if the Ad never expires.";
$adTextField				= "Advertisement Text";
$adTextFieldHelp			= "The Advertisement Text to display to the User. Text Ads are only used for In-line with Confessions Ads.";
$saveUpdatesBtn				= "Save Updates";
$adTitleReq					= "A Title for the New Ad is required.";
$adURLReq					= "The Destination URL is required.";
$adUpdatedMsg				= "The Advertisement has been updated.";
$invalidAdImageMsg			= "Invalid Ad Image Type, please select a different image to upload.";
$newAdImageUploadedMsg		= "The new Advertisement Image has been uploaded.";
$newAdErrorMsg				= "An error was encountered, and the new image could not be uploaded";

// View/Edit Confession
// --------------------------------------------------------------------------------------------------
$viewModifyConfPageHead		= "View/Modify Confession";
$deleteUploadLink			= "Delete Upload";
$viewPostersIpText			= "Poster's IP:";
$confCommentsTitle			= "Confession Comments";
$noConfCommentsFound		= "No Comments have been posted for this Confession.";
$deleteConfUploadImage		= "Are you sure you want to Delete the uploaded image for this Confession?";
$deleteUploadedImgBtn		= "Delete Uploaded Image";
$imageDeletedMsg			= "The Image Upload has been removed from the Confession.";
$imageDeleteErrorMsg		= "There was an error, and the Image Upload could not be deleted from the server.";

// View User
// --------------------------------------------------------------------------------------------------
$viewUserPageHead			= "View User Account";
$updateUserLink				= "Update User";
$usersPasswordLink			= "User's Password";
$usersAccLink				= "User's Account";
$emailUserLink				= "Email User";
$userAccStatusField			= "Account Status";
$userAccQuip				= "Inactive Users can not log in or access their accounts.<br />Administrators have full Access &mdash; Add/Modify and Delete permissions.";
$emailUserField				= "Email Message to Send";
$sendEmailBtn				= "Send Email";
$usersConfTitle				= "User's Confessions";
$usersCommentsTitle			= "User's Comments";
$noUserConfFound			= "No Confessions found for this User.";
$noUserComFound				= "No Comments have been posted from this User.";
$userAccUpdatedMsg			= "The User's Account information has been updated.";
$userPasswordChangedMsg		= "The User's Password has been changed successfully.";
$userAccStatusUpdatedMsg	= "The User's Account Type &amp; Status have been updated.";
$emailMsgReq				= "Please include an email message.";

// *******************************
// Email User Email
// *******************************
$emailUserSubject			= "Your have received a message from ".$set['siteName'];
$emailSentMsg				= "Your email has been sent successfully.";
