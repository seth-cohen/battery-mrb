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
 * @property string $eap_num
 * @property string $celltype_id
 * @property integer $is_stacked
 *
 * The followings are the available model relations:
 * @property Cell[] $cells
 * @property Celltype $celltype
 * @property User $kitter
 * @property RefNum $refNum
 * 
 * 
 * @property Electrode[] $anodes
 * @property Electrode[] $cathodes
 */
class Kit extends CActiveRecord
{
	public $anodeIds = array();
	public $cathodeIds = array();
	public $stackableList = array();
	
	public $celltype_search;
	public $serial_search;
	public $refnum_search;
	public $kitter_search;
	public $anode_search;
	public $cathode_search;
	
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
			//array('serial_num, ref_num_id, kitting_date, kitter_id, anodeIds, cathodeIds, celltype_id', 'required'),
			array('serial_num, ref_num_id, kitting_date, kitter_id, celltype_id', 'required'),
			array('anodeIds, cathodeIds', 'checkLotNumbers'),
			array('serial_num, eap_num', 'length', 'max'=>50),
			array('serial_num', 'checkUniqueInType'),
			array('kitter_id, celltype_id, ref_num_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, eap_num, serial_num, ref_num_id, kitter_id, kitting_date, celltype_id, is_stacked
				refnum_search, kitter_search, celltype_search, serial_search, anode_search, cathode_search', 
				'safe', 'on'=>'search'),
		);
	}

	public function checkUniqueInType($attribute,$params) 
	{
	    if(Kit::model()->count('celltype_id=:celltype_id AND serial_num=:serial_num',
	        array(':celltype_id'=>$this->celltype_id,':serial_num'=>$this->serial_num)) > 0) 
	    {
	        if($this->hasSerialChanged())
	        {
	        	$this->addError( $attribute, "Serial No. $this->serial_num already used for this cell type!" );
	        }	    
	    }
	}
	
	public function checkLotNumbers($attribute,$params) 
	{
        if(in_array(0,$this->$attribute))
        {
        	$this->addError( $attribute, 'Select only valid '.$this->getAttributeLabel($attribute));
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
			'anodes' => array(self::MANY_MANY, 'Electrode', 'tbl_electrode_kit(kit_id, electrode_id)', 'alias'=>'anode', 'condition'=>'anode.is_anode=1'),
			'cathodes' => array(self::MANY_MANY, 'Electrode', 'tbl_electrode_kit(kit_id, electrode_id)', 'alias'=>'cathode', 'condition'=>'cathode.is_anode=0'),
		);
	}

	/**
	 * @return array of the query criteria to be used for particular query
	 */
	public function scopes()
	{
		$alias = $this->getTableAlias( false, false );
        return array(
			'oldest'=>array(
				'order'=>$alias.'.kit_date DESC',
			),
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
			'anodeIds' => 'Anode Lots',
			'cathodeIds' => 'Cathode Lots',
			'is_stacked' => 'Stacked',
		
			'celltype_search' => 'Cell Type',
		 	'refnum_search' => 'Reference No.',
			'kitter_search' => 'Kitter',
			'serial_search' => 'Serial No.',
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
						'celltype'=>array('alias'=>'celltype'), 
						'kitter'=>array('alias'=>'user'), 
						'refNum'=>array('alias'=>'ref'),	
						'anodes'=>array('alias'=>'anode'),
						'cathodes'=>array('alias'=>'cathode'),
		); // needed for alias of search parameter tables
		
		//$criteria->group = 't.id, anode.id, cathode.id';
		$criteria->together = true;
		
		$criteria->compare('id',$this->id,true);
		$criteria->compare('kitter_id',$this->kitter_id,true);
		$criteria->compare('kitting_date',$this->kitting_date,true);
		$criteria->compare('is_stacked',$this->is_stacked);
		$criteria->compare('t.eap_num',$this->eap_num, true);
		
		$criteria->compare('ref.number', $this->refnum_search, true);	
		$criteria->compare('celltype.name',$this->celltype_search, true);
		
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
		$criteria->addSearchCondition('concat(celltype.name,"-",t.serial_num)',$this->serial_search, true);
		$criteria->addSearchCondition('concat(user.first_name, " ", user.last_name)', $this->kitter_search, true);
		
		return new KeenActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'withKeenLoading' => array('anodes', 'cathodes'),
			'sort'=>array(
				'defaultOrder'=>'kitting_date',
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
					'anode_search'=>array(
						'asc'=>'anode.lot_num',
						'desc'=>'anode.lot_num DESC',
					),
					'cathode_search'=>array(
						'asc'=>'cathode.lot_num',
						'desc'=>'cathode.lot_num DESC',
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
	/*
	 * REturns one dimensional array to use to populate dropdown list stacking
	 * only kits with no cells already associated with them will show up
	 * @return 1-D array of id=>name
	 */
	public function forStackList()
	{
		if (empty($this->stackableList))
		{
			$criteria = new CDbCriteria();
			$criteria->select = array('t.id', 't.serial_num');
			$criteria->with = array(
				'celltype' => array( 'select' => '*'),
			);
			
			$kits = Kit::model()->findAll($criteria);
		
			foreach ($kits as $kit)
			{
				if(!count($kit->cells))
					$this->stackableList[$kit->id] = $kit->getFormattedSerial();
			}
		}
		 			
		return CHtml::dropDownList('Kit_list', '', $this->stackableList);
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
	
	public function getAnodeList()
	{

		$result = array();
		foreach($this->anodes as $anode)
		{
			$result[] = $anode->lot_num;
		}

		sort($result);
		return implode(', ', $result);
	}
	
	public function getCathodeList()
	{
		$result = array();
		foreach($this->cathodes as $cathode)
		{
			$result[] = $cathode->lot_num;
		}

		sort($result);
		return implode(', ', $result);
	}
	
	/**
	 * 
	 */
	public function afterFind()
	{
		foreach($this->anodes as $index=>$anode)
		{
			$this->anodeIds[] = $anode->id;
		}
		
		foreach($this->cathodes as $index=>$cathode)
		{
			$this->cathodeIds[] = $cathode->id;
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
	
	public function getListForMulti($length)
	{
		$list = array();
		for ($i=1; $i<=$length; $i++)
		{
			$list[] = array('id'=>$i);
		}
		return $list;
	}
	
	public function saveKitElectrodes($electrodes)
	{
		/* clear the join table of roles */
		$commandDelete = Yii::app()->db->createCommand();
		$commandDelete->delete('tbl_electrode_kit', 
			'kit_id = :id',
			array(':id'=>$this->id)
		);

		if(!empty($electrodes))
		{
			/* add new roles list */
			foreach($electrodes as $electrode)
			{
				$commandInsert = Yii::app()->db->createCommand();
				$commandInsert->insert('tbl_electrode_kit', array(
					'kit_id'=>$this->id,
					'electrode_id'=>$electrode,
				));
			}
		}
	}

	/**
	 * Saves all of the models only if ALL models validate
	 * returns associative array if successful (with details on saved model)
	 * returns error summary if any of them fail
	 * @param Kit[] $kitModels 
	 */
	public static function multiSaveKits($kitModels)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($kitModels))
			return;
			
		foreach($kitModels as $kit)
		{
			$model = $kit;
				
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
					$model->saveKitElectrodes(array_merge($model->anodeIds, $model->cathodeIds));
					
					$result[] = array(
						'serial'=>$model->getFormattedSerial(), 
						'kitter'=>User::getFullNameProper($model->kitter_id),
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
