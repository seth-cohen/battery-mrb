<?php

/**
 * This is the model class for table "tbl_test_assignment".
 *
 * The followings are the available columns in table 'tbl_formation_detail':
 * @property string $id
 * @property string $cell_id
 * @property string $channel_id
 * @property string $chamber_id
 * @property string $operator_id
 * @property string $formation_start
 * @property integer $is_formation
 * @property integer $is_active
 * @property integer $is_conditioning
 * @property integer $is_misc
 * @property string $test_start_time
 * @property string $desc
 *
 * The followings are the available model relations:
 * @property Cell $cell
 * @property Chamber $chamber
 * @property Channel $channel
 * @property User $operator
 */
class TestAssignment extends CActiveRecord
{
	const FORMATION = 0;
	const CAT = 1;
	const CONDITIONING = 2;
	const MISC = 3;
	
	public $serial_search;
	public $chamber_search;
	public $cycler_search;
	public $battery_search;
	public $operator_search;
	public $type_search = 4;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_test_assignment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cell_id, channel_id, chamber_id, operator_id, test_start, test_start_time', 'required'),
			array('cell_id, channel_id, chamber_id, operator_id', 'length', 'max'=>10),
			array('test_start_time', 'length', 'max'=>10),
			array('desc','length', 'max'=>50),
			array('is_formation, is_active, is_conditioning, is_misc', 'numerical', 'integerOnly'=>true),
			array('test_start, desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cell_id, channel_id, chamber_id, operator_id, test_start, is_active, is_formation, is_misc, 
					serial_search, chamber_search, cycler_search, is_conditioning, test_start_time, type_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'cell' => array(self::BELONGS_TO, 'Cell', 'cell_id'),
			'chamber' => array(self::BELONGS_TO, 'Chamber', 'chamber_id'),
			'channel' => array(self::BELONGS_TO, 'Channel', 'channel_id'),
			'operator' => array(self::BELONGS_TO, 'User', 'operator_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cell_id' => 'Cell',
			'channel_id' => 'Channel',
			'chamber_id' => 'Chamber',
			'operator_id' => 'Operator',
			'test_start' => 'Test Date',
			'test_start_time' => 'Start Time',
			'desc' => 'Description',
		
			'serial_search' => 'Cell Serial',
			'chamber_search' => 'Chamber',
			'cycler_search' => 'Cycler {Channel}',
			'refNum_search' => 'Reference No.',
			'operator_search' => 'Operator',
		);
	}

