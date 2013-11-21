<?php

class TestlabController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('cellformation', 'ajaxformation', 'cellcat', 'ajaxcat'),
				'roles' => array('testlab'),
				//'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'roles' => array('admin'),
				//'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/*
	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	/**
	 * Allows user to stack mulitple kits that are not associated with a cell yet.
	 */
	public function actionCellFormation()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		$model->fill_date = '>='.date("Y-m-d",time());
		$model->not_formed = 1;
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('cellformation',array(
			'model'=>$model,
		));
	}
	/**
	 * Allows user to stack mulitple kits that are not associated with a cell yet.
	 */
	public function actionAjaxFormation()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$formationCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		$chambers = $_POST['chambers'];
		$channels = $_POST['channels'];
		
		if(count($formationCells)>0)
		{
			$error = null;
			
			foreach($formationCells as $cell_id)
			{
				$model = new TestAssignment();
		 
				if(isset($userIds[$cell_id]) && isset($dates[$cell_id]))
				{
					$model->cell_id = $cell_id;
					$model->channel_id =  $channels[$cell_id];
					$model->chamber_id = $chambers[$cell_id];
					$model->operator_id =  $userIds[$cell_id];
					$model->test_start = $dates[$cell_id];
					$model->is_formation = 1;
					
					if(!$model->save())
					{
						$error = CHtml::errorSummary($model);
					}	
				}
			}
			echo $error;
		}
	}
	
	/**
	 * Allows user to stack mulitple kits that are not associated with a cell yet.
	 */
	public function actionCellCAT()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('cellcat',array(
			'model'=>$model,
		));
	}
	/**
	 * Allows user to stack mulitple kits that are not associated with a cell yet.
	 */
	public function actionAjaxCAT()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$formationCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		$chambers = $_POST['chambers'];
		$channels = $_POST['channels'];
		
		if(count($formationCells)>0)
		{
			$error = null;
			
			foreach($formationCells as $cell_id)
			{
				$model = new FormationDetail();
		 
				if(isset($userIds[$cell_id]) && isset($dates[$cell_id]))
				{
					$model->cell_id = $cell_id;
					$model->channel_id =  $channels[$cell_id];
					$model->chamber_id = $chambers[$cell_id];
					$model->operator_id =  $userIds[$cell_id];
					$model->formation_start = $dates[$cell_id];
					
					if(!$model->save())
					{
						$error = CHtml::errorSummary($model);
					}	
				}
			}
			echo $error;
		}
	}
	
	/**
	 * generates the text fields for the stacker
	 */
	protected function getUserInputTextField($data,$row)
	{
		$disabled = '';
		$userName = '';
		$userId = '';
		
		if (Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer'))
		{
			
		}
		else
		{
			$disabled = 'true';
			$userName = User::getFullNameProper(Yii::app()->user->id);
			$userId = Yii::app()->user->id;
		}
		
		$returnString = CHtml::textField("user_names[$data->id]",$userName,array(
				"style"=>"width:110px;",
				"class"=>"ui-autocomplete-input",
				"autocomplete"=>"off",
				"disabled"=>$disabled,
			));
			
		$returnString.= CHtml::hiddenField("user_ids[$data->id]",$userId, array("class"=>"user-id-input"));
	
		return $returnString;
	}
}