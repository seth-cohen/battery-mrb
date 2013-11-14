<?php

class ManufacturingController extends Controller
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
				'actions'=>array('index', 'viewanodelots', 'viewcathodelots'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('createanodelot','viewanode', 'createcathodelot', 'viewcathode'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('updatecathodelot', 'updateanodelot'),
				'roles' => array('manufacturing supervisor', 'manufacturing engineer'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/* TODO move these actions into the ANODE/Cathode controller */
	public function actionCreateAnodeLot()
	{
		$model = new Anode;
		if(!Yii::app()->user->checkAccess('manufacturing supervisor') && !Yii::app()->user->checkAccess('manufacturing engineer'))
		{
			$model->coater_id = Yii::app()->user->id;
		}
		// uncomment the following code to enable ajax-based validation
	   
	    if(isset($_POST['ajax']) && $_POST['ajax']==='createanode-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    
	
	    if(isset($_POST['Anode']))
	    {
	        $model->attributes=$_POST['Anode'];
	   	 	if($model->save())
	        {
	            // form inputs are valid, do something here
	            $this->redirect(array('viewanode','id'=>$model->id));
	        }
	    }
	    $this->render('createanodelot',array('model'=>$model));
	}
	
	public function actionCreateCathodeLot()
	{
		$model = new Cathode;
		if(!Yii::app()->user->checkAccess('manufacturing supervisor') && !Yii::app()->user->checkAccess('manufacturing engineer'))
		{
			$model->coater_id = Yii::app()->user->id;
		}
		// uncomment the following code to enable ajax-based validation
	   
	    if(isset($_POST['ajax']) && $_POST['ajax']==='createcathode-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    
	
	    if(isset($_POST['Cathode']))
	    {
	        $model->attributes=$_POST['Cathode'];
	        if($model->save())
	        {
	            // form inputs are valid, do something here
	            $this->redirect(array('viewcathode','id'=>$model->id));
	        }
	    }
	    $this->render('createcathodelot',array('model'=>$model));
	}
	
	public function actionViewAnodeLots()
	{

		$model=new Anode('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Anode']))
			$model->attributes=$_GET['Anode'];

		$this->render('viewanodelots',array(
			'model'=>$model,
		));
	}
	
	
	public function actionViewCathodeLots()
	{

		$model=new Cathode('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cathode']))
			$model->attributes=$_GET['Cathode'];

		$this->render('viewcathodelots',array(
			'model'=>$model,
		));
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewAnode($id)
	{
		$this->render('viewanode',array(
			'model'=>Anode::model()->findByPk($id),
		));
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewCathode($id)
	{
		$this->render('viewcathode',array(
			'model'=>Cathode::model()->findByPk($id),
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateAnodeLot($id)
	{
		$model=Anode::model()->findByPk($id);
		$model->coater_search = User::getFullNameProper($model->coater_id);
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Anode']))
		{
			$model->attributes=$_POST['Anode'];
			
			if($model->save())
				$this->redirect(array('viewanodelot','id'=>$model->id));
		}

		$this->render('updateanodelot',array('model'=>$model,));
	}
	
/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateCathodeLot($id)
	{
		$model=Cathode::model()->findByPk($id);
		$model->coater_search = User::getFullNameProper($model->coater_id);
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cathode']))
		{
			$model->attributes=$_POST['Cathode'];
			
			if($model->save())
				$this->redirect(array('viewcathodelot','id'=>$model->id));
		}

		$this->render('updatecathodelot',array('model'=>$model,));
	}
}