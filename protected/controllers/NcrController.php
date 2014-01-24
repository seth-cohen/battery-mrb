<?php

class NcrController extends Controller
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
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete',
								'putcellsonncr', 'ajaxputcellsonncr',
								'dispositioncells','ajaxgetncrcelldispo','ajaxsetdispo'
			),
				'roles'=>array('engineering, qa, testing'),
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
		$model=new Ncr;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ncr']))
		{
			$model->attributes=$_POST['Ncr'];
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

		if(isset($_POST['Ncr']))
		{
			$model->attributes=$_POST['Ncr'];
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
		$dataProvider=new CActiveDataProvider('Ncr');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ncr('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ncr']))
			$model->attributes=$_GET['Ncr'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ncr the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ncr::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ncr $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ncr-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionPutCellsOnNCR()
	{
		$ncrModel = new Ncr;
		$ncrModel->number = '';
		
		$cellModel=new Cell('search');
		$cellModel->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Cell']))
		{
			$cellModel->attributes=$_GET['Cell'];
		}
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($ncrModel);
		
		$this->render('putcellsonncr', array(
			'ncrModel'=>$ncrModel,
			'cellModel'=>$cellModel,
		));
	}
	
	public function actionAjaxPutCellsOnNCR()
	{
		$ncrModel = new Ncr;
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		if(isset($_POST['Ncr']['number']) && $_POST['Ncr']['number']!='')
		{/* we have a new model so set the number accordingly */
			if(isset($_POST['Ncr']['id']))
			{
				$ncrModel->addError('ncr_error', 'You must select only Existing NCR or create a new NCR to put cells on. Not both.');
				echo CHtml::errorSummary($ncrModel);
				Yii::app()->end();
			}
			$ncrModel->number = $_POST['Ncr']['number'];
			$ncrModel->date = date("Y-m-d",time());
			if(!$ncrModel->save())
			{
				echo CHtml::errorSummary($ncrModel);
			}				
		}
		else 
		{/* we have an existing model so load it from the ID */
			if(!isset($_POST['Ncr']['id']))
			{
				$ncrModel->addError('ncr_error', 'You must select an NCR to put the cell(s) on.');
				echo CHtml::errorSummary($ncrModel);
				Yii::app()->end();
			}
			else 
			{
				$ncrModel=Ncr::model()->findByPk($_POST['Ncr']['id']);
			}
		}
		
		/* if we made it here then we have cells to put on NCR */
		$cellIds = $_POST['autoId'];
		
		echo $ncrModel->addCells($cellIds);
	}

	public function actionDispositionCells()
	{
		
		$cellModel=new Cell('searchOnNCR');
		$cellModel->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Cell']))
		{
			$cellModel->attributes=$_GET['Cell'];
		}
		
		/* uses Cell->searchOnNCR to find cells on NCR */
		
		$this->render('dispositioncells', array(
			'cellModel'=>$cellModel,
		));
	}
	
	public function actionAjaxGetNcrCellDispo()
	{
		if(!isset($_POST['id']) || !isset($_POST['cell_id']))
		{
			Yii::app()->end();
		}
	
		$ncrCellModel = NcrCell::model()->findByAttributes(array('ncr_id'=>$_POST['id'], 'cell_id'=>$_POST['cell_id']));
		
		if($ncrCellModel==null)
			Yii::app()->end();
		else	
			echo $ncrCellModel->disposition;
	}
	
	/**
	 * Sets the disposition of the cells ncr satus
	 */
	public function actionAjaxSetDispo()
	{
		$dispoStrings = array(
									"0"=>"Open",
									"1"=>"Scrap",
									"2"=>"Eng Use Only",
									"3"=>"Accept",
									"4"=>"Use As Is"
								);

		if(!isset($_POST['id']) || !isset($_POST['cell_id']) ||  !isset($_POST['dispo']))
		{
			echo '0';
			Yii::app()->end();
		}
	
		$ncrCellModel = NcrCell::model()->findByAttributes(array('ncr_id'=>$_POST['id'], 'cell_id'=>$_POST['cell_id']));
		
		if($ncrCellModel == null)
		{
			echo '0';
			Yii::app()->end();
		}
			
		if(isset($_POST['dispo']))
		{
			$ncrCellModel->disposition = $_POST['dispo'];
			$ncrCellModel->disposition_string = $dispoStrings[$ncrCellModel->disposition];
			if($ncrCellModel->save())
			{
				echo '1';
				Yii::app()->end();
			}
		}
		echo '0';
	}
}
