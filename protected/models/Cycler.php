<?php

/**
 * This is the model class for table "tbl_cycler".
 *
 * The followings are the available columns in table 'tbl_cycler':
 * @property string $id
 * @property integer $sy_number
 * @property string $name
 * @property integer $num_channels
 * @property string $cal_date
 * @property string $cal_due_date
 * @property string $calibrator_id
 * @property string $maccor_job_num
 * @property string $govt_tag_num
 *
 * The followings are the available model relations:
 * @property Channel[] $channels
 * @property User $calibrator
 */
class Cycler extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_cycler';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sy_number, name, num_channels', 'required'),
			array('sy_number, num_channels', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('calibrator_id', 'length', 'max'=>10),
			array('maccor_job_num, govt_tag_num', 'length', 'max'=>50),
			array('cal_date, cal_due_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sy_number, name, num_channels, cal_date, cal_due_date, calibrator_id, maccor_job_num, govt_tag_num', 'safe', 'on'=>'search'),
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
			'channels' => array(self::HAS_MANY, 'Channel', 'cycler_id'),
			'calibrator' => array(self::BELONGS_TO, 'User', 'calibrator_id'),
			'channelCount' => array(self::STAT, 'Channel', 'cycler_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sy_number' => 'Sy Number',
			'name' => 'Name',
			'num_channels' => 'Num Channels',
			'cal_date' => 'Cal Date',
			'cal_due_date' => 'Cal Due Date',
			'calibrator_id' => 'Calibrator',
			'maccor_job_num' => 'Maccor Job Num',
			'govt_tag_num' => 'Govt Tag Num',
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
		$criteria->compare('sy_number',$this->sy_number);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('num_channels',$this->num_channels);
		$criteria->compare('cal_date',$this->cal_date,true);
		$criteria->compare('cal_due_date',$this->cal_due_date,true);
		$criteria->compare('calibrator_id',$this->calibrator_id,true);
		$criteria->compare('maccor_job_num',$this->maccor_job_num,true);
		$criteria->compare('govt_tag_num',$this->govt_tag_num,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/*
	 * REturns one dimensional array to use to populate dropdown list for filtering
	 * @return 1-D array of id=>name
	 */
	public function forList()
	{
		$arr = array();
		$cyclers = Cycler::model()->findAll();
	
		foreach ($cyclers as $cycler)
		{
			$arr[$cycler->id] = $cycler->name;
		}
		 			
		return $arr;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cycler the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
}
