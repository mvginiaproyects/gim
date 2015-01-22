<?php

/**
 * This is the model class for table "socio".
 *
 * The followings are the available columns in table 'socio':
 * @property integer $id_socio
 * @property integer $id_persona
 * @property string $fecha_ingreso
 * @property string $nombre
 * @property string $apellido
 *
 * The followings are the available model relations:
 * @property ContrataEvento[] $contrataEventos
 * @property Persona $idPersona
 */
class Socio extends CActiveRecord
{

	private $_nombre = null;
	private $_apellido = null;
	private $_dni = null;
	private $_estado = null;
        private $_direccion = null;
        private $_email = null;
        private $_telefono = null;
        private $_fecha_nac = null;
        private $_sexo = null;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'socio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, apellido, dni, sexo', 'required'),
			array('id_persona', 'numerical', 'integerOnly'=>true),
			array('nombre, apellido', 'length', 'max'=>30),
                        //array('dni', 'unique', 'on' => 'insert, update'),
			array('dni', 'length', 'max'=>11),
			array('id_persona, fecha_ingreso, estado, direccion, email, fecha_nac, telefono', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_socio, fecha_ingreso, nombre, apellido, dni', 'safe', 'on'=>'search'),
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
			'contratos' => array(self::HAS_MANY, 'Contrato', 'id_socio'),
			'idPersona' => array(self::BELONGS_TO, 'Persona', 'id_persona'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_socio' => 'Id Socio',
			'id_persona' => 'Id Persona',
			'fecha_ingreso' => 'Fecha Ingreso',
			'nombre' => 'Nombre',
			'apellido' => 'Apellido',
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
		$criteria->with='idPersona';
		//$criteria->compare('id_persona',$this->id_persona);
		//$criteria->addCondition("idPersona.apellido like :apeSocio");
		//$criteria->params = array(':apeSocio' => '%'.$this->apellido.'%');
		$criteria->compare('id_socio',$this->id_socio);
		$criteria->compare('idPersona.apellido',$this->apellido,true);
		$criteria->compare('idPersona.nombre',$this->nombre,true);
		$criteria->compare('fecha_ingreso',$this->fecha_ingreso,true);
		$criteria->compare('idPersona.dni',$this->dni,true);
		$criteria->compare('idPersona.estado',$this->estado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'pagination'=>array(
                        //'pageSize'=>2,
                )
		));
	}

        protected function afterFind(){
            $this->fecha_ingreso=Yii::app()->dateFormatter->format("dd-MM-yyyy", $this->fecha_ingreso); 
            return parent::afterFind();
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Socio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getNombre()
	{
		if ($this->_nombre === null && $this->idPersona !== null)
		{
			$this->_nombre = $this->idPersona->nombre;
		}
		return $this->_nombre;
	}
	public function setNombre($value)
	{
		$this->_nombre = $value;
	}

	public function getApellido()
	{
		if ($this->_apellido === null && $this->idPersona !== null)
		{
			$this->_apellido = $this->idPersona->apellido;
		}
		return $this->_apellido;
	}
	public function setApellido($value)
	{
		$this->_apellido = $value;
	}

	public function getDni()
	{
		if ($this->_dni === null && $this->idPersona !== null)
		{
			$this->_dni = $this->idPersona->dni;
		}
		return $this->_dni;
	}
	public function setDni($value)
	{
		$this->_dni = $value;
	}

	public function getEstado()
	{
		if ($this->_estado === null && $this->idPersona !== null)
		{
			$this->_estado = $this->idPersona->estado;
		}
		return $this->_estado;
	}
	public function setEstado($value)
	{
		$this->_estado = $value;
	}	

        public function getDireccion()
	{
		if ($this->_direccion === null && $this->idPersona !== null)
		{
			$this->_direccion = $this->idPersona->direccion;
		}
		return $this->_direccion;
	}
	public function setDireccion($value)
	{
		$this->_direccion = $value;
	}
        
        public function getEmail()
	{
		if ($this->_email === null && $this->idPersona !== null)
		{
			$this->_email = $this->idPersona->email;
		}
		return $this->_email;
	}
	public function setEmail($value)
	{
		$this->_email = $value;
	}

        public function getTelefono()
	{
		if ($this->_telefono === null && $this->idPersona !== null)
		{
			$this->_telefono = $this->idPersona->telefono;
		}
		return $this->_telefono;
	}
	public function setTelefono($value)
	{
		$this->_telefono = $value;
	}
        
        public function getFecha_nac()
	{
		if ($this->_fecha_nac === null && $this->idPersona !== null)
		{
			$this->_fecha_nac = $this->idPersona->fecha_nac;
		}
		return $this->_fecha_nac;
	}
	public function setFecha_nac($value)
	{
		$this->_fecha_nac = $value;
	}
        
        public function getSexo()
	{
		if ($this->_sexo === null && $this->idPersona !== null)
		{
			$this->_sexo = $this->idPersona->sexo;
		}
		return $this->_sexo;
	}
	public function setSexo($value)
	{
		$this->_sexo = $value;
	}
}
