<?php

/**
 * This is the model class for table "descuento_recargo".
 *
 * The followings are the available columns in table 'descuento_recargo':
 * @property integer $id_descuento_recargo
 * @property string $tipo
 * @property string $descripcion
 * @property integer $modo
 * @property integer $monto
 *
 * The followings are the available model relations:
 * @property CuentaSocio[] $cuentaSocios
 */
class DescuentoRecargo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'descuento_recargo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tipo, descripcion, modo, monto', 'required'),
			array('monto', 'numerical', 'integerOnly'=>true),
			array('modo', 'length', 'max'=>2),
			array('tipo', 'length', 'max'=>10),
			array('descripcion', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_descuento_recargo, tipo, descripcion, modo, monto', 'safe', 'on'=>'search'),
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
			'cuentaSocios' => array(self::HAS_MANY, 'CuentaSocio', 'id_descuento_recargo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_descuento_recargo' => 'Id Descuento Recargo',
			'tipo' => 'Tipo',
			'descripcion' => 'Descripcion',
			'modo' => 'Modo',
			'monto' => 'Monto',
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

		$criteria->compare('id_descuento_recargo',$this->id_descuento_recargo);
		$criteria->compare('tipo',$this->tipo,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('modo',$this->modo);
		$criteria->compare('monto',$this->monto);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DescuentoRecargo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
