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
			array('lot_num, coat_date, is_anode', 'required'),
			array('is_anode', 'numerical', 'integerOnly'=>true),
			array('lot_num, eap_num', 'length', 'max'=>50),
			
			array('eap_num', 'checkEAP'),
			array('lot_num', 'unique',),
			array('coater_id, ref_num_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lot_num, eap_num, coater_id, ref_num_id, coat_date, is_anode, coater_search, refnum_search', 'safe', 'on'=>'search'),
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
		return array(
			'anodes'=>array(
				'condition'=>'is_anode=1',
			),
			'cathodes'=>array(
				'condition'=>'is_anode=0',
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
		$criteria->compare('is_anode',$this->is_anode);

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
