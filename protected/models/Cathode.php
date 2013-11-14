<?php

/**
 * This is the model class for table "tbl_cathode".
 *
 * The followings are the available columns in table 'tbl_cathode':
 * @property string $id
 * @property string $lot_num
 * @property string $eap_num
 * @property string $coater_id
 * @property string $ref_num_id
 * @property string $coat_date
 *
 * The followings are the available model relations:
 * @property User $coater
 * @property Kit[] $kits
 * @property RefNum $refNum
 */
class Cathode extends CActiveRecord
{
	public $coater_search;
	public $refnum_search;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_cathode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lot_num, coat_date', 'required'),
			array('lot_num, eap_num', 'length', 'max'=>50),
			array('coater_id, ref_num_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lot_num, coat_date, eap_num, coater_id, ref_num_id, coater_search, refnum_search', 'safe', 'on'=>'search'),
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
			'kits' => array(self::HAS_MANY, 'Kit', 'cathode_id'),
			'refNum' => array(self::BELONGS_TO, 'RefNum', 'ref_num_id'),
		
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
			'coat_date' => 'Coated On',
			'ref_num_id' => 'Reference No.',
			
			'coater_search' => 'Coater',
			'refnum_search' => 'Reference No.',
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
						'coater'=>array('alias'=>'user'), 
		); // needed for alias of search parameter tables
		
		$criteria->compare('t.id',$this->id,true);
		$criteria->compare('lot_num',$this->lot_num,true);
		$criteria->compare('eap_num',$this->eap_num,true);
		$criteria->compare('coater_id',$this->coater_id,true);
		$criteria->compare('coat_date',$this->coat_date,true);
		
		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(user.first_name, " ", user.last_name)', $this->coater_search);
		$criteria->addSearchCondition('ref.number', $this->refnum_search);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'coater_search'=>array(
						'asc'=>"CONCAT(first_name, ' ', last_name)",
						'desc'=>"CONCAT(first_name, ' ', last_name) DESC",
					),
					'refnum_search'=>array(
						'asc'=>'ref.number',
						'desc'=>'ref.number DESC',
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
	 * @return Cathode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
