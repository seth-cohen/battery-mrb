<?php

class CelltypeController extends Controller
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
					'addcelltype','ajaxaddcelltype',
				),
				'roles'=>array('quality, engineering'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Creates a new cell type.
	 */
	public function actionAddCellType()
	{
		$celltypeModel=new Celltype('search');
		$celltypeModel->unsetAttributes();  // clear any default values
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($celltypeModel);

		if(isset($_POST['Celltype']))
		{
			$celltypeModel->attributes=$_POST['Celltype'];
			$celltypeModel->name = strtoupper($celltypeModel->name);
			$celltypeModel->save();
			$celltypeModel->unsetAttributes();  // clear any default values
		}
		
		if(isset($_GET['Celltype']))
		{
			$celltypeModel->attributes=$_GET['Celltype'];
		}
		
		$this->render('addcelltype',array(
			'celltypeModel'=>$celltypeModel,
		));
	}
	
	/**
	 * Creates a new cell type as an ajax action
	 */
	public function actionAjaxAddCellType()
	{
		$celltypeModel=new Celltype('search');
		$celltypeModel->unsetAttributes();  // clear any default values

		if(isset($_POST['Celltype']))
		{
			$celltypeModel->attributes=$_POST['Celltype'];
			$celltypeModel->name = strtoupper($celltypeModel->name);
			if($celltypeModel->save())
			{
				echo 'Successfully added Cell Type.';
			}
			else
			{
				echo 'There was an error adding the Cell Type. Try again!';
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
		$model=Celltype::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='celltype-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}