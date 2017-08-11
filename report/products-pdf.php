<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";

require '../fpdf/fpdf.php';

$products = ProductData::getAll();

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
    $this->Image('../fpdf/tutorial/logo_pb.png',10,8,33);

    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(40);
    $this->Cell(120,10,'Sistema de Stock',0,0,'C');
    // Arial 10
    $this->SetFont('Arial','',10);
    // Fecha y hora del reporte
    $this->Cell(30,5,date("Y-m-d"),0,1,'R');
    $this->Cell(190,5,date("H:i:s"),0,1,'R');

    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Título
    $this->Cell(40);
    $this->Cell(120,10,'- Productos -','B',0,'C');

    $this->Ln(15);

    // Arial 12 Bold
    $this->SetFont('Arial','B',12);
    $this->Cell(0,10, utf8_decode('Listado Alfabético'), 'B',0,'L');
    // Salto de línea
    $this->Ln(12);

    // Titulos
    // Arial 12 Bold
    $this->SetFont('Arial','B',10);
    $w = array(10, 70, 15, 25, 30, 27, 12);
    $header= array('Id', 'Nombre', 'Unidad', 'Presentación', 'Categoría', 'Mín. inventario','Activo');
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,utf8_decode($header[$i]),1,0,'C',false);
    $this->Ln();
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}
}



$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$fill = false;
$w = array(10, 70, 15, 25, 30, 27, 12);
foreach($products as $product)
{
    $pdf->Cell($w[0],6,$product->id,'LR',0,'L',$fill);
    $pdf->Cell($w[1],6,$product->name,'LR',0,'L',$fill);
    $pdf->Cell($w[2],6,$product->unit,'LR',0,'L',$fill);
    $pdf->Cell($w[3],6,$product->presentation,'LR',0,'L', $fill);
    if($product->category_id!=null){
        $pdf->Cell($w[4],6,$product->getCategory()->name,'LR',0,'L',$fill);
    }else{
        $pdf->Cell($w[4],6,"---",'LR',0,'L',$fill);
    }
    $pdf->Cell($w[5],6,$product->inventary_min,'LR',0,'L',$fill);
    if($product->is_active){
        $pdf->Cell($w[6],6,"Si",'LR',0,'L',$fill);
    }else{
        $pdf->Cell($w[6],6,"No",'LR',0,'L',$fill);
    }
    $pdf->Ln();
}
$pdf->Output();
?>
