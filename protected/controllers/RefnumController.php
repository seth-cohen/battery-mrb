<?php

class RefnumController extends Controller
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array(
					'addreferencenumber', 'ajaxaddreferencenumber',
				),
				'roles'=>array('quality, engineering'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Creates a new reference number.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAddReferenceNumber()
	{
		$refnumModel=new RefNum('search');
		$refnumModel->unsetAttributes();  // clear any default values
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($refnumModel);

		if(isset($_POST['RefNum']))
		{
			$refnumModel->attributes=$_POST['RefNum'];
			$refnumModel->save();
			$refnumModel->unsetAttributes();  // clear any default values
		}
		
		if(isset($_GET['RefNum']))
		{
			$refnumModel->attributes=$_GET['RefNum'];
		}
		
		$this->render('addreferencenumber',array(
			'refnumModel'=>$refnumModel,
		));
	}

	/**
	 * Ajax Creates a new reference number.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAjaxAddReferenceNumber()
	{
		$refnumModel=new RefNum('search');
		$refnumModel->unsetAttributes();  // clear any default values
		
		if(isset($_POST['RefNum']))
		{
			$refnumModel->attributes=$_POST['RefNum'];
			if($refnumModel->save())
			{
				echo 'Successfully added Reference Number.';
			}
			else
			{
				echo 'There was an error adding the Reference Number. Try again!';
			}
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return RefNum the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=RefNum::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param RefNum $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ref-num-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
