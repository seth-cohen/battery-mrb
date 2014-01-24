<?php

class CellController extends Controller
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
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 
								'multistackcells', 'ajaxstackcells', 
								'multiinspectcells', 'ajaxinspectcells',
								'multilasercells', 'ajaxlasercells',
								'multifillcells', 'ajaxfillcells', 
								'multitipoffcells', 'ajaxtipoffcells',			
								'multiacceptcatdata', 'ajaxacceptcatdata',		
				),
				'roles' => array('manufacturing'),
				//'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','ajaxmfgupdate','downloadlist'),
				'roles' => array('admin'),
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
		$model = $this->loadModel($id);
		
		$this->render('view',array(
			'model'=>$model,
			'kit'=>$model->kit,
			'celltype'=>$model->kit->celltype,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Cell;
		$kit = new Kit;
		$celltype = new Celltype;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cell']))
		{
			$model->attributes=$_POST['Cell'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'kit'=>$kit,
			'celltype'=>$celltype,
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

		if(isset($_POST['Cell']))
		{
			$model->attributes=$_POST['Cell'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'kit'=>$model->kit,
			'celltype'=>$model->kit->celltype,
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
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		$visibleColumns = array(1,2,5,19,20);
		if(isset($_GET['Columns']))
		{
			$visibleColumns = $_GET['Columns'];
		}
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('index',array(
			'model'=>$model,
			'visibleColumns'=>$visibleColumns,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cell the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cell::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cell $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cell-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Allows user to stack mulitple kits that are not associated with a cell yet.
	 */
	public function actionMultiStackCells()
	{
		$model=new Kit('search');
		$model->unsetAttributes();  // clear any default values
		
		/* get all kits that haven't been stacked yet */
		$model->is_stacked = 0;
		
		if(isset($_GET['Kit']))
		{
			$model->attributes=$_GET['Kit'];
		}
				
		$this->render('stackcells',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Allows user to stack mulitple kits that are not associated with a cell yet.
	 */
	public function actionAjaxStackCells()
	{
		/* TODO move most of this logic into the cell model */
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$stackedKits = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		$refnumIds = $_POST['refnumIds'];
		$eaps = $_POST['eaps'];
		
		if(count($stackedKits)>0)
		{
			$cellsStacked = array();
			
			foreach($stackedKits as $kitId)
			{
				$tempCell = new Cell();
				
				$tempCell->stacker_id =  $userIds[$kitId];
				$tempCell->stack_date = $dates[$kitId];
				$tempCell->ref_num_id = $refnumIds[$kitId];
				$tempCell->eap_num = $eaps[$kitId];
				$tempCell->kit_id = $kitId;
				
				$cellsStacked[$kitId] = $tempCell;
			}
			
			$result = Cell::createStackedCells($cellsStacked);  
			
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
	 * Allows user to inspect mulitple cells.
	 */
	public function actionMultiInspectCells()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		/* any cell that is stacked and not inspected can be inspected */
		$model->inspector_id = 1; 
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('inspectcells',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Ajax action to save the model for inspected cells.
	 */
	public function actionAjaxInspectCells()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$inspectedCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		
		if(count($inspectedCells)>0)
		{
			$cellsInspected = array();
			
			foreach($inspectedCells as $cell_id)
			{
				$tempCell = new Cell();
				
				$tempCell->inspector_id = $userIds[$cell_id];
				$tempCell->inspection_date = $dates[$cell_id];
				
				$cellsInspected[$cell_id] = $tempCell;
			}
			
			$result = Cell::inspectCells($cellsInspected);
			
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
	 * Allows user to laser weld mulitple cells.
	 */
	public function actionMultiLaserCells()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		/* only cells that have been inspected and not welded can
		 * be laser welded */
		$model->laserwelder_id = 1;
		$model->inspector_id = '>1'; 
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('lasercells',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Ajax action to save the model for laser welded cells.
	 */
	public function actionAjaxLaserCells()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$laseredCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		
		if(count($laseredCells)>0)
		{
			$cellsLasered = array();
			
			foreach($laseredCells as $cell_id)
			{
				$tempCell = new Cell();
				
				$tempCell->laserwelder_id = $userIds[$cell_id];
				$tempCell->laserweld_date = $dates[$cell_id];
				
				$cellsLasered[$cell_id] = $tempCell;
			}
			
			$result = Cell::laserCells($cellsLasered);
			
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
	 * Allows user to fill mulitple cells that have been welded.
	 */
	public function actionMultiFillCells()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		/* any cell that has been laser welded but not filled yet */
		$model->filler_id = 1;
		$model->laserwelder_id = '>1';
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('fillcells',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Allows user to fill mulitple kits that are not associated with a cell yet.
	 */
	public function actionAjaxFillCells()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$filledCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		$wet_wts = $_POST['wet_wts'];
		$dry_wts = $_POST['dry_wts'];
		
		if(count($filledCells)>0)
		{
			$cellsFilled = array();
			
			foreach($filledCells as $cell_id)
			{
				$tempCell = new Cell();
				
				$tempCell->filler_id = $userIds[$cell_id];
				$tempCell->fill_date = $dates[$cell_id];
				$tempCell->dry_wt = $dry_wts[$cell_id];
				$tempCell->wet_wt = $wet_wts[$cell_id];
				
				$cellsFilled[$cell_id] = $tempCell;
			}
			
			$result = Cell::fillCells($cellsFilled);
			
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
	 * Allows user to weld the fill port on mulitple cells.
	 */
	public function actionMultiTipoffCells()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		/* only cells that have been filled and not port welded can
		 * be port welded */
		//$model->portwelder_id = 1;
		//$model->filler_id = '>1'; 
		
		/* uses cell->searchAtForm() to find cells actively on formation */
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('tipoffcells',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Ajax action to save the model for fill port welded cells.
	 */
	public function actionAjaxTipoffCells()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$tippedoffCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		
		if(count($tippedoffCells)>0)
		{
			$cellsTippedoff = array();
			
			foreach($tippedoffCells as $cell_id)
			{
				$tempCell = new Cell();
				
				$tempCell->portwelder_id = $userIds[$cell_id];
				$tempCell->portweld_date = $dates[$cell_id];
				
				$cellsTippedoff[$cell_id] = $tempCell;
			}
			
			$result = Cell::tipoffCells($cellsTippedoff);
			
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
	 * Allows user to accept the CAT data.
	 */
	public function actionMultiAcceptCATData()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		/* uses cell->searchCompletedCAT() to find cells that have completed  CAT  */
		
		if(isset($_GET['Cell']))
		{
			$model->attributes=$_GET['Cell'];
		}
				
		$this->render('acceptdata',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Ajax action to save the model for fill port welded cells.
	 */
	public function actionAjaxAcceptCATData()
	{
		
		if(!isset($_POST['autoId']))
		{
			echo 'hide';
			Yii::app()->end();
		}
		
		$acceptedCells = $_POST['autoId'];
		$userIds = $_POST['user_ids'];
		$dates = $_POST['dates'];
		
		if(count($acceptedCells)>0)
		{	
			$result = Cell::acceptData($acceptedCells);
			
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
	 * Performs the AJAX update of the detailView on the cellview.
	 * @param Cell $model the model to be validated
	 */
	public function actionAjaxMFGUpdate($id=null)
	{	
		/* load cell detail information */
		if($id == null)
		{
			echo 'hide';
		}
		else
		{
			$model = $this->loadModel($id);
			
			$this->renderPartial('_ajaxcelldetail', array(
					'model'=>$model,
				), 
				false, 
				true
			);
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
		
		$returnString = CHtml::textField("user_names[$data->id]",$userName,array(
				"style"=>"width:150px;",
				"class"=>"autocomplete-user-input",
				"autocomplete"=>"off",
				"disabled"=>$disabled,
			));
			
		$returnString.= CHtml::hiddenField("user_ids[$data->id]",$userId, array("class"=>"user-id-input"));
	
		return $returnString;
	}
	
	/**
	 *  user download of csv data for selected cells 
	 *  
	 */
	public function actionDownloadList()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Cell']))
			$model->attributes=$_GET['Cell'];
			
		if(isset($_GET['Columns']))
			$visibleColumns=$_GET['Columns'];
		
		$this->widget('application.extensions.EExcelView', array( 
				'dataProvider'=> $model->search(), 
				'grid_mode'=>'export', 
				'exportType'=>'Excel2007', 
				'filename'=>'cell_details', 
				'stream'=>true,
				'columns'=>array(
					array(
						'name'=>'serial_search',
						'value'=>'$data->kit->celltype->name."-".$data->kit->serial_num',
						'visible'=>in_array(1,$visibleColumns),
					),
					array(
						'name'=>'refnum_search',
						'value'=>'$data->refNum->number',
						'visible'=>in_array(2,$visibleColumns),
					),
					array(
						'name'=>'eap_num',
						'visible'=>in_array(3,$visibleColumns),
					),
					array(
						'name'=>'celltype_search',
						'value'=>'$data->kit->celltype->name',
						'visible'=>in_array(4,$visibleColumns),
					),
					array(
						'name'=>'stacker_search',
						'value'=>'$data->stacker->getFullName()',
						'visible'=>in_array(5,$visibleColumns),
					),
					array(
						'name'=>'stack_date',
						'visible'=>in_array(6,$visibleColumns),
					),
					array(
						'name'=>'inspector_search',
						'value'=>'$data->inspector->getFullName()',
						'visible'=>in_array(7,$visibleColumns),
					),
					array(
						'name'=>'inspection_date',
						'visible'=>in_array(8,$visibleColumns),
					),
					array(
						'name'=>'laserwelder_search',
						'value'=>'$data->laserwelder->getFullName()',
						'visible'=>in_array(9,$visibleColumns),
					),
					array(
						'name'=>'laserweld_date',
						'visible'=>in_array(10,$visibleColumns),
					),
					array(
						'name'=>'filler_search',
						'value'=>'$data->filler->getFullName()',
						'visible'=>in_array(11,$visibleColumns),
					),
					array(
						'name'=>'fill_date',
						'visible'=>in_array(12,$visibleColumns),
					),
					array(
						'name'=>'portwelder_search',
						'value'=>'$data->portwelder->getFullName()',
						'visible'=>in_array(13,$visibleColumns),
					),
					array(
						'name'=>'portweld_date',
						'visible'=>in_array(14,$visibleColumns),
					),
					array(
						'name'=>'dry_wt',
						'visible'=>in_array(15,$visibleColumns),
					),
					array(
						'name'=>'wet_wt',
						'visible'=>in_array(16,$visibleColumns),
					),
					array(
						'name'=>'anode_search',
						'value'=>'$data->kit->getAnodeList()',
						'visible'=>in_array(17,$visibleColumns),
					),
					array(
						'name'=>'cathode_search',
						'value'=>'$data->kit->getCathodeList()',
						'visible'=>in_array(18,$visibleColumns),
					),
					array(
						'name'=>'ncr_search',
						'type'=>'html',
						'value'=>'$data->getNCRLinks()',
						'visible'=>in_array(19,$visibleColumns),
					),
					array(
						'name'=>'location',
						'visible'=>in_array(20,$visibleColumns),
					),
				)
			)
		);
	}

	public function actionDownloadListOG()
	{
		$model=new Cell('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Cell']))
			$model->attributes=$_GET['Cell'];
			
		$data = array();
		$dataProvider = $model->search();
		$dataProvider->setPagination(false);
		
		$cells = $dataProvider->getData();

		$data[] = array(
			'Serial Number',
			'Stacker', 'Stack Date', 
			'Filler', 'Fill Date',
			'Dry Wt(g)', 'Wet wt(g)',
			'Inspector', 'Inspection Date',
			'Location'
		);

		foreach($cells as $cell)
		{
			$data[] = array(
				$cell->kit->getFormattedSerial(),
				$cell->stacker->getFullName(), $cell->stack_date, 
				$cell->filler->getFullName(), $cell->fill_date,
				$cell->dry_wt, $cell->wet_wt,
				$cell->inspector->getFullName(), $cell->inspection_date,
				$cell->location,
			);
		}
		
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header("Content-Disposition: attachment; filename=file.csv");
		header("Pragma: no-cache");
		
		$this->outputCSV($data);
	}
		
	/* TODO move this to an extension or component */
	function outputCSV($data) {
	    $output = fopen("php://output", "w");
	    foreach ($data as $row) {
	        fputcsv($output, $row);
	    }
	    fclose($output);
	}
}
