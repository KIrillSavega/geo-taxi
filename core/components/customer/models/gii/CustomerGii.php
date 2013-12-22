<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $id
 * @property integer $permission_group_id
 * @property string $first_name
 * @property string $last_name
 * @property string $company_email
 * @property string $private_email
 * @property string $mobile_phone
 * @property string $password
 * @property string $pos_pin_code
 * @property string $is_active
 * @property integer $company_id
 * @property integer $status
 * @property integer $timezone
 */
class CustomerGii extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerGii the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection database connection
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbCustomer;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('permission_group_id, first_name, last_name, company_id', 'required'),
			array('permission_group_id, company_id, status, timezone', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, password, pos_pin_code', 'length', 'max'=>128),
			array('company_email, private_email, mobile_phone', 'length', 'max'=>255),
			array('is_active', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, permission_group_id, first_name, last_name, company_email, private_email, mobile_phone, password, pos_pin_code, is_active, company_id, status, timezone', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'permission_group_id' => 'Permission Group',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'company_email' => 'Company Email',
			'private_email' => 'Private Email',
			'mobile_phone' => 'Mobile Phone',
			'password' => 'Password',
			'pos_pin_code' => 'Pos Pin Code',
			'is_active' => 'Is Active',
			'company_id' => 'Company',
			'status' => 'Status',
			'timezone' => 'Timezone',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('permission_group_id',$this->permission_group_id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('company_email',$this->company_email,true);
		$criteria->compare('private_email',$this->private_email,true);
		$criteria->compare('mobile_phone',$this->mobile_phone,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('pos_pin_code',$this->pos_pin_code,true);
		$criteria->compare('is_active',$this->is_active,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('timezone',$this->timezone);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}