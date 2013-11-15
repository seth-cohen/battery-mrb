<?php

/**
 * This is the model class for table "tbl_kit".
 *
 * The followings are the available columns in table 'tbl_kit':
 * @property string $id
 * @property string $serial_num
 * @property string $ref_num_id
 * @property string $kitter_id
 * @property string $kitting_date
 * @property string $celltype_id
 *
 * The followings are the available model relations:
 * @property Cell[] $cells
 * @property Celltype $celltype
 * @property User $kitter
 * @property RefNum $refNum
 * 
 * @property Electrode[] $electrodes
 */
class Kit extends CActiveRecord
{
	public $electrodeIds = array();
	public $anodeIds = array();
	public $cathodeIds = array();
	
	public $celltype_search;
	public $serial_search;
	public $refnum_search;
	public $kitter_search;
	
	public $previousSerialNum = null;
	public $previousCellType = null;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_kit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('serial_num, ref_num_id, kitting_date, kitter_id, anodeIds, cathodeIds, kitter_search', 'required'),
			array('anodeIds, cathodeIds', 'checkLotNumbers'),
			array('serial_num, eap_num', 'length', 'max'=>50),
			array('serial_num, celltype_id', 'checkUniqueInType'),
			array('kitter_id, celltype_id, ref_num_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, eap_num, serial_num, ref_num_id, kitter_id, kitting_date, celltype_id, refnum_search, kitter_search, celltype_search, serial_search', 'safe', 'on'=>'search'),
		);
	}

	public function checkUniqueInType($attribute,$params) 
	{
	    if(Kit::model()->count('celltype_id=:celltype_id AND serial_num=:serial_num',
	        array(':celltype_id'=>$this->celltype_id,':serial_num'=>$this->serial_num)) > 0) 
	    {
	        if($this->hasSerialChanged())
	        {
	        	$this->addError( $attribute, "Serial No. already used for this cell type!" );
	        }	    
	    }
	}
	
	public function checkLotNumbers($attribute,$params) 
	{
        if(in_array(0,$this->$attribute))
        {
        	$this->addError( $attribute, 'Please select valid '.$this->getAttributeLabel($attribute));
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
			'cells' => array(self::HAS_MANY, 'Cell', 'kit_id'),
			'celltype' => array(self::BELONGS_TO, 'Celltype', 'celltype_id'),
			'kitter' => array(self::BELONGS_TO, 'User', 'kitter_id'),
			'refNum' => array(self::BELONGS_TO, 'RefNum', 'ref_num_id'),
			'electrodes' => array(self::MANY_MANY, 'Electrode', 'tbl_electrode_kit(kit_id, electrode_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lot_num' => 'Lot No.',
			'ref_num_id' => 'Ref No.',
			'kitter_id' => 'Kitter',
			'kitting_date' => 'Kitting Date',
			'serial_num' => 'Serial No.',
			'celltype_id' => 'Cell Type',
		
			'celltype_search' => 'Cell Type',
		 	'refnum_search' => 'Reference No.',
			'kitter_search' => 'Kitter',
			'serial_search' => 'Serial No.',
			'anodeIds' => 'Anode Lot',
			'cathodeIds' => 'Cathode Lot',
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
						'celltype'=>array('alias'=>'celltype'), 
						'kitter'=>array('alias'=>'user'), 
						'refNum'=>array('alias'=>'ref'),
		); // needed for alias of search parameter tables
		
		$criteria->compare('id',$this->id,true);
		$criteria->compare('lot_num',$this->lot_num,true);
		$criteria->compare('ref_num',$this->ref_num,true);
		$criteria->compare('anode_id',$this->anode_id,true);
		$criteria->compare('cathode_id',$this->cathode_id,true);
		$criteria->compare('kitter_id',$this->kitter_id,true);
		$criteria->compare('kitting_date',$this->kitting_date,true);
		
		$criteria->compare('ref.number', $this->refnum_search, true);	
		$criteria->compare('celltype.name',$this->celltype_search, true);

		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(celltype.name,"-",t.serial_num)',$this->serial_search, true);
		$criteria->addSearchCondition('concat(user.first_name, " ", user.last_name)', $this->kitter_search);
		
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
					'kitter_search'=>array(
						'asc'=>"CONCAT(first_name, ' ', last_name)",
						'desc'=>"CONCAT(first_name, ' ', last_name) DESC",
					),
					'*',		// all others treated normally
				),
			),
		));
	}

	/*
	 * REturns one dimensional array to use to populate dropdown list for filtering
	 * @return 1-D array of id=>name
	 */
	public function forList()
	{
		$arr = array();
		$kits = Kit::model()->findAll();
	
		$arr[''] = 'All';
		foreach ($kits as $kit)
		{
			$arr[$kit->id] = $kit->celltype->name.'-'.$kit->serial_num;
		}
		 			
		return $arr;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Kit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getElectrodesList($isAnode)
	{
		$result = array();
		foreach($this->electrodes as $electrode)
		{
			if($electrode->is_anode == $isAnode)
			{
				$result[] = $electrode->lot_num;
			}
		}
		return implode(', ', $result);
	}
	/**
	 * 
	 */
	public function afterFind()
	{
		if (!empty($this->electrodes))
		{
			foreach($this->electrodes as $index=>$electrode)
			{
				$this->electrodeIds[] = $electrode->id;
				if($electrode->is_anode==1)
				{
					$this->anodeIds[] = $electrode->id;
				}
				else 
				{
					$this->cathodeIds[] = $electrode->id;
				}
			}
		}
		
		$this->previousSerialNum = $this->serial_num;
		$this->previousCellType = $this->celltype_id;
		
		return parent::afterFind();
		
	}
	
	public function hasSerialChanged()
	{
		if($this->previousCellType == null || $this->previousSerialNum == null)
		{	/* this is a new record and serial number must be validated */
			return true;
		}
		else
		{ 	/* if either have changed must validate */
			return ($this->previousCellType !== $this->celltype_id || $this->previousSerialNum !== $this->serial_num);
			
		}
	}
	
	public function getFormattedSerial()
	{
		return $this->celltype->name.'-'.$this->serial_num;
	}
}
