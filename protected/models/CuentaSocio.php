<?php

/**
 * This is the model class for table "cuenta_socio".
 *
 * The followings are the available columns in table 'cuenta_socio':
 * @property integer $id_cuenta_socio
 * @property string $movimiento
 * @property string $concepto
 * @property integer $id_descuento_recargo
 * @property integer $id_contrato
 * @property integer $monto
 * @property string $fecha
 *
 * The followings are the available model relations:
 * @property Contrato $idContrato
 * @property DescuentoRecargo $idDescuentoRecargo
 */
class CuentaSocio extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cuenta_socio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('movimiento, concepto, id_contrato, monto, fecha', 'required'),
			array('id_descuento_recargo, id_contrato, monto', 'numerical', 'integerOnly'=>true),
			array('movimiento', 'length', 'max'=>6),
			array('concepto', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_cuenta_socio, movimiento, concepto, id_descuento_recargo, id_contrato, monto, fecha', 'safe', 'on'=>'search'),
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
			'idContrato' => array(self::BELONGS_TO, 'Contrato', 'id_contrato'),
			'idDescuentoRecargo' => array(self::BELONGS_TO, 'DescuentoRecargo', 'id_descuento_recargo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_cuenta_socio' => 'Id Cuenta Socio',
			'movimiento' => 'Movimiento',
			'concepto' => 'Concepto',
			'id_descuento_recargo' => 'Id Descuento Recargo',
			'id_contrato' => 'Id Contrato',
			'monto' => 'Monto',
			'fecha' => 'Fecha',
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

		$criteria->compare('id_cuenta_socio',$this->id_cuenta_socio);
		$criteria->compare('movimiento',$this->movimiento,true);
		$criteria->compare('concepto',$this->concepto,true);
		$criteria->compare('id_descuento_recargo',$this->id_descuento_recargo);
		$criteria->compare('id_contrato',$this->id_contrato);
		$criteria->compare('monto',$this->monto);
		$criteria->compare('fecha',$this->fecha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CuentaSocio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        protected function beforeSave(){ 
            $this->fecha=Yii::app()->dateFormatter->format("yyyy-MM-dd", $this->fecha);
            return parent::beforeSave();
        }
        protected function afterFind(){
            $this->fecha=Yii::app()->dateFormatter->format("dd-MM-yyyy", $this->fecha); 
            return parent::afterFind();
        }
}
