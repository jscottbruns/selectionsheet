<?php
if ($_REQUEST['search_str'] && substr(base64_decode($_REQUEST['search_str']),0,32) == $_SESSION['id_hash']) {
	$search_str = base64_decode($_REQUEST['search_str']);
	$search_str = substr($search_str,32);
	
	$result = $db->query("SELECT COUNT(*) AS Total
						  FROM `message_contacts`
						  WHERE `id_hash` = '".$obj->current_hash."' ".($obj->category_hash ? "
						  && `category` = '".$obj->category_hash."'" : NULL).($_REQUEST['search_str'] ? "
						  && (".$search_str.")" : NULL));
	$total = $db->result($result);
} else {
	unset($_REQUEST['search_str'],$search_str);
	$total = $obj->total_contacts;
}
 	
$Per_Page = 25;
$num_pages = ceil($total / $Per_Page);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
$start_from = $Per_Page * ($p - 1);
$end = $start_from + $Per_Page;
if ($end > $total)
	$end = $total;

$start_from++;
$order_by_ops = array("first_name","last_name","company");
if ($_REQUEST['order_by'] && in_array($_REQUEST['order_by'],$order_by_ops))
	$order_by = $_REQUEST['order_by'];
else
	$order_by = "last_name";								

if (!$order_by)
	$order_by = "last_name";
	
echo hidden(array("cmd"			=>		$_REQUEST['cmd'],
				  "order_by"	=>		$_REQUEST['order_by'],
				  "search_str"	=>		$_REQUEST['search_str'],
				  "search_hash" =>		"",
				  "category"	=>		$obj->category_hash,
				  "search"		=>		"",
				  "contactbtn"	=>		""
				  )).
"<script Language=\"javascript\">
function checkthis() {
	el = document.selectionsheet.elements.length;
	for (var i = 0; i < el; i++) {
		if (document.selectionsheet.elements[i].type == 'checkbox') {
			if (document.selectionsheet.elements[i].checked == 1) {
				document.selectionsheet.elements[i].checked = 0;
			} else {
				document.selectionsheet.elements[i].checked = 1;
			}
		}
	}
}

</script>

<table style=\"width:100%;\">
	<tr>
		<td class=\"smallfont\">
			<table style=\"width:100%;\" >
				<tr>
					<td colspan=\"2\">".($num_pages > 0 ? "
						<div style=\"padding:5px 10px;float:right;\" class=\"smallfont\">Showing Contacts $start_from - $end of $total</div>" : NULL)."
						<h2 style=\"color:#0A58AA;\">Category: ".$obj->category_name."</h2>
					</td>
				</tr>
				<tr>
					<td style=\"vertical-align:bottom;\">
						".($_REQUEST['feedback'] ? "
						<div class=\"alertbox\">
							".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
							<p>".base64_decode($_REQUEST['feedback'])."</p>
						</div>" : NULL)."
						<div class=\"smallfont\">";
						for ($i = ord('A'); $i <= ord('Z'); $i++) {
							$res = $db->query("SELECT COUNT(*) AS Total
											   FROM `message_contacts`
											   WHERE `id_hash` = '".$obj->current_hash."' && (`first_name` LIKE '".chr($i)."%' || `last_name` LIKE '".chr($i)."%' || `company` LIKE '".chr($i)."%')");
							echo "
							<span style=\"color:#0A58AA;".($db->result($res) ? "font-weight:bold;" : NULL)."\">
							".($db->result($res) ? "
								<a href=\"javascript:go('".chr($i)."');\" style=\"color:#0A58AA;\">".chr($i)."</a>" : chr($i))."
							</span>";				
						}
				echo "
						</div>
					</td>
					<td>".($num_pages > 0 ? "
						<div style=\"float:right;padding-top:5px;\">".paginate($num_pages,$p,"?category=".$obj->category_hash."&order_by=".$_REQUEST['order_by']."&search_str=".$_REQUEST['search_str'])."</div>" : NULL)	."		
					</td>
				</tr>
			</table>			
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\">";
					if (count($categories) > 1) {
						echo "
						<div style=\"float:right;padding-right:10px;\" class=\"smallfont\">
							<strong>Move To Category: </strong>
							<select name=\"moveto\">";
							for ($i = 1; $i < count($categories); $i++) 
								echo "
								<option value=\"".$cat_hash[$i]."\">
									".$categories[$i]."&nbsp;&nbsp;&nbsp;
								</option>";
							echo 
							"	<option value=\"\">--No Category--</option>
							</select>
							&nbsp;
							".submit('contactbtn','Move')."
						</div>";
					}
					echo "
						<table>
							<tr>
								<td>".submit(contactbtn,"DELETE")."</td>
								<td>".submit(contactbtn,"SEND EMAIL")."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style=\"padding:0;\">
						<table id=\"datatable\" class=\"tbldata\" width=\"100%\" cellpadding=2 cellspacing=0 border=0>
							<thead>
								<tr>
									<th width=100% nowrap align=center>
										<div style=\"padding:3px 10px;float:right;font-weight:normal;\" class=\"smallfont\">
											<strong>Sort by: </strong>
											&nbsp;&nbsp;".($order_by == "first_name" ? "
											<span style=\"font-weight:bold;\">First Name</span>" : "
											<a href=\"?category=".$obj->category_hash."&order_by=first_name&p=$p&search_str=".$_REQUEST['search_str']."\">First Name</a>")."
											&nbsp;|&nbsp;".($order_by == "last_name" ? "
											<span style=\"font-weight:bold;\">Last Name</span>" : "
											<a href=\"?category=".$obj->category_hash."&order_by=last_name&p=$p&search_str=".$_REQUEST['search_str']."\">Last Name</a>")."
											&nbsp;|&nbsp;".($order_by == "company" ? "
											<span style=\"font-weight:bold;\">Company</span>" : "
											<a href=\"?category=".$obj->category_hash."&order_by=company&p=$p&search_str=".$_REQUEST['search_str']."\">Company</a>")."
										</div>
										".checkbox(a,a,NULL,NULL,NULL,"onClick=\"checkthis();\"")."
									</th>													
								</tr>
							</thead>
						</table>
					</td>
				</tr>
				<tr>
					<td style=\"padding:0;\">
						<table id=\"datatable\" width=\"100%\" cellpadding=2 cellspacing=0 border=0>
						";
						if ($total > 0) {
							$result = $db->query("SELECT `contact_hash` , `first_name` , `last_name` , `company` , `phone1` , `phone2` , 
												  `fax` , `mobile1` , `mobile2` , `nextel_id` , `email`
												  FROM `message_contacts`
												  WHERE `id_hash` = '".$obj->current_hash."' ".($obj->category_hash ? "
												  && `category` = '".$obj->category_hash."'" : NULL).($_REQUEST['search_str'] ? "
												  && (".$search_str.")" : NULL)."												  
												  ORDER BY `$order_by` ASC
												  LIMIT ".($start_from - 1)." , $Per_Page");
							while ($row = $db->fetch_assoc($result)) {
								echo "
								<tr class=\"msgnew\">
									<td style=\"border-width: 0 0 1px 0;border-style: solid;padding:2px 0 2px 5px;vertical-align:center;\">
										<table class=\"smallfont\">
											<tr >
												<td >".checkbox("contact_hash[]",$row['contact_hash'])."</td>
												<td style=\"font-weight:bold;\" >
													<a href=\"javascript:void(0);\" onClick=\"openWin('id_card.php?contactid=".$row['contact_hash']."',400,300);\">
													".($order_by != "company" && !$row['first_name'] && !$row['last_name'] ? $row['company'] : NULL).($order_by == "first_name" ? 
													$row['first_name']." ".$row['last_name'] : ($order_by == "last_name" ? 
														($row['last_name'] ? $row['last_name'].", " : NULL).$row['first_name'] : $row['company'])).($order_by == "company" && !$row['company'] ? 
															($row['last_name'] ? $row['last_name'].", " : NULL).$row['first_name'] : NULL)."
													</a>
												</td>
											</tr>".(($order_by == "company" && $row['company']) || ($order_by != "company" && $row['company'] && $row['first_name'] && $row['last_name']) ? "
											<tr>
												<td></td>
												<td style=\"font-weight:bold;padding-left:10px;\" onClick=\"openWin('id_card.php?contactid=".$row['contact_hash']."',400,300);\" style=\"cursor:hand;\">
													<small>".($order_by == "company" && $row['company'] ? 
														($row['last_name'] ? $row['last_name'].", " : NULL).$row['first_name'] : ($order_by != "company" && $row['company'] ? 
															$row['company'] : NULL))."</small>
												</td>
											</tr>" : NULL)."
											<tr>
												<td></td>
												<td style=\"padding-left:10px;\">
													<small>".($row['phone1'] ? 
														format_phone($row['phone1'])." (h) " : NULL).($row['phone2'] ? 
														format_phone($row['phone2'])." (w) " : NULL).($row['mobile1'] ? 
														format_phone($row['mobile1'])." (m) " : NULL).($row['mobile2'] ? 
														format_phone($row['mobile2'])." (m) " : NULL).($row['fax'] ? 
														format_phone($row['fax'])." (f)" : NULL)."
													</small>
												</td>
											</tr>
											<tr>
												<td></td>
												<td >[<small><a href=\"?category=".$obj->category_hash."&order_by=$order_by&cmd=new&contact_hash=".$row['contact_hash']."\">Edit</a></small>]</td>
											</tr>
										</table>
									</td>	
								</tr>";
							}
							/*
							echo "
							<tr>
								<td style=\"padding:0;\">
									<table id=\"datatable\" class=\"tbldata\" width=\"100%\" cellpadding=2 cellspacing=0 border=0>
										<thead>
											<tr>
												<th width=100% nowrap align=center>
													<div style=\"padding:3px 10px;float:right;\" class=\"smallfont\">
													
													</div>
												</th>													
											</tr>
										</thead>
									</table>
								</td>
							</tr>";
							*/
						} else 
							echo "
							<tr class=msgnew >
								<td style=\"padding:10px;\">You have no contacts ".($_REQUEST['search_str'] ? "that match your search query" : NULL).".</td>
							</tr>";
						
						echo "
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>"
?>