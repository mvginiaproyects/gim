<?php

/**
 * This is the model class for table "contrato".
 *
 * The followings are the available columns in table 'contrato':
 * @property integer $id_contrato
 * @property integer $id_evento
 * @property integer $id_socio
 * @property integer $estado
 * @property string $fecha_contrata
 * @property integer $cantidad_clases
 * @property integer $tipo_contrato
 * @property string $vencimiento
 * @property integer $saldo
 *
 * The followings are the available model relations:
 * @property ContrataHorario[] $contrataHorarios
 * @property Evento $idEvento
 * @property Socio $idSocio
 * @property CuentaSocio[] $cuentaSocios
 */
class Contrato extends CActiveRecord
{
        private $_nombreEvento = null;
	private $_nombreSocio = null;
	private $_apellidoSocio = null;

        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contrato';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_evento, id_socio, fecha_contrata, tipo_contrato', 'required'),
			array('id_evento, id_socio, estado, cantidad_clases, tipo_contrato, saldo', 'numerical', 'integerOnly'=>true),
			array('vencimiento', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_contrato, id_evento, id_socio, estado, fecha_contrata, cantidad_clases, tipo_contrato, vencimiento, saldo', 'safe', 'on'=>'search'),
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
			'contrataHorarios' => array(self::HAS_MANY, 'ContrataHorario', 'id_contrato'),
			'idEvento' => array(self::BELONGS_TO, 'Evento', 'id_evento'),
			'idSocio' => array(self::BELONGS_TO, 'Socio', 'id_socio'),
			'cuentaSocios' => array(self::HAS_MANY, 'CuentaSocio', 'id_contrato'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_contrato' => 'Id Contrato',
			'id_evento' => 'Id Evento',
			'id_socio' => 'Id Socio',
			'estado' => 'Estado',
			'fecha_contrata' => 'Fecha Contrata',
			'cantidad_clases' => 'Cantidad Clases',
			'tipo_contrato' => 'Tipo Contrato',
			'vencimiento' => 'Vencimiento',
			'saldo' => 'Saldo',
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

		$criteria->compare('id_contrato',$this->id_contrato);
		$criteria->compare('id_evento',$this->id_evento);
		$criteria->compare('id_socio',$this->id_socio);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('fecha_contrata',$this->fecha_contrata,true);
		$criteria->compare('cantidad_clases',$this->cantidad_clases);
		$criteria->compare('tipo_contrato',$this->tipo_contrato);
		$criteria->compare('vencimiento',$this->vencimiento,true);
		$criteria->compare('saldo',$this->saldo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Contrato the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
	public function getNombreEvento()
	{
		if ($this->_nombreEvento === null && $this->idEvento !== null)
		{
			$this->_nombreEvento = $this->idEvento->nombre;
			//echo "Paso getNombreEvento {$this->_nombreEvento}</br>";
		}
		return $this->_nombreEvento;
	}
        
	public function setNombreEvento($value)
	{
		$this->_nombreEvento = $value;
	}
        
	public function getNombreSocio()
	{
		if ($this->_nombreSocio === null && $this->idSocio !== null)
		{
			$this->_nombreSocio = $this->idSocio->nombre;
			//echo "Paso getNombre {$this->_nombre}</br>";
		}
		return $this->_nombreSocio;
	}
        
	public function setNombreSocio($value)
	{
		$this->_nombreSocio = $value;
	}

	public function getApellidoSocio()
	{
		if ($this->_apellidoSocio === null && $this->idSocio !== null)
		{
			$this->_apellidoSocio = $this->idSocio->apellido;
			//echo "Paso getNombre {$this->_nombre}</br>";
		}
		return $this->_apellidoSocio;
	}
        
	public function setApellidoSocio($value)
	{
		$this->_apellidoSocio = $value;
	}
        
        protected function afterFind(){
            $this->fecha_contrata=Yii::app()->dateFormatter->format("dd-MM-yyyy", $this->fecha_contrata); 
            $this->vencimiento=Yii::app()->dateFormatter->format("dd-MM-yyyy", $this->vencimiento); 
            return parent::afterFind();
        }

        protected function beforeSave(){ 
            $this->fecha_contrata=Yii::app()->dateFormatter->format("yyyy-MM-dd", $this->fecha_contrata);
            $this->vencimiento=Yii::app()->dateFormatter->format("yyyy-MM-dd", $this->vencimiento); 
            return parent::beforeSave();
        }
}
