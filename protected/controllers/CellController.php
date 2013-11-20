<?php

class CellController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'multistackcells', 'ajaxstackcells'),
				'roles' => array('manufacturing'),
				//'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','ajaxmfgupdate','downloadlist'),
				'roles' => array('admin'),
				//'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		
		$this->render('view',array(
			'model'=>$model,
			'kit'=>$model->kit,
			'celltype'=>$model->kit->celltype,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Cell;
		$kit = new Kit;
		$celltype = new Celltype;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cell']))
		{
			$model->attributes=$_POST['Cell'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'kit'=>$kit,
			'celltype'=>$celltype,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cell']))
		{
			$model->attributes=$_POST['Cell'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'kit'=>$model->kit,
			'celltype'=>$model->kit->celltype,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Cell');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cell the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cell::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cell $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cell-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Allows user to stack mulitple kits that are not associated with a cell yet.
	 */
	public function actionMultiStackCells()
	{
		$model=new Kit('search');
		$model->unsetAttributes();  // clear any default values
		$model->is_stacked = 0;
		
		if(isset($_GET['Kit']))
		{
			$model->attributes=$_GET['Kit'];
		}
				
		$this->render('stackcells',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Allows user to stack mulitple kits that are not associated with a cell yet.
	 */
	public function actionAjaxStackCells()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$stackedKits = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		$refnumIds = $_POST['refnumIds'];
		$eaps = $_POST['eaps'];
		
		if(count($stackedKits)>0)
		{
			$error = null;
			
			foreach($stackedKits as $kitId)
			{
				$model = new Cell('stack');
				// Uncomment the following line if AJAX validation is needed
		 		$this->performAjaxValidation($model);
		 
				$model->kit_id = $kitId;
				if(isset($userIds[$kitId]) && isset($dates[$kitId]))
				{
					$model->stacker_id = $userIds[$kitId];
					$model->stack_date = $dates[$kitId];
					$model->ref_num_id = $refnumIds[$kitId]?null:$refnumIds[$kitId];
					$model->eap_num = $eaps[$kitId]?null:$eaps[$kitId];
					
					if($model->save())
					{
						$kit = Kit::model()->findByPk($kitId);
						$kit->is_stacked = 1;
						$kit->save()?'':var_dump($kit->getErrors());
						
					}
					else 
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
	protected function getStackerTextField($data,$row)
	{
		$disabled = '';
		$userName = '';
		
		if (Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer'))
		{
			
		}
		else
		{
			$disabled = '"disabled"=>true';
			$userName = User::getFullNameProper(Yii::app()->user->id);
		}
		
		$returnString = CHtml::textField("user_names[$data->id]",$userName,array(
				"style"=>"width:150px;",
				"class"=>"ui-autocomplete-input",
				"autocomplete"=>"off",'.$disabled.'
			));
			
		$returnString.= CHtml::hiddenField("user_ids[$data->id]");
	
		return $returnString;
	}
    
	/**
	 * Performs the AJAX update of the detailView on the cellview.
	 * @param Cell $model the model to be validated
	 */
	public function actionAjaxMFGUpdate($id=null)
	{	
		/* load cell detail information */
		if($id == null)
		{
			echo 'hide';
		}
		else
		{
			$model = $this->loadModel($id);
			
			$this->renderPartial('_ajaxcelldetail', array(
					'model'=>$model,
				), 
				false, 
				true
			);
		}
	}
	
	/**
	 *  user download of csv data for selected cells 
	 *  
	 */
	public function actionDownloadList()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Cell']))
			$model->attributes=$_GET['Cell'];
			
		$data = array();
		$dataProvider = $model->search();
		$dataProvider->setPagination(false);
		
		$cells = $dataProvider->getData();
		foreach($cells as $cell)
		{
			$data[] = array($cell->stacker->getFullName(), $cell->stack_date);
		}
		
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header("Content-Disposition: attachment; filename=file.csv");
		header("Pragma: no-cache");
		
		$this->outputCSV($data);
	}
		
	/* TODO move this to an extension or component */
	function outputCSV($data) {
	    $output = fopen("php://output", "w");
	    foreach ($data as $row) {
	        fputcsv($output, $row);
	    }
	    fclose($output);
	}
}
