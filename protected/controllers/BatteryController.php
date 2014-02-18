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
					'cellselection', 'ajaxselection',
					'ajaxtypeselected', 'ajaxavailablecells',
					'ajaxcellsforbatteryview',
					'ajaxaddspares', 'ajaxusespares',
					'ship', 'ajaxship',
					'uploadselection'
				),
				'roles'=>array('engineering, quality'),
				//'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(
					'assemble', 'ajaxserialsforassembly', 'ajaxcellsforbatteryassembly', 'ajaxassemble',
				),
				'roles'=>array('manufacturing battery assembly'),
				//'users'=>array('@'),
			),
			array('allow',
				'actions'=>array(
					'accepttestdata', 'ajaxaccepttestdata',
				),
				'roles'=>array('quality, engineering'),
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
		$model = Battery::model()->with(
				array(
					'batterytype',
					'assembler',
					'cells'=>array('with'=>array(
						'kit'=>array('with'=>array(
							'anodes', 
							'cathodes', 
							'celltype'
						)),
					)),
				)
			)->findByPk($id);
		
		$cellDataProvider= new CArrayDataProvider($model->getBatteryCells(), array(
		    'pagination'=>array(
		        'pageSize'=>16,
		    ),
		    'sort'=>array(
		    	'defaultOrder'=>'position',
		    ),
		 ));
		 
		 /* get the spares as a list of options for the dropdownlist */
		$spareCells = BatterySpare::model()->with('cell.kit')->findAllByAttributes(array('battery_id'=>$id));
		$spareOptions = array();
		
		foreach($spareCells as $spare){
			$spareOptions[$spare->cell->id] = $spare->cell->kit->getFormattedSerial();
		}
		
		$this->render('view',array(
			'model'=>$model,
			'cellDataProvider'=>$cellDataProvider,
			'spareOptions'=>$spareOptions,
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
		$this->performAjaxValidation($model);

		$cellDataProvider= new CArrayDataProvider($model->getBatteryCells(), array(
		    'pagination'=>array(
		        'pageSize'=>16,
		    ),
		    'sort'=>array(
		    	'defaultOrder'=>'position',
		    ),
		 ));
		 
		 /* get the spares as a list of options for the dropdownlist */
		$spareCells = BatterySpare::model()->with('cell.kit')->findAllByAttributes(array('battery_id'=>$id), 
			array('order'=>'kit.serial_num')
		);
		$spareOptions = array();
		
		foreach($spareCells as $spare){
			$spareOptions[$spare->cell->id] = $spare->cell->kit->getFormattedSerial();
		}
		 
		if(isset($_POST['Battery']))
		{
			$model->attributes=$_POST['Battery'];
			
			if($model->ship_date < date('Y-m-d', mktime(0,0,0,1,1,1900)))
			{
				$model->ship_date = null;
				$model->location = '[ACCEPTED]';
			}
			if($model->data_accepted == 0)
			{
				$model->ship_date = null;
				$model->location = '[ASSEMBLED] ' .$model->assembly_date;
			}
			else 
			{
				/* find the spares and delete them */
				/* clear the join table of roles */
				$commandDelete = Yii::app()->db->createCommand();
				$commandDelete->delete('tbl_battery_spare', 
					'battery_id = :id',
					array(':id'=>$model->id)
				);
			}
			if($model->assembler_id == 1)
			{
				$model->assembly_date = null;
				$model->data_accepted = false;
				$model->location = '[EAP] Cell Selection';
			}
			else 
			{ // battery has been assembled.
				foreach ($model->cells as $cell)
				{
					if (strpos($cell->location, '[Assembled]' )  == FALSE)
					{ // then the location of the cells hasn't been set yet.
						$cell->location = '[Assembled] '.$model->batterytype->name . ' SN: '. $model->serial_num;
						$cell->save();
					}
				}
			}
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		 
		$sparesDataProvider = new CArrayDataProvider($model->batterytype->getSparesInputArray(), array(
		    'pagination'=>array(
		        'pageSize'=>10,
		    )
		 ));
		 
		$this->render('update',array(
			'model'=>$model,
			'cellDataProvider'=>$cellDataProvider,
			'spareOptions'=>$spareOptions,
			'sparesDataProvider'=>$sparesDataProvider,
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
		$model=new Battery('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Battery']))
			$model->attributes=$_GET['Battery'];

		$this->render('index',array(
			'model'=>$model,
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
		
		$sparesDataProvider = new CArrayDataProvider($batterytypeModel->getSparesInputArray(), array(
		    'pagination'=>array(
		        'pageSize'=>10,
		    )
		 ));
		
		$this->render('cellselection',array(
			'batteryModel'=>$batteryModel,
			'batterytypeModel'=>$batterytypeModel,
			'sparesDataProvider'=>$sparesDataProvider,
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
			$cellDataProviders[] = new CArrayDataProvider($batterytypeModel->getCellInputArray(), array(
			    'pagination'=>array(
			        'pageSize'=>$pageSize,
					'currentPage'=>$i,
			    )
			 ));
		}
		
		$result = array();
		$result['view'] = $this->renderPartial('_selectionform',array(
				'batterytypeModel'=>$batterytypeModel,
				'cellDataProviders'=>$cellDataProviders,
			),
			true,
			false
		);
		
		/* get the highest serial number for that battery model that has been created */
		$batteryModel = Battery::model()->findByAttributes(
			array('batterytype_id'=>$id),
			array('order'=>'id DESC', 'limit'=>1)
		);
		
		if($batteryModel)
		{
			 $result['serial'] = $batteryModel->serial_num;
		}
		else 
		{
			$result['serial'] =  'N/A';
		}
		
		echo json_encode($result);
	}
	
	/**
	 * 
	 * Returns options for dropdown box of all available cells that have been
	 * approved by QA of the cell type needed for the battery type_id selected
	 * that are not on open NCR or Scrapped or for ENG use only
	 */
	public function actionAjaxAvailableCells()
	{
		if(!isset($_GET['type_id']))
		{
			Yii::app()->end();
		}
		
		$id = $_GET['type_id'];
		$batterytype = Batterytype::model()->findByPk($id,array('select'=>'celltype_id'));
		
		$criteria=new CDbCriteria;
		$criteria->with = array(
			'kit'=>array(
				'select'=>array('id','serial_num', 'celltype_id'),
				'alias'=>'kit',
			),
			'kit.celltype'=>array('alias'=>'celltype'),
		);
		$criteria->addcondition('kit.celltype_id=:ct_id');
		$criteria->params = array(':ct_id'=>$batterytype->celltype_id);
		$criteria->addcondition('data_accepted=1');
		$criteria->addcondition('battery_id IS NULL');
		
		/* but are not currently on an open NCR or scrapped/eng use only */
		$criteria->addCondition('NOT EXISTS (SELECT *
											FROM tbl_ncr_cell ncr
											WHERE t.id = ncr.cell_id
											AND ncr.disposition < 3
											GROUP BY t.id)');
			
		$bForSpares = isset($_GET['bForSpares'])?$_GET['bForSpares']:0;
		if($bForSpares == 0)
		{
			/* but are not currently a spare for another battery*/
			$criteria->addCondition('NOT EXISTS (SELECT *
												FROM tbl_battery_spare spare
												WHERE t.id = spare.cell_id
												GROUP BY t.id)');
		}
		else
		{
			$battery_id = isset($_GET['battery_id'])?$_GET['battery_id']:0;
			/* but are not currently a spare for this battery*/
			$criteria->addCondition('NOT EXISTS (SELECT *
												FROM tbl_battery_spare spare
												WHERE spare.cell_id = t.id
												AND spare.battery_id = '. $battery_id
												.' GROUP BY t.id)');
		}
		$criteria->order = 'kit.serial_num';
		
		$cells = Cell::model()->findAll($criteria);
		
		echo CHtml::tag('option', array('value'=>''), '-N/A-', true);
		foreach($cells as $cell)
		{
			echo CHtml::tag('option', array('value'=>$cell->id), CHtml::encode($cell->kit->getFormattedSerial()), true);
		}
	}
	
	/**
	 * 
	 * Returns options for dropdown box of all available cells that have been
	 * approved by QA of the cell type needed for the battery type_id selected
	 */
	public function actionAjaxSelection()
	{
		$batteryModel=new Battery;
		$cells = array();
		$spares = array();
		
		if(isset($_POST['Battery']))
		{
			/* make sure that cells were selected for the battery */	
			if(!isset($_POST['Battery']['Cells']) ||
				(count(array_unique($_POST['Battery']['Cells'])) != $_POST['num_cells']))
			{
				$batteryModel->addError('selection_error', 'Not enough cells selected');
				echo CHtml::errorSummary($batteryModel);
				Yii::app()->end();
			}
			else 
			{
				$cells = $_POST['Battery']['Cells'];
			}
			if(isset($_POST['Battery']['Spares']))
			{
				$spares = $_POST['Battery']['Spares'];
			}
			
			$batteryModel->attributes=$_POST['Battery'];
			
			$result = Battery::batteryCellSelection($batteryModel, $cells, $spares);
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
/**
	 * Provides manufacturing the ability to assemble  a battery
	 * which has had cells selected for it.
	 */
	public function actionAssemble()
	{
		$batteryModel=new Battery('assemble'); 
		$batteryModel->unsetAttributes();  // clear any default values
		$batteryModel->assembly_date = date("Y-m-d",time());
		
		//validate the battery attributes
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($batteryModel);
			Yii::app()->end();
		}
		
		if(isset($_POST['Battery']))
		{
			$batteryModel->attributes=$_POST['Battery'];
		}
				
		$this->render('assemblebattery',array(
			'batteryModel'=>$batteryModel,
		));
	}
	
	/**
	 * 
	 * Performs the action that validates the battery asembly action. 
	 * If there are spares that are used then 
	 */
	public function actionAjaxAssemble()
	{
		$batteryModel;
		$cells = array();
		$spares = array();
		
		if(isset($_POST['Battery']))
		{
			$batteryModel = Battery::model()->findByPk($_POST['Battery']['serial_num']);
			$cells = $_POST['Battery']['Cells'];
			
			/* Check if spares need to be used for the battery */	
			if( isset($_POST['autoId']) )
			{
				$bSparesOK = true;
				
				/* for each of the cell_ids that needs a spare make sure one was selected */
				foreach($_POST['autoId'] as $id)
				{
					if($cells[$id] == '')
					{
						$bSparesOK = false;
					}
					else
					{
						$spares[$id] = $cells[$id];
					}
				}
				if ($bSparesOK == false)
				{
					$batteryModel->addError('spares_error', 'There was no spare selected for a cell marked bad');
					echo CHtml::errorSummary($batteryModel);
					Yii::app()->end();
				}
			}
			
			$batteryModel->assembly_date =$_POST['Battery']['assembly_date'];
			$batteryModel->assembler_id =$_POST['Battery']['assembler_id'];
			$batteryModel->scenario ='assemble';
			
			$result = Battery::batteryAssemble($batteryModel, $spares);
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * 
	 * Returns options for dropdown box of all available batteries that have been
	 * had cells selected on EAP and not been assembled
	 */
	public function actionAjaxSerialsForAssembly()
	{
		if(!isset($_GET['type_id']))
		{
			Yii::app()->end();
		}
		
		$type_id = $_GET['type_id'];
		$batteryModels = Battery::model()->findAllByAttributes(
			array('batterytype_id'=>$type_id, 'assembler_id'=>1), 
			array('select'=>'id, serial_num')
		);
		
		echo CHtml::tag('option', array('value'=>''), '-Select Serial-', true);
		foreach($batteryModels as $battery)
		{
			echo CHtml::tag('option', array('value'=>$battery->id), CHtml::encode($battery->serial_num), true);
		}
	}
	
	/**
	 * Returns list of the cells that were selected for battery with the specified ID
	 */
	public function actionAjaxCellsForBatteryAssembly()
	{
		$id=null;
		$pageSize = 8;
		
		if(isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		else
		{	// we shouldn't be here.
			Yii::app()->end();
		}
		
		$batteryModel = Battery::model()->findByPk($id);
		
		if ($batteryModel==null)
			Yii::app()->end();
			
		/* get the cell datarpoviders */
		$cellModel = new Cell();
		$cellModel->unsetAttributes(); // clear any defaults
		$cellModel->battery_id = $batteryModel->id;
		$cellModel->battery_position = '<1000';
		
		for($i=0; $i<ceil($batteryModel->batterytype->num_cells/$pageSize); $i++)
		{
			$cellDataProviders[] = $cellModel->searchInBattery($pageSize, $i);
		}
		
		/* get the spares as a list of options for the dropdownlist */
		$spareCells = BatterySpare::model()->with('cell.kit')->findAllByAttributes(array('battery_id'=>$id));
		$spareOptions = array();
		
		foreach($spareCells as $spare){
			$spareOptions[$spare->cell->id] = $spare->cell->kit->getFormattedSerial();
		}
		
		$this->renderPartial('_assemblyform',array(
				'batteryModel'=>$batteryModel,
				'cellDataProviders'=>$cellDataProviders,
				'spareOptions'=>$spareOptions,
			),
			false,
			true
		);
	}
	
	/**
	 * Performs the AJAX update of the battery-detail view on the index page.
	 * @param integer $id of the battery to get the cells for
	 */
	public function actionAjaxCellsForBatteryView($id=null)
	{	
		/* load cell detail information */
		if($id == null)
		{
			echo 'hide';
		}
		else
		{
			$model = Battery::model()->with(
				array(
					array('cells'=>array('with'=>'kit'))
				)
			)->findByPk($id);
			
			$cellDataProvider= new CArrayDataProvider($model->getBatteryCells(), array(
			    'pagination'=>array(
			        'pageSize'=>16,
			    ),
			    'sort'=>array(
			    	'defaultOrder'=>'position',
			    ),
			 ));
			
			$this->renderPartial('_batterycells', array(
					'model'=>$model,
					'cellDataProvider'=>$cellDataProvider,
				), 
				false, 
				false
			);
		}
	}

	/**
	 * Allows user to accept the CAT data.
	 */
	public function actionAcceptTestData()
	{
		$model=new Battery('search');
		$model->unsetAttributes();  // clear any default values
		
		$model->assembler_id = '<>1';
		$model->data_accepted = 0;
		
		/* uses battery->search() to find cells that have been assembled  */
		
		if(isset($_GET['Battery']))
		{
			$model->attributes=$_GET['Battery'];
		}
				
		$this->render('accepttestdata',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Ajax action to save the model for Accepting CAT data.
	 */
	public function actionAjaxAcceptTestData()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$acceptedBatteries = $_POST['autoId'];
		$dates = $_POST['dates'];
		
		if(count($acceptedBatteries)>0)
		{	
			$result = Battery::acceptData($acceptedBatteries);
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * Allows user to mark battery as shipped.
	 */
	public function actionShip()
	{
		$model=new Battery('search');
		$model->unsetAttributes();  // clear any default values
		
		$model->data_accepted = 1;
		
		$model->ship_date = array(null);
		
		/* uses battery->search() to find cells that have had data accepted  */
		
		if(isset($_GET['Battery']))
		{
			$model->attributes=$_GET['Battery'];
		}
				
		$this->render('shipbatteries',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Ajax action to save the model for shipping battery.
	 */
	public function actionAjaxship()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$shippedBatteries = $_POST['autoId'];
		$dates = $_POST['dates'];
		
		if(count($shippedBatteries)>0)
		{	
			$result = Battery::ship($shippedBatteries);
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * Creates a battery cell selection from uploaded CSV file
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUploadSelection()
	{
		/* this cannot be an AJAX request... uploading files from AJAX is not straight forward, though it 
		 * is possible with the use of an iframe and some javascript
		 */
		$batteryModel=new Battery;
		$batterytypeModel = new Batterytype;
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($batteryModel);

		if(isset($_FILES['Uploaded']))
		{
			$batteryModel->attributes = $_POST['Battery'];
			if(Battery::selectionFromUpload($batteryModel, $_FILES['Uploaded']))
			{
				$cellDataProvider= new CArrayDataProvider($batteryModel->getBatteryCells(), array(
				    'pagination'=>array(
				        'pageSize'=>16,
				    ),
				    'sort'=>array(
				    	'defaultOrder'=>'position',
				    ),
				 ));
				 
				$this->redirect(array('view', 'id'=>$batteryModel->id));
			}
		}
		else
		{ /* go try the cell selection again */
			$batteryModel->addError('selection_error', 'No file uploaded');
		}
		
		$sparesDataProvider = new CArrayDataProvider($batterytypeModel->getSparesInputArray(), array(
		    'pagination'=>array(
		        'pageSize'=>10,
		    )
		 ));
		
		$this->render('cellselection',array(
			'batteryModel'=>$batteryModel,
			'batterytypeModel'=>$batterytypeModel,
			'sparesDataProvider'=>$sparesDataProvider,
		));
				
			/*$target = Yii::app()->basePath."/uploads/"; 
 			$target = $target . basename( $_FILES['uploaded']['name']) ; 
			 $ok=1; 
			 
			 if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) )
			 {
				echo "The file ". basename( $_FILES['uploaded']['name']). " has been uploaded";
			 } 
			 else 
			 {
				echo "Sorry, there was a problem uploading your file.";
			 }
			 
			 echo "</br>";
			 $handle = fopen($target, "r");
			 $row = 1;
			 *
			 
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		*/
	}
	
/**
	 * 
	 * Add the ability to add spares to the battery population and saves the battery
	 */
	public function actionAjaxAddSpares($id=null)
	{
		if(isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		else
		{	// we shouldn't be here.
			Yii::app()->end();
		}
		$batteryModel = Battery::model()->findByPk($id);
		
		$spares = array();
		$spareCount = 0;
		
		if(isset($_POST['Battery']))
		{
			if(isset($_POST['Battery']['Spares']))
			{
				$spares = $_POST['Battery']['Spares'];
				$existingSpares = BatterySpare::model()->with('cell')->findAllByAttributes(array('battery_id'=>$batteryModel->id), 
					array('order'=>'position DESC')
				);
				$posOffset = (!empty($existingSpares))?$existingSpares[0]->position:0;
				
				foreach($spares as $pos=>$spare)
				{
					if($spare['id'])
					{
						/* create the batteryspares */
						$spareModel = new BatterySpare;
						
						$spareModel->cell_id = $spare['id'];
						$spareModel->battery_id = $batteryModel->id;
						$spareModel->position = $pos + $posOffset;
						
						$spareModel->save();
						
						$spareCount++;
					}
				}
				if ($spareCount == 0)
				{
					$batteryModel->addError('selection_error', 'No spare cells selected to add.');
					echo CHtml::errorSummary($batteryModel);
					Yii::app()->end();
				}
			}
			
			$result = json_encode(array(
				'batterytype' => $batteryModel->batterytype->name,
				'serial_num' => $batteryModel->serial_num,
				'num_spares' => $spareCount,
			));
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
		
	/**
	 * 
	 * Add the ability to use the spares in the battery population and saves the battery
	 */
	public function actionAjaxUseSpares($id=null)
	{
		if(isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		else
		{	// we shouldn't be here.
			Yii::app()->end();
		}
		$batteryModel = Battery::model()->findByPk($id);
		
		$spares = array();
		$spareCount = 0;
		
		if(isset($_POST['Battery']))
		{
			if(isset($_POST['Battery']['Cells']))
			{
				$cellsReplaced = $_POST['Battery']['Cells'];
				foreach($cellsReplaced as $cell_id=>$spare_id)
				{
					if($spare_id)
					{
						$cellModel = Cell::model()->findByPk($cell_id);
						$spareModel = Cell::model()->findByPk($spare_id);
						
						$spareModel->battery_id = $cellModel->battery_id;
						$spareModel->battery_position = $cellModel->battery_position;
						$spareModel->save();
						
						$cellModel->battery_id = null;
						$cellModel->battery_position = null;
						$cellModel->notes = 'Was replaced by spare '.$spareModel->kit->getFormattedSerial().' in '
							.$batteryModel->batterytype->name .' SN: ' .$batteryModel->serial_num;
						$cellModel->location = 'Battery Assembly';
						
						$cellModel->save();
						
						/* delete all instances of the spare as a batteryspare */
						$batterySpares = BatterySpare::model()->findAllByAttributes(array('cell_id'=>$spareModel->id));
						foreach($batterySpares as $spare)
						{
							$spare->delete();
						}
					
						$spareCount++;
					}
				}
				if ($spareCount == 0)
				{
					$batteryModel->addError('selection_error', 'No spare cells selected for use.');
					echo CHtml::errorSummary($batteryModel);
					Yii::app()->end();
				}
			}
			
			$result = json_encode(array(
				'batterytype' => $batteryModel->batterytype->name,
				'serial_num' => $batteryModel->serial_num,
				'num_spares' => $spareCount,
			));
			
			if (!json_decode($result))
			{ /* the save failed otherwise result would be json_encoded*/
				echo $result;
			} 
			else 
			{ /* success so show count and serial numbers */
				echo $result;
			}
		}
	}
	
	/**
	 * generates the text fields for the operator
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
		
		$returnString = CHtml::textField("user_names[$data->id]",$userName,array(
				"style"=>"width:110px;",
				"class"=>"autocomplete-user-input",
				"autocomplete"=>"off",
				"disabled"=>$disabled,
			));
			
		$returnString.= CHtml::hiddenField("user_ids[$data->id]",$userId, array("class"=>"user-id-input"));
	
		return $returnString;
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
	
}
	