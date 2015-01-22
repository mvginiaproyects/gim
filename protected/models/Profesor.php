<?php

/**
 * This is the model class for table "profesor".
 *
 * The followings are the available columns in table 'profesor':
 * @property integer $id_profesor
 * @property integer $id_empleado
 *
 * The followings are the available model relations:
 * @property Evento[] $eventos
 * @property Empleado $idEmpleado
 */
class Profesor extends CActiveRecord
{

        private $_nombreProfesor = null;
        private $_apellidoProfesor = null;
    
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profesor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_empleado', 'required'),
			array('id_empleado', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_profesor, id_empleado, nombreProfesor, apellidoProfesor', 'safe', 'on'=>'search'),
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
			'eventos' => array(self::HAS_MANY, 'Evento', 'id_profesor'),
			'idEmpleado' => array(self::BELONGS_TO, 'Empleado', 'id_empleado'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_profesor' => 'Profesor',
			'id_empleado' => 'Empleado',
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

		$criteria->compare('id_profesor',$this->id_profesor);
		$criteria->compare('id_empleado',$this->id_empleado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Profesor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getFullName(){
            return $this->idEmpleado->idPersona->apellido . ", " . $this->idEmpleado->idPersona->nombre;
        }

	public function getNombreProfesor()
	{
		if ($this->_nombreProfesor === null && $this->idEmpleado !== null)
		{
			$this->_nombreProfesor = $this->idEmpleado->idPersona->nombre;
		}
		return $this->_nombreProfesor;
	}
	public function setNombreProfesor($value)
	{
		$this->_nombreProfesor = $value;
	}	        

	public function getApellidoProfesor()
	{
		if ($this->_apellidoProfesor === null && $this->idEmpleado !== null)
		{
			$this->_apellidoProfesor = $this->idEmpleado->idPersona->apellido;
		}
		return $this->_apellidoProfesor;
	}
	public function setApellidoProfesor($value)
	{
		$this->_apellidoProfesor = $value;
	}	        
        
        
}
