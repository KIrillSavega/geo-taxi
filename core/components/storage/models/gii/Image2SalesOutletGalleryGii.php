<?php

/**
 * This is the model class for table "image2sales_outlet_gallery".
 *
 * The followings are the available columns in table 'image2sales_outlet_gallery':
 * @property string $image_uid
 * @property string $sales_outlet_id
 * @property string $order
 */
class Image2SalesOutletGalleryGii extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Image2SalesOutletGalleryGii the static model class
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
		return Yii::app()->dbStorage;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'image2sales_outlet_gallery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image_uid, sales_outlet_id', 'required'),
			array('image_uid', 'length', 'max'=>32),
			array('sales_outlet_id', 'length', 'max'=>20),
			array('order', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('image_uid, sales_outlet_id, order', 'safe', 'on'=>'search'),
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
			'image_uid' => 'Image Uid',
			'sales_outlet_id' => 'Sales Outlet',
			'order' => 'Order',
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

		$criteria->compare('image_uid',$this->image_uid,true);
		$criteria->compare('sales_outlet_id',$this->sales_outlet_id,true);
		$criteria->compare('order',$this->order,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}