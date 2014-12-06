<?php
require('../fpdf.php');


class PDF_MC_Table extends FPDF
{
	var $widths;
	var $aligns;
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	
	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
	
	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
}



$data=array();
	$r = 0;
	for ($i = 0;  $i < 6; $i++) {
		$data[$i]['sun']['tasks'] = array ("task1", "task2");
		$data[$i]['mon']['tasks'] = array ("task1", "task2", "task3", "task4", "task5", "task6", "task7");
		$data[$i]['tue']['tasks'] = array ("task1", "task2", "task3", "task4", "task5");
		$data[$i]['wed']['tasks'] = array ("task1", "task2", "task3", "task4", "task5", "task6");
		$data[$i]['thu']['tasks'] = array ("task1", "task2", "task3", "task4", "task5", "task6", "task7", "task8");
		$data[$i]['fri']['tasks'] = array ("task1", "task2", "task3", "task4");
		$data[$i]['sat']['tasks'] = array ("task1", "task2", "task3");
		$data[$i]['sun']['date'] =  $r; $r++;
		$data[$i]['mon']['date'] =  $r; $r++;
		$data[$i]['tue']['date'] =  $r; $r++;
		$data[$i]['wed']['date'] =  $r; $r++;
		$data[$i]['thu']['date'] =  $r; $r++;
		$data[$i]['fri']['date'] =  $r; $r++;
		$data[$i]['sat']['date'] =  $r; $r++;
		
	}



$pdf=new PDF_MC_Table();
$pdf->Open();
$pdf->AddPage();
$pdf->SetFont('Arial','',14);
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(26,26,26,26,26,26,26));
$header=array("sun","mon","tue","wed","thu","fri","sat");

		$pdf->Row($header);
	//Data
	
	for ($j = 0; $j <count($data); $j++) 
	{
		$max_count = 0;
		for ($i = 0; $i < 7; $i++) {
		if ($max_count < count($data[$j][$header[$i]]['tasks']))
			$max_count = count($data[$j][$header[$i]]['tasks']);
		}
		//must start a new page

		//force a page break  5 is the height of the multicell argument
		if($pdf->GetY()+($max_count*5)>$pdf->PageBreakTrigger) {
			$pdf->CheckPageBreak(10000);
			$pdf->Row($header);
		}

		$pdf->Row(array($data[$j]["sun"]['date'],$data[$j]["mon"]['date'],$data[$j]["tue"]['date'],
						$data[$j]["wed"]['date'],$data[$j]["thu"]['date'],$data[$j]["fri"]['date'],
						$data[$j]["sat"]['date']));

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
			$m[$day] = $msg;
			
		}
		$pdf->Row(array($m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6]));

		
		
		//$this->Cell($w[1],6,$row[1],'LR');
		//$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
		//$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
		//$this->Ln();
	}

$pdf->Output();
?> 