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
			array('assembly_date, ship_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, batterytype_id, ref_num_id, eap_num, serial_num, assembler_id, 
					assembly_date, ship_date, location, refnum_search,
					batterytype_search, assembler_search' , 
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
		$criteria->compare('assembler_id',$this->assembler_id,true);
		$criteria->compare('assembly_date',$this->assembly_date,true);
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
		
		$batteryModel->location = 'Assembled';
		
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
