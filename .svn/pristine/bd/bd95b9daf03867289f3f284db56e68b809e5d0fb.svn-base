<?php
$result = $db->query("SELECT COUNT(*) AS Total
					  FROM `sales_leads`");
$total = $db->result($result);

$Per_Page = 45;
$num_pages = ceil($total / $Per_Page);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
$start_from = $Per_Page * ($p - 1);

$end = $start_from + $Per_Page;
if ($end > $total)
	$end = $total;

$order_by_ops = array("company","contact","user_name");

if ($_REQUEST['order_by'] && in_array($_REQUEST['order_by'],$order_by_ops))
	$order_by = $_REQUEST['order_by'];
else
	$order_by = "company";								

if ($_REQUEST['dir']) {
	$dir = $_REQUEST['dir'];
	if ($dir == 'ASC')
		$dir = 'DESC';
	else
		$dir = 'ASC';
} else 
	$dir = "ASC";

echo hidden(array("cmd"			=>		$_REQUEST['cmd'],
				  "order_by"	=>		$_REQUEST['order_by']
				  )
			)."
<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
	<tr>
		<td style=\"padding:15;\">
			 <table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:100%;\">
				<tr>
					<td style=\"font-weight:bold;vertical-align:middle;\" colspan=\"5\">
						<div style=\"float:right;font-weight:normal;padding-right:10px;\">".paginate($num_pages,$p,'?'.query_str("p"))."</div>
						Showing ".($start_from + 1)." - ".($start_from + $main_config['pagnation_num'] > $total ? $total : $start_from + $main_config['pagnation_num'])." of ".$total." contacts.
					</td>
				</tr>
				<tr>
					<td style=\"background-color:#cccccc;\" colspan=\"5\"></td>
				</tr>
				<tr >
					<td style=\"font-weight:bold;background-color:#efefef;\"><a href=\"?cmd=leads&order_by=company&dir=$dir&p=$p\">Company</a></td>
					<td style=\"font-weight:bold;background-color:#efefef;\"><a href=\"?cmd=leads&order_by=contact&dir=$dir&p=$p\">Contact</a></td>
					<td style=\"font-weight:bold;background-color:#efefef;\">Phone</td>
					<td style=\"font-weight:bold;background-color:#efefef;\">Location</td>
					<td style=\"font-weight:bold;background-color:#efefef;\"><a href=\"?cmd=leads&order_by=user_name&p=$p\">Sales Rep</a></td>
				</tr>";

		$result = $db->query("SELECT sales_leads.lead_hash , sales_leads.company ,  sales_leads.contact , sales_leads.phone1 , sales_leads.address , user_login.user_name
							  FROM `sales_leads`
							  LEFT JOIN user_login ON user_login.id_hash = sales_leads.id_hash
							  ORDER BY `$order_by` $dir
							  LIMIT $start_from , $Per_Page");
		$num_rows = $db->num_rows($result);
		while ($row = $db->fetch_assoc($result)) {
			$c++;
			
			list($add1,$add2,$add3,$city,$state) = explode("+",$row['address']);			
			echo "
				<tr style=\"cursor:hand;\" onClick=\"window.location='?cmd=leads&action=newlead&lead_hash=".$row['lead_hash']."'\">
					<td >".$row['company']."</td>
					<td >".($row['contact'] ? $row['contact'] : NULL)."</td>
					<td >".($row['phone1'] ? $row['phone1'] : NULL)."</td>
					<td >".($city ? $city : NULL).($state ? " ".$state : NULL)."</td>
					<td >".$row['user_name']."</td>
				</tr>".($c < $num_rows ? "
				<tr>
					<td style=\"background-color:#efefef;\" colspan=\"5\"></td>
				</tr>" : NULL);
		}
		
	echo "
			</table>
		</td>
	</tr>
</table>";
?>