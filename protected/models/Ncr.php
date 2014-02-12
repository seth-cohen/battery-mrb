<?php

/**
 * This is the model class for table "tbl_ncr".
 *
 * The followings are the available columns in table 'tbl_ncr':
 * @property string $id
 * @property string $number
 * @property string $date
 *
 * The followings are the available model relations:
 * @property Cell[] $cells
 * @property Cell[] $openCells
 */
class Ncr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_ncr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number', 'length', 'max'=>10),
			array('date', 'required'),
			array('number', 'unique'),
			array('number', 'numerical', 'integerOnly'=>true),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, number, date', 'safe', 'on'=>'search'),
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
			'cells' => array(self::MANY_MANY, 'Cell', 'tbl_ncr_cell(ncr_id, cell_id)'),
			'openCells' => array(self::MANY_MANY, 'Cell', 'tbl_ncr_cell(ncr_id, cell_id)', 'alias'=>'openCells', 'condition'=>'disposition=0'),
		);
	}

	public function defaultScope()
    {
    	$alias = $this->getTableAlias( false, false );
        return array(
            'order'=>$alias.'.number',
        );
    }
    
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'number' => 'NCR Number',
			'date' => 'NCR Date',
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
		$criteria->compare('number',$this->number,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ncr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function addCells($cellIds)
	{
		$error = 0;
		$models = array();
		
		if(empty($cellIds))
			return;
			
		/* create a new ncr_cell record for each of the cells added to the NCR */
		foreach($cellIds as $id)
		{
			$ncrCellModel = new  NcrCell;
			$ncrCellModel->cell_id = $id;
			$ncrCellModel->ncr_id = $this->id;
			$ncrCellModel->disposition_string = 'Open';
			$ncrCellModel->disposition = 0;
			
			if(!$ncrCellModel->validate())
			{
				$error = 1;
			}
			$models[] = $ncrCellModel;	
		}
		/* all models validated save them all */
		if ($error==0)
		{
			/* create array to return with JSON */
			$result = array();
			$result['ncr'] = $this->number;
			$result['cells'] = array();
			
			foreach($models as $model)
			{
				if($model->save())
				{
					$result['cells'][] = array(
						'serial'=>$model->cell->kit->getFormattedSerial(), 
					);
				}
			}
			return json_encode($result);
		}
		else /* a model failed, don't save any */
		{
			return CHtml::errorSummary($models); 	
		}			
		return null;
	}
}
