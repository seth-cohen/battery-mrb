<?php

/**
 * This is the model class for table "tbl_channel".
 *
 * The followings are the available columns in table 'tbl_channel':
 * @property string $id
 * @property integer $number
 * @property string $cycler_id
 * @property integer $max_charge_rate
 * @property integer $max_discharge_rate
 * @property integer $multirange
 * @property integer $in_use
 * @property integer $in_commission
 * @property integer $min_voltage
 * @property integer $max_voltage
 *
 * The followings are the available model relations:
 * @property Cycler $cycler
 */
class Channel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_channel';
	}

	/**
	 * @property $cycler_searh
	 * enables ability to search on cycler name rather than cycler_id
	 */
	public $cycler_search;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number, max_charge_rate, max_discharge_rate, min_voltage, max_voltage', 'required'),
			array('number, max_charge_rate, max_discharge_rate, multirange, in_use, in_commission, min_voltage, max_voltage', 'numerical', 'integerOnly'=>true),
			array('number, max_charge_rate, max_discharge_rate, max_voltage', 'numerical', 'integerOnly'=>true, 'min'=>0),
			array('cycler_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, number, cycler_id, max_charge_rate, max_discharge_rate, multirange, in_use, in_commission, min_voltage, max_voltage, cycler_search', 'safe', 'on'=>'search'),
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
			'cycler' => array(self::BELONGS_TO, 'Cycler', 'cycler_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'number' => 'Channel Number',
			'cycler_id' => 'Cycler',
			'max_charge_rate' => 'Max Charge Rate (A)',
			'max_discharge_rate' => 'Max Discharge Rate (A)',
			'multirange' => 'Multirange',
			'in_use' => 'In Use',
			'in_commission' => 'In Commission',
			'min_voltage' => 'Min Voltage (V)',
			'max_voltage' => 'Max Voltage (V)',
			'cycler_search' => 'Cycler',
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

		$criteria->with = array('cycler');		// needed for cycler name search
		$criteria->compare('id',$this->id,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('cycler_id',$this->cycler_id,true);
		$criteria->compare('max_charge_rate',$this->max_charge_rate);
		$criteria->compare('max_discharge_rate',$this->max_discharge_rate);
		$criteria->compare('multirange',$this->multirange);
		$criteria->compare('in_use',$this->in_use);
		$criteria->compare('in_commission',$this->in_commission);
		$criteria->compare('min_voltage',$this->min_voltage);
		$criteria->compare('max_voltage',$this->max_voltage);
		
		$criteria->compare('cycler.id',$this->cycler_search, true);	// needed to change this to id to make the dropdownlist filter work.

		return new CActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'cycler_search'=>array(
						'asc'=>'cycler.name',
						'desc'=>'cycler.name DESC',
					),
					'*',		// all others treated normally
				),
			),
		));
	}
	
	/*
	 * Returns one dimensional array to use to populate dropdown list for filtering
	 * of multirange boolean
	 * @return 1-D array of id=>name
	 */
	public function forListBoolean()
	{
		$arr = array();
		
		$arr[''] = 'All';
		$arr[0] = 'No';
		$arr[1] = 'Yes';
		
		return $arr;
	}

	/**
	 * Saves all of the models only if ALL models validate
	 * returns associative array if successful (with details on saved model)
	 * returns error summary if any of them fail
	 * @param Channel[] $channelModels
	 * @param integer $cycler_id
	 */
	public static function attachChannelsToCycler($channelModels, $cycler_id)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($channelModels))
			return;
			
		foreach($channelModels as $channel)
		{
			$model = $channel;
			$model->cycler_id = $cycler_id;
			
			if(!$model->validate())
			{
				$error = 1;
				/* delete the cycler that these were going to connect to */
				$commandDelete = Yii::app()->db->createCommand();
				$commandDelete->delete('tbl_cycler', 
					'id = :id',
					array(':id'=>$cycler_id)
				);
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
						'num'=>$model->number,
						'minV'=>$model->min_voltage,
						'maxV'=>$model->max_voltage,
						'maxC'=>$model->max_charge_rate,
						'maxD'=>$model->max_discharge_rate,
						'multi'=>$model->multirange,
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
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Channel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
