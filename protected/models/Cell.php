<?php

/**
 * This is the model class for table "tbl_cell".
 *
 * The followings are the available columns in table 'tbl_cell':
 * @property string $id
 * @property string $kit_id
 * @property string $ref_num_id
 * @property string $eap_num
 * @property string $stacker_id
 * @property string $stack_date
 * @property double $dry_wt
 * @property double $wet_wt
 * @property string $filler_id
 * @property string $fill_date
 * @property string $inspector_id
 * @property string $inspection_date
 * @property string $laserwelder_id
 * @property string $laserweld_date
 * @property string $portwelder_id
 * @property string $portweld_date
 * @property string $location
 *
 * The followings are the available model relations:
 * @property Kit $kit
 * @property RefNum $refNum
 * @property User $stacker
 * @property User $filler
 * @property User $inspector
 * @property User $portwelder
 * @property User $laserwelder
 * @property TestAssignment[] $testAssignments
 */
class Cell extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_cell';
	}
	
	/* related model helpers */
	public $serial_search;
	public $celltype_search;
	public $stacker_search;
	public $filler_search;
	public $inspector_search;
	public $portwelder_search;
	public $laserwelder_search;
	public $refnum_search;
	public $anode_search;
	public $cathode_search;
	
	public $not_formed=null;
	public $formed_only=null;
	
	public $channel_error;
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('stack_date, dry_wt, wet_wt, fill_date, inspection_date', 'required', 'on'=>'create'),
			array('wet_wt', 'greaterThanDry'),
			
			array('stack_date, stacker_id, kit_id, ref_num_id, eap_num', 'required', 'on'=>'stack'),
			array('inspection_date, inspector_id', 'required', 'on'=>'inspect'),
			array('laserweld_date, laserwelder_id', 'required', 'on'=>'laser'),
			array('fill_date, filler_id, wet_wt, dry_wt', 'required', 'on'=>'fill'),
			array('portweld_date, portwelder_id', 'required', 'on'=>'tipoff'),
			
			array('eap_num', 'checkEAP'),
			array('dry_wt, wet_wt', 'numerical'),
			array('kit_id, ref_num_id, stacker_id, filler_id, inspector_id', 'length', 'max'=>10),
			array('eap_num, location', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eap_num, stack_date, dry_wt, wet_wt, fill_date, inspection_date, serial_search, celltype_search, 
					refnum_search, stacker_search, filler_search, inspector_search, laserwelder_search, portwelder_search,
					location, not_formed, formed_only, inspector_id, laserwelder_id, portwelder_id, anode_search, cathode_search', 
					'safe', 'on'=>'search'),
		);
	}

	public function greaterThanDry($attribute,$params) 
	{
		if($this->$attribute < $this->dry_wt)
		{
			$this->addError($attribute, "Wet Weight must be greater than Dry!");
		}
	}
	
	public function checkEAP($attribute,$params) 
	{
		$pattern = '/ADD$/';
		
        if(preg_match($pattern, $this->$attribute))
        {
        	$this->addError( $attribute, "EAP Addendum is missing!" );
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
			'kit' => array(self::BELONGS_TO, 'Kit', 'kit_id'),
			'refNum' => array(self::BELONGS_TO, 'RefNum', 'ref_num_id'),
			'stacker' => array(self::BELONGS_TO, 'User', 'stacker_id'),
			'filler' => array(self::BELONGS_TO, 'User', 'filler_id'),
			'inspector' => array(self::BELONGS_TO, 'User', 'inspector_id'),
			'laserwelder' => array(self::BELONGS_TO, 'User', 'laserwelder_id'),
			'portwelder' => array(self::BELONGS_TO, 'User', 'portwelder_id'),
			'testAssignments' => array(self::HAS_MANY, 'TestAssignment', 'cell_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kit_id' => 'Kit',
			'ref_num_id' => 'Reference No.',
			'eap_num' => 'EAP No.',
			'stacker_id' => 'Stacked By',
			'stack_date' => 'Stack Date',
			'dry_wt' => 'Dry Wt',
			'wet_wt' => 'Wet Wt',
			'filler_id' => 'Filled By',
			'fill_date' => 'Fill Date',
			'inspector_id' => 'Inspected By',
			'inspection_date' => 'Inspection Date',
			'laserwelder_id' => 'Laser Welded By',
			'laserweld_date' => 'Laser Weld Date',
			'portwelder_id' => 'Fill Port Welded By',
			'portweld_date' => 'Fill Port Weld Date',
			'location' => 'Location',
			
			'refnum_search' => "Reference No.",
			'serial_search' => 'Serial No.',
			'celltype_search' => 'Cell Type',
			'stacker_search' => 'Stacked By',
			'laserwelder_search' => 'Laser Welded By',
			'filler_search' => 'Filled By',
			'portwelder_search' => 'Fill Port Welded By',
			'inspector_search' => 'Inspected By',
			'activetest_search' => 'Active Test',
			'anode_search' => 'Anode Lots',
			'cathode_search' => 'Cathode Lots',
			
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
						'kit'=>array(
							'select'=>array('id','serial_num'),
							'with'=>array(
								'celltype',
								'anodes'=>array('select'=>'id, lot_num', 'alias'=>'anode'), 
								'cathodes'=>array('select'=>'id, lot_num', 'alias'=>'cathode'),
							), 
						),
						'stacker'=>array('alias'=>'stack'), 
						'filler'=>array('alias'=>'fill'), 
						'inspector'=>array('alias'=>'insp'), 
						'laserwelder'=>array('alias'=>'laser'),
						'portwelder'=>array('alias'=>'port'),
						'refNum'=>array('alias'=>'ref'),
						'testAssignments'=>array('alias'=>'test'),
		); // needed for alias of search parameter tables

		$criteria->together = true;
		
//		$criteria->compare('id',$this->id,true);
//		$criteria->compare('kit_id',$this->kit_id,true);
		$criteria->compare('t.eap_num',$this->eap_num,true);
		$criteria->compare('stack_date',$this->stack_date,true);
		$criteria->compare('location',$this->location, true);
		$criteria->compare('dry_wt',$this->dry_wt);
		$criteria->compare('wet_wt',$this->wet_wt);
		
		$criteria->compare('stacker_id',$this->stacker_id);
		$criteria->compare('filler_id',$this->filler_id);
		$criteria->compare('inspector_id',$this->inspector_id);
		$criteria->compare('laserwelder_id',$this->laserwelder_id);
		$criteria->compare('portwelder_id',$this->portwelder_id);
		
		$criteria->compare('stack_date',$this->stack_date,true);
		$criteria->compare('laserweld_date',$this->laserweld_date,true);
		$criteria->compare('portweld_date',$this->portweld_date,true);
		$criteria->compare('fill_date',$this->fill_date,true);
		$criteria->compare('inspection_date',$this->inspection_date,true);
		$criteria->compare('location',$this->location,true);
		
		$criteria->compare('celltype.name',$this->celltype_search, true);
		
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
		
		/*  enable searching for multiple lots using comma or spaces */
		if ($this->anode_search)
		{
			$anodeLots = explode(',', str_replace(' ', ',', $this->anode_search));
			$anodeLotCriteria = new CDbCriteria();
			foreach ($anodeLots as $anodeLot)
			{
				if(!empty($anodeLot))
				{
					$anodeLotCriteria->compare('anode.lot_num', $anodeLot, true, 'OR');
				}
			}
			$criteria->mergeWith($anodeLotCriteria);
		}
		
		if ($this->cathode_search)
		{
			$cathodeLots = explode(',', str_replace(' ', ',', $this->cathode_search));
			$cathodeLotCriteria = new CDbCriteria();
			foreach ($cathodeLots as $cathodeLot)
			{
				if(!empty($cathodeLot))
				{
					$cathodeLotCriteria->compare('cathode.lot_num', $cathodeLot, true, 'OR');
				}
			}
			$criteria->mergeWith($cathodeLotCriteria);
		}	
		
		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		$criteria->addSearchCondition('concat(stack.first_name, " ", stack.last_name)', $this->stacker_search);
		$criteria->addSearchCondition('concat(fill.first_name, " ", fill.last_name)', $this->filler_search);
		$criteria->addSearchCondition('concat(insp.first_name, " ", insp.last_name)', $this->inspector_search);
		$criteria->addSearchCondition('concat(laser.first_name, " ", laser.last_name)', $this->laserwelder_search);
		$criteria->addSearchCondition('concat(port.first_name, " ", port.last_name)', $this->portwelder_search);

		return new KeenActiveDataProvider($this, array(
			'withKeenLoading' => array(
				array('kit'),
				array('kit.anodes', 'kit.cathodes', 'kit.celltype'),		
				//'testAssignments'=>array('alias'=>'test'),
			),
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'refnum_search'=>array(
						'asc'=>'ref.number',
						'desc'=>'ref.number DESC',
					),
					'serial_search'=>array(
						'asc'=>"CONCAT(celltype.name, serial_num)",
						'desc'=>"CONCAT(celltype.name, serial_num) DESC",
					),
					'celltype_search'=>array(
						'asc'=>'celltype.name',
						'desc'=>'celltype.name DESC',
					),
					'stacker_search'=>array(
						'asc'=>"CONCAT(stack.first_name, ' ', stack.last_name)",
						'desc'=>"CONCAT(stack.first_name, ' ', stack.last_name) DESC",
					),
					'filler_search'=>array(
						'asc'=>"CONCAT(fill.first_name, ' ', fill.last_name)",
						'desc'=>"CONCAT(fill.first_name, ' ', fill.last_name) DESC",
					),
					'inspector_search'=>array(
						'asc'=>"CONCAT(insp.first_name, ' ', insp.last_name)",
						'desc'=>"CONCAT(insp.first_name, ' ', insp.last_name) DESC",
					),
					'laserwelder_search'=>array(
						'asc'=>"CONCAT(laser.first_name, ' ', laser.last_name)",
						'desc'=>"CONCAT(laser.first_name, ' ', laser.last_name) DESC",
					),
					'portwelder_search'=>array(
						'asc'=>"CONCAT(port.first_name, ' ', port.last_name)",
						'desc'=>"CONCAT(port.first_name, ' ', port.last_name) DESC",
					),
					'*',		// all others treated normally
				),
			),
		));
	}

	public function searchUnformed()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->select = 'id';
		$criteria->with = array(
						'kit'=>array(
							'select'=>array('id','serial_num'),
							'with'=>array(
								'celltype',
								'anodes'=>array('select'=>'id'), 
								'cathodes'=>array('select'=>'id'),
							),
						), 
						'refNum'=>array('alias'=>'ref'),
						'testAssignments'=>array('alias'=>'test', 'select'=>'is_formation'),
		); // needed for alias of search parameter tables

		$criteria->together = true;
		
		$criteria->compare('stack_date',$this->stack_date,true);
		$criteria->compare('fill_date',$this->fill_date,true);

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
		
		$criteria->addcondition('test.cell_id IS NULL');
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		
		return new KeenActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'withKeenLoading' => array(
				'kit'=>array('select'=>array('celltype','serial_num')),
				//'testAssignments'=>array('alias'=>'test'),
			),
			'sort'=>array(
				'attributes'=>array(
					'refnum_search'=>array(
						'asc'=>'ref.number',
						'desc'=>'ref.number DESC',
					),
					'serial_search'=>array(
						'asc'=>"CONCAT(celltype.name, serial_num)",
						'desc'=>"CONCAT(celltype.name, serial_num) DESC",
					),
					'*',		// all others treated normally
				),
			),
		));
	}
	
	public function searchAtForm()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->select = 'id, eap_num';
		$criteria->with = array(
						'kit'=>array(
							'select'=>array('id','serial_num'),
							'with'=>array(
								'celltype',
								'anodes'=>array('select'=>'id'), 
								'cathodes'=>array('select'=>'id'),
							),
						), 
						'refNum'=>array('alias'=>'ref'),
						//'testAssignments'=>array('alias'=>'test', 'select'=>'is_formation, id'),
		); // needed for alias of search parameter tables

		$criteria->together = true;
		
		$criteria->compare('stack_date',$this->stack_date,true);
		$criteria->compare('fill_date',$this->fill_date,true);

		/* for tipoff filtering */
		$criteria->compare('filler_id',$this->filler_id);
		$criteria->compare('portwelder_id',$this->portwelder_id);
		
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
		
		/* look for cells with formation test assignments  that are active*/
		$criteria->addCondition('EXISTS (SELECT test.id, test.is_formation, test.is_active
											FROM tbl_test_assignment test
											WHERE t.id = test.cell_id
											AND test.is_formation = 1
											AND test.is_active = 1
											GROUP BY t.id)');
		//$criteria->addcondition('t.location LIKE "[FORM]%"');
		
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		
		return new KeenActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'withKeenLoading' => array(
				'kit'=>array('select'=>array('celltype','serial_num')),
				//'testAssignments'=>array('alias'=>'test'),
			),
			'sort'=>array(
				'attributes'=>array(
					'refnum_search'=>array(
						'asc'=>'ref.number',
						'desc'=>'ref.number DESC',
					),
					'serial_search'=>array(
						'asc'=>"CONCAT(celltype.name, serial_num)",
						'desc'=>"CONCAT(celltype.name, serial_num) DESC",
					),
					'*',		// all others treated normally
				),
			),
		));
	}
	
	public function searchFormed()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->select = 'id, eap_num';
		$criteria->with = array(
						'kit'=>array(
							'select'=>array('id','serial_num'),
							'with'=>array(
								'celltype',
								'anodes'=>array('select'=>'id'), 
								'cathodes'=>array('select'=>'id'),
							),
						), 
						'refNum'=>array('alias'=>'ref'),
						'testAssignments'=>array('alias'=>'test', 'select'=>'is_active, id'),
		); // needed for alias of search parameter tables

		$criteria->together = true;
		
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
		
		/* cells that have been through formation */
		$criteria->addCondition('EXISTS (SELECT test.id, test.is_formation
											FROM tbl_test_assignment test
											WHERE t.id = test.cell_id
											AND test.is_formation = 1
											AND test.is_active = 0
											GROUP BY t.id)');
		
		/* but are not currently on CAT*/
		$criteria->addCondition('NOT EXISTS (SELECT test.id, test.is_active
											FROM tbl_test_assignment test
											WHERE t.id = test.cell_id
											AND test.is_active = 1
											GROUP BY t.id)');
		
		//$criteria->addcondition('t.location LIKE "[FORM]%"');
		
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		
		return new KeenActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'withKeenLoading' => array(
				'kit'=>array('select'=>array('celltype','serial_num')),
				'testAssignments'=>array('alias'=>'test'),
			),
			'sort'=>array(
				'attributes'=>array(
					'refnum_search'=>array(
						'asc'=>'ref.number',
						'desc'=>'ref.number DESC',
					),
					'serial_search'=>array(
						'asc'=>"CONCAT(celltype.name, serial_num)",
						'desc'=>"CONCAT(celltype.name, serial_num) DESC",
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
	 * @return Cell the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function createStackedCells($stackedCells)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($stackedCells))
			return;
			
		foreach($stackedCells as $cell)
		{
			$model = $cell;
			$model->scenario = 'stack';
			
			$model->location = 'stacked';
				
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
					$kit = Kit::model()->findByPk($model->kit_id);
					$kit->is_stacked = 1;
					$kit->save();
					
					$result[] = array(
						'serial'=>$kit->getFormattedSerial(), 
						'stacker'=>User::getFullNameProper($model->stacker_id),
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

	public static function inspectCells($inspectedCells)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($inspectedCells))
			return;
			
		foreach($inspectedCells as $cell_id=>$cell)
		{
			$model = Cell::model()->findByPk($cell_id);
			$model->scenario = 'inspect';
					 
			$model->inspector_id = $cell->inspector_id;
			$model->inspection_date = $cell->inspection_date;
			$model->location = 'inspected';
				
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
						'serial'=>$model->kit->getFormattedSerial(), 
						'inspector'=>User::getFullNameProper($model->inspector_id),
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

	public static function laserCells($laseredCells)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($laseredCells))
			return;
			
		foreach($laseredCells as $cell_id=>$cell)
		{
			$model = Cell::model()->findByPk($cell_id);
			$model->scenario = 'laser';
					 
			$model->laserwelder_id = $cell->laserwelder_id;
			$model->laserweld_date = $cell->laserweld_date;
			$model->location = 'laserwelded';
				
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
						'serial'=>$model->kit->getFormattedSerial(), 
						'laserwelder'=>User::getFullNameProper($model->laserwelder_id),
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

	public static function fillCells($filledCells)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($filledCells))
			return;
			
		foreach($filledCells as $cell_id=>$cell)
		{
			$model = Cell::model()->findByPk($cell_id);
			$model->scenario = 'fill';
					 
			$model->filler_id = $cell->filler_id;
			$model->fill_date = $cell->fill_date;
			$model->dry_wt = $cell->dry_wt;
			$model->wet_wt = $cell->wet_wt;
			$model->location = 'filled';
				
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
						'serial'=>$model->kit->getFormattedSerial(), 
						'filler'=>User::getFullNameProper($model->filler_id),
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
	
	public static function tipoffCells($cellsTippedoff)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($cellsTippedoff))
			return;
			
		foreach($cellsTippedoff as $cell_id=>$cell)
		{
			$model = Cell::model()->findByPk($cell_id);
			$model->scenario = 'tipoff';
					 
			$model->portwelder_id = $cell->portwelder_id;
			$model->portweld_date = $cell->portweld_date;
			$model->location = 'Fillport Weld';
				
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
					/* set previous channel assignment to free */
					/* get the latest testAssignment to find current channel */
					$testAssignment = TestAssignment::model()->latest()->findByAttributes(
						array(
							'cell_id'=>$model->id,
							'is_formation'=>1,
						)
					);
					
					if($testAssignment!=null)
					{
						$testAssignment->is_active = 0;
						$testAssignment->save();
						
						$testAssignment->channel->in_use = 0;
						$testAssignment->channel->save();
					}
					$result[] = array(
						'serial'=>$model->kit->getFormattedSerial(), 
						'portwelder'=>User::getFullNameProper($model->portwelder_id),
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
	
	public static function getColumnList()
	{
		$results = array();
		
		$columns = array(
			'Serial No.', 'Reference No.',
			'EAP No.', 'Cell Type',
			'Stacker', 'Stack Date',
			'Inspector', 'Inspection Date',
			'Laser Welder', 'Laser Weld Date',
			'Filler', 'Fill Date',
			'Fillport Welder', 'Fillport Weld Date',
			'Dry Wt(g)', 'Wet wt(g)',
			'Anode Lots', 'Cathode Lots',
			'Location'
		);
		foreach($columns as $id=>$column)
		{
			$result[] = array('id'=>$id+1, 'value'=>$column);
		}
		return $result;
	}
}
