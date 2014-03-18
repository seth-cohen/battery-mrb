<?php

/**
 * This is the model class for table "tbl_electrode".
 *
 * The followings are the available columns in table 'tbl_electrode':
 * @property string $id
 * @property string $lot_num
 * @property string $eap_num
 * @property string $coater_id
 * @property string $ref_num_id
 * @property string $coat_date
 * @property integer $is_anode
 * @property string $moisture
 * @property string $thickness
 * @property string $cal_date
 *
 * The followings are the available model relations:
 * @property RefNum $refNum
 * @property User $coater
 * @property Kit[] $kits
 */
class Electrode extends CActiveRecord
{
	public $kitIds = array();
	public $coater_search;
	public $refnum_search;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_electrode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lot_num, coat_date, is_anode, coater_id', 'required'),
			array('is_anode, thickness, moisture', 'numerical', 'integerOnly'=>true),
			array('lot_num, eap_num', 'length', 'max'=>50),
			array('cal_date','safe'),  // variables with no rules won't save
			
			array('eap_num', 'checkEAP'),
			array('lot_num', 'unique','on'=>'insert'),
			array('coater_id, ref_num_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lot_num, eap_num, coater_id, ref_num_id, coat_date, is_anode, coater_search, refnum_search, cal_date, thickness, moisture', 'safe', 'on'=>'search'),
		);
	}
	
	public function checkEAP($attribute,$params) 
	{
		$pattern = '/ADD\s$|ADD$/';
		
        if(preg_match($pattern, $this->$attribute))
        {
        	$this->addError( $attribute, "EAP Addendum is missing!" );
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
			'refNum' => array(self::BELONGS_TO, 'RefNum', 'ref_num_id'),
			'coater' => array(self::BELONGS_TO, 'User', 'coater_id'),
			'kits' => array(self::MANY_MANY, 'Kit', 'tbl_electrode_kit(electrode_id, kit_id)'),
		);
	}

	public function defaultScope()
    {
    	$alias = $this->getTableAlias( false, false );
        return array(
            'order'=>$alias.'.lot_num DESC',
        );
    }
    
	/**
	 * @return array of the query criteria to be used for particular query
	 */
	public function scopes()
	{
		$alias = $this->getTableAlias( false, false );
		return array(
			'anodes'=>array(
				'condition'=>'is_anode=1',
			),
			'cathodes'=>array(
				'condition'=>'is_anode=0',
			),
			'notGeneric'=>array(
				'condition'=>$alias.'.id <> 29 AND '.$alias.'.id <> 30',
			),
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
			'ref_num_id' => 'Ref Num',
			'coat_date' => 'Coat Date',
			'is_anode' => 'Type',
			'moisture' => 'Moisture (PPM)',
			'thickness'=> 'Thickness (um)',
			'cal_date' => 'Cal Date',
		
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
						'refNum'=>array('alias'=>'ref'),
		); // needed for alias of search parameter tables
		
		$criteria->compare('id',$this->id,true);
		$criteria->compare('lot_num',$this->lot_num,true);
		$criteria->compare('eap_num',$this->eap_num,true);
		$criteria->compare('coater_id',$this->coater_id,true);
		$criteria->compare('ref_num_id',$this->ref_num_id,true);
		$criteria->compare('coat_date',$this->coat_date,true);
		$criteria->compare('cal_date',$this->cal_date,true);
		$criteria->compare('thickness',$this->thickness,true);
		$criteria->compare('moisture',$this->moisture,true);
		$criteria->compare('is_anode',$this->is_anode);

		/* for concatenated user name search */
		$criteria->addSearchCondition('concat(user.first_name, " ", user.last_name)', $this->coater_search);
		
		if($this->refnum_search)
		{
			$references = explode(',', str_replace(' ', ',', $this->refnum_search));
			
			$refCriteria = new CDbCriteria();
			foreach ($references as $reference)
			{
				if(!empty($reference))
				{
					$refCriteria->compare('ref.number', $reference, true, 'OR');
				}
			}
			$criteria->mergeWith($refCriteria);
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize' => 16),
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
	 * 
	 * Creates new electrodes and creates ref number if needed using an uploaded file
	 * 
	 * @param array $uploadedFile
	 */
	public static function uploadFromCSV($electrodeModel, $uploadedFile)
	{	
		if(empty($electrodeModel) || empty($uploadedFile))
			return false;
			
		/* save the uploaded file */
		$target = Yii::app()->basePath."/uploads/";
		$target = $target . 'electrodes-'.date('Y_m_d');
			
		$ext = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
		if($ext != 'csv')
		{
			$electrodeModel->addError('upload_error', "The file must be a csv file.  The uploaded file's extension was '$ext'" );
			return false;
		}
	 	if(!move_uploaded_file($uploadedFile['tmp_name'], $target) )
		 {
		 	$electrodeModel->addError('upload_error', 'There was an error uploading the file please try again');
			return false;
		 }
			 
		 /* parse the file to get an array of cells */
		$row = 1;
		$handle = fopen($target, "r");
			
		while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) { 
		    $electrode = new Electrode;
		    
		    // try to find reference number, create new one if not exists
		    $ref_num_id = RefNum::model()->findByAttributes(array('number'=>$data[4]));
		    if($ref_num_id == null)
		    {
		    	$refnum = new RefNum;
		    	$refnum->number = $data[4];
		    	$refnum->save();
		    	$ref_num_id = $refnum;	
		    }
			$ref_num_id = $ref_num_id->id;	
			
		    $electrode->lot_num = $data[1];
		    $electrode->eap_num = $data[2];
		    $electrode->coater_id = $data[3];
		    $electrode->ref_num_id = $ref_num_id;
		    $electrode->coat_date = $data[5];
		    $electrode->is_anode = $data[6];
		    
		    $electrode->save();
		}
		return true;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Electrode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*public function afterFind()
	{
		if (!empty($this->kits))
		{
			foreach($this->kits as $index=>$kit)
			{
				$this->kitIds[] = $kit->id;
			}
		}
		
		return parent::afterFind();
		
	}*/
	
	
}
