<?php

/**
 * This is the model class for table "tbl_cell".
 *
 * The followings are the available columns in table 'tbl_cell':
 * @property string $id
 * @property string $kit_id
 * @property string $ref_num_id
 * @property string $eap_num
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
 * @property Kit $kit
 * @property RefNum $refNum
 * @property User $stacker
 * @property User $filler
 * @property User $inspector
 * @property FormationDetail[] $formationDetails
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
	
	/* related model helpers */
	public $serial_search;
	public $celltype_search;
	public $stacker_search;
	public $filler_search;
	public $inspector_search;
	public $location_search;
	public $refnum_search;
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stack_date, dry_wt, wet_wt, fill_date, inspection_date', 'required'),
			array('dry_wt, wet_wt', 'numerical'),
			array('kit_id, ref_num_id, stacker_id, filler_id, inspector_id', 'length', 'max'=>10),
			array('eap_num', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('eap_num, stack_date, dry_wt, wet_wt, fill_date, inspection_date, serial_search, celltype_search, 
					refnum_search, stacker_search, filler_search, inspector_search, location_search', 'safe', 'on'=>'search'),
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
			'kit' => array(self::BELONGS_TO, 'Kit', 'kit_id'),
			'refNum' => array(self::BELONGS_TO, 'RefNum', 'ref_num_id'),
			'stacker' => array(self::BELONGS_TO, 'User', 'stacker_id'),
			'filler' => array(self::BELONGS_TO, 'User', 'filler_id'),
			'inspector' => array(self::BELONGS_TO, 'User', 'inspector_id'),
			'formationDetails' => array(self::HAS_MANY, 'FormationDetail', 'cell_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kit_id' => 'Kit',
			'ref_num' => 'Ref Num',
			'eap_num' => 'EAP No.',
			'stacker_id' => 'Stacker',
			'stack_date' => 'Stack Date',
			'dry_wt' => 'Dry Wt',
			'wet_wt' => 'Wet Wt',
			'filler_id' => 'Filler',
			'fill_date' => 'Fill Date',
			'inspector_id' => 'Inspector',
			'inspection_date' => 'Inspection Date',
			
			'refnum_search' => "Reference No.",
			'serial_search' => 'Serial No.',
			'celltype_search' => 'Cell Type',
			'stacker_search' => 'Stacker',
			'filler_search' => 'Filler',
			'inspector_search' => 'Inspector',
			'location_search' => 'Location',
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
						'kit'=>array('with'=>'celltype'), 
						'stacker'=>array('alias'=>'user'), 
						'refNum'=>array('alias'=>'ref'),
		); // needed for alias of search parameter tables

		$criteria->compare('id',$this->id,true);
		$criteria->compare('kit_id',$this->kit_id,true);
		$criteria->compare('eap_num',$this->eap_num,true);
		$criteria->compare('stack_date',$this->stack_date,true);
		$criteria->compare('dry_wt',$this->dry_wt);
		$criteria->compare('wet_wt',$this->wet_wt);
		$criteria->compare('fill_date',$this->fill_date,true);
		$criteria->compare('inspection_date',$this->inspection_date,true);
		
		$criteria->compare('ref.number', $this->refnum_search, true);
		$criteria->compare('kit.serial_num',$this->serial_search, true);	
		$criteria->compare('celltype.name',$this->celltype_search, true);
		
		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(user.first_name, " ", user.last_name)', $this->stacker_search);
		$criteria->addSearchCondition('concat(user.first_name, " ", user.last_name)', $this->filler_search);
		$criteria->addSearchCondition('concat(user.first_name, " ", user.last_name)', $this->inspector_search);

		return new CActiveDataProvider($this, array(
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'refnum_search'=>array(
						'asc'=>'ref.number',
						'desc'=>'ref.number DESC',
					),
					'serial_search'=>array(
						'asc'=>"CONCAT(celltype.name, serial_num)",
						'desc'=>"CONCAT(celltype.name, serial_num) DESC",
					),
					'celltype_search'=>array(
						'asc'=>'celltype.name',
						'desc'=>'celltype.name DESC',
					),
					'stacker_search'=>array(
						'asc'=>"CONCAT(first_name, ' ', last_name)",
						'desc'=>"CONCAT(first_name, ' ', last_name) DESC",
					),
					'filler_search'=>array(
						'asc'=>"CONCAT(first_name, ' ', last_name)",
						'desc'=>"CONCAT(first_name, ' ', last_name) DESC",
					),
					'inspector_search'=>array(
						'asc'=>"CONCAT(first_name, ' ', last_name)",
						'desc'=>"CONCAT(first_name, ' ', last_name) DESC",
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
