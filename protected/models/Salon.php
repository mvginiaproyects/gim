<?php

/**
 * This is the model class for table "salon".
 *
 * The followings are the available columns in table 'salon':
 * @property integer $id_salon
 * @property string $nombre
 * @property integer $capacidad_max
 * @property string $descripcion
 *
 * The followings are the available model relations:
 * @property HorarioDias[] $horarioDiases
 */
class Salon extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'salon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre', 'required'),
			array('nombre', 'length', 'max'=>15),
                        array('color', 'unique', 'on' => 'insert, update','message'=>'El {attribute} "{value}" ya existe.'),
                        array('color', 'length', 'max'=>20),
                        array('capacidad_max', 'numerical', 'integerOnly'=>true),
			array('descripcion', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_salon, nombre, color, capacidad_max, descripcion', 'safe', 'on'=>'search'),
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
			'horarioDiases' => array(self::HAS_MANY, 'HorarioDias', 'id_salon'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_salon' => 'Id Salon',
			'nombre' => 'Nombre',
                        'color' => 'Color',
			'capacidad_max' => 'Capacidad Max',
			'descripcion' => 'Descripcion',
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

		$criteria->compare('id_salon',$this->id_salon);
		$criteria->compare('nombre',$this->nombre,true);
                $criteria->compare('color',$this->nombre,true);
		$criteria->compare('capacidad_max',$this->capacidad_max);
		$criteria->compare('descripcion',$this->descripcion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Salon the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
