<?php

class ElectrodeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	// Uncomment the following methods and override them if needed
	
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index', 'ajaxGetElectrodeCells','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create', 'update'),
				'roles'=>array('manufacturing'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin', 'uploadelectrodes'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionCreate()
	{
		$model = new Electrode;
		$model->coat_date = date("Y-m-d",time());
		
		if(!Yii::app()->user->checkAccess('manufacturing supervisor') && !Yii::app()->user->checkAccess('manufacturing engineer'))
		{
			$model->coater_id = Yii::app()->user->id;
		}
		
		// uncomment the following code to enable ajax-based validation
	    if(isset($_POST['ajax']) && $_POST['ajax']==='createelectrode-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    
	
	    if(isset($_POST['Electrode']))
	    {
	        $model->attributes=$_POST['Electrode'];
	   	 	if($model->save())
	        {
	            // form inputs are valid, do something here
	            $this->redirect(array('view','id'=>$model->id));
	        }
	    }
	    $this->render('createlot',array('model'=>$model));
	}
	
	public function actionIndex()
	{

		$model=new Electrode('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Electrode']))
			$model->attributes=$_GET['Electrode'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = Electrode::model()->with(array(
			'kits'=>array('with'=>array(
				'anodes',
				'cathodes',
				'celltype',
				'cell'=>array('select'=>'id, location, stack_date')
			)),
			'coater',
		))->findByPk($id);
		
		if ($model == null)
		{
			$model = Electrode::model()->findByPk($id);
		}
		
		$kits = array();
		if(!empty($model->kits))
		{
			foreach($model->kits as $key=>$kit){
				if($kit->cell == null)
				{
					$kits[] = array('num'=>$key+1, 'kit'=>$kit->getFormattedSerial(), 'location'=>'Not Stacked', 'id'=>999999999, 'stack_date'=>'Not Stacked');
				}
				else 
				{
					$kits[] = array('num'=>$key+1, 'kit'=>$kit->getFormattedSerial(), 'location'=>$kit->cell->location, 'id'=>$kit->cell->id, 'stack_date'=>$kit->cell->stack_date);
				}
				
			}
		}
		
		$kitDataProvider = new CArrayDataProvider($kits);
		
		$this->render('viewlot',array(
			'model'=>$model,
			'kitDataProvider'=>$kitDataProvider,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=Electrode::model()->findByPk($id);
		$model->coater_search = User::getFullNameProper($model->coater_id);
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Electrode']))
		{
			$model->attributes=$_POST['Electrode'];
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('updatelot',array('model'=>$model,));
	}
	
	/**
	 * creates electrode lots from the uploaded CSV file
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionUploadElectrodes()
	{
		/* this cannot be an AJAX request... uploading files from AJAX is not straight forward, though it 
		 * is possible with the use of an iframe and some javascript
		 */
		$electrodeModel=new Electrode;
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($electrodeModel);

		if(isset($_FILES['Uploaded']))
		{
			if(Electrode::uploadFromCSV($electrodeModel, $_FILES['Uploaded']))
			{	 
				$this->redirect(array('index'));
			}
		}
		else
		{ /* go try the cell selection again */
			$electrodeModel->addError('selection_error', 'No file uploaded');
		}
		
		
		$this->render('uploadelectrodes',array(
			'electrodeModel'=>$electrodeModel,
		));
	}
		
/**
	 * Performs the AJAX update of the detailView on the cellview.
	 * @param Cell $model the model to be validated
	 */
	public function actionAjaxGetElectrodeCells($id=null)
	{	
		/* load cell detail information */
		if($id == null)
		{
			echo 'hide';
		}
		else
		{
			$model = Electrode::model()->with(array(
				'kits'=>array('with'=>array(
					'anodes',
					'cathodes',
					'celltype',
					'cell'=>array('select'=>'id, location, stack_date')
				)),
				'coater',
			))->findByPk($id);
			
			if ($model == null)
			{
				$model = Electrode::model()->findByPk($id);
			}
			
			$kits = array();
			if(!empty($model->kits))
			{
				foreach($model->kits as $key=>$kit){
					if($kit->cell == null)
					{
						$kits[] = array('num'=>$key+1, 'kit'=>$kit->getFormattedSerial(), 'location'=>'Not Stacked', 'id'=>999999999, 'stack_date'=>'Not Stacked', 'anodes'=>$kit->getAnodeList(),  'cathodes'=>$kit->getCathodeList());
					}
					else 
					{
						$kits[] = array('num'=>$key+1, 'kit'=>$kit->getFormattedSerial(), 'location'=>$kit->cell->location, 'id'=>$kit->cell->id, 'stack_date'=>$kit->cell->stack_date,  'anodes'=>$kit->getAnodeList(), 'cathodes'=>$kit->getCathodeList());
					}
					
				}
			}
			
			$kitDataProvider = new CArrayDataProvider($kits);
		
			$this->renderPartial('_ajaxelectrodecells', array(
					'model'=>$model,
					'kitDataProvider'=>$kitDataProvider,
				), 
				false, 
				true
			);
		}
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param Electrode $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}