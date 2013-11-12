<?php

/**
 * This is the model class for table "tbl_anode".
 *
 * The followings are the available columns in table 'tbl_anode':
 * @property string $id
 * @property string $lot_num
 * @property string $eap_num
 * @property string $coater_id
 *
 * The followings are the available model relations:
 * @property User $coater
 * @property Kit[] $kits
 */
class Anode extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_anode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lot_num', 'required'),
			array('lot_num, eap_num', 'length', 'max'=>50),
			array('coater_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lot_num, eap_num, coater_id', 'safe', 'on'=>'search'),
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
			'coater' => array(self::BELONGS_TO, 'User', 'coater_id'),
			'kits' => array(self::HAS_MANY, 'Kit', 'anode_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lot_num' => 'Lot Num',
			'eap_num' => 'Eap Num',
			'coater_id' => 'Coater',
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
		$criteria->compare('lot_num',$this->lot_num,true);
		$criteria->compare('eap_num',$this->eap_num,true);
		$criteria->compare('coater_id',$this->coater_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Anode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
