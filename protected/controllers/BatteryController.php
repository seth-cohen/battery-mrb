<?php

class BatteryController extends Controller
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
				'actions'=>array(
					'create','update', 
					'cellselection', 'ajaxtypeselected',
				),
				'roles'=>array('engineering'),
				//'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Battery;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Battery']))
		{
			$model->attributes=$_POST['Battery'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
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

		if(isset($_POST['Battery']))
		{
			$model->attributes=$_POST['Battery'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
		$dataProvider=new CActiveDataProvider('Battery');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Battery('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Battery']))
			$model->attributes=$_GET['Battery'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Battery the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Battery::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Battery $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionCellSelection()
	{
		$batteryModel = new Battery;
		$batterytypeModel = new Batterytype;

		// Uncomment the following line if AJAX validation is needed
		if(isset($_POST['ajax']) && $_POST['ajax']==='battery-form') 
			$this->performAjaxValidation($batteryModel);
			
		if(isset($_POST['ajax']) && $_POST['ajax']==='batterytype-form') 
			$this->performAjaxValidation($batterytypeModel);
		
		if(isset($_POST['Battery']))
		{
			$batteryModel->attributes=$_POST['Battery'];
			if($batteryModel->save())
				$this->redirect(array('view','id'=>$batteryModel->id));
		}

		if(isset($_POST['Batterytype']))
		{
			$batterytypeModel->attributes=$_POST['Batterytype'];
			$batterytypeModel->save();
		}
		
		$this->render('cellselection',array(
			'batteryModel'=>$batteryModel,
			'batterytypeModel'=>$batterytypeModel,
		));
	}
	
	public function actionAjaxTypeSelected()
	{
		$id=null;
		$pageSize = 8;
		
		if(isset($_GET['type_id']))
		{
			$id = $_GET['type_id'];
		}
		else
		{	// we shouldn't be here.
			Yii::app()->end();
		}
		
		$batterytypeModel = Batterytype::model()->findByPk($id);
		
		if ($batterytypeModel==null)
			Yii::app()->end();
			
		$cellDataProviders = array();
		for($i=0; $i<ceil($batterytypeModel->num_cells/$pageSize); $i++)
		{
			$cellDataProviders[] = new CArrayDataProvider($batterytypeModel->getCellCount(), array(
			    'pagination'=>array(
			        'pageSize'=>$pageSize,
					'currentPage'=>$i,
			    )
			 ));
		}
		$this->renderPartial('_selectionform',array(
			'batterytypeModel'=>$batterytypeModel,
			'cellDataProviders'=>$cellDataProviders,
			false,
			true,
		));
	}
}
