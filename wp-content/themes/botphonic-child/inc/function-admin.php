<?php

add_action('init', function () {
	if (isset($_GET['h']) && $_GET['h'] == 'true') {
		add_filter('show_admin_bar', '__return_true');
	} else {
		add_filter('show_admin_bar', '__return_false');
	}
}); 

// add_action('init', function () {
// 	if (is_user_logged_in()) return;
// 	$request_uri = $_SERVER['REQUEST_URI'];

// 	$login_paths = ['wp-login.php', 'wp-admin', 'login', 'admin'];

// 	foreach ($login_paths as $path) {
// 		if (stripos($request_uri, $path) !== false) {
// 			// Lockout check: blocked for 10 minutes after 5 failed attempts
// 			if (isset($_COOKIE['secret_blocked'])) {
// 				$remaining = intval($_COOKIE['secret_blocked']) - time();
// 				if ($remaining > 0) {
// 					$minutes = floor($remaining / 60);
// 					$seconds = $remaining % 60;
// 					echo '<!DOCTYPE html>
// 						<html><body>
// 						<p style="color:red;text-align:center;">
// 							Too many attempts. Try again in ' . sprintf('%02d:%02d', $minutes, $seconds) . ' minutes.
// 						</p>
// 						</body></html>';
// 					exit;
// 				}
// 			}

// 			// Allow if passed secret already
// 			if (isset($_COOKIE['passed_secret']) && $_COOKIE['passed_secret'] === '1') return;

// 			$error = '';
// 			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// 				if ($_POST['secret'] === 'nbc0t') {
// 					setcookie('passed_secret', '1', time() + 1800, '/'); // valid 30 mins
// 					wp_redirect('https://botphonic.ai/nbc0t/');
// 					exit;
// 				} else {
// 					// Count failed attempts
// 					$fail_count = isset($_COOKIE['secret_fail_count']) ? intval($_COOKIE['secret_fail_count']) + 1 : 1;
// 					setcookie('secret_fail_count', $fail_count, time() + 600, '/'); // 10 min expiry


// 					if ($fail_count >= 5) {
// 						$blocked_until = time() + 600; // 10 mins from now
// 						setcookie('secret_blocked', $blocked_until, $blocked_until, '/');

// 						echo '<!DOCTYPE html><html><body><p style="color:red;text-align:center;">Too many attempts. Try again in 10 minutes.</p></body></html>';
// 						exit;
// 					}
// 					$error = '<p style="color:red;margin-top:1rem;">Wrong secret. Attempts left: ' . (5 - $fail_count) . '</p>';
// 				}
// 			}

// 			// Display form
// 			echo '
// 			<!DOCTYPE html>
// 			<html>
// 			<head>
// 				<title>Secure Access</title>
// 				<meta name="robots" content="noindex, nofollow">
// 				<style>
// 					*{ margin: 0; padding: 0; box-sizing: border-box;}
// 					body { font-family: Poppins, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; background: #f9f9f9; }
// 					form { padding: 2rem; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
// 					input { padding: 0.5rem; width: 100%; margin-top: 0.5rem; }
// 					button { margin-top: 1rem; padding: 0.5rem 1rem; background: #0073aa; color: white; border: none; border-radius: 4px; width: 100%; }
// 					button:hover { background: #005d88; }
// 					h3 { margin-bottom: 1rem; }
// 				</style>
// 			</head>
// 			<body>
// 				<form method="post">
// 					<h3>Enter Secret Word</h3>
// 					<div style="margin-bottom: 1rem;">
// 					  <input type="password" name="secret" id="secretInput" placeholder="Secret Word" required style="width: 100%; padding: 0.5rem;">
// 					</div>
// 					<div style="margin-bottom: 1rem;">
// 					  <label style="font-size: 14px;">
// 						<input type="checkbox" id="toggleSecret" style="margin-right: 5px;">
// 						Show Secret Word
// 					  </label>
// 					</div>

// 					' . $error . '
// 					<button type="submit">Continue</button>
// 				</form>
// 				<script>
// 				  document.getElementById("toggleSecret").addEventListener("change", function () {
// 					const input = document.getElementById("secretInput");
// 					input.setAttribute("type", this.checked ? "text" : "password");
// 				  });
// 				</script>
// 			</body>
// 			</html>';
// 			exit;
// 		}
// 	}
// });

