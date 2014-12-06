<?php
if ($_GET['public_hash']) {
	/*
	session_start();
	@include 'include/config.php';
	
	// If PUN isn't defined, config.php is missing or corrupt
	if (!defined('PUN'))
	exit('The file \'config.php\' doesn\'t exist or is corrupt. Please check the validity of the primary configuration file first.');

	// Load DB abstraction layer and connect
	require_once 'include/db_layer.php';
	$db = new DBLayer($db_host, $db_username, $db_password, $db_name, $p_connect);
	
	// Start a transaction
	$db->start_transaction();
	require_once ('include/library.class.php');
	require_once ('include/login_funcs.class.php');
	require_once ('include/form_funcs.php');
	*/
	include_once ('include/common.php');
	require_once ('pdf/fpdf.php');
} else {
	require_once ('include/common.php');
	require_once ('pdf/fpdf.php');
}
	
require_once ('schedule/tasks.class.php');
require_once ('running_sched/schedule.class.php');

if (eregi("index.php",$_SERVER['HTTP_REFERER']) || eregi("schedule.php?",$_SERVER['HTTP_REFERER']) || eregi("lots.location.php?",$_SERVER['HTTP_REFERER'])) {
	unset($_SESSION['print']);
	$_SESSION['print']['lot_hash'] = $_GET['lot_hash'];
	$_SESSION['print']['lot_no'] = $_GET['lot_no'];
	$_SESSION['print']['community'] = $_GET['community'];
	$_SESSION['print']['view'] = $_GET['view'];
	$_SESSION['print']['complete'] = $_GET['complete'];
}

