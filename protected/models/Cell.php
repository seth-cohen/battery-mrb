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
 * @property string $location
 *
 * The followings are the available model relations:
 * @property Kit $kit
 * @property RefNum $refNum
 * @property User $stacker
 * @property User $filler
 * @property User $inspector
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
	public $refnum_search;
	
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
			array('stack_date, stacker_id, kit_id', 'required', 'on'=>'stack'),
			array('wet_wt', 'greaterThanDry'),
			array('fill_date, filler_id, wet_wt, dry_wt', 'required', 'on'=>'fill'),
			array('inspection_date, inspector_id', 'required', 'on'=>'inspect'),
			
			array('eap_num', 'checkEAP'),
			array('dry_wt, wet_wt', 'numerical'),
			array('kit_id, ref_num_id, stacker_id, filler_id, inspector_id', 'length', 'max'=>10),
			array('eap_num, location', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eap_num, stack_date, dry_wt, wet_wt, fill_date, inspection_date, serial_search, celltype_search, 
					refnum_search, stacker_search, filler_search, inspector_search, location,
					not_formed, formed_only', 'safe', 'on'=>'search'),
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
			'ref_num' => 'Reference No.',
			'eap_num' => 'EAP No.',
			'stacker_id' => 'Stacker',
			'stack_date' => 'Stack Date',
			'dry_wt' => 'Dry Wt',
			'wet_wt' => 'Wet Wt',
			'filler_id' => 'Filler',
			'fill_date' => 'Fill Date',
			'inspector_id' => 'Inspector',
			'inspection_date' => 'Inspection Date',
			'location' => 'Location',
			
			'refnum_search' => "Reference No.",
			'serial_search' => 'Serial No.',
			'celltype_search' => 'Cell Type',
			'stacker_search' => 'Stacker',
			'filler_search' => 'Filler',
			'inspector_search' => 'Inspector',
			
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
						'kit'=>array('with'=>'celltype'), 
						'stacker'=>array('alias'=>'stack'), 
						'filler'=>array('alias'=>'fill'), 
						'inspector'=>array('alias'=>'insp'), 
						'refNum'=>array('alias'=>'ref'),
						'testAssignments'=>array('alias'=>'test'),
		); // needed for alias of search parameter tables

		$criteria->together = true;
		
//		$criteria->compare('id',$this->id,true);
//		$criteria->compare('kit_id',$this->kit_id,true);
		$criteria->compare('t.eap_num',$this->eap_num,true);
		$criteria->compare('stack_date',$this->stack_date,true);
		$criteria->compare('location',$this->location, true);
//		$criteria->compare('dry_wt',$this->dry_wt);
//		$criteria->compare('wet_wt',$this->wet_wt);
		$criteria->compare('filler_id',$this->filler_id);
		$criteria->compare('inspector_id',$this->inspector_id);
		$criteria->compare('fill_date',$this->fill_date,true);
		$criteria->compare('inspection_date',$this->inspection_date,true);
		
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
		
		if($this->not_formed)
		{
			$formCriteria = new CDbCriteria();
			$formCriteria->addcondition('test.cell_id IS NULL');
			$criteria->mergeWith($formCriteria);
		}
		
		if($this->formed_only)
		{
			$formCriteria = new CDbCriteria();
			$formCriteria->addcondition('test.cell_id = t.id');
			$criteria->mergeWith($formCriteria);
		}
		
		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		$criteria->addSearchCondition('concat(stack.first_name, " ", stack.last_name)', $this->stacker_search);
		$criteria->addSearchCondition('concat(fill.first_name, " ", fill.last_name)', $this->filler_search);
		$criteria->addSearchCondition('concat(insp.first_name, " ", insp.last_name)', $this->inspector_search);

		return new CActiveDataProvider($this, array(
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
							'with'=>array('celltype',
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

		$criteria->select = 'id';
		$criteria->with = array(
						'kit'=>array(
							'select'=>array('id','serial_num'),
							'with'=>array('celltype',
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
		
		/* cells at form will have only 1 test_assignment and it will be formation */
		$criteria->addCondition('EXISTS (SELECT test.id, test.is_formation
											FROM tbl_test_assignment test
											WHERE t.id = test.cell_id
											GROUP BY t.id
											HAVING COUNT(test.id) = 1
											AND test.is_formation = 1)');
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
			$model = new Cell('stack');
					 
			$model->stacker_id = $cell['stacker_id'];
			$model->stack_date = $cell['stack_date'];
			$model->ref_num_id = $cell['ref_num_id'];
			$model->eap_num = $cell['eap_num'];
			$model->location = $cell['location'];
			$model->kit_id = $cell['kit_id'];
				
				
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
}