	/**
	 * @return array of the query criteria to be used for particular query
	 */
	public function scopes()
	{
		$alias = $this->getTableAlias( false, false );
        return array(
			'latest'=>array(
				'order'=>$alias.'.id DESC',
        		'limit'=>1,
			),
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
			'channel'=>array(
				'alias'=>'chan', 
				'with'=>array(
					'cycler'=>array('alias'=>'cyc'),
				),
			),
			'chamber'=>array('alias'=>'cham'),
			'cell'=>array(
				'alias'=>'cell',
				'with'=>array(
					'kit'=>array('alias'=>'kit', 'with'=>array('celltype', 'anodes', 'cathodes')),
				),
			),
		);
		$criteria->compare('id',$this->id,true);
		$criteria->compare('cell_id',$this->cell_id,true);
		$criteria->compare('channel_id',$this->channel_id,true);
		$criteria->compare('chamber_id',$this->chamber_id,true);
		$criteria->compare('operator_id',$this->operator_id,true);
		$criteria->compare('test_start',$this->test_start,true);
		$criteria->compare('is_active',$this->is_active,true);
		$criteria->compare('test_start_time',$this->test_start_time,true);
		
		$criteria->compare('cham.name',$this->chamber_search,true);
		
		// search for test type
		if($this->type_search == self::FORMATION) //formation
		{
			$criteria->compare('is_formation', 1);
		}
		elseif ($this->type_search == self::CAT) //CAT
		{
			$criteria->compare('is_formation',0); 
			$criteria->compare('is_conditioning',0); 
			$criteria->compare('is_misc', 0);
		}
		elseif ($this->type_search == self::CONDITIONING) // conditioning
		{	
			$criteria->compare('is_conditioning', 1);
		}
		elseif ($this->type_search == self::MISC) // conditioning
		{	
			$criteria->compare('is_misc', 1);
		}

		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		$criteria->addSearchCondition('concat(cyc.name,"-",chan.number)',$this->cycler_search, true);
		
		return new KeenActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => 16),
			'withKeenLoading'=>array(
				'cell',
				'chamber',
				'channel'=>array('with'=>'cycler'),
			),
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'CONCAT(celltype.name, kit.serial_num)',
				'attributes'=>array(
					'serial_search'=>array(
						'asc'=>"CONCAT(celltype.name, kit.serial_num)",
						'desc'=>"CONCAT(celltype.name, kit.serial_num) DESC",
					),
					'chamber_search'=>array(
						'asc'=>'cham.name',
						'desc'=>'cham.name DESC',
					),
					'cycler_search'=>array(
						'asc'=>'CONCAT(cyc.name, chan.number)',
						'desc'=>'CONCAT(cyc.name, chan.number) DESC',
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
	 * @return FormationDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function putCellsOnTest($testAssignments)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($testAssignments))
			return;
			
		foreach($testAssignments as $testAssignment)
		{
			$model = $testAssignment;
			$model->is_active = 1;
				
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
					/* update the cell location */
					$cell = Cell::model()->findByPk($model->cell_id);
					if($model->is_formation)
					{
						$cell->location = '[FORM] ';
					}
					elseif($model->is_conditioning) 
					{
						$cell->location = '[COND] ';
					}
					elseif($model->is_misc) 
					{
						$cell->location = '[MISC] ';
					}
					else
					{
						$cell->location = '[CAT] ';
					}
					
					$cell->location .= $model->channel->cycler->name.
										'{'.$model->channel->number.'} '.
										'('.$model->chamber->name.')';
					$cell->save();
					
					/* set the channel status as in_use */
					$channel = Channel::model()->findByPk($model->channel_id);
						
					$channel->in_use = 1;
					$channel->save();
						
					$result[] = array(
						'serial'=>$cell->kit->getFormattedSerial(), 
						'cycler'=>$model->channel->cycler->name,
						'channel'=>$model->channel->number,
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
	 * 
	 * Creates new test assignment and set previous test assignment and channel details 
	 * to default states 
	 * 
	 * @param TestAssignmnet[] $testAssignments
	 * @param Array $badTestChannels associative array of testassignmnets with bad channels
	 */
	public static function channelReassignment($testAssignments, $badTestChannels)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($testAssignments))
			return;
			
		foreach($testAssignments as $test_id=>$testAssignment)
		{
			$model = $testAssignment;
			$model->is_active = 1;
				
			if(!$model->validate())
			{
				$error = 1;
			}
			$models[$test_id] = $model;	
		}
		
		/* all models validated save them all */
		if ($error==0)
		{
			/* create array to return with JSON */
			$result = array();
			foreach($models as $test_id=>$model)
			{
				if($model->save())
				{ 	
					/* need to find the previous testAssignment and channel
					 * -must set channel in_use to false and in_commission to false if
					 * 	marked as bad.
					 */	
					$oldTest = TestAssignment::model()->with('channel')->findByPk($test_id);
					$oldTest->is_active = 0;
					$oldTest->save();
					
					/*  set old channel as no longer in use. Do we need to set it out of 
					 * commission as well 
					 */
					$oldChannel = $oldTest->channel;
					$oldChannel->in_use = 0;
					$oldChannel->in_commission = $badTestChannels[$test_id]?0:$oldChannel->in_commission;
					$oldChannel->save();
					
					/* update the cell location */
					$cell = Cell::model()->findByPk($model->cell_id);
					$cell->location = $model->is_formation ? '[FORM] ':'[CAT] ';
					$cell->location .= $model->channel->cycler->name.
										'{'.$model->channel->number.'} '.
										'('.$model->chamber->name.')';
					$cell->save();
					
					/* update the channel status */
					$channel = Channel::model()->findByPk($model->channel_id);
					$channel->in_use = 1;
					$channel->save();
					
					$result[] = array(
						'serial'=>$cell->kit->getFormattedSerial(), 
						'ogCycler'=>$oldTest->channel->cycler->name,
						'ogChannel'=>$oldTest->channel->number,
						'cycler'=>$model->channel->cycler->name,
						'channel'=>$model->channel->number,
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
