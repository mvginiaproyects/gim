<?php

/**
 * This is the model class for table "precio".
 *
 * The followings are the available columns in table 'precio':
 * @property integer $id_precio
 * @property integer $id_evento
 * @property integer $tipo
 * @property string $descripcion
 * @property integer $clases_x_semana
 * @property integer $precio
 *
 * The followings are the available model relations:
 * @property Evento $idEvento
 */
class Precio extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'precio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_evento, tipo, precio', 'required'),
			array('id_evento, tipo, clases_x_semana, precio', 'numerical', 'integerOnly'=>true),
			array('descripcion', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_precio, id_evento, tipo, descripcion, clases_x_semana, precio', 'safe', 'on'=>'search'),
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
			'idEvento' => array(self::BELONGS_TO, 'Evento', 'id_evento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_precio' => 'Id Precio',
			'id_evento' => 'Id Evento',
			'tipo' => 'Tipo',
			'descripcion' => 'Descripcion',
			'clases_x_semana' => 'Clases X Semana',
			'precio' => 'Precio',
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

		$criteria->compare('id_precio',$this->id_precio);
		$criteria->compare('id_evento',$this->id_evento);
		$criteria->compare('tipo',$this->tipo);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('clases_x_semana',$this->clases_x_semana);
		$criteria->compare('precio',$this->precio);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Precio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
