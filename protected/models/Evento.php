<?php

/**
 * This is the model class for table "evento".
 *
 * The followings are the available columns in table 'evento':
 * @property integer $id_evento <-Este
 * @property string $nombre <-Este
 * @property string $costo X
 * @property integer $id_profesor <-Este
 * @property integer $limite X
 * @property string $color X
 *
 * The followings are the available model relations:
 * @property Contrato[] $contratos
 * @property Profesor $idProfesor
 * @property Horario[] $horarios
 * @property Precio[] $precios
 */
class Evento extends CActiveRecord
{

        private $_nombreProfesor = null;
        private $_apellidoProfesor = null;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'evento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, id_profesor', 'required'),
			array('id_profesor, limite', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>30),
			array('costo', 'length', 'max'=>10),
			array('color', 'length', 'max'=>20),
                        array('nombre, id_profesor', 'ECompositeUniqueValidator', 
                                   //'attributesToAddError'=>'id_profesor',
                                   'message'=>'Ya existe un evento con el mismo nombre dictado por el profesor seleccionado.'),                    
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_evento, nombre, costo, id_profesor, limite, color, nombreProfesor, apellidoProfesor', 'safe', 'on'=>'search'),
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
			'contratos' => array(self::HAS_MANY, 'Contrato', 'id_evento'),
			'idProfesor' => array(self::BELONGS_TO, 'Profesor', 'id_profesor'),
			'horarios' => array(self::HAS_MANY, 'Horario', 'id_evento'),
			'precios' => array(self::HAS_MANY, 'Precio', 'id_evento'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_evento' => 'Id Evento',
			'nombre' => 'Nombre Evento',
			'nombreProfesor' => 'Nombre profesor',
			'apellidoProfesor' => 'Apellido profesor',
			'costo' => 'Costo',
			'id_profesor' => 'Profesor',
			'limite' => 'Limite',
			'color' => 'Color',
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
                //$criteria->with = array('idProfesor'=>array('with'=>array('idEmpleado'=>array('with'=>'idPersona'))));
                //$criteria->with = 'idProfesor';
                $criteria->with = array('idProfesor'=>array('with'=>array('idEmpleado'=>array('with'=>'idPersona'))));
                
		$criteria->compare('t.id_evento',$this->id_evento, true);
		$criteria->compare('t.nombre',$this->nombre,true);
		$criteria->compare('idPersona.nombre',$this->nombreProfesor,true);
		$criteria->compare('idPersona.apellido',$this->apellidoProfesor,true);
		//$criteria->compare('costo',$this->costo,true);
		//$criteria->compare('id_profesor',$this->id_profesor);
		//$criteria->compare('limite',$this->limite);
		//$criteria->compare('color',$this->color,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Evento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getFullName(){
            return $this->apellidoProfesor . ", " . $this->nombreProfesor;
        }        
        
	public function getNombreProfesor()
	{
		if ($this->_nombreProfesor === null && $this->idProfesor !== null)
		{
			$this->_nombreProfesor = $this->idProfesor->nombreProfesor;
		}
		return $this->_nombreProfesor;
	}
	public function setNombreProfesor($value)
	{
		$this->_nombreProfesor = $value;
	}	        

	public function getApellidoProfesor()
	{
		if ($this->_apellidoProfesor === null && $this->idProfesor !== null)
		{
			$this->_apellidoProfesor = $this->idProfesor->apellidoProfesor;
		}
		return $this->_apellidoProfesor;
	}
	public function setApellidoProfesor($value)
	{
		$this->_apellidoProfesor = $value;
	}	        
        
}
