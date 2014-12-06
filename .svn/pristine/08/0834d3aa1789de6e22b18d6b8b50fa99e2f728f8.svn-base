<?php
require('../fpdf.php');

class PDF extends FPDF
{
//Load data
function LoadData($file)
{
	//Read file lines
	$data = array();
	$week = array();
	$tasks = array();
	$w = 'sun';
	$i = 0;
	$lines=file($file);
	$data=array();
	for ($i = 0;  $i < 6; $i++) {
		$data[$i]['sun']['tasks'] = array ("task1", "task2");
		$data[$i]['mon']['tasks'] = array ("task1", "task2", "task3", "task4", "task5", "task6", "task7");
		$data[$i]['tue']['tasks'] = array ("task1", "task2", "task3", "task4", "task5");
		$data[$i]['wed']['tasks'] = array ("task1", "task2", "task3", "task4", "task5", "task6");
		$data[$i]['thu']['tasks'] = array ("task1", "task2", "task3", "task4", "task5", "task6", "task7", "task8");
		$data[$i]['fri']['tasks'] = array ("task1", "task2", "task3", "task4");
		$data[$i]['sat']['tasks'] = array ("task1", "task2", "task3");
		$data[$i]['sun']['date'] = date("M d");
		$data[$i]['mon']['date'] = date("M d");
		$data[$i]['tue']['date'] = date("M d");
		$data[$i]['wed']['date'] = date("M d");
		$data[$i]['thu']['date'] = date("M d");
		$data[$i]['fri']['date'] = date("M d");
		$data[$i]['sat']['date'] = date("M d");	
		
	}
	return $data;
}

//Simple table
function BasicTable($header,$data)
{
	//Header
	foreach($header as $col)
		$this->Cell(40,7,$col,1);
	$this->Ln();
	//Data
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(40,6,$col,1);
		$this->Ln();
	}
}

//Better table
function ImprovedTable($header,$data)
{
	//Column widths
	$w=array(25,25,25,25,25,25,25);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	//Data
	
	for ($j = 0; $j <count($data); $j++) 
	{
		$max_count = 0;
		for ($i = 0; $i < 7; $i++) {
		if ($max_count < count($data[$j][$header[$i]]['tasks']))
			$max_count = count($data[$j][$header[$i]]['tasks']);
		}
		//must start a new page
		if (($this->GetY() + ($max_count * 7)) > 270) {	
			while ($this->GetY() + 7 < 280)
				$this->MultiCell($w[0], 7, " ",'LR');
			//print closing line
			//$this->Cell(array_sum($w),0,'','T');
			//$this->Ln();
			//print headers on new page
			for($i=0;$i<count($header);$i++)
				$this->Cell($w[$i],7,$header[$i],1,0,'C');
			$this->Ln();
		}
		//print date headers
		for ($day = 0; $day < 7; $day++) {
			$week = $header[$day];
			$this->Cell($w[$day],7,$data[$j][$week]['date'],1,0,'C');
		}
		$this->Ln();
		//print task cells
		for ($day = 0; $day < 7; $day++) {
			$week = $header[$day];
			$msg = "";
			for ($i = 0; $i < count($data[$j][$week]['tasks']); $i++) {
				if ($i > 0)
					$msg .= "\n".$data[$j][$week]['tasks'][$i];
				else
					$msg = $data[$j][$week]['tasks'][0];
			}
			//$this->MultiCell($w[$day],7,$msg,'LR');
			$this->MultiCell(12, 7, "hi");
			$this->MultiCell(15, 7, "bye");
		}
		
		
		//$this->Cell($w[1],6,$row[1],'LR');
		//$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
		//$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
		//$this->Ln();
	}
	//Closure line
	$this->Cell(array_sum($w),0,'','T');
}

//Colored table
function FancyTable($header,$data)
{
	//Colors, line width and bold font
	$this->SetFillColor(255,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(128,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B');
	//Header
	$w=array(40,35,40,45);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',1);
	$this->Ln();
	//Color and font restoration
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('');
	//Data
	$fill=0;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
		$this->Ln();
		$fill=!$fill;
	}
	$this->Cell(array_sum($w),0,'','T');
}
}

$pdf=new PDF();
//Column titles
$header=array("sun","mon","tue","wed","thu","fri","sat");
//Data loading
$data=$pdf->LoadData("countries.txt");
$pdf->SetFont('Arial','',14);
//$pdf->AddPage();
//$pdf->BasicTable($header,$data);
$pdf->AddPage();
$pdf->ImprovedTable($header,$data);
//$pdf->AddPage();
//$pdf->FancyTable($header,$data);
$pdf->Output();
?>
