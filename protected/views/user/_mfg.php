<?php
/* @var $this UserController */
/* @var $model User */
?>

<div class="mfg-user">
<h2>Manufacturing Employee Details</h2>
<?php foreach($model->cellsStacked as $cell): ?>
		<p> <?php echo 'stacked '.$cell->serial_num;  ?> </p>
<?php endforeach; ?>
</div>
