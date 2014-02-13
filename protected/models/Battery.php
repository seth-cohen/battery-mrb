<?php

/**
 * This is the model class for table "tbl_battery".
 *
 * The followings are the available columns in table 'tbl_battery':
 * @property string $id
 * @property string $batterytype_id
 * @property string $ref_num_id
 * @property string $eap_num
 * @property integer $serial_num
 * @property string $assembler_id
 * @property string $assembly_date
 * @property string $ship_date
 * @property string $location
 *
 * The followings are the available model relations:
 * @property Batterytype $batterytype
 * @property RefNum $refNum
 * @property User $assembler
 * @property Cell[] $cells
 * @property BatterySpare[] $spares
 */
class Battery extends CActiveRecord
{
	
	public $refnum_search;
	public $batterytype_search;
	public $assembler_search;
	public $upload_file;
	
	public $previousSerialNum = null;
	public $previousBatteryType = null;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_battery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('serial_num, batterytype_id, location, ref_num_id, eap_num', 'required'),
			array('serial_num, batterytype_id, assembler_id, assembly_date', 'required', 'on'=>'assemble'),
			array('serial_num', 'checkUniqueInType'),
			
			array('batterytype_id, ref_num_id, assembler_id', 'length', 'max'=>10),
			array('eap_num, serial_num, location', 'length', 'max'=>50),
			array('assembly_date, ship_date, data_accepted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, batterytype_id, ref_num_id, eap_num, serial_num, assembler_id, 
					assembly_date, ship_date, location, refnum_search,
					batterytype_search, assembler_search, data_accepted' , 
					'safe', 'on'=>'search'
			),
		);
	}

	public function checkUniqueInType($attribute,$params) 
	{
	    if(Battery::model()->count('batterytype_id=:batterytype_id AND serial_num=:serial_num',
	        array(':batterytype_id'=>$this->batterytype_id,':serial_num'=>$this->serial_num)) > 0) 
	    {
	        if($this->hasSerialChanged())
	        {
	        	$this->addError( $attribute, "Serial No. $this->serial_num already used for this battery type!" );
	        }	    
	    }
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'batterytype' => array(self::BELONGS_TO, 'Batterytype', 'batterytype_id'),
			'refNum' => array(self::BELONGS_TO, 'RefNum', 'ref_num_id'),
			'assembler' => array(self::BELONGS_TO, 'User', 'assembler_id'),
			'cells' => array(self::HAS_MANY, 'Cell', 'battery_id'),
			'spares' => array(self::HAS_MANY, 'BatterySpare', 'battery_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'batterytype_id' => 'Battery Type',
			'ref_num_id' => 'Ref No.',
			'eap_num' => 'Eap No.',
			'serial_num' => 'Serial No.',
			'assembler_id' => 'Assembler',
			'assembly_date' => 'Assembly Date',
			'ship_date' => 'Ship Date',
			'location' => 'Location',
		
			'refnum_search' => 'Ref No.',
			'batterytype_search' =>'Battery Type' ,
			'assembler_search' =>'Assembler',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->with = array(
						'refNum'=>array('alias'=>'ref'),
						'batterytype'=>array('alias'=>'type'),
						'assembler'=>array('alias'=>'ass'), 
		); // needed for alias of search parameter tables
		
		$criteria->together = true;
		
		$criteria->compare('id',$this->id,true);
		$criteria->compare('batterytype_id',$this->batterytype_id,true);
		$criteria->compare('ref_num_id',$this->ref_num_id,true);
		$criteria->compare('eap_num',$this->eap_num,true);
		$criteria->compare('serial_num',$this->serial_num,true);
		$criteria->compare('assembler_id',$this->assembler_id);
		$criteria->compare('assembly_date',$this->assembly_date,true);
		$criteria->compare('data_accepted',$this->data_accepted,true);
		$criteria->compare('ship_date',$this->ship_date,true);
		$criteria->compare('location',$this->location,true);
		
		$criteria->compare('type.name',$this->batterytype_search, true);
		
		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(ass.first_name, " ", ass.last_name)', $this->assembler_search);

		if($this->refnum_search)
		{
			$references = explode(',', str_replace(' ', ',', $this->refnum_search));
			
			$refCriteria = new CDbCriteria();
			foreach ($references as $reference)
			{
				if(!empty($reference))
				{
					$refCriteria->compare('ref.number', $reference, true, 'OR');
				}
			}
			$criteria->mergeWith($refCriteria);
		}
		
		return new KeenActiveDataProvider($this, array(
			'withKeenLoading' => array(
				array('refNum'),
			),
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'refnum_search'=>array(
						'asc'=>'ref.number',
						'desc'=>'ref.number DESC',
					),
					'batterytype_search'=>array(
						'asc'=>'type.name',
						'desc'=>'type.name DESC',
					),
					'assembler_search'=>array(
						'asc'=>'CONCAT(ass.first_name, " ", ass.last_name)',
						'desc'=>'CONCAT(ass.first_name, " ", ass.last_name) DESC',
					),
					'*',		// all others treated normally
				),
			),
		));
	}

	/**
	 * Returns dataprovider with all batteries that have had cell selections done but
	 * have not been assembled.
	 */
	public function searchForAssembly()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->with = array(
						'refNum'=>array('alias'=>'ref'),
						'batterytype'=>array('alias'=>'type'),
		); // needed for alias of search parameter tables
		
		$criteria->together = true;
		
		$criteria->compare('id',$this->id,true);
		$criteria->compare('batterytype_id',$this->batterytype_id,true);
		$criteria->compare('ref_num_id',$this->ref_num_id,true);
		$criteria->compare('eap_num',$this->eap_num,true);
		$criteria->compare('serial_num',$this->serial_num,true);
		$criteria->compare('assembler_id',$this->assembler_id,true);
		$criteria->compare('assembly_date',$this->assembly_date,true);
		$criteria->compare('data_accepted',$this->data_accepted,true);
		
		$criteria->compare('type.name',$this->batterytype_search, true);

		if($this->refnum_search)
		{
			$references = explode(',', str_replace(' ', ',', $this->refnum_search));
			
			$refCriteria = new CDbCriteria();
			foreach ($references as $reference)
			{
				if(!empty($reference))
				{
					$refCriteria->compare('ref.number', $reference, true, 'OR');
				}
			}
			$criteria->mergeWith($refCriteria);
		}
		
		return new KeenActiveDataProvider($this, array(
			'withKeenLoading' => array(
				array('refNum'),
			),
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'refnum_search'=>array(
						'asc'=>'ref.number',
						'desc'=>'ref.number DESC',
					),
					'batterytype_search'=>array(
						'asc'=>'type.name',
						'desc'=>'type.name DESC',
					),
					'assembler_search'=>array(
						'asc'=>'CONCAT(ass.first_name, " ", ass.last_name)',
						'desc'=>'CONCAT(ass.first_name, " ", ass.last_name) DESC',
					),
					'*',		// all others treated normally
				),
			),
		));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Battery the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * 
	 */
	public function afterFind()
	{
		$this->previousSerialNum = $this->serial_num;
		$this->previousBatteryType = $this->batterytype_id;;
		
		return parent::afterFind();
		
	}

	public function hasSerialChanged()
	{
		if($this->previousBatteryType == null || $this->previousSerialNum == null)
		{	/* this is a new record and serial number must be validated */
			return true;
		}
		else
		{ 	/* if either have changed must validate */
			return ($this->previousBatteryType !== $this->batterytype_id || $this->previousSerialNum !== $this->serial_num);
			
		}
	}

	/**
	 * 
	 * Return an associative array for an arraydataprovider that contains the cell id and serial number
	 */
	public function getBatteryCells()
	{
		$result = array();
		
		foreach($this->cells as $cell)
		{
			 $result[] = array(
			 	'id' =>$cell->id,
			 	'position'=>$cell->battery_position,
			 	'serial' => $cell->kit->getFormattedSerial(),
			 	'location'=> $cell->location,
			);
		}
		
		/* get the list of spares */
		$spareCells = BatterySpare::model()->with('cell.kit')->findAllByAttributes(array('battery_id'=>$this->id));
		foreach($spareCells as $spare)
		{
			$cell = $spare->cell;
			$result[] = array(
			 	'id' =>$cell->id,
			 	'position'=>$spare->position + 1000,
			 	'serial' => $cell->kit->getFormattedSerial(),
			 	'location'=> $cell->location,
			);
		}
		
		return $result;
	}
	
 	/**
	 * saves Battery records and updates as data accepted
	 * This function is different than the other mmulti save functions in that the
	 * parameter passed is not an associative array containing actual Cell objects
	 * it is an array of battery_ids to be updated.
	 * 
	 * Spares for the battery should be cleared
	 * 
	 * @param array $acceptedCells
	 */
	public static function acceptData(array $acceptedBatteries)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($acceptedBatteries))
			return;
			
		foreach($acceptedBatteries as $battery_id)
		{
			$model = Battery::model()->findByPk($battery_id);
			$model->data_accepted = 1;
			$model->location = '[ACCEPTED] '.date("Y-m-d",time());
			
			if(!$model->validate())
			{
				$error = 1;
			}
			$models[] = $model;	
		}
		
		/* all models validated save them all */
		if ($error==0)
		{
			/* create array to return with JSON */
			$result = array();
			foreach($models as $model)
			{
				if($model->save())
				{
					$result[] = array(
						'serial'=>$model->getFormattedSerial(), 
					);
					
					/* find the spares and delete them */
					/* clear the join table of roles */
					$commandDelete = Yii::app()->db->createCommand();
					$commandDelete->delete('tbl_battery_spare', 
						'battery_id = :id',
						array(':id'=>$model->id)
					);
				}
			}
			return json_encode($result);
		}
		else /* a model failed, don't save any */
		{
			return CHtml::errorSummary($models); 	
		}			
		return null;
	}
	
