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
	public $serial_search;
	public $refnum_search;
	public $ncr_search;
	
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
			array('cell_id', 'checkUniqueOnNCR', 'on'=>'insert'),
			array('disposition', 'numerical', 'integerOnly'=>true),
			array('disposition_string', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array(
				'cell_id, ncr_id, disposition, disposition_string,
				serial_search, ncr_search, refnum_search',
				'safe', 'on'=>'search'
			),
		);
	}

	public function checkUniqueOnNCR($attribute,$params) 
	{
	    if(NcrCell::model()->count('cell_id=:cell_id AND ncr_id=:ncr_id',
	        array(':cell_id'=>$this->cell_id,':ncr_id'=>$this->ncr_id)) > 0) 
	    {
	        $this->addError( $attribute, "{$this->cell->kit->getFormattedSerial()} is already on NCR {$this->ncr->number}!" );   
	    }
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
			'serial_search' => 'Cell Serial',
			'ncr_search' => 'NCR No.',
			'refnum_search' => 'Ref No.',
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
						'cell'=>array(
							'with'=>array(
								'refNum'=>array('select'=>'number', 'alias'=>'ref'),
								'kit'=>array(
									'select'=>array('id','serial_num'),
									'with'=>array(
										'celltype'=>array('alias'=>'celltype'),
										'anodes'=>array('select'=>'id, lot_num', 'alias'=>'anodes'), 
										'cathodes'=>array('select'=>'id, lot_num', 'alias'=>'cathodes'),
									), 
								),
							),
						),
						'ncr',
		);
		
		$criteria->together = true;
		
		$criteria->compare('cell_id',$this->cell_id,true);
		$criteria->compare('ncr_id',$this->ncr_id,true);
		$criteria->compare('disposition_string',$this->disposition_string,true);
		$criteria->compare('disposition',$this->disposition, true);
		
		$criteria->compare('ncr.number',$this->ncr_search, true);
		$criteria->compare('ref.number',$this->refnum_search, true);
		
		$criteria->addSearchCondition('concat(celltype.name,"-",kit.serial_num)',$this->serial_search, true);
		
		return new KeenActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'withKeenLoading' => array(
				//array('ncr'),
				//array('cell'),
				//array('cell.kit', 'cell.refNum'),
				//array('cell.kit.anodes', 'cell.kit.cathodes', 'cell.kit.celltype'),		
			),
			'pagination'=>array('pageSize' => 16),
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'ncr.number',
				'attributes'=>array(
					'serial_search'=>array(
						'asc'=>"CONCAT(celltype.name, kit.serial_num)",
						'desc'=>"CONCAT(celltype.name, kit.serial_num) DESC",
					),
					'ncr_search'=>array(
						'asc'=>"ncr.number",
						'desc'=>"ncr.number DESC",
					),
						'refnum_search'=>array(
						'asc'=>"ref.number",
						'desc'=>"ref.number DESC",
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
	 * @return NcrCell the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
