<?php
$user_tasks = new tasks();
$rand = $user_tasks->task_bank_search_engine();

$lots = new lots;

echo "
<script language=\"JavaScript1.1\" src=\"user/taskbank_search_engine_".$user_tasks->current_hash.$rand.".js\"></script>"; 
$k = $i;

echo  (!$active ? "
<script>
function recursive(me,id) {
	switch(id) {
		case 1:
		var id2 = 2;
		break;
		
		case 2:
		var id2 = 1;
		break;
	}

	if (me == true && document.getElementById('div_recursive'+id2).style.display == 'none') 
		document.getElementById('div_recursive'+id).style.display = 'block';
}
</script>" : NULL)."
<script>
var scroll_to_select;
var search_results;
function field_check(searchArray) {
	var entry = \$F('query1');
	search_results = new Array();
	scroll_to_select = 0;
	var scrolled;

	while (entry.charAt(0) == ' ') {
		entry = entry.substring(1,entry.length);
		\$('query'+searchArray).value = entry;
	}
	if (entry.length > 2) {
		var findings = new Array();

		for (i = 0; i < records_primary.length; i++) {
			var allString = records_primary[i].toUpperCase();
			var refineAllString = allString.substring(allString.indexOf('|'));
			var allElement = entry.toUpperCase();
			
			if (refineAllString.indexOf(allElement) != -1) 
			{
				if (!scrolled)
					scroll_to(searchArray,records_primary[i].substr(0,records_primary[i].indexOf('|')));
				
				scrolled = true;
				search_results[search_results.length] = records_primary[i].substr(0,records_primary[i].indexOf('|'));
			}
		}
	}
	search_str_msg(searchArray);
}

function search_str_msg(searchArray) {
	\$('search_results_msg'+searchArray).update(search_results.length+' Matches '+(scroll_to_select > 0 ? '<a href=\'javascript:void(0);\' onClick=\'prev('+searchArray+');\'><-</a> ' : '&nbsp;&nbsp;&nbsp;&nbsp;')+(scroll_to_select < search_results.length && search_results.length > 1 ? '<a href=\'javascript:void(0);\' onClick=\'next('+searchArray+');\'>-></a>' : ''));
}

function next(type) {
	if ((scroll_to_select + 1) >= search_results.length)
		return alert('End of search results');
	
	scroll_to_select++;
	scroll_to(type,search_results[scroll_to_select]);
	search_str_msg(type);
}

function prev(type) {
	if (scroll_to_select == 0)
		return alert('Beginning of search results');
	
	scroll_to_select--;
	scroll_to(type,search_results[scroll_to_select]);
	search_str_msg(type);
}

function scroll_to(type,id) {
	var canvasTop = document.getElementById('bank_'+id).offsetTop;
	\$('type_'+type).scrollTop = (canvasTop - 25);
	return;
}

</script>
<tr id=\"all_trades\">
	<td style=\"background-color:#ffffff;text-align:right;font-weight:bold;vertical-align:top;\">
		$err[6]Trades:
		<div style=\"padding:15px 5px;color:#000000;font-weight:normal\">
			Search:<br />".text_box(query1,NULL,10,NULL,NULL,"input_bg",NULL,NULL,"onKeyUp=\"field_check(1);\"")."
			<div id=\"search_results_msg1\"></div>
		</div>
	</td>
	<td class=\"smallfont\" style=\"background-color:#ffffff;\">
		<div class=\"alt2\" id=\"type_1\" style=\"margin:0px; padding:6px; border:1px inset; width:400px; height:150px; overflow:auto\">";
	
	for ($j = 0; $j < count($user_tasks->task); $j++) {
		if (in_array(substr($user_tasks->task[$j],0,1),$user_tasks->primary_types)) {
			echo "
			<div id=\"bank_".$user_tasks->task[$j]."\">".
				checkbox("task_".$user_tasks->task[$j],$user_tasks->task[$j],($_REQUEST["task_".$user_tasks->task[$j]] ? $_REQUEST["task_".$user_tasks->task[$j]] : (@in_array($user_tasks->task[$j],$subs->sub_trades[$k]) ? $user_tasks->task[$j] : NULL)),($_GET[$user_tasks->task[$j]] ? 1 : NULL),NULL,"onClick=\"if (command_win && !command_win.closed) command_win.location.href = 'tag_sub.php?contact_hash=".$_REQUEST['contact_hash']."';\" id=\"input_".$user_tasks->task[$j]."\"")."&nbsp;
				".$user_tasks->name[$j]." \n
			</div>";
		}
	}
	echo  "
		</div>
	</td>
</tr>
<tr id=\"all_communities\">
	<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\" valign=\"top\">
		$err[7]<a name=\"community\">Communities:</a>
		<br />
		".help(7)."
	</td>
	<td style=\"background-color:#ffffff;\">";

	if (count($community->community_hash) > 0) {
		$my_communities = array_count_values($community->community_owner);
		echo  "
		<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:400px; height:".($cHeight = $my_communities[$_SESSION['id_hash']] * 35 > 150 ? 150 : $cHeight)."; font-size:12px; overflow:auto\">";
		for ($j = 0; $j < count($community->community_hash); $j++) {
			if (defined('PROD_MNGR') || $community->community_owner[$j] == $_SESSION['id_hash']) 
				echo  checkbox($community->community_hash[$j],$community->community_hash[$j],($_REQUEST[$community->community_hash[$j]] ? $_REQUEST[$community->community_hash[$j]] : (@in_array($community->community_hash[$j],$subs->sub_community[$k]) ? $community->community_hash[$j] : NULL)))." ".$community->community_name[$j]." \n<br />";
		}
		echo  "
		</div>";
	} else {
		echo  "
		<div style=\"padding:10 0;\">
			<table>
				<tr>
					<td rowspan=\"3\"><a href=\"communities.location.php?cmd=edit&p=1\"><img src=\"images/icon4.gif\" border=\"0\"></a></td>
					<td style=\"padding:0 0 0 10;\"><a href=\"communities.location.php?cmd=edit&p=1\">Create a new community now.</a></td>
				</tr>
				<tr>
					<td style=\"padding:5 0 0 40;\"><strong>Or</strong></td>
				</tr>
				<tr>
					<td style=\"padding:0 0 0 10;\"><a href=\"javascript:void(0);\" onClick=\"jumptoCommunity.checked='1';\">Create a community after.</a>".checkbox(jumptoCommunity,$_REQUEST['jumptoCommunity'],$_REQUEST['jumptoCommunity'])."</td>
				</tr>
			</table>
		</div>";
	}
echo  "
	</td>
</tr>".(count($lots->lot_hash) && $_REQUEST['contact_hash'] ? "
<tr id=\"all_communities\">
	<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\" valign=\"top\">
		<a name=\"community\">Lots:</a>
	</td>
	<td style=\"background-color:#ffffff;\"><a href=\"javascript:void(0);\" onClick=\"!command_win || command_win.closed ? openWin('tag_sub.php?contact_hash=".$_REQUEST['contact_hash']."','600','700') : command_win.focus()\">Tag this sub to individual lots.</a></td>
</tr>" : NULL);
?>