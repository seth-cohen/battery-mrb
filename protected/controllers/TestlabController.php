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
				'actions'=>array('index','formationindex', 'catindex', 'conditioningindex', 'testindex'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(
					'cellformation', 'ajaxformation', 
					'cellcat', 'ajaxcat', 
					'cellconditioning', 'ajaxconditioning',
					'testreassignment', 'ajaxtestreassignment',
					'storage', 'ajaxstorage',
					'deliverforbattery', 'ajaxdelivery'
				),
				'roles'=>array('admin', 'engineering', 'testlab', 'quality'),
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
	
	/**
	 * Lists all cells that are actively on formation.
	 */
	public function actionFormationIndex()
	{
		$model=new TestAssignment('search');
		$model->unsetAttributes();  // clear any default values
		
		/* uses TestAssignment->search() to find all active formation
		 * test assignments	 */
		$model->type_search = TestAssignment::FORMATION;
		$model->is_active = 1;
		
		if(isset($_GET['TestAssignment']))
		{
			$model->attributes=$_GET['TestAssignment'];
		}
				
		$this->render('formationindex',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Lists all cells that are actively on formation.
	 */
	public function actionCATIndex()
	{
		$model=new TestAssignment('search');
		$model->unsetAttributes();  // clear any default values
		
		/* uses TestAssignment->search() to find all active formation
		 * test assignments	 */
		$model->type_search = TestAssignment::CAT;
		$model->is_active = 1;
		
		if(isset($_GET['TestAssignment']))
		{
			$model->attributes=$_GET['TestAssignment'];
		}
				
		$this->render('catindex',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Lists all cells that are actively on formation.
	 */
	public function actionConditioningIndex()
	{
		$model=new TestAssignment('search');
		$model->unsetAttributes();  // clear any default values
		
		/* uses TestAssignment->search() to find all active formation
		 * test assignments	 */
		$model->type_search = TestAssignment::CONDITIONING;
		$model->is_active = 1;
		
		if(isset($_GET['TestAssignment']))
		{
			$model->attributes=$_GET['TestAssignment'];
		}
				
		$this->render('conditioningindex',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Lists all test assignments that have ever been.
	 */
	public function actionTestIndex()
	{
		$model=new TestAssignment('search');
		$model->unsetAttributes();  // clear any default values
		
		/* uses TestAssignment->search() to find all */
		
		if(isset($_GET['TestAssignment']))
		{
			$model->attributes=$_GET['TestAssignment'];
		}
				
		$this->render('alltestassignments',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Allows user to put multiple cells on formation as long as they have been
	 * filled today or yesterday
	 */
	public function actionCellFormation()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		/* Uses cell->searchUnFormed to find all cells with no
		 * test assignments and that were filled today or yesterday */
		$model->fill_date = '>='.date("Y-m-d",mktime(0,0,0, date('m'), date('d')-1, date('Y')));
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('cellformation',array(
			'model'=>$model,
		));
	}
	
	/**
	 * This is the ajax action to save the formation test assignments
	 * TODO it should be possible to combine CAT and FORM into one action
	 * by passing a parameter
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
		$times = $_POST['times'];
		$chambers = $_POST['chambers'];
		$tempChannels = $_POST['channels'];
		
		/* make sure there are no duplicate channel selections */
		$channels = array();
		foreach($formationCells as $cell_id)
		{
			$channels[$cell_id] = $tempChannels[$cell_id];		
		}

		if (count($channels) !== count(array_unique($channels)))
		{ /* then we have duplicates set error and bail */		
			$model = new Channel();
			$model->addError('channel_error', 'Duplicate Channel Selection!');
			echo CHtml::errorSummary($model);
			Yii::app()->end();
		}
		
		if(count($formationCells)>0)
		{
			$cellsFormation = array();
			
			foreach($formationCells as $cell_id)
			{
				$tempTest = new TestAssignment;
				
				$splitTime = array_map('trim',explode(':', $times[$cell_id]));
				$date = strtotime($dates[$cell_id]);
				
				if($splitTime[0] < 0 || $splitTime[0] > 23 || $splitTime[1] < 0 || $splitTime[1] > 59 
				  ||!is_numeric($splitTime[0]) || !is_numeric($splitTime[1]) )
				{
					$model = new Channel();
					$model->addError('channel_error', 'There was an error in your time formatting!!');
					echo CHtml::errorSummary($model);
					Yii::app()->end();
				}
				
				$tempTest->cell_id = $cell_id;
				$tempTest->channel_id = $channels[$cell_id];
				$tempTest->chamber_id = $chambers[$cell_id];
				$tempTest->operator_id = $userIds[$cell_id];
				$tempTest->test_start = $dates[$cell_id];
				$tempTest->test_start_time = mktime($splitTime[0], $splitTime[1], 0, date('n',$date), date('j',$date), date('Y',$date));
				$tempTest->is_formation = 1;
				
				$cellsFormation[$cell_id] = $tempTest;
			}
			
			$result = TestAssignment::putCellsOnTest($cellsFormation);  
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * Allows user to put multiple cells on CAT as long as they have been
	 * filled today or yesterday
	 */
	public function actionCellCAT()
	{
		$model=new Cell('search'); 
		$model->unsetAttributes();  // clear any default values
		
		/* uses cell->searchFormed() to get all cells that have been through 
		 * formation and are not actively on test */
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('cellcat',array(
			'model'=>$model,
		));
	}
	
	/**
	 * This is the ajax action to save the CAT test assignments
	 * TODO it should be possible to combine CAT and FORM into one action
	 * by passing a parameter
	 */
	public function actionAjaxCAT()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$catCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		$chambers = $_POST['chambers'];
		$tempChannels = $_POST['channels'];
		
		/* make sure there are no duplicate channel selections */
		$channels = array();
		foreach($catCells as $test_id)
		{
			$channels[$test_id] = $tempChannels[$test_id];		
		}

		if (count($channels) !== count(array_unique($channels)))
		{ /* then we have duplicates set error and bail */		
			$model = new Cell();
			$model->addError('channel_error', 'Duplicate Channel Selection!');
			echo CHtml::errorSummary($model);
			Yii::app()->end();
		}
		
		if(count($catCells)>0)
		{
			$cellsCAT = array();
			
			foreach($catCells as $cell_id)
			{	
				$tempTest = new TestAssignment;
				
				$tempTest->cell_id = $cell_id;
				$tempTest->channel_id = $channels[$cell_id];
				$tempTest->chamber_id = $chambers[$cell_id];
				$tempTest->operator_id = $userIds[$cell_id];
				$tempTest->test_start = date("Y-m-d",time());
				$tempTest->test_start_time = time();
				$tempTest->is_formation = 0;
					
				$cellsCAT[$cell_id] = $tempTest;
				
			}
			
			$result = TestAssignment::putCellsOnTest($cellsCAT);  
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * Allows user to put multiple cells on conditioning charge for battery assembly
	 */
	public function actionCellConditioning()
	{
		$model=new Cell('search'); 
		$model->unsetAttributes();  // clear any default values
		
		/* uses cell->searchForAssembly() to get all cells that have been through 
		 * formation and are not actively on test */
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('cellconditioning',array(
			'model'=>$model,
		));
	}
	
	/**
	 * This is the ajax action to save the Conditioning test assignments
	 * TODO it should be possible to combine CAT and FORM into one action
	 * by passing a parameter
	 */
	public function actionAjaxConditioning()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$conditionCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		$chambers = $_POST['chambers'];
		$tempChannels = $_POST['channels'];
		
		/* make sure there are no duplicate channel selections */
		$channels = array();
		foreach($conditionCells as $test_id)
		{
			$channels[$test_id] = $tempChannels[$test_id];		
		}

		if (count($channels) !== count(array_unique($channels)))
		{ /* then we have duplicates set error and bail */		
			$model = new Cell();
			$model->addError('channel_error', 'Duplicate Channel Selection!');
			echo CHtml::errorSummary($model);
			Yii::app()->end();
		}
		
		if(count($conditionCells)>0)
		{
			$cellsCondition = array();
			
			foreach($conditionCells as $cell_id)
			{	
				$tempTest = new TestAssignment;
				
				$tempTest->cell_id = $cell_id;
				$tempTest->channel_id = $channels[$cell_id];
				$tempTest->chamber_id = $chambers[$cell_id];
				$tempTest->operator_id = $userIds[$cell_id];
				$tempTest->test_start = date("Y-m-d",time());
				$tempTest->test_start_time = time();
				$tempTest->is_formation = 0;
				$tempTest->is_conditioning = 1;
					
				$cellsCondition[$cell_id] = $tempTest;
				
			}
			
			$result = TestAssignment::putCellsOnTest($cellsCondition);  
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * This action will allow the operator to change the channel that a
	 * cell is currently testing on... gives the operator a chance to 
	 * label that channel as out of commission
	 */
	public function actionTestReassignment()
	{
		$model=new TestAssignment('search');
		$model->unsetAttributes();  // clear any default values
		
		/* uses TestAssignment->search() to find all active 
		 * test assignments	 */
		$model->is_active = 1;
		
		if(isset($_GET['TestAssignment']))
		{
			$model->attributes=$_GET['TestAssignment'];
		}
				
		$this->render('testreassignment',array(
			'model'=>$model,
		));
		
	} 
	
	/**
	 * This is the ajax action to save the testassignment channel
	 * reassignments.  User has option to mark the channel as 'BAD' 
	 * or out of commission.
	 */
	public function actionAjaxTestReassignment()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$changedTests = $_POST['autoId'];
		
		$chambers = $_POST['chambers'];
		$tempChannels = $_POST['channels'];
		$userIds = $_POST['user_ids'];
		$cellIds = $_POST['cell_ids'];
		$is_formation = $_POST['is_formation'];
		
		/* make sure there are no duplicate channel selections */
		$channels = array();
		foreach($changedTests as $test_id)
		{
			$channels[$test_id] = $tempChannels[$test_id];		
			/* array of testAssignments where channel needs to be set out of commission */
			$testArray = isset($_POST['badId'])?$_POST['badId']:array();
			$badTestChannels[$test_id] = in_array($test_id, $testArray); 
		}

		if (count($channels) !== count(array_unique($channels)))
		{ /* then we have duplicates set error and bail */		
			$model = new Cell();
			$model->addError('channel_error', 'Duplicate Channel Selection!');
			echo CHtml::errorSummary($model);
			Yii::app()->end();
		}
		
		if(count($changedTests)>0)
		{
			$testsChanged = array();
			
			foreach($changedTests as $test_id)
			{
				$tempTest = new TestAssignment;
				
				$tempTest->cell_id = $cellIds[$test_id];
				$tempTest->channel_id = $channels[$test_id];
				$tempTest->chamber_id = $chambers[$test_id];
				$tempTest->operator_id = $userIds[$test_id];
				$tempTest->test_start = date("Y-m-d",time());
				$tempTest->is_formation = $is_formation[$test_id];
					
				$testsChanged[$test_id] = $tempTest;
			}
			
			$result = TestAssignment::channelReassignment($testsChanged, $badTestChannels);  
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * This action will allow the operator to move a cell from testassignment to storage
	 * this will clear the channel and set the testassignment to inactive
	 */
	public function actionStorage()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		/* uses Cell->searchForStorage() to find all cells filled with no battery_id
		 * or with a battery_id but the battery hasn't been built.
		 */
		$model->filler_id = '<>1';
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('storage',array(
			'model'=>$model,
		));
		
	} 
	
	/**
	 * This is the ajax action to move a cell to storage
	 */
	public function actionAjaxStorage()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$storageCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$locations = $_POST['locations'];
		
		if(count($storageCells)>0)
		{
			$cellStorageLocations= array(); // associative array of cell_id => location string
			
			foreach($storageCells as $cell_id)
			{
				if(!$locations[$cell_id])
				{
					$model = new StorageLocation();
					$model->addError('location_empty', 'Must Select a Storage Location for All Cells');
					echo CHtml::errorSummary($model);
					Yii::app()->end();
				}
				
				$storageLocation = StorageLocation::model()->findByPk($locations[$cell_id]);
				$cellStorageLocations[$cell_id] = $storageLocation->name;  					
			}
			
			$result = Cell::moveCellsToStorage($cellStorageLocations);  
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * This action will allow the operator to move a cell to Battery Assembly
	 * this will clear the channel and set the testassignment to inactive
	 */
	public function actionDeliverForBattery()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		/* uses Cell->searchForDelivery() to find all cells filled with a battery_id
		 *  but the battery hasn't been built yet.
		 */
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('delivery',array(
			'model'=>$model,
		));
		
	} 
	
	/**
	 * This is the ajax action to deliver cells to Battery Assembly
	 */
	public function actionAjaxDelivery()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$deliveredCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		
		if(count($deliveredCells)>0)
		{	
			$result = Cell::deliverCellsToAssembly($deliveredCells);  
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * generates the text fields for the operator
	 */
	protected function getUserInputTextField($data,$row)
	{
		$disabled = '';
		$userName = '';
		$userId = '';
		
		if (Yii::app()->user->checkAccess('testlab supervisor, testlab'))
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
				"class"=>"autocomplete-user-input",
				"autocomplete"=>"off",
				"disabled"=>$disabled,
			));
			
		$returnString.= CHtml::hiddenField("user_ids[$data->id]",$userId, array("class"=>"user-id-input"));
	
		return $returnString;
	}
	
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}