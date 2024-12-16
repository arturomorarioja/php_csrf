<?php

session_start();
require_once 'src/token.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(csrfTokenIsValid()) {
		$message = 'Valid form submission';
		if(csrfTokenIsRecent()) {
			$message .= ' (recent)';
		} else {
			$message .= ' (not recent)';
		}
	} else {
		$message = 'CSRF token missing or mismatched';
	}
} else {
	// Form not submitted or GET request
	$message = 'Please login.';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <header>
        <h1>CSRF Protection</h1>
    </header>
    <main>
        <p>
            <?=$message ?>
        </p>
        <form method="POST">
            <?=csrfTokenTag() ?>
            <div>
                <label for="txtUsername">Username</label>
                <input type="text" id="txtUsername" name="username">
            </div>
            <div>
                <label for="txtPassword">Password</label>
                <input type="password" id="txtPassword" name="password">
            </div>
            <div>
                <input type="submit" value="Submit">
            </div>
        </form>
    </main>
</body>
</html>
