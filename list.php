<?php
	include_once("conf.php");

	global $SHORT_BASE;
?>
<!DOCTYPE HTML>
<html lang="en">
	<!-- pbucho, 19-04-2016 -->
	<head>
		<title>Redirector URL list</title>
		<link rel="stylesheet" href="/resources/backwards.css">
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/jquery.dataTables.min.css">
	</head>
	<body>
		<?php include("resources/top_menu.php"); ?>
		<div class="container">
			<h1>Redirector URL list</h1>
			<p>Short URLs to be used in the format <code><?php echo $SHORT_BASE; ?>/string</code></p>
			<hr/>
			<table class="table table-hover" id="link_table">
				<thead>
					<tr>
						<th>String</th>
						<th>Long URL</th>
						<th>Date added</th>
						<th>Views</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$sql = "SELECT * FROM translation";
						$conn = getConnection();
						$result = $conn->query($sql);
						$conn = null;

						$result = $result->fetchAll();

						foreach($result as $item){
							echo "<tr>";
							echo "<td>".$item['short_url']."</td>";
							echo "<td><a href='".$item['long_url']."' target='_blank'>".$item['long_url']."</a></td>";
							echo "<td>".$item['added']."</td>";
							echo "<td>".$item['views']."</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js">
			</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#link_table").dataTable( {
					"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
					"order": [[ 2, "desc" ]]
				});
			});
		</script>
		</div>
		<?php include("./resources/footer.php"); ?>
	</body>
</html>
