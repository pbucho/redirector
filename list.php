<?php
	include_once("includes/conf.php");
	include_once("includes/cookies.php");
	include_once("includes/cache.php");
	include_once("includes/roles.php");
	global $SHORT_BASE;

	$session_token = cookies_has_session();
	$is_admin = roles_is_admin(cache_get_cached_user($session_token));

?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<title>Redirector URL list</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="/css/backwards.css">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
		<link rel="stylesheet" href="/resources/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
	</head>
	<body>
		<?php include("resources/top_menu.php"); ?>
		<div class="container">
			<h1>Redirector URL list</h1>
			<p>Short URLs to be used in the format <code><?php echo $SHORT_BASE; ?>/string</code></p>
			<hr/>
			<?php echo "<input type='hidden' id='is_admin' name='is_admin' value='".conf_bin_2_eng($is_admin)."'>"; ?>
			<table class="table table-hover" id="link_table">
				<thead>
					<tr>
						<th>String</th>
						<th>Long URL</th>
						<th>Date added</th>
						<th>Views</th>
						<?php
							if($is_admin){
								echo "<th>Owner</th>";
								echo "<th>Admin actions</th>";
							}
						?>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.js">
			</script>
			<script type="text/javascript" src="/resources/bootstrap/js/bootstrap.min.js">
			</script>
			<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js">
			</script>
			<script type="text/javascript" src="/js/api_com.js"></script>
			<script type="text/javascript">
				var token = getToken();
				var isAdmin = $("#is_admin").val() == "true" ? true : false;
				var table;
				$(document).ready(function(){
					if(!isAdmin){
						table = $("#link_table").DataTable( {
							"ajax": {
								"url": token == null ? '/api/listall.php' : '/api/listall.php?token='+token,
								"dataSrc": "items"
							},
							"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
							"order": [[ 2, "desc" ]],
							"columns": [
								{ data: "string" },
								{ data: "longurl" },
								{ data: "dateadded" },
								{ data: "views" }
							]
						});
					}else{
						table = $("#link_table").DataTable({
							"ajax": {
								"url": '/api/listall.php?token='+token,
								"dataSrc": "items"
							},
							"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
							"order": [[ 2, "desc" ]],
							"columns": [
								{ data: "string" },
								{ data: "longurl" },
								{ data: "dateadded" },
								{ data: "views" },
								{ data: "owner" },
								{ data: null,
									"target": 5,
									"defaultContent": "<button class='btn btn-primary' data-toggle='modal' data-target='#edit_modal'><span class='fa fa-pencil'></span></button>&nbsp;&nbsp;&nbsp;<button class='btn btn-danger' data-toggle='modal' data-target='#confirm_modal'><span class='fa fa-trash-o'></span></button>"
								}
							]
						});
					}
					$("#link_table").on('click','button',function(){
						var data = table.row($(this).parents('tr')).data();
						setEditFields(data['string'], data['longurl']);
						setConfirmFields(data['string']);
					});
				});
				function setEditFields(short_url, long_url) {
					$("#ed_short_url").val(short_url);
					$("#ed_long_url").val(long_url);
					$("#ed_short_url_disabled").val(short_url);
				}
				function setConfirmFields(short_url) {
					$("#conf_short_url").val(short_url);
					$("#conf_show_url").html(short_url);
				}
			</script>
		</div>
		<?php
			include("./resources/footer.php");
			include("./resources/edit_modal.html");
			include("./resources/confirm_modal.html");
		?>
	</body>
</html>
