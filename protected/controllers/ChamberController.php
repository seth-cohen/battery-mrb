<?php

class ChamberController extends Controller
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
				//'expression'=>'isset($user->depart_id) && $user->depart_id==3',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'roles'=>array('admin', 'engineering', 'testlab'),
				//'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array('admin'),
				//'users'=>array('admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('ajaxsetstatus'),
				'roles'=>array('testlab'),
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
		$model = Chamber::model()->with(array(
			'testAssignments'=>array('with'=>array(
				'cell'=>array('with'=>array(
					'kit.celltype',
					'kit.anodes',
					'kit.cathodes',
				)),
			)),
		))->findByPk($id);
		
		if($model == null){
			$model = Chamber::model()->findByPk($id);
		}
		
		$testAssignment = new TestAssignment('search');
		$testAssignment->unsetAttributes();  // clear any default values
		$testAssignment->chamber_id = $id;
		$testAssignment->is_active = 1;
		
		if(isset($_GET['TestAssignment']))
		{
			$testAssignment->attributes = $_GET['TestAssignment'];
		}
		$testAssignmentDataProvider = $testAssignment->search();
		
		$this->render('view',array(
			'model'=>$model,
			'testAssignmentDataProvider'=>$testAssignmentDataProvider,
			'testAssignment'=>$testAssignment,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Chamber;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Chamber']))
		{
			$model->attributes=$_POST['Chamber'];
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

		if(isset($_POST['Chamber']))
		{
			$model->attributes=$_POST['Chamber'];
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
		$model=new Chamber('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Chamber']))
			$model->attributes=$_GET['Chamber'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Chamber('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Chamber']))
			$model->attributes=$_GET['Chamber'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Sets the chamber in_commission to the $_POSTed value
	 */
	public function actionAjaxSetStatus()
	{
		
		$model=isset($_POST['id'])?Chamber::model()->findByPk($_POST['id']):null;
		
		if($model == null)
		{
			echo '0';
			Yii::app()->end();
		}
			
		if(isset($_POST['status']))
		{
			$model->in_commission = $_POST['status'];
			if($model->save())
			{
				echo '1';
				Yii::app()->end();
			}
		}
		echo '0';
		Yii::app()->end();
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Chamber the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Chamber::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Chamber $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='chamber-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
