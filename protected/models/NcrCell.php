<?php

/**
 * This is the model class for table "tbl_ncr_cell".
 *
 * The followings are the available columns in table 'tbl_ncr_cell':
 * @property string $cell_id
 * @property string $ncr_id
 * @property integer $disposition
 * @property string $disposition_string
 * 
 */
class NcrCell extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_ncr_cell';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cell_id, ncr_id', 'length', 'max'=>10),
			array('disposition', 'numerical', 'integerOnly'=>true),
			array('disposition_string', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cell_id, ncr_id, disposition, disposition_string', 'safe', 'on'=>'search'),
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
			'ncr' => array(self::BELONGS_TO, 'Ncr', 'ncr_id'),
			'cell' => array(self::BELONGS_TO, 'Cell', 'cell_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cell_id' => 'Cell',
			'ncr_id' => 'Ncr',
			'disposition_string' => 'Disposition',
			'disposition' => 'Disposition ID',
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

		$criteria->compare('cell_id',$this->cell_id,true);
		$criteria->compare('ncr_id',$this->ncr_id,true);
		$criteria->compare('disposition_string',$this->disposition_string,true);
		$criteria->compare('disposition',$this->disposition);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NcrCell the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
