<?php

class KitController extends Controller
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
					'multicreate', 'ajaxmulticreate',
				),
				'roles'=>array('manufacturing'),
				//'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array('admin'),
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
		$model=new Kit;
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
			
		if(isset($_POST['Kit']))
		{
			$model->attributes=$_POST['Kit'];
			
			/* needed to validate the anode and cathode lot IDs */
			if(isset($_POST['Kit']['anodeIds']))
			{
				$model->anodeIds = $_POST['Kit']['anodeIds'];
			}
			if(isset($_POST['Kit']['cathodeIds']))
			{
				$model->cathodeIds = $_POST['Kit']['cathodeIds'];
			}
			
			if($model->save())
			{
				$model->saveKitElectrodes(array_merge($model->anodeIds, $model->cathodeIds));
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates multiple new models.
	 * If creation is successful, the browser will be redirected to the 'view all' page.
	 */
	public function actionMultiCreate()
	{
		$pageSize = 16;
		
		$model=new Kit;
		$dataProvider = new CArrayDataProvider($model->getListForMulti($pageSize), array(
			'id'=>'kits',
			'pagination'=>array(
				'pageSize'=>$pageSize,
			),
		));
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
			
		if(isset($_POST['Kit']))
		{
			$model->attributes=$_POST['Kit'];
			
			/* needed to validate the anode and cathode lot IDs */
			if(isset($_POST['Kit']['anodeIds']))
			{
				$model->anodeIds = $_POST['Kit']['anodeIds'];
			}
			if(isset($_POST['Kit']['cathodeIds']))
			{
				$model->cathodeIds = $_POST['Kit']['cathodeIds'];
			}
			
			if($model->save())
			{
				$model->saveKitElectrodes(array_merge($model->anodeIds, $model->cathodeIds));
				$this->redirect(array('index'));
			}
		}

		$this->render('multicreate',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Creates multiple new models.
	 * If creation is successful, the browser will be redirected to the 'view all' page.
	 */
	public function actionAjaxMultiCreate()
	{
		$i = 16;
	}
	
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->kitter_search = User::getFullNameProper($model->kitter_id);
		
		/* needed to validate the anode and cathode lot IDs */
		if(isset($_POST['Kit']['anodeIds']))
		{
			$model->anodeIds = $_POST['Kit']['anodeIds'];
		}
		if(isset($_POST['Kit']['cathodeIds']))
		{
			$model->cathodeIds = $_POST['Kit']['cathodeIds'];
		}
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Kit']))
		{
			$model->attributes=$_POST['Kit'];
			if($model->save())
			{
				$model->saveKitElectrodes(array_merge($model->anodeIds, $model->cathodeIds));
				$this->redirect(array('view','id'=>$model->id));
			}
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
		$model=new Kit('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Kit']))
			$model->attributes=$_GET['Kit'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Kit('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Kit']))
			$model->attributes=$_GET['Kit'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Kit the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Kit::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Kit $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='kit-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
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
		
		$returnString = CHtml::textField('user_names['.$data["id"].']',$userName,array(
				"style"=>"width:110px;",
				"class"=>"ui-autocomplete-input",
				"autocomplete"=>"off",
				"disabled"=>$disabled,
			));
			
		$returnString.= CHtml::hiddenField('user_ids['.$data["id"].']',$userId, array("class"=>"user-id-input"));
	
		return $returnString;
	}
}
