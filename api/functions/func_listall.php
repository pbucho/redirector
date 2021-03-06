<?php
	$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
	include_once($DOC_ROOT."/includes/base.php");
	include_once($DOC_ROOT."/includes/cache.php");
	include_once($DOC_ROOT."/includes/roles.php");
	include_once($DOC_ROOT."/includes/conf.php");
	include_once("func_authenticate.php");

	function api_listall($token, $minlim, $maxlim){
		global $DEBUG;
		if(!is_null($minlim) || !is_null($maxlim)){
			if(is_null($maxlim) || is_null($minlim)){
				return json_encode(array('success' => false, 'reason' => 'Missing minimum or maximum limit'));
			}
			if($minlim > $maxlim || $maxlim < 0 || $minlim < 0){
				return json_encode(array('success' => false, 'reason' => 'Invalid limits'));
			}
		}

		$sqlList = "SELECT short_url, long_url, added, views, u.name AS owner, u.id AS uid, unlisted_url FROM translation t LEFT JOIN users u ON t.owner = u.id";
		$sqlOwner = "SELECT u.id AS user FROM users u INNER JOIN tokens t ON t.owner = u.id WHERE t.value = '$token'";
		if(!is_null($minlim) && !is_null($maxlim)){
			$sqlList = $sqlList." LIMIT $minlim , $maxlim";
		}
		$conn = base_get_connection();
		$currentuser = $conn->query($sqlOwner);
		$currentuser = base_fetch_lazy($currentuser)['user'];
		$isadmin = roles_is_admin(cache_get_cached_user($token));
		try{
			$result = $conn->query($sqlList);
			$conn = null;
			$response_array = array('success' => true, 'items' => array());

			foreach($result as $item){
				if($item['unlisted_url'] == 1){
					if(is_null($currentuser)){
						continue;
					}
					if(!$isadmin && !is_null($currentuser) && $item['uid'] != $currentuser){
						continue;
					}
				}
				$item_response = array('string' => $item['short_url'], 'longurl' => $item['long_url'], 'dateadded' => $item['added'], 'views' => (int) $item['views']);
				if(!is_null($token) && json_decode(api_authenticate($token), true)['success']){
					if($isadmin){
						$item_response['owner'] = $item['owner'];
						$item_response['unlistedurl'] = (bool) $item['unlisted_url'];
					}
				}
				array_push($response_array['items'], $item_response);
			}
			return json_encode($response_array);
		}catch(PDOException $e){
			$conn = null;
			$response_array = array('success' => false, 'reason' => 'Unknown error', 'code' => $e->getCode());
			if($DEBUG){
				array_push($response_array['message'], $e->getMessage());
			}
			return json_encode($response_array);
		}
	}
?>
