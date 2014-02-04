<?php

class CyclerController extends Controller
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
				'actions'=>array('create', 'ajaxcreate', 'update', 'ajaxchannellist'),
				'users'=>array('@'),
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
		
		$model = Cycler::model()->with(array(
			'channels',
		))->findByPk($id);
		
		$channel = new Channel('search');
		$channel->unsetAttributes();  // clear any default values
		$channel->cycler_id = $id;
		
		if(isset($_GET['Channel']))
		{
			$channel->attributes = $_GET['Channel'];
		}
		
		$channelDataProvider = $channel->search();
//		foreach($model->channels as $channel){
//			$channels[] = array('num'=>$channel->number, 'in_use'=>$channel->in_use, 'id'=>$channel->id, 'commission'=>$channel->in_commission);
//		}
//		
//		$channelDataProvider = new CArrayDataProvider($channels);
		
		$this->render('view',array(
			'model'=>$model,
			'channelDataProvider'=>$channelDataProvider,
			'channel'=>$channel,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Cycler;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		$channelsArray = array();
		for ($i = 1; $i <= 5; ++$i){
			$channelsArray[] = array('id'=>$i);
		}
		
		$channelsDataProvider = new CArrayDataProvider($channelsArray, array(
		    'pagination'=>array(
		        'pageSize'=>10,
		    )
		 ));
		 
		$this->render('create',array(
			'model'=>$model,
			'channelsDataProvider' => $channelsDataProvider,
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

		if(isset($_POST['Cycler']))
		{
			$model->attributes=$_POST['Cycler'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionAjaxCreate()
	{
		$model = new Cycler;
		
		if(isset($_POST['Cycler']))
		{
			$model->attributes=$_POST['Cycler'];
			
			$channelModels = array();
			$channelCount= 0;
			
			foreach($_POST['Channels'] as $channel)
			{		
				if($channel['num'] > 0)
				{
					if(!isset($channel['minV']) || !isset($channel['maxV']) 
						|| !isset($channel['maxC']) || !isset($channel['maxD'])
						|| !isset($channel['multi'])  )
					{
						$channelModel = new Channel;
						$channelModel->addError('channel_details', 'Incomplete channel details.  Cycler has NOT been saved!!');
						echo CHtml::errorSummary($channelModel);
						Yii::app()->end();
					}
					else 
					{
						
						for ($i = $channelCount+1; $i <= $channelCount + $channel['num']; $i++ )
						{
							$tempChannel = new Channel;
							$tempChannel->number = $i;
							$tempChannel->min_voltage = $channel['minV'];
							$tempChannel->max_voltage = $channel['maxV'];
							$tempChannel->max_charge_rate = $channel['maxC'];
							$tempChannel->max_discharge_rate = $channel['maxD'];
							$tempChannel->multirange = $channel['multi'];
							$tempChannel->in_commission = 1;
							$tempChannel->in_use = 0;
							
							$channelModels[] = $tempChannel;
						}
						$channelCount += $channel['num'];
					}
				}
			}
			
			$model->num_channels = $channelCount;
			if(!$model->save())
			{
				echo CHtml::errorSummary($model);
				Yii::app()->end();
			}
			
			echo Channel::attachChannelsToCycler($channelModels, $model->id);
		}
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
		$model=new Cycler('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cycler']))
			$model->attributes=$_GET['Cycler'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cycler('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cycler']))
			$model->attributes=$_GET['Cycler'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cycler the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cycler::model()->findByPk($id);
		
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cycler $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cycler-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionAjaxChannelList()
	{
		if(!isset($_POST['id']))
		{
			Yii::app()->end();
		}
		
		$id = $_POST['id'];
		$cycler = Cycler::model()->with(array(
			'channels'=>array('condition'=>'in_use=0 AND in_commission=1')
		))->findByPk($id);
		
		echo CHtml::tag('option', array('value'=>''), '-N/A-', true);
		foreach($cycler->channels as $channel)
		{
			echo CHtml::tag('option', array('value'=>$channel->id), CHtml::encode($channel->number), true);
		}
	}
}
