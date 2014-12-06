<?php
$Per_Page = 50;

$result = $db->query("SELECT COUNT(*) AS Total 
					FROM `user_login` WHERE `user_status` > '2'");
$total = $db->result($result);
if ($total > 0) {
	$sql = "SELECT user_login.register_date , user_login.timestamp , user_login.id_hash , user_login.user_name , user_login.user_status , 
			user_login.first_name , user_login.last_name , user_login.builder , session.time , user_billing.credit_end_date
			FROM `user_login` 
			LEFT JOIN session ON session.id_hash = user_login.id_hash 
			LEFT JOIN user_billing ON user_billing.id_hash = user_login.id_hash
			WHERE user_login.user_status > '2' ";
	//For sorting
	if ($_REQUEST['o']) {
		$o = $_REQUEST['o'];
		if ( $o == 'name' )
			$o = 'user_login.last_name';
		
		if ($_REQUEST['dir'] == NULL) {
			$_REQUEST['dir'] = "ASC";
			$order = "ORDER BY $o ".$_REQUEST['dir'];
		} elseif ($_REQUEST['dir'] == "ASC") {
			$_REQUEST['dir'] = "DESC";
			$order = "ORDER BY $o ".$_REQUEST['dir'];
		} elseif ($_REQUEST['dir'] == "DESC") {
			$_REQUEST['dir'] = "ASC";
			$order = "ORDER BY $o ".$_REQUEST['dir'];
		}
	}

	$sql .= $order;
	
	//Append a LIMIT clause
	if (empty($_REQUEST['Result_Set'])) {
		 $Result_Set = 0;
		 
		 $sql .= " LIMIT $Result_Set, $Per_Page";		
	} else {
		 $Result_Set = $_REQUEST['Result_Set'];
		 
		 $sql .= " LIMIT $Result_Set, $Per_Page";
	}
	
	$result = $db->query($sql);

	if ($Result_Set < $total && $Result_Set > 0) {
		$Res1 = $Result_Set - $Per_Page;
		$Prev = "
		<td class=\"alt1_nav\" >
		<a href=\"?cmd=".$_REQUEST['cmd']."&sq=".$_REQUEST['sq']."&search=".$_REQUEST['search']."&o=".$_REQUEST['o']."&Result_Set=$Res1\" title=\"Previous page\" style=\"font-weight:bold\"><</a>
		</td>";
	}
	//Page Numbers and Links
	$Pages = $total / $Per_Page;
	$currentPage = ($Result_Set / $Per_Page) + 1;
	
	if ($Pages > 1) {
		if ($Pages > 4) {
			$low = $currentPage - 3;
			$lowc = $low + 1;
			if ($low < 0) {
				$low = 0;
				$lowc = 1;
			}
			$high = $currentPage + 2;
			if ($high > $Pages) $high = $Pages;
		} else {
			$low = 0;
			$lowc = 1;
			$high = $Pages;
		}
		
		//If we're not showing 1 in the nav bar, print a link to jump to page 1
		if ($low > 0) {
			$PageNumber = "
			<td class=\"alt1_nav\" >
			<a href=\"?cmd=".$_REQUEST['cmd']."&sq=".$_REQUEST['sq']."&search=".$_REQUEST['search']."&o=".$_REQUEST['o']."&Result_Set=0\" title=\"Jump to the first page\" style=\"font-weight:bold\"><< First Page</a>
			</td>";
		}
		
		//Previous link
		$PageNumber .= $Prev;

		for ($b = $low,$c = $lowc; $b < $high; $b++,$c++) {
			$Res1 = $Per_Page * $b;
			if ($c != $currentPage) {
				$linkO = "<a href=\"?cmd=".$_REQUEST['cmd']."&sq=".$_REQUEST['sq']."&search=".$_REQUEST['search']."&o=".$_REQUEST['o']."&Result_Set=$Res1\" style=\"font-weight:bold\">";
				$linkC = "</a>";
			}
			$PageNumber .= "<td class=\"alt1_nav\" >$linkO $c $linkC</td>";
			unset($linkO,$linkC);
		} 
		
		if ($Result_Set < $total) {
			$Res1 = $Result_Set + $Per_Page;
			if ($Res1 < $total) {
				$PageNumber .= "
				<td class=\"alt1_nav\" >
				<a href=\"?cmd=".$_REQUEST['cmd']."&sq=".$_REQUEST['sq']."&search=".$_REQUEST['search']."&o=".$_REQUEST['o']."&Result_Set=$Res1\" style=\"font-weight:bold\">></a>
				</td>";
			}
		}

		//If we're not showing the last page in the nav bar, print a link to the last page
		if ($high < $Pages) {
			$PageNumber .= "
			<td class=\"alt1_nav\" >
			<a href=\"?cmd=".$_REQUEST['cmd']."&sq=".$_REQUEST['sq']."&search=".$_REQUEST['search']."&o=".$_REQUEST['o']."&dir=".$_REQUEST['dir']."&Result_Set=".(intval($Pages) * $Per_Page)."\" title=\"Jump to the last page\" style=\"font-weight:bold\">Last Page >></a>
			</td>";
		}
		
	}
	
	$PerPageResults = $Result_Set + $Per_Page;
	$status = array("3" => "Demo", "4" => "Trial", "5" => "Registered", "7" => "Beta");
	
	while ($row = $db->fetch_assoc($result)) {
		$user_status[] = $row['user_status'];
		if ($row['user_status'] == 4) {
			$time = (30 - intval((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$row['register_date']))) / 86400));
			if ($time <= 0) $remaining[] = "Expired";
			else $remaining[] = $time." days";
		} elseif ($row['user_status'] == 5) {
			$time = intval(intval(strtotime(date("Y-m-d",$row['credit_end_date'])) - strtotime(date("Y-m-d"))) / 86400);
			if ($time <= 0) $remaining[] = "Expired";
			else $remaining[] = $time." days";
		} else $remaining[] = "N/A";
		
		$admin_id_hash[] = $row['id_hash'];
		$admin_username[] = $row['user_name'];
		$first_name[] = $row['first_name'];
		$last_name[] = $row['last_name'];
		$builder[] = $row['builder'];
		if ($row['time']) $login[] = "Logged In";
		elseif (!$row['timestamp']) $login[] = "Never";
		elseif (date("Y-m-d",$row['timestamp']) == date("Y-m-d",strtotime(date("Y-m-d")))) $login[] = "Today ".date("g:i a",$row['timestamp']);
		elseif (date("Y-m-d",$row['timestamp']) == date("Y-m-d",strtotime(date("Y-m-d")." -1 day"))) $login[] = "Yesterday ".date("g:i a",$row['timestamp']);
		elseif (time() - $row['timestamp'] < 514800) $login[] = date("l, g:i a",$row['timestamp']);
		else $login[] = date("M d, Y g:i a",$row['timestamp']);
		
		$result2 = $db->query("SELECT COUNT(*) AS Total
								FROM `lots` 
								WHERE `id_hash` = '".$row['id_hash']."' && `status` = 'SCHEDULED'");
		$lots[] = $db->result($result2);
	}
	
	echo "
	<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"90%\" >
		<thead>
			<tr>
				<td class=\"tcat\" colspan=\"7\" style=\"padding:6px 0\">".($Pages > 1 ? "
					<span class=\"smallfont\" style=\"float:right;padding-right:6px;\">
						<table class=\"tborder\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\">
						<tr>
							<td class=\"vbmenu_control\" style=\"font-weight:normal\">Page $currentPage of ".ceil($Pages)."</td>	
							$PageNumber
						</tr>
						</table>
					</span>&nbsp;" : NULL)."
					&nbsp;&nbsp;SelectionSheet Membership
				</td>
			</tr>
		</thead>
		<tr>
			<td><strong><a href=\"?cmd=".$_REQUEST['cmd']."&o=user_name&dir=".$_REQUEST['dir']."\">Username</a></strong></td>
			<td><strong><a href=\"?cmd=".$_REQUEST['cmd']."&o=name&dir=".$_REQUEST['dir']."\">Name</a></strong></td>
			<td><strong><a href=\"?cmd=".$_REQUEST['cmd']."&o=user_status&dir=".$_REQUEST['dir']."\">Status</a></strong></td>
			<td><strong><a href=\"?cmd=".$_REQUEST['cmd']."&o=builder&dir=".$_REQUEST['dir']."\">Builder</a></strong></td>
			<td><strong><a href=\"?cmd=".$_REQUEST['cmd']."&o=timestamp&dir=".$_REQUEST['dir']."\">Last Login</a></strong></td>
			<td><strong>Remaining</strong></td>
			<td><strong>Lots</strong></td>
		</tr>";
	for ($i = 0; $i < count($admin_username); $i++) {
		$name = $last_name[$i].", ".$first_name[$i];
		list($add1,$add2,$city,$st,$zip) = explode("+",$address[$i]);
		list($phone1,$phone2) = explode("+",$phone[$i]);		
		
		echo "
		<tr>
			<td class=\"smallfont\" style=\"background-color:#efefef;\"><a href=\"?cmd=userprofile&id=".$admin_id_hash[$i]."\">".$admin_username[$i]."</a></td>
			<td class=\"smallfont\" style=\"background-color:#efefef;\">$name</td>
			<td class=\"smallfont\" style=\"background-color:#efefef;\">".$status[$user_status[$i]]."</td>
			<td class=\"smallfont\" style=\"background-color:#efefef;\">".$builder[$i]."</td>
			<td class=\"smallfont\" style=\"background-color:#efefef;\">".$login[$i]."</td>
			<td class=\"smallfont\" style=\"background-color:#efefef;\">".$remaining[$i]."</td>
			<td class=\"smallfont\" style=\"background-color:#efefef;\">".$lots[$i]."</td>
		</tr>";
	}

	echo "
		</tr>
	</table>";
	
}
?>