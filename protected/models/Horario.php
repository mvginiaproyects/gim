<?php

/**
 * This is the model class for table "horario".
 *
 * The followings are the available columns in table 'horario':
 * @property integer $id_horario
 * @property string $dia
 * @property integer $orden
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property integer $id_salon
 * @property integer $id_evento
 * @property integer $id_grupo
 * @property integer $disponibilidad
 *
 * The followings are the available model relations:
 * @property Asistencia[] $asistencias
 * @property ContrataHorario[] $contrataHorarios
 * @property Evento $idEvento
 * @property Grupo $idGrupo
 * @property Salon $idSalon
 */
class Horario extends CActiveRecord
{

         private $_nombreEvento = null;
         private $_nombreSalon = null;
    
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'horario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dia, orden, hora_inicio, hora_fin, id_salon, id_evento', 'required'),
			array('orden, id_salon, id_evento, id_grupo, disponibilidad', 'numerical', 'integerOnly'=>true),
			array('dia', 'length', 'max'=>9),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_horario, dia, orden, hora_inicio, hora_fin, id_salon, id_evento, id_grupo, disponibilidad', 'safe', 'on'=>'search'),
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
			'asistencias' => array(self::HAS_MANY, 'Asistencia', 'id_horario'),
			'contrataHorarios' => array(self::HAS_MANY, 'ContrataHorario', 'id_horario'),
			'idEvento' => array(self::BELONGS_TO, 'Evento', 'id_evento'),
			'idGrupo' => array(self::BELONGS_TO, 'Grupo', 'id_grupo'),
			'idSalon' => array(self::BELONGS_TO, 'Salon', 'id_salon'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_horario' => 'Id Horario',
			'dia' => 'Dia',
			'orden' => 'Orden',
			'hora_inicio' => 'Hora Inicio',
			'hora_fin' => 'Hora Fin',
			'id_salon' => 'Id Salon',
			'id_evento' => 'Id Evento',
			'id_grupo' => 'Id Grupo',
			'disponibilidad' => 'Disponibilidad',
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

		$criteria->compare('id_horario',$this->id_horario);
		$criteria->compare('dia',$this->dia,true);
		$criteria->compare('orden',$this->orden);
		$criteria->compare('hora_inicio',$this->hora_inicio,true);
		$criteria->compare('hora_fin',$this->hora_fin,true);
		$criteria->compare('id_salon',$this->id_salon);
		$criteria->compare('id_evento',$this->id_evento);
		$criteria->compare('id_grupo',$this->id_grupo);
		$criteria->compare('disponibilidad',$this->disponibilidad);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Horario the static model class
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
		}
		return $this->_nombreEvento;
	}
	public function setNombreEvento($value)
	{
		$this->_nombreEvento = $value;
	}        

	public function getNombreSalon()
	{
		if ($this->_nombreSalon === null && $this->idSalon !== null)
		{
			$this->_nombreSalon = $this->idSalon->nombre;
		}
		return $this->_nombreSalon;
	}
	public function setNombreSalon($value)
	{
		$this->_nombreSalon = $value;
	}        
            
        
}
