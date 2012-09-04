<?php
// Calls the login process, and effectively starts the application...
// Perhaps use OpenID to authenticate user - later though...

// * Login requires the user to have been registered, either as a user through
// 	the NGOData registration process, or through a registered user having authorised
// 	the user.
// * A user may ONLY authorise another user within the entity the first user
// 	has been assigned to.
// * registration can only be allowed by NGOData staff.
// * Requests for registration are emailed to NGOData staff.
// * Only one registration is required for each entity - any other users are granted
// 	access by the original user (by default they are administrstors for the entity).
// * An ABN (or other registered business number/identifier recognised in other
// 	countries) is required for a valid registration.
// * An email address is required as the login username - the user may request a
// 	preferred name the system recognises them as

// First contact with NGOData is via email through the registration process
if (isset($_POST['registration'])) {
	// call the registration container
	$rc = new RegistrationContainer();
	$rc->startRegistrationProcess();
}
?>