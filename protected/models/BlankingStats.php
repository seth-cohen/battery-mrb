<?php

/**
 * This is the model class for table "tbl_blanking_stats".
 *
 * The followings are the available columns in table 'tbl_blanking_stats':
 * @property string $id
 * @property string $electrode_id
 * @property string $blanking_date
 * @property integer $reject_count
 * @property integer $good_count
 * @property string $blanker_id
 *
 * The followings are the available model relations:
 * @property User $blanker
 * @property Electrode $electrode
 */
class BlankingStats extends CActiveRecord
{
	
	public $blanker_search;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_blanking_stats';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reject_count, good_count', 'numerical', 'integerOnly'=>true),
			array('reject_count, good_count, electrode_id, blanker_id, blanking_date', 'required'),
			array('electrode_id, blanker_id', 'length', 'max'=>10),
			array('blanking_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, electrode_id, blanking_date, reject_count, good_count, blanker_id', 'safe', 'on'=>'search'),
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
			'blanker' => array(self::BELONGS_TO, 'User', 'blanker_id'),
			'electrode' => array(self::BELONGS_TO, 'Electrode', 'electrode_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'electrode_id' => 'Electrode',
			'bagging_date' => 'Bagging Date',
			'reject_count' => 'Reject Count',
			'good_count' => 'Good Count',
			'blanker_id' => 'Operator Name',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('electrode_id',$this->electrode_id,true);
		$criteria->compare('blanking_date',$this->blanking_date,true);
		$criteria->compare('reject_count',$this->reject_count);
		$criteria->compare('good_count',$this->good_count);
		$criteria->compare('blanker_id',$this->blanker_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BlankingStats the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 
	 * Saves the models in the array but validates them first...
	 * if there is an error an any of them then none are saved.
	 * 
	 * @param $statsModel Array
	 */
	function saveBlankingStats($statsModels)
	{
		$error = 0;
		$models = array();

		/* oops, we were passed bad data */
		if(empty($statsModels))
			return;
			
		foreach($statsModels as &$statsModel)
		{
			if(!$statsModel->validate())
			{
				$error = 1;
			}
			
			unset($statsModel);
		}
		
		/* all models validated save them all */
		if ($error==0)
		{
			/* create array to return with JSON */
			$result = array();
			foreach($statsModels as &$statsModel)
			{
				if($statsModel->save())
				{		
					$result[] = array(
						'date'=>$statsModel->blanking_date, 
						'reject_count'=>$statsModel->reject_count,
						'good_count'=>$statsModel->good_count,
					);
				}
			}
			return json_encode($result);
		}
		else /* a model failed, don't save any */
		{
			return CHtml::errorSummary($statsModels); 	
		}			
		return null;		
	}
}
