<?php
include("../../php/config.php"); 

$query = "SELECT * FROM producto";
$result = $cx->query($query);

if ($result->num_rows > 0) {
    $listaProductos = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $listaProductos = [];
}
?>

<?php
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF PRODUCTOS</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .page-break {
            page-break-before: always;
        }
        .page-number {
            position: absolute;
            top: -15px;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #000;
        }
        .logo {
            width: 200px;
            height: 100px;
        }
      
        #tabla{
            border: 2px solid deepskyblue;
        }
        td{
            border: 2px solid deepskyblue;
        }
        tr{
            border: 2px solid deepskyblue;
        }
    </style>
</head>
<body class="text-center">
    <div class="page-number"></div>
    <!-- Ruta completa a la imagen pública de Spiderman -->
    <img src="https://i.pinimg.com/736x/4b/9b/4a/4b9b4ae44da084048429ef33f3506c1c.jpg" alt="Logo" class="logo" >
    <h1>REPORTE DE PRODUCTOS</h1>

    <table class="table w-75 m-auto mt-4 table-striped-columns table-dark table-bordered border-primary text-center" id="tabla">
        <tr>
            <td>ID</td>
            <td>NOMBRE PRODUCTO</td>
            <td>PRECIO</td>
            <td>CANTIDAD</td>
            <td>PROVEEDOR</td>
            <td>UNIDAD MEDIDA</td>
            <td>CATEGORÍA</td>
        </tr>

        <?php 
        $i = 0;
        foreach($listaProductos as $item): 
        $i++;?>
        <tr>
            <td><?php echo $item['id']; ?></td>
            <td><?php echo $item['nombrep']; ?></td>
            <td><?php echo $item['precio']; ?></td>
            <td><?php echo $item['cantidad']; ?></td>
            <td><?php echo $item['proveedor']; ?></td>
            <td><?php echo $item['unidadm']; ?></td>
            <td><?php echo $item['categoria']; ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="6">TOTAL:</td>
            <td><?php echo $i; ?></td>
        </tr>
    </table>
    
    <div class="page-break"></div>
    
</body>
</html>

<?php
$html = ob_get_clean();
require_once '../libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('letter');
$dompdf->render();

// Agregar numeración de páginas
$canvas = $dompdf->getCanvas();
$canvas->page_script(function($pageNumber, $pageCount, $canvas, $fontMetrics) {
    $text = "Página $pageNumber de $pageCount";
    $font = $fontMetrics->getFont("Helvetica", "normal");
    $size = 10;
    $width = $fontMetrics->getTextWidth($text, $font, $size);
    $canvas->text(15, 15, $text, $font, $size);
});

// Visualizar el PDF en el navegador
$dompdf->stream("archivo_.pdf", array("Attachment" => false));
?>