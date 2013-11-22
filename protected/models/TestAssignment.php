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
			array('cell_id, channel_id, chamber_id, operator_id, test_start', 'required'),
			array('cell_id, channel_id, chamber_id, operator_id', 'length', 'max'=>10),
			array('test_start', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cell_id, channel_id, chamber_id, operator_id, test_start, serial_search', 'safe', 'on'=>'search'),
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
			'channel'=>array('with'=>array('cycler')),
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

		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(celltype.name,"-",serial_num)',$this->serial_search, true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'test_start DESC',
				'attributes'=>array(
					'serial_search'=>array(
						'asc'=>"CONCAT(celltype.name, serial_num)",
						'desc'=>"CONCAT(celltype.name, serial_num) DESC",
					),
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
}
