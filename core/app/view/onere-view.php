
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/onere-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
  </ul>
</div>
<h1>Resumen de Reabastecimiento</h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;
?>
<?php
if(isset($_COOKIE["selled"])){
	foreach ($operations as $operation) {
//		print_r($operation);
		$qx = OperationData::getQYesF($operation->product_id);
		// print "qx=$qx";
			$p = $operation->getProduct();
		if($qx==0){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";			
		}else if($qx<=$p->inventary_min/2){
			echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
		}else if($qx<=$p->inventary_min){
			echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
		}
	}
	setcookie("selled","",time()-18600);
}

?>
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
	<td style="width:150px;"><b>Proveedor</b></td>
	<td><?php echo $client->name." ".$client->lastname;?></td>
</tr>
<?php endif; ?>

<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
	<td><b>Atendido por</b></td>
	<td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<tr>
    <td><b>Remito</b></td>
    <td><?php echo $sell->remito;?></td>
</tr>
<tr>
    <td><b>Fecha</b></td>
    <td><?php echo $sell->fecharemito;?></td>
</tr>
<?php endif; ?>


</table>
<br><table class="table table-bordered table-hover">
	<thead>
		<th>Codigo</th>
		<th>Cantidad</th>
		<th>Nombre del Producto</th>

	</thead>
<?php
	foreach($operations as $operation){
		$product  = $operation->getProduct();
?>
<tr>
	<td><?php echo $product->id ;?></td>
	<td><?php echo $operation->q ;?></td>
	<td><?php echo $product->name ;?></td>
</tr>
<?php
	}
?>
</table>
<br><br><h1>Reabastecimiento realizado!</h1>
	<?php

?>	
<?php else:?>
	501 Internal Error
<?php endif; ?>