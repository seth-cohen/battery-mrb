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
 * @property string $is_formation
 * @property string $is_active
 *
 * The followings are the available model relations:
 * @property Cell $cell
 * @property Chamber $chamber
 * @property Channel $channel
 * @property User $operator
 */
class TestAssignment extends CActiveRecord
{
	
	public $serial_search;
	public $chamber_search;
	public $cycler_search;
	
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
			array('cell_id, channel_id, chamber_id, operator_id, test_start, is_formation, is_active', 'required'),
			array('cell_id, channel_id, chamber_id, operator_id', 'length', 'max'=>10),
			array('test_start', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cell_id, channel_id, chamber_id, operator_id, test_start, is_active, is_formation 
					serial_search, chamber_search, cycler_search', 'safe', 'on'=>'search'),
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
		
			'serial_search' => 'Cell Serial',
			'chamber_search' => 'Chamber',
			'cycler_search' => 'Cycler {Channel}',
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
		$criteria->compare('is_formation',$this->is_formation,true);
		$criteria->compare('is_active',$this->is_active,true);

		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(celltype.name,"-",serial_num)',$this->serial_search, true);
		
		return new KeenActiveDataProvider($this, array(
			'withKeenLoading'=>array(
				'cell',
				'chamber',
				'channel'=>array('with'=>'cycler'),
			),
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'test_start DESC',
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
	
	public static function putCellsOnTest($cellsFormation)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($cellsFormation))
			return;
			
		foreach($cellsFormation as $cell)
		{
			$model = new TestAssignment();
					 
			$model->cell_id = $cell['cell_id'];
			$model->channel_id = $cell['channel_id'];
			$model->chamber_id = $cell['chamber_id'];
			$model->operator_id = $cell['operator_id'];
			$model->test_start = $cell['test_start'];
			$model->is_formation = $cell['is_formation'];
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
					$cell->location = $model->is_formation ? '[FORM]':'[CAT]';
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
}
