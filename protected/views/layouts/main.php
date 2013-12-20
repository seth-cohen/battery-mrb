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

	<div id="nav-bar">
		<?php $this->widget('application.extensions.mbmenu.MbMenu',array(
			//'activeCssClass'=>'active',
			//'activateParents'=>true,
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array(
					'label'=>'Manufacturing',
					'url'=>array('/manufacturing/index/'), 
					'active'=>(strpos( Yii::app()->request->url, 'manufacturing') > 0),
					'visible'=>!Yii::app()->user->isGuest,
					'items'=>array(
						array('label'=>'Electrodes', 'items'=>array(
							array('label'=>'Create Electrode Lot', 'url'=>array('electrode/create'), 'active'=>0),
							array('label'=>'Calender Electrode Lot', 'url'=>array('electrode/calender'), 'active'=>0),
							array('label'=>'Bag Cathode Lot', 'url'=>array('electrode/bag'), 'active'=>0),
							array('label'=>'View Electrode Lots', 'url'=>array('electrode/index'), 'active'=>0),
						)),
						array('label'=>'Kits', 'items'=>array(
							array('label'=>'Create New Kit', 'url'=>array('kit/create'), 'active'=>0),
							array('label'=>'Create Kits (Multi)', 'url'=>array('kit/multicreate'), 'active'=>0),
							array('label'=>'View All Kits', 'url'=>array('kit/index'), 'active'=>0),
						)),
						array('label'=>'Cells', 'items'=>array(
							array('label'=>'Stack Cells (Multi)', 'url'=>array('cell/multistackcells'), 'active'=>0),
							array('label'=>'Inspect Cells (Multi)', 'url'=>array('cell/multiinspectcells'), 'active'=>0),
							array('label'=>'Laser Weld Cells (Multi)', 'url'=>array('cell/multilasercells'), 'active'=>0),
							array('label'=>'Fill Cells (Multi)', 'url'=>array('cell/multifillcells'), 'active'=>0),
							array('label'=>'Fillport Weld Cells (Multi)', 'url'=>array('cell/multitipoffcells'), 'active'=>0),
							array('label'=>'View All Cells', 'url'=>array('cell/index'), 'active'=>0),
						)),
						array('label'=>'Batteries', 'items'=>array(
							array('label'=>'Battery EAP', 'url'=>array('/manufacturing/anode/createanodelot'), 'active'=>0),
							array('label'=>'View Anode Lots', 'url'=>array('/manufacturing/anode/viewanodelots'), 'active'=>0),
							array('label'=>'Create Cathode Lot', 'url'=>array('/manufacturing/createcathodelot'), 'active'=>0),
							array('label'=>'View Cathode Lots', 'url'=>array('/manufacturing/viewcathodelots'), 'active'=>0),
						)),
					),
				),
				array(
					'label'=>'TestLab', 
					'url'=>array('/testlab/index/'),
					'active'=>(strpos( Yii::app()->request->url, 'testlab') > 0),
					'visible'=>!Yii::app()->user->isGuest,
					'items'=>array(
						array('label'=>'Testing', 'items'=>array(
							array('label'=>'Put Cells on Formation', 'url'=>array('testlab/cellformation'), 'active'=>0),
							array('label'=>'View All Cells on Formation', 'url'=>array('testlab/formationindex'), 'active'=>0),
							array('label'=>'Put Cells on CAT', 'url'=>array('testlab/cellcat'), 'active'=>0),
							array('label'=>'View All Cells on CAT', 'url'=>array('testlab/catindex'), 'active'=>0),
							array('label'=>'View All Cells on Test', 'url'=>array('testlab/cellindex'), 'active'=>0),
							array('label'=>'Change Channel Assignment', 'url'=>array('testlab/testreassignment'), 'active'=>0),
						)),	
						array('label'=>'Channels', 'url'=>array('/channel/')),
						array('label'=>'Chambers', 'url'=>array('/chamber/'), 'active'=>0),
						array('label'=>'Cyclers', 'url'=>array('/cycler/'), 'active'=>0),
					),
				),
				array(
					'label'=>'Admin', 
					'url'=>array('/admin/index/'),
					'active'=>(strpos( Yii::app()->request->url, 'user') > 0),
					'visible'=>Yii::app()->user->checkAccess('admin'),
					'items'=>array(
						array('label'=>'View Users', 'url'=>array('user/admin')),
					),
				),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Register', 'url'=>array('/site/register'), 'visible'=>Yii::app()->user->isGuest),
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
