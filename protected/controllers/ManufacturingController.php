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
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	public function actionCreateAnodeLot()
	{
		$model = new Anode;
		// uncomment the following code to enable ajax-based validation
	   
	    if(isset($_POST['ajax']) && $_POST['ajax']==='createanode-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    
	
	    if(isset($_POST['Anode']))
	    {
	        $model->attributes=$_POST['Anode'];
	        if($model->validate())
	        {
	            // form inputs are valid, do something here
	            return;
	        }
	    }
	    $this->render('createanodelot',array('model'=>$model,'last_lot'=>$last_lot));
	}
	
	public function actionCreateCathodeLot()
	{
		$model = new Cathode;
		
		// uncomment the following code to enable ajax-based validation
	   
	    if(isset($_POST['ajax']) && $_POST['ajax']==='createcathode-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	    
	
	    if(isset($_POST['Cathode']))
	    {
	        $model->attributes=$_POST['Cathode'];
	        if($model->validate())
	        {
	            // form inputs are valid, do something here
	            return;
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
}