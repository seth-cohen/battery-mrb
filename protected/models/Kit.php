<?php

/**
 * This is the model class for table "tbl_kit".
 *
 * The followings are the available columns in table 'tbl_kit':
 * @property string $id
 * @property string $lot_num
 * @property string $ref_num
 * @property string $anode_id
 * @property string $cathode_id
 * @property string $kitter_id
 * @property string $kitting_date
 *
 * The followings are the available model relations:
 * @property Cell[] $cells
 * @property Anode $anode
 * @property Cathode $cathode
 * @property User $kitter
 */
class Kit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_kit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lot_num, ref_num, kitting_date', 'required'),
			array('lot_num, ref_num', 'length', 'max'=>50),
			array('anode_id, cathode_id, kitter_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lot_num, ref_num, anode_id, cathode_id, kitter_id, kitting_date', 'safe', 'on'=>'search'),
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
			'cells' => array(self::HAS_MANY, 'Cell', 'kit_id'),
			'anode' => array(self::BELONGS_TO, 'Anode', 'anode_id'),
			'cathode' => array(self::BELONGS_TO, 'Cathode', 'cathode_id'),
			'kitter' => array(self::BELONGS_TO, 'User', 'kitter_id'),
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
			'ref_num' => 'Ref Num',
			'anode_id' => 'Anode',
			'cathode_id' => 'Cathode',
			'kitter_id' => 'Kitter',
			'kitting_date' => 'Kitting Date',
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
		$criteria->compare('ref_num',$this->ref_num,true);
		$criteria->compare('anode_id',$this->anode_id,true);
		$criteria->compare('cathode_id',$this->cathode_id,true);
		$criteria->compare('kitter_id',$this->kitter_id,true);
		$criteria->compare('kitting_date',$this->kitting_date,true);

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
		$kits = Kit::model()->findAll();
	
		$arr[''] = 'All';
		foreach ($kits as $kit)
		{
			$arr[$kit->id] = $kit->lot_num;
		}
		 			
		return $arr;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Kit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
