<?php

/**
 * This is the model class for table "tbl_cell".
 *
 * The followings are the available columns in table 'tbl_cell':
 * @property string $id
 * @property string $serial_num
 * @property string $kit_id
 * @property string $ref_num
 * @property string $eap_num
 * @property string $celltype_id
 * @property string $stacker_id
 * @property string $stack_date
 * @property double $dry_wt
 * @property double $wet_wt
 * @property string $filler_id
 * @property string $fill_date
 * @property string $inspector_id
 * @property string $inspection_date
 *
 * The followings are the available model relations:
 * @property Celltype $celltype
 * @property Kit $kit
 * @property User $stacker
 * @property User $filler
 * @property User $inspector
 */
class Cell extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_cell';
	}
	
	/**
	 * @property $kit_search
	 * enables ability to search on kit lotnumber rather than kit id
	 */
	public $kit_search;
	/**
	 * @property $celltype_search
	 * enables ability to search on kit lotnumber rather than kit id
	 */
	public $celltype_search;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('serial_num, ref_num, stack_date, dry_wt, wet_wt, fill_date, inspection_date', 'required'),
			array('dry_wt, wet_wt', 'numerical'),
			array('serial_num, kit_id, celltype_id, stacker_id, filler_id, inspector_id', 'length', 'max'=>10),
			array('ref_num, eap_num', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('serial_num, ref_num, eap_num, stack_date, dry_wt, wet_wt, fill_date, inspection_date, kit_search, celltype_search', 'safe', 'on'=>'search'),
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
			'celltype' => array(self::BELONGS_TO, 'Celltype', 'celltype_id'),
			'kit' => array(self::BELONGS_TO, 'Kit', 'kit_id'),
			'stacker' => array(self::BELONGS_TO, 'User', 'stacker_id'),
			'filler' => array(self::BELONGS_TO, 'User', 'filler_id'),
			'inspector' => array(self::BELONGS_TO, 'User', 'inspector_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'serial_num' => 'Serial Num',
			'kit_id' => 'Kit',
			'ref_num' => 'Ref Num',
			'eap_num' => 'EAP Num',
			'celltype_id' => 'Celltype',
			'stacker_id' => 'Stacker',
			'stack_date' => 'Stack Date',
			'dry_wt' => 'Dry Wt',
			'wet_wt' => 'Wet Wt',
			'filler_id' => 'Filler',
			'fill_date' => 'Fill Date',
			'inspector_id' => 'Inspector',
			'inspection_date' => 'Inspection Date',
			'kit_search' => 'Kit',
			'celltype_search' => 'Cell Type',
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

		$criteria->with = array('kit'); // needed for kit lot_num search
		$criteria->with = array('celltype'); // needed for celltype_name search
		$criteria->compare('id',$this->id,true);
		$criteria->compare('serial_num',$this->serial_num,true);
		$criteria->compare('kit_id',$this->kit_id,true);
		$criteria->compare('t.ref_num',$this->ref_num,true);
		$criteria->compare('eap_num',$this->eap_num,true);
		$criteria->compare('celltype_id',$this->celltype_id,true);
		$criteria->compare('stacker_id',$this->stacker_id,true);
		$criteria->compare('stack_date',$this->stack_date,true);
		$criteria->compare('dry_wt',$this->dry_wt);
		$criteria->compare('wet_wt',$this->wet_wt);
		$criteria->compare('filler_id',$this->filler_id,true);
		$criteria->compare('fill_date',$this->fill_date,true);
		$criteria->compare('inspector_id',$this->inspector_id,true);
		$criteria->compare('inspection_date',$this->inspection_date,true);
		$criteria->compare('kit.lot_num',$this->kit_search, true);	
		$criteria->compare('celltype.name',$this->celltype_search, true);

		return new CActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'kit_search'=>array(
						'asc'=>'kit.lot_num',
						'desc'=>'kit.lot_num DESC',
					),
					'celltype_search'=>array(
						'asc'=>'celltype.name',
						'desc'=>'celltype.name DESC',
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
	 * @return Cell the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
