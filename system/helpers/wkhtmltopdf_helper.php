<?php

include "wkhtmltopdf/vendor/autoload.php";

use mikehaertl\wkhtmlto\Pdf;

function savePDFByURL($url,$oufile)
{
	$pdf = new Pdf($url);
	$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';
	//$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';
	
	$pdf->saveAs($oufile);
}

function savePDFByContent($content,$oufile)
{
	$pdf = new Pdf();
	$pdf->addPage($content);
	$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';
	//$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';
	$pdf->saveAs($oufile);
}

function downloadPDFByURL($url,$filename="")
{
	$pdf = new Pdf($url);
	$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';
	//$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';

	if($filename == "")
	{
		$pdf->send();
	}
	else
	{
		$pdf->send($filename);
	}
}

function downloadPDFByContent($content,$filename="")
{
	$pdf = new Pdf();
	$pdf->addPage($content);
	$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';
	//$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';
	if($filename == "")
	{
		$pdf->send();
	}
	else
	{
		$pdf->send($filename);
	}
}

?>