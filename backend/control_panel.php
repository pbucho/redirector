<?php
	include_once("../includes/conf.php");
	include_once("../includes/cookies.php");
	include_once("../includes/cache.php");
	
	conf_validate_login("control_panel");
	$session_token = cookies_has_session();
	$cuser = cache_get_cached_user($session_token);
	$user_id = cache_get_cached_user_id($session_token);
	$active_sessions = tokens_get_active_sessions($user);
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<title>Control panel</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="/css/backwards.css">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
		<link rel="stylesheet" href="/resources/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
		<link rel="stylesheet" href="/css/font-awesome.min.css">
	</head>
	<body>
		<?php include("../resources/top_menu.php"); ?>
		<div class="container">
			<h1>Control panel</h1>
			<hr/>
			<br/>
			<div class="col-sm-6">
				<h2>Personal details</h2>
				<table class="table">
					<tr>
						<td class="col-sm-2"><b>User name</b></td><td class="col-sm-10"><?php echo $cuser; ?></td>
					</tr>
					<tr>
						<td class="col-sm-2"><b>Registered</b></td><td class="col-sm-10">
						<?php
							$sqlDate = "SELECT registered FROM users WHERE name = '$cuser'";
							$conn = conf_get_connection();
							$result = $conn->query($sqlDate);
							$conn = null;
							$result = conf_fetch_lazy($result);
							echo $result['registered'];
						?>
						</td>
					</tr>
					<tr>
						<td class="col-sm-2"><b>Current IP</b></td><td class="col-sm-10">
						<?php
							echo $_SERVER['REMOTE_ADDR'];
						?>
						</td>
					</tr>
					<tr>
						<td class="col-sm-2"><b>Roles</b></td><td class="col-sm-10"><b><kbd>
						<?php
							$role_print = roles_is_admin($cuser) ? "admin" : "none";
							echo $role_print;
						?>
						</kbd></b></td>
					</tr>
				</table>
			</div>
			<div class="col-sm-6">
				<h2>Password</h2>
				<form class="form-horizontal" role="form">
					<table class="table">
						<tr>
							<td class="col-sm-4"><b>Old password</b></td><td class="col-sm-8">
								<div class="form-group"><input type="password" class="form-control" id="old_password" name="old_password"></div>
							</td>
						</tr>
						<tr>
							<td class="col-sm-4"><b>New password</b></td><td class="col-sm-8">
								<div class="form-group"><input type="password" class="form-control" id="new_password" name="new_password"></div>
							</td>
						</tr>
						<tr>
							<td class="col-sm-4"><b>Confirm new password</b></td><td class="col-sm-8">
								<div class="form-group"><input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password"></div>
							</td>
						</tr>
					</table>
					<input type="submit" class="btn btn-primary" value="Change password">
				</form>
			</div>
			<br/>
			<h2>Login management</h2>
			You have <?php echo $active_sessions; ?> active session<?php echo ($active_sessions == 1 ? "" : "s"); ?>.
			<br/><br/>
			<button class="btn btn-primary" onclick="terminateOtherSessions()">Terminate all other sessions</button>
			&nbsp;&nbsp;<i id="spinner" class="fa fa-circle-o-notch fa-spin"></i>
			<script type="text/javascript" src="/js/jquery.js"></script>
			<script type="text/javascript" src="/resources/bootstrap/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="/js/api_com.js"></script>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#spinner").hide();
				});
				var token = getToken();
				function terminateOtherSessions() {
					var req = new XMLHttpRequest();
					var connection = "/api/terminatesessions.php?token="+token;
					req.open("GET", connection, true);
					req.onreadystatechange = function(){
						if(req.readyState == 4 && req.status == 200){
							var response = req.responseText;
							response = JSON.parse(response);
							if(response['success']){
								location.reload();
							}else{
								alert("Could not terminate sessions: "+response['reason']);
							}
						}
					};
					req.send();
				}
			</script>
		</div>
		<?php
			include("../resources/footer.php");
		?>
	</body>
</html>