/**
	 * saves Battery records and updates as shipped
	 * 
	 * Spares for the battery should be cleared
	 * 
	 * @param array $shippedBatteries
	 */
	public static function ship(array $shippedBatteries)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($shippedBatteries))
			return;
			
		foreach($shippedBatteries as $battery_id)
		{
			$model = Battery::model()->findByPk($battery_id);
			$model->ship_date = date("Y-m-d",time());
			$model->location = '[SHIP] ' .date("Y-m-d",time());
			
			if(!$model->validate())
			{
				$error = 1;
			}
			$models[] = $model;	
		}
		
		/* all models validated save them all */
		if ($error==0)
		{
			/* create array to return with JSON */
			$result = array();
			foreach($models as $model)
			{
				if($model->save())
				{
					$result[] = array(
						'serial'=>$model->getFormattedSerial(), 
					);
				}
			}
			return json_encode($result);
		}
		else /* a model failed, don't save any */
		{
			return CHtml::errorSummary($models); 	
		}			
		return null;
	}
	
	public function getFormattedSerial()
	{
		return $this->batterytype->name. ' SN: ' .$this->serial_num;
	}
	
	/**
	 * 
	 * Creates new battery and associates designated cells from the cell selection...
	 * to default states 
	 * 
	 * @param Battery $batteryModel
	 * @param Array $cells
	 * @param Array $spares
	 */
	public static function batteryCellSelection($batteryModel, $cells=array(), $spares=array())
	{
		if(empty($batteryModel) || empty($cells))
			return;
		
		$batteryModel->location = '[EAP] Cell Selection';
		$result = array();
		
		if($batteryModel->save())
		{
			$battery_id = $batteryModel->id;
			
			/* assign the cells to the battery and set the location to be on EAP */
			if(!empty($cells))
			{
				foreach($cells as $position=>$cell)
				{
					$cellModel = Cell::model()->findByPk($cell);
					$cellModel->battery_id = $battery_id;
					
					/* moved this to testlab action deliver because the location doesn't actually change until
					 * it is delivered to Battery Assembly
					 */
//					$cellModel->location = '[EAP] '.$batteryModel->batterytype->name 
//												.'- SN: '.$batteryModel->serial_num;

					$cellModel->battery_position = $position;
					
					$cellModel->save();
				}
			}
			/* assign the spares to the battery and set the location to be on EAP-spare */
			$spareCount = 0;
			if(!empty($spares))
			{
				foreach($spares as $position=>$spare)
				{
					if($spare['id'])
					{
						$spareCount += 1;
						$cellModel = Cell::model()->findByPk($spare['id']);
						/* moved this to testlab action deliver because the location doesn't actually change until
						 * it is delivered to Battery Assembly
						 */
						//$cellModel->location = '[EAP-Spare] '.$batteryModel->batterytype->name;
						
						$cellModel->save();
						
						/* create the batteryspares */
						$spareModel = new BatterySpare;
						
						$spareModel->cell_id = $spare['id'];
						$spareModel->battery_id = $battery_id;
						$spareModel->position = $position;
						
						$spareModel->save();
					}
				}
			}
			$result = array(
				'batterytype' => $batteryModel->batterytype->name,
				'serial_num' => $batteryModel->serial_num,
				'num_spares' => $spareCount,
			);
			return json_encode($result);
		}
		else
		{
			return CHtml::errorSummary($batteryModel);
		}
		
		return null;
		
	}
	
	/**
	 * 
	 * Creates new battery and associates designated cells from the cell using an uploaded file
	 * 
	 * @param Battery $batteryModel
	 * @param array $uploadedFile
	 */
	public static function selectionFromUpload($batteryModel, $uploadedFile)
	{
		$cells = array();
		$spares = array();
		
		if(empty($batteryModel) || empty($uploadedFile))
			return false;
		
		$batteryModel->location = '[EAP] Cell Selection';
		$result = array();
		
		if($batteryModel->save())
		{
			$battery_id = $batteryModel->id;
			
			/* save the uploaded file */
			$target = Yii::app()->basePath."/uploads/";
			$target = $target . $batteryModel->batterytype->name . "-" . $batteryModel->serial_num .".csv";
			
			$ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
			if($ext != 'csv')
			{
				$batteryModel->addError('upload_error', "The file must be a csv file.  The uploaded file's extension was '$ext'" );
				$batteryModel->delete();
				return false;
			}
		 	if(!move_uploaded_file($uploadedFile['tmp_name'], $target) )
			 {
			 	$batteryModel->addError('upload_error', 'There was an error uploading the file please try again');
			 	$batteryModel->delete();
				return false;
			 }
			 
			 /* parse the file to get an array of cells */
			$row = 1;
			$handle = fopen($target, "r");
			
			while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) { 
			    $pos = strrpos($data[1], "-");  // position of the last hyphen
			    $serial = substr($data[1], $pos+1);
			    $type = substr($data[1], 0, $pos);
			    $is_numeric = is_numeric($data[0]);
			    
			    if($type != $batteryModel->batterytype->celltype->name)
			    {
			    	$batteryModel->addError('upload_error', "Expecting cell type $batteryModel->batterytype->celltype->name 
			    		but $type was found for cell at position $data[0]"
			    	);
			    	$batteryModel->delete();
					return false;
			    }
			    
			    /* --------------------------------------------------------------------------------------
			     * ------- REPLACED WITH CDBCriteria BELOW!!!!!!!!! -------
			     * --------------------------------------------------------------------------------------
			   $cell = Cell::model()->with(
					array(
						'kit'=>array(
							'alias'=>'kit',
							'with'=>array(
								'celltype'=>array(
									'alias'=>'type',
								)
							)
						)
					)
				)->findByAttributes(
					array(), 
					array(
						'condition'=>'type.name=:typename AND kit.serial_num=:serial AND battery_id IS NULL AND data_accepted = 1',
						'params'=>array(':typename'=>$type, ':serial'=>$serial)
					)
				);*/
			    $criteria=new CDbCriteria;
			    $criteria->with = array(
			    	'kit'=>array(
						'alias'=>'kit',
						'with'=>array(
							'celltype'=>array(
								'alias'=>'type',
							)
						)
					)
			    );
			    $criteria->together = true;
			    
			    $criteria->compare('type.name', $type);
			    $criteria->compare('kit.serial_num', $serial);
				$criteria->addcondition('battery_id IS NULL');
			    $criteria->addcondition('data_accepted=1');
			    
			    /* but are not currently on an open NCR or scrapped/eng use only */
				$criteria->addCondition('NOT EXISTS (SELECT *
											FROM tbl_ncr_cell ncr
											WHERE t.id = ncr.cell_id
											AND ncr.disposition < 3
											GROUP BY t.id)');
			    
				if($is_numeric)
			    {
			    	/* but are not currently a spare for another battery*/
					$criteria->addCondition('NOT EXISTS (SELECT *
												FROM tbl_battery_spare spare
												WHERE t.id = spare.cell_id
												GROUP BY t.id)');
			    }
				
				$cell = Cell::model()->find($criteria);
				
				if ($cell == null){
					$batteryModel->addError('upload_error', "There was an error for cell at position $data[0]. It is possible that the cell has already been 
						selected, does not exist, is Open/Scrapped/Eng Use on an NCR or the data has not yet been accepted"
					);
					$batteryModel->delete();
					return false;
				}
				
				/* put the cell into the data array as a spare if it is none numeric but has the correct configuration */
			    if($is_numeric)
			    {
			    	if(in_array($cell,array_merge($cells, $spares)))
			    	{
			    		$batteryModel->addError('upload_error', "There was duplicate cell selection at position $data[0]");
						$batteryModel->delete();
						return false;
			    	}
			    	$cells[$data[0]] = $cell;
			    } 
			    else 
			    {
			    	$position = str_replace(array('s','S'),'',$data[0]);
			    	if (is_numeric($position))
			    	{
				    	if(in_array($cell,array_merge($cells, $spares)))
				    	{
				    		$batteryModel->addError('upload_error', "There was duplicate cell selection at position $data[0]");
							$batteryModel->delete();
							return false;
				    	}
			    		$spares[$position] = $cell;
			    	} 
			    	else 
			    	{
			    		$batteryModel->addError('upload_error', "There was an error for cell at position $data[0].");
			    		$batteryModel->delete();
			    		return false;
			    	}
			    }
					
			 }
			/* assign the cells to the battery and set the location to be on EAP */
			if(!empty($cells) && count($cells)==$batteryModel->batterytype->num_cells)
			{
				foreach($cells as $position=>$cellModel)
				{
					$cellModel->battery_id = $battery_id;
					$cellModel->battery_position = $position;
					
					$cellModel->save();
				}
			}
			else {
				$batteryModel->addError('upload_error', "Not enough cells were selected, or there may have been a duplicate
					position listed.  Please fix the file and try again."
				);
				$batteryModel->delete();
				return false;
			}
			/* assign the spares to the battery and set the location to be on EAP-spare */
			$spareCount = 0;
			if(!empty($spares))
			{
				foreach($spares as $position=>$cellModel)
				{
					if($cellModel)
					{
						$spareCount += 1;
						
						/* create the batteryspares */
						$spareModel = new BatterySpare;
						
						$spareModel->cell_id = $cellModel->id;
						$spareModel->battery_id = $battery_id;
						$spareModel->position = $position;
						
						$spareModel->save();
					}
				}
			}
		}
		else
		{
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * Creates new battery and associates designated cells from the cell selection...
	 * to default states 
	 * 
	 * @param Battery $batteryModel
	 * @param Array $cells
	 */
	public static function batteryAssemble($batteryModel, $spares=array())
	{
		$spareCount = 0;
		
		if(empty($batteryModel))
			return;
		
		$batteryModel->location = '[ASSEMBLED] '.date("Y-m-d",time());
		
		if($batteryModel->save())
		{
			$battery_id = $batteryModel->id;
			
			/* Replace the cells with spares that need to be replace in the battery and remove spares from list  */
			if(!empty($spares))
			{
				foreach($spares as $cell_id=>$spare_id)
				{
					$spareCount += 1;
					
					$cellModel = Cell::model()->findByPk($cell_id);
					$spareModel = Cell::model()->findByPk($spare_id);
					
					$spareModel->battery_id = $cellModel->battery_id;
					$spareModel->battery_position = $cellModel->battery_position;
					$spareModel->save();
					
					$cellModel->battery_id = null;
					$cellModel->battery_position = null;
					$cellModel->location = '[Dispo] - Replaced by spare {'.$spareModel->kit->getFormattedSerial().'}';
					$cellModel->save();
					
					/* delete all instances of the spare as a batteryspare */
					$batterySpares = BatterySpare::model()->findAllByAttributes(array('cell_id'=>$spareModel->id));
					foreach($batterySpares as $spare)
					{
						$spare->delete();
					}
				}
			}
			
			/* Set cell location in the battery serial number*/
			if(!empty($batteryModel->cells))
			{
				foreach($batteryModel->cells as $cellModel)
				{
					$cellModel->location = '[Assembled] '.$batteryModel->batterytype->name . ' SN: '. $batteryModel->serial_num;
					$cellModel->save();
				}
			}
			
			$result = array(
				'batterytype' => $batteryModel->batterytype->name,
				'serial_num' => $batteryModel->serial_num,
				'num_spares' => $spareCount,
			);
			
			return json_encode($result);
		}
		else
		{
			return CHtml::errorSummary($batteryModel);
		}
		
		return null;
		
	}

}
