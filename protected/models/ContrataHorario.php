<?php

/**
 * This is the model class for table "contrata_horario".
 *
 * The followings are the available columns in table 'contrata_horario':
 * @property integer $id_contrata_horario
 * @property integer $id_contrato
 * @property integer $id_horario
 *
 * The followings are the available model relations:
 * @property Contrato $idContrato
 * @property Horario $idHorario
 */
class ContrataHorario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contrata_horario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
                return array(
			array('id_contrato, id_horario', 'required'),
			array('id_contrato, id_horario', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_contrata_horario, id_contrato, id_horario', 'safe', 'on'=>'search'),
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
			'idHorario' => array(self::BELONGS_TO, 'Horario', 'id_horario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_contrata_horario' => 'Id Contrata Horario',
			'id_contrato' => 'Id Contrato',
			'id_horario' => 'Id Horario',
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

		$criteria->compare('id_contrata_horario',$this->id_contrata_horario);
		$criteria->compare('id_contrato',$this->id_contrato);
		$criteria->compare('id_horario',$this->id_horario);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContrataHorario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
