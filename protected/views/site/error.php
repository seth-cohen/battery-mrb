<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>


<div style="width:400px; margin:10px auto">
	<?php 	echo CHtml::image(Yii::app()->baseUrl.'/images/'.$code.'.jpg', "$code Error", array('style'=>'width:400px; margin:10px auto'));?>
</div>
