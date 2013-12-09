<?php

/**
 * This is the model class for table "tbl_battery".
 *
 * The followings are the available columns in table 'tbl_battery':
 * @property string $id
 * @property string $batterytype_id
 * @property string $ref_num_id
 * @property string $eap_num
 * @property string $serial_num
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
 */
class Battery extends CActiveRecord
{
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
			array('serial_num, batterytype_id', 'required'),
			array('batterytype_id, ref_num_id, assembler_id', 'length', 'max'=>10),
			array('eap_num, serial_num, location', 'length', 'max'=>50),
			array('assembly_date, ship_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, batterytype_id, ref_num_id, eap_num, serial_num, assembler_id, assembly_date, ship_date, location', 'safe', 'on'=>'search'),
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
			'batterytype' => array(self::BELONGS_TO, 'Batterytype', 'batterytype_id'),
			'refNum' => array(self::BELONGS_TO, 'RefNum', 'ref_num_id'),
			'assembler' => array(self::BELONGS_TO, 'User', 'assembler_id'),
			'cells' => array(self::HAS_MANY, 'Cell', 'battery_id'),
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
		$criteria->compare('batterytype_id',$this->batterytype_id,true);
		$criteria->compare('ref_num_id',$this->ref_num_id,true);
		$criteria->compare('eap_num',$this->eap_num,true);
		$criteria->compare('serial_num',$this->serial_num,true);
		$criteria->compare('assembler_id',$this->assembler_id,true);
		$criteria->compare('assembly_date',$this->assembly_date,true);
		$criteria->compare('ship_date',$this->ship_date,true);
		$criteria->compare('location',$this->location,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
}
