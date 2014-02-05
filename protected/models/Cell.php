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
 * @property integer $data_accepted
 * @property string $battery_id
 * @property string $battery_position
 *
 * The followings are the available model relations:
 * @property Battery $battery
 * @property Kit $kit
 * @property RefNum $refNum
 * @property User $stacker
 * @property User $filler
 * @property User $inspector
 * @property User $portwelder
 * @property User $laserwelder
 * @property TestAssignment[] $testAssignments
 * @property Ncr[] $ncrs
 * 
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
	public $ncr_search;
	public $battery_search;
	
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
			array('data_accepted', 'required', 'on'=>'accept'),
			array('location', 'required', 'on'=>'storage'),
			
			array('eap_num', 'checkEAP'),
			array('dry_wt, wet_wt', 'numerical'),
			array('kit_id, ref_num_id, stacker_id, filler_id, inspector_id', 'length', 'max'=>10),
			array('eap_num, location', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eap_num, stack_date, dry_wt, wet_wt, fill_date, inspection_date, serial_search, celltype_search, 
					refnum_search, stacker_search, filler_search, inspector_search, laserwelder_search, portwelder_search,
					location, not_formed, formed_only, inspector_id, laserwelder_id, portwelder_id, anode_search, cathode_search,
					battery_search, battery_id, ncr_search', 
					'safe', 'on'=>'search'
			),
			array('serial_search, celltype_search, refnum_search, ncr_search', 
					'safe', 'on'=>'searchOnNCR'
			),
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
		$pattern = '/ADD\s$|ADD$/';
		
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
			'battery' => array(self::BELONGS_TO, 'Battery', 'battery_id'),
			'kit' => array(self::BELONGS_TO, 'Kit', 'kit_id'),
			'refNum' => array(self::BELONGS_TO, 'RefNum', 'ref_num_id'),
			'stacker' => array(self::BELONGS_TO, 'User', 'stacker_id'),
			'filler' => array(self::BELONGS_TO, 'User', 'filler_id'),
			'inspector' => array(self::BELONGS_TO, 'User', 'inspector_id'),
			'laserwelder' => array(self::BELONGS_TO, 'User', 'laserwelder_id'),
			'portwelder' => array(self::BELONGS_TO, 'User', 'portwelder_id'),
			'testAssignments' => array(self::HAS_MANY, 'TestAssignment', 'cell_id'),
			'ncrs' => array(self::MANY_MANY, 'Ncr', 'tbl_ncr_cell(cell_id, ncr_id)'),
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
			'data_accepted' => 'Data Accepted',
			
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
			'ncr_search' => 'NCRs',
			
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
								'anodes'=>array('select'=>'id, lot_num', 'alias'=>'anodes'), 
								'cathodes'=>array('select'=>'id, lot_num', 'alias'=>'cathodes'),
							), 
						),
						'stacker'=>array('alias'=>'stack'), 
						'filler'=>array('alias'=>'fill'), 
						'inspector'=>array('alias'=>'insp'), 
						'laserwelder'=>array('alias'=>'laser'),
						'portwelder'=>array('alias'=>'port'),
						'refNum'=>array('alias'=>'ref'),
						'testAssignments'=>array('alias'=>'test'),
						'ncrs',
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
		
		/*  enable searching for multiple NCRS using comma or spaces */
		if ($this->ncr_search)
		{
			$ncrs = explode(',', str_replace(' ', ',', $this->ncr_search));
			$ncrCriteria = new CDbCriteria();
			foreach ($ncrs as $ncr)
			{
				if(!empty($ncr))
				{
					$ncrCriteria->compare('ncrs.number', $ncr, true, 'OR');
				}
			}
			$criteria->mergeWith($ncrCriteria);
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
		$criteria->compare('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		$criteria->addSearchCondition('concat(stack.first_name, " ", stack.last_name)', $this->stacker_search);
		$criteria->addSearchCondition('concat(fill.first_name, " ", fill.last_name)', $this->filler_search);
		$criteria->addSearchCondition('concat(insp.first_name, " ", insp.last_name)', $this->inspector_search);
		$criteria->addSearchCondition('concat(laser.first_name, " ", laser.last_name)', $this->laserwelder_search);
		$criteria->addSearchCondition('concat(port.first_name, " ", port.last_name)', $this->portwelder_search);

		return new KeenActiveDataProvider($this, array(
			'withKeenLoading' => array(
				array('ncrs'),
				array('kit'),
				array('kit.anodes', 'kit.cathodes', 'kit.celltype'),		
				//'testAssignments'=>array('alias'=>'test'),
			),
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'CONCAT(celltype.name, serial_num)',
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
					'ncr_search'=>array(
						'asc'=>'ncrs.number',
						'desc'=>'ncrs.number DESC',
					),
					'*',		// all others treated normally
				),
			),
		));
	}

	/**
	 *Searches but only finds cells that are on NCRs.
	 */
	public function searchOnNCR()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->with = array(
						'kit'=>array(
							'select'=>array('id','serial_num'),
							'with'=>array(
								'celltype',
								'anodes'=>array('select'=>'id, lot_num', 'alias'=>'anodes'), 
								'cathodes'=>array('select'=>'id, lot_num', 'alias'=>'cathodes'),
							), 
						),
						'refNum'=>array('alias'=>'ref'),
						'ncrs',
		); // needed for alias of search parameter tables

		$criteria->together = true;
		
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
		
		/*  enable searching for multiple NCRS using comma or spaces */
		if ($this->ncr_search)
		{
			$ncrs = explode(',', str_replace(' ', ',', $this->ncr_search));
			$ncrCriteria = new CDbCriteria();
			foreach ($ncrs as $ncr)
			{
				if(!empty($ncr))
				{
					$ncrCriteria->compare('ncrs.number', $ncr, true, 'OR');
				}
			}
			$criteria->mergeWith($ncrCriteria);
		}
		
		/* and have been put on an NCR */
		$criteria->addCondition('EXISTS (SELECT *
											FROM tbl_ncr_cell ncrs
											WHERE t.id = ncrs.cell_id
											GROUP BY t.id)');
		
		/* for concatenated user name search */
		$criteria->compare('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);

		return new KeenActiveDataProvider($this, array(
			'withKeenLoading' => array(
				array('ncrs'),
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
					'ncr_search'=>array(
						'asc'=>'ncrs.number',
						'desc'=>'ncrs.number DESC',
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
	
	public function searchForCAT()
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
		
		/*and aren't already in a built battery */
		$criteria->addCondition('NOT EXISTS (SELECT batt.id, batt.assembler_id
											FROM tbl_battery batt
											WHERE t.battery_id = batt.id
											AND batt.assembler_id <> 1
											GROUP BY t.id)');
	
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
	
	public function searchCATComplete()
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
		
		/* all cells that have  CAT testing that is completed*/
		$criteria->addCondition('EXISTS (SELECT test.id, test.is_active
											FROM tbl_test_assignment test
											WHERE t.id = test.cell_id
											AND test.is_formation = 0
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
		$criteria->addCondition('data_accepted = 0');
		
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
	 * Cells that haven't been filled should not need to be put into storage and should not
	 * be present in this dataprovider.
	 */
	public function searchForStorage()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->select = 'id, eap_num, location';
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
		
		/* cell not selected for a battery yet */
		$criteria->addCondition('portwelder_id <> 1');
		
		/* cell not selected for a battery yet */
		$criteria->addCondition('battery_id is null');
		
		/* or, if selected the battery hasn't been built yet */
		$criteria->addCondition('EXISTS (SELECT batt.id, batt.assembler_id
											FROM tbl_battery batt
											WHERE t.battery_id = batt.id
											AND batt.assembler_id = 1
											GROUP BY t.id)', 'OR');
				
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		
		$criteria->compare('location',$this->location, true);
		
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
	 * Cells that have been selected for a battery but the battery has not yet been built
	 */
	public function searchForDelivery()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->select = 'id, eap_num, location';
		$criteria->with = array(
						'kit'=>array(
							'select'=>array('id','serial_num'),
							'with'=>array(
								'celltype',
								'anodes'=>array('select'=>'id'), 
								'cathodes'=>array('select'=>'id'),
							),
						), 
						'battery'=>array(
							'select'=>array('id', 'serial_num'),
							'with'=>array(
								'batterytype',
							),
						),
						'refNum'=>array('alias'=>'ref'),
						'testAssignments'=>array('alias'=>'test', 'select'=>'is_active, id'),
		); // needed for alias of search parameter tables

		$criteria->together = true;
		
		/*if selected the battery hasn't been built yet */
		$criteria->addCondition('EXISTS (SELECT batt.id, batt.assembler_id
											FROM tbl_battery batt
											WHERE t.battery_id = batt.id
											AND batt.assembler_id = 1
											GROUP BY t.id)', 'OR');
				
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		
		$criteria->compare('location',$this->location, true);
		
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
		
		/* for concatenated user name search */
		$criteria->compare('concat(batterytype.name,"-",battery.serial_num)',$this->battery_search, true);
		
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
					'battery_search'=>array(
						'asc'=>"CONCAT(batterytype.name, battery.serial_num)",
						'desc'=>"CONCAT(batterytype.name, battery.serial_num) DESC",
					),
					'*',		// all others treated normally
				),
			),
		));
	}

	public function searchInBattery($pageSize, $currentPage)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->select = 'id, battery_position';
		$criteria->with = array(
						'kit'=>array(
							'select'=>array('id','serial_num'),
							'with'=>array(
								'celltype',
								'anodes'=>array('select'=>'id'), 
								'cathodes'=>array('select'=>'id'),
							),
						), 
		); // needed for alias of search parameter tables

		$criteria->together = true;
		
		$criteria->compare('battery_id',$this->battery_id, true);
		$criteria->compare('battery_position',$this->battery_position, true);
		
		return new KeenActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => $pageSize, 'currentPage'=>$currentPage),
			'criteria'=>$criteria,
			'withKeenLoading' => array(
				'kit'=>array('select'=>array('celltype','serial_num')),
				//'testAssignments'=>array('alias'=>'test'),
			),
			'sort'=>array(
				'defaultOrder' => 'battery_position'
			),
		));
	}
	
	/**
	 * Returns a comma separated list of links to the NCRs that cell has been on 
	 * Open NCRs will be bold
	 */
	public function getNCRLinks()
	{
		$result = array();

		foreach($this->ncrs as $ncr)
		{
			$style = '';
			$ncrCell = NcrCell::model()->findByAttributes(array('cell_id'=>$this->id, 'ncr_id'=>$ncr->id));
			
			if($ncrCell!= null && $ncrCell->disposition < 3)
			{
				$style = 'color:red; font-weight:bold;';
			}
			$result[] = CHtml::link($ncr->number, Yii::app()->createUrl('ncr/view', array('id'=>$ncr->id)), array(
				'style'=>$style,
			));
		}

		return implode(', ', $result);
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
							'is_active'=>1,
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
	
	/**
	 * saves cell records and updates as data accepted
	 * This function is different than the other mmulti save functions in that the
	 * parameter passed is not an associative array containing actual Cell objects
	 * it is an array of cell_ids to be updated.
	 * 
	 * @param array $acceptedCells
	 */
	public static function acceptData(array $acceptedCells)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($acceptedCells))
			return;
			
		foreach($acceptedCells as $cell_id)
		{
			$model = Cell::model()->findByPk($cell_id);
			$model->scenario = 'accept';
					 
			$model->data_accepted = 1;
				
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
	 * Provides the ability for the user to change cell location to a storage chamber
	 * It will also set any active testassignments on that cell to inactive/complete.
	 * -This function is different than the other mmulti save functions in that the
	 * parameter passed is not an associative array containing actual Cell objects
	 * it is an associative array of cell_ids and storage location string.
	 * 
	 * @param array $storageCells
	 */
	public static function moveCellsToStorage(array $cellStorageLocations)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($cellStorageLocations))
			return;
			
		foreach($cellStorageLocations as $cell_id=>$location)
		{
			$model = Cell::model()->findByPk($cell_id);
			$model->scenario = 'location';
					 
			$model->location = $location;
				
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
					/* set previous channel assignment to free and TestAssignment to completed */
					/* get the latest testAssignment to find current channel */
					$testAssignment = TestAssignment::model()->latest()->findByAttributes(
						array(
							'cell_id'=>$model->id,
							'is_active'=>1,
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
						'location'=> $model->location,
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
	 * Provides the ability for the user to deliver a cell to battery assembly
	 * It sets the location as [EAP} or [EAP-Spare] depedning on battery
	 * position.
	 * -The parameter is an array of cell_ids
	 * @param array $deliveredCells
	 */
	public static function deliverCellsToAssembly(array $deliveredCells)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($deliveredCells))
			return;
			
		foreach($deliveredCells as $cell_id)
		{
			$model = Cell::model()->findByPk($cell_id);
			$batteryModel = Battery::model()->findByPk($model->battery_id);

			$model->location = ($model->battery_position>1000)?'[EAP-Spare] ':'[EAP] ';
			$model->location .= $batteryModel->batterytype->name;
			$model->location .= '- SN: '.$batteryModel->serial_num;
				
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
					/* set previous channel assignment to free and TestAssignment to completed */
					/* get the latest testAssignment to find current channel */
					$testAssignment = TestAssignment::model()->latest()->findByAttributes(
						array(
							'cell_id'=>$model->id,
							'is_active'=>1,
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
						'location'=> $model->location,
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
			'NCRs', 'Location'
		);
		foreach($columns as $id=>$column)
		{
			$result[] = array('id'=>$id+1, 'value'=>$column);
		}
		return $result;
	}
}
