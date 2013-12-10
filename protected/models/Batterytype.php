<?php

/**
 * This is the model class for table "tbl_batterytype".
 *
 * The followings are the available columns in table 'tbl_batterytype':
 * @property string $id
 * @property string $part_num
 * @property string $name
 * @property string $num_cells
 * @property string $celltype_id
 *
 * The followings are the available model relations:
 * @property Celltype $celltype
 */
class Batterytype extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_batterytype';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, num_cells, part_num, celltype_id', 'required'),
			array('part_num, name', 'length', 'max'=>50),
			array('num_cells, celltype_id', 'length', 'max'=>10),
			array('num_cells', 'numerical', 
				'integerOnly'=>true,
				'min'=>1,
				'tooSmall'=>'Battery must use at least one cell',
			),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, part_num, name, num_cells, celltype_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'part_num' => 'Part Num',
			'name' => 'Name',
			'num_cells' => 'No. of Cells',
			'celltype_id' => 'Cell Type',
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
		$criteria->compare('part_num',$this->part_num,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('num_cells',$this->num_cells,true);
		$criteria->compare('celltype_id',$this->celltype_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Batterytype the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCellCount()
	{
		$cellArray = array();

		for($x=1; $x<=$this->num_cells; $x++){
			$cellArray[] = array('id'=>$x, 'value'=>$x);
		}
		return $cellArray;
	}
}
