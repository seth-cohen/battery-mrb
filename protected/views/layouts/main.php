<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'activeCssClass'=>'active',
			'activateParents'=>true,
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array(
					'label'=>'Manufacturing', 
					'url'=>array('/manufacturing/index'),
					'active'=>(Yii::app()->controller->id=='cell' || Yii::app()->controller->id=='anode' || Yii::app()->controller->id=='cathode' || Yii::app()->controller->id=='manufacturing'),
					'linkOptions'=>array('id'=>'menuMFG'),
					'itemOptions'=>array('id'=>'itemMFG'),
					'items'=>array(
						array('label'=>'Cells', 'url'=>array('/cell/'), 'active'=>0),
						array('label'=>'Anode Lots', 'url'=>array('/anode/'), 'active'=>0),
						array('label'=>'Cathode Lots', 'url'=>array('/cathode/'), 'active'=>0),
					),
				),
				array(
					'label'=>'TestLab', 
					'url'=>array('/testlab/index'),
					'active'=>(Yii::app()->controller->id=='channel' || Yii::app()->controller->id=='chamber' || Yii::app()->controller->id=='cycler' || Yii::app()->controller->id=='testlab'),
					'linkOptions'=>array('id'=>'menuTestLab'),
					'itemOptions'=>array('id'=>'itemTestLab'),
					'items'=>array(
						array('label'=>'Channels', 'url'=>array('/channel/')),
						array('label'=>'Chambers', 'url'=>array('/chamber/'), 'active'=>0),
						array('label'=>'Cyclers', 'url'=>array('/cycler/'), 'active'=>0),
					),
				),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Yardney Technical Products.<br/>
		All Rights Reserved.<br/>
		Contact <a href="mailto:scohen@yardney.com?Subject=YTPDB%20Question" target="_top">Seth Cohen</a> with all correspondances
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
