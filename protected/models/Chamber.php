<?php

/**
 * This is the model class for table "tbl_chamber".
 *
 * The followings are the available columns in table 'tbl_chamber':
 * @property string $id
 * @property string $name
 * @property string $brand
 * @property string $model
 * @property string $serial_num
 * @property string $in_commission
 * @property string $govt_tag_num
 * @property string $cycler_id
 * @property integer $min_temp
 * @property integer $max_temp
 *
 * The followings are the available model relations:
 * @property Cycler $cycler
 */
class Chamber extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_chamber';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('brand, model, serial_num, in_commission, min_temp, max_temp', 'required'),
			array('min_temp, max_temp', 'numerical', 'integerOnly'=>true),
			array('name, brand, model, serial_num, in_commission, govt_tag_num', 'length', 'max'=>50),
			array('cycler_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, brand, model, serial_num, in_commission, govt_tag_num, cycler_id, min_temp, max_temp', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'brand' => 'Brand',
			'model' => 'Model',
			'serial_num' => 'Serial Num',
			'in_commission' => 'In Commission',
			'govt_tag_num' => 'Govt Tag Num',
			'cycler_id' => 'Cycler',
			'min_temp' => 'Min Temp',
			'max_temp' => 'Max Temp',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('serial_num',$this->serial_num,true);
		$criteria->compare('in_commission',$this->in_commission,true);
		$criteria->compare('govt_tag_num',$this->govt_tag_num,true);
		$criteria->compare('cycler_id',$this->cycler_id,true);
		$criteria->compare('min_temp',$this->min_temp);
		$criteria->compare('max_temp',$this->max_temp);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Chamber the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