if ($_GET['public_hash'] || $_SESSION['print']) {
	if ($_GET['public_hash']) {
		$lot_hash = $_GET['public_hash'];
		$result = $db->query("SELECT `id_hash` , `status` , `lot_no` , `community` , `cust_name` , `public` 
							FROM `lots` 
							WHERE `lot_hash` = '$lot_hash'");
		$row = $db->fetch_assoc($result);
		
		if ($db->num_rows($result) == 0) 
			$error = 1;
		elseif ($row['public'] != 1)
			$error = 2;
		elseif ($row['status'] == 'PENDING')
			$error = 3;	
		$public = true;
		$owner_hash = $row['id_hash'];
		$community = $row['community'];
		$cust_name = $row['cust_name'];
		$lot_no = $row['lot_no'];
	} else {
		$lot_hash = $_SESSION['print']['lot_hash'];
		$lot_no = base64_decode($_SESSION['print']['lot_no']);
		$community = $_SESSION['print']['community'];
		$owner_hash = $_SESSION['print']['owner_hash'];
		if (!$owner_hash)
			$owner_hash = $_SESSION['id_hash'];
		$view = $_SESSION['print']['view'];
		$print_prefs = new sched_prefs();			
	}
	if (!$view) 
		$view = 1;
		
	if ($_GET['entire_sched'])
		$entire_sched = true;
	
	$GoToDay = $_GET['GoToDay'];

	$schedule = new schedule($owner_hash,($_SESSION['print']['complete'] ? 1 : NULL));
	
	if ($entire_sched) {
		$schedule->set_current_lot($lot_hash);
		$_GET['GoToDay'] = strtotime($schedule->current_lot['start_date']);
		$_REQUEST['GoToDay'] = $_GET['GoToDay'];
		$GoToDay = $_GET['GoToDay'];
	}
	if (!$GoToDay && ($view == 1 || $view == 2)) 
		$GoToDay = strtotime(date("Y-m-d"));
	if (!$GoToDay && $view == 3) 
		$GoToDay = strtotime(date("Y-m-01"));
	
	$schedDate = $GoToDay;
		
	if ($view == 1 || $view == 2) {
		while (date("w",$schedDate) != 0)
			$schedDate -= 86400;

		$week1 = "Week of ".date("M d, Y",$schedDate);
	} else 
		$week1 = "Month of ".date("F Y",$schedDate);
	
	if ($entire_sched) {
		$start_date = strtotime($schedule->current_lot['start_date']);
		$end_date = strtotime($schedule->current_lot['start_date']." +".max($schedule->current_lot['phase'])." days");
		$today = $start_date;
		$array_cnt = 0;		
		$cnt = 0;
		
		while ($today <= $end_date) {
			$cnt++;
			$dayNumber = $schedule->getDayNumber($start_date,$today);
			$match_task = preg_grep("/^".$dayNumber."$/",$schedule->current_lot['phase']);
			
			if ($match_task) {
				unset($task_array);
				while (list($key,$phase) = each($match_task)) {
					$task_array[] = $schedule->current_lot['task'][$key]."|".$schedule->current_lot['sched_status'][$key]."|-".$schedule->profile_object->getTaskName($schedule->current_lot['task'][$key]);
				}
			} else {
				unset($task_array);
				$task_array[0] = '';
			}
			
			$data[$array_cnt][date("D",$today)] = array('date' 	=> 	date("d",$today),
											 		    'tasks'	=>	$task_array
											 			);
			if ($cnt % 7 == 0) {
				$array_cnt++;
				$cnt = 0;
			} 
				
			$today = strtotime(date("Y-m-d",$today)." +1 day");
		}
	} else
		$data = $schedule->running_schedule($community,array("lots" => array($lot_hash)),($_GET['public_hash'] ? 2 : 1),$print_prefs);
	
	$pdf=new PDF_MC_Table();
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
	$pdf->selectionsheet_section = "running_sched";
	$pdf->community_name = $schedule->active_lots[$community]['community_name'];
	$pdf->lot_no = $lot_no;
	$pdf->week1 = $week1;
	
	//Print a header
	$pdf->Image('images/selectionsheetLogo.png',10,5);
	$pdf->y = 33;
	$pdf->x = 45;
	$pdf->Cell(600,1,$pdf->community_name.", Lot: ".$pdf->lot_no,0,2,"L");
	$pdf->Cell(600,10,$week1,0,2,"L");
	$pdf->x = 10;
	
	$pdf->SetFont('Arial','',9);
	//Table with 20 rows and 4 columns
	$pdf->SetWidths(array(26,26,26,26,26,26,26));
	$pdf->page_header = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
	$pdf->SetAligns(array("C","C","C","C","C","C","C"));
	$pdf->Row($pdf->page_header);
		for ($j = 0; $j <count($data); $j++) 
		{
			$pdf->date_array = array($data[$j]["Sun"]['date'],$data[$j]["Mon"]['date'],$data[$j]["Tue"]['date'],
									 $data[$j]["Wed"]['date'],$data[$j]["Thu"]['date'],$data[$j]["Fri"]['date'],
									 $data[$j]["Sat"]['date']);
			
			$pdf->SetAligns(array("L","L","L","L","L","L","L"));
			$max_count = 0;
			for ($i = 0; $i < 7; $i++) {
			if ($max_count < count($data[$j][$pdf->page_header[$i]]['tasks']))
				$max_count = count($data[$j][$pdf->page_header[$i]]['tasks']);
			}
			//must start a new page
	
			//force a page break  5 is the height of the multicell argument
			if($pdf->GetY()+($max_count*5)>$pdf->PageBreakTrigger) {
				$pdf->CheckPageBreak(10000);
				$pdf->Image('images/selectionsheetLogo.png',10,5);
				$pdf->x = 45;
				$pdf->y = 33;
				$pdf->SetFont('Arial','',12);
				$pdf->Cell(600,1,$pdf->community_name.", Lot: ".$pdf->lot_no,0,2,"L");
				$pdf->Cell(600,10,$pdf->week1,0,2,"L");
				$pdf->x = 10;		
				$pdf->SetFont('Arial','',9);
				$pdf->Row($pdf->page_header);
			}
			
			//print task cells
			for ($day = 0; $day < 7; $day++) {
				$week = $pdf->page_header[$day];
				
				for ($i = 0; $i < count($data[$j][$week]['tasks']); $i++) {
					list($code,$status,$txt) = explode("|",$data[$j][$week]['tasks'][$i]);
					list($r,$g,$b) = $schedule->setColor($status,$code,1);
					if ($i > 0) {
						array_push($msg,$txt);
						array_push($color_array,$r.'|'.$g.'|'.$b);
					} else {
						$msg = array($txt);
						$color_array = array($r.'|'.$g.'|'.$b);
					}
				}
				$m[$day] = $msg;
				$color[$day] = $color_array;				
			}
			$data_array = array($m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6]);
			$data_color = array($color[0], $color[1], $color[2], $color[3], $color[4], $color[5], $color[6]);
			//$pdf->Row($data_array);
			$nb = 0;
			for($i = 0;$i < count($data_array); $i++)
				$nb = max($nb,$pdf->NbLines($pdf->widths[$i],implode("\n",$data_array[$i])));
			
			$h = 5 * $nb;
						
			if ($pdf->CheckPageBreak($h)) {
				//Print a header
				$pdf->Image('images/selectionsheetLogo.png',10,5);
				$pdf->x = 45;
				$pdf->y = 33;
				$pdf->SetFont('Arial','',12);
				$pdf->Cell(600,1,$pdf->community_name.", Lot: ".$pdf->lot_no,0,2,"L");
				$pdf->Cell(600,10,$pdf->week1,0,2,"L");
				$pdf->x = 10;		
				$pdf->SetFont('Arial','',9);
				$pdf->Row($pdf->page_header);
			}		
			$pdf->Row($pdf->date_array);
			
			//if (defined('JEFF'))
				//echo "<pre>".print_r($data_color,1)."</pre>";
			//Draw the cells of the row
			for($i = 0; $i < count($data_array); $i++) {
				$w = $pdf->widths[$i];
				$a = isset($pdf->aligns[$i]) ? $pdf->aligns[$i] : 'L';
				
				$x = $pdf->GetX();
				$y = $pdf->GetY();
				//Draw the border
				$pdf->Rect($x,$y,$w,$h);
				//Print the text
				for ($k = 0; $k < count($data_array[$i]); $k++) {
					list($r,$g,$b) = explode("|",$data_color[$i][$k]);
					$pdf->SetTextColor($r,$g,$b);
					
					$pdf->MultiCell($w,5,$data_array[$i][$k],0,$a);
					$pdf->SetX($x);
				}
				$pdf->SetTextColor(0,0,0);
				$pdf->SetXY($x+$w,$y);
			}
			//Go to the next line
			$pdf->Ln($h);
		}
	
	echo $pdf->Output('asdf.pdf');

} else 
	exit;	
?>