<?php

/**
 * This is the model class for table "asistencia".
 *
 * The followings are the available columns in table 'asistencia':
 * @property integer $id_asistencia
 * @property integer $id_socio
 * @property integer $id_horario
 * @property string $fecha
 *
 * The followings are the available model relations:
 * @property Horario $idHorario
 * @property Socio $idSocio
 */
class Asistencia extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'asistencia';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_socio, id_horario, fecha', 'required'),
			array('id_socio, id_horario', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_asistencia, id_socio, id_horario, fecha', 'safe', 'on'=>'search'),
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
			'idHorario' => array(self::BELONGS_TO, 'Horario', 'id_horario'),
			'idSocio' => array(self::BELONGS_TO, 'Socio', 'id_socio'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_asistencia' => 'Id Asistencia',
			'id_socio' => 'Id Socio',
			'id_horario' => 'Id Horario',
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

		$criteria->compare('id_asistencia',$this->id_asistencia);
		$criteria->compare('id_socio',$this->id_socio);
		$criteria->compare('id_horario',$this->id_horario);
		$criteria->compare('fecha',$this->fecha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Asistencia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
