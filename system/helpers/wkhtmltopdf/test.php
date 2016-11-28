<?php

include "vendor/autoload.php";

use mikehaertl\wkhtmlto\Pdf;

$pdf = new Pdf('http://www.google.com');
$pdf->binary = 'D:\wkhtmltopdf\bin\wkhtmltopdf';
$pdf->saveAs('./new.pdf');

echo "done";


?>