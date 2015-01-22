<?php

/**
 * This is the model class for table "contrata_evento".
 *
 * The followings are the available columns in table 'contrata_evento':
 * @property integer $id_contrata
 * @property integer $id_evento
 * @property integer $id_socio
 * @property integer $estado
 * @property string $fecha_contrata
 * @property integer $vencimiento
 * @property integer $tipo_contrato
 *
 * The followings are the available model relations:
 * @property Evento $idEvento
 * @property Socio $idSocio
 */
class ContrataEvento extends CActiveRecord
{
    
	private $_nombreEvento = null;
	private $_nombreSocio = null;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contrata_evento';
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
			array('id_evento, id_socio, estado, vencimiento, tipo_contrato', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_contrata, id_evento, id_socio, estado, fecha_contrata, vencimiento, tipo_contrato', 'safe', 'on'=>'search'),
			array('id_contrata, id_evento, id_socio, estado, fecha_contrata, vencimiento, tipo_contrato', 'safe', 'on'=>'actualizarContrato'),
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
			'idSocio' => array(self::BELONGS_TO, 'Socio', 'id_socio'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_contrata' => 'Id Contrata',
			'id_evento' => 'Id Evento',
			'id_socio' => 'Id Socio',
			'estado' => 'Estado',
			'fecha_contrata' => 'Fecha Contrata',
			'vencimiento' => 'Vencimiento',
			'tipo_contrato' => 'Tipo Contrato',
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

		$criteria->compare('id_contrata',$this->id_contrata);
		$criteria->compare('id_evento',$this->id_evento);
		$criteria->compare('id_socio',$this->id_socio);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('fecha_contrata',$this->fecha_contrata,true);
		$criteria->compare('vencimiento',$this->vencimiento);
		$criteria->compare('tipo_contrato',$this->tipo_contrato);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContrataEvento the static model class
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
     
        protected function afterFind(){
            $this->fecha_contrata=Yii::app()->dateFormatter->format("dd-MM-yyyy", $this->fecha_contrata); 
            return parent::afterFind();
        }
 
}
