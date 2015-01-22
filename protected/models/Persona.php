<?php

/**
 * This is the model class for table "persona".
 *
 * The followings are the available columns in table 'persona':
 * @property integer $id_persona
 * @property string $nombre
 * @property string $apellido
 * @property string $dni
 * @property integer $estado
 * @property string $direccion
 * @property string $email
 * @property string $telefono
 * @property string $fecha_nac
 * @property string $sexo
 *
 * The followings are the available model relations:
 * @property Empleado[] $empleados
 * @property Socio[] $socios
 */
class Persona extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'persona';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, apellido, dni, estado, sexo', 'required'),
			array('estado, dni', 'numerical', 'integerOnly'=>true),
			array('nombre, apellido, telefono', 'length', 'max'=>30),
                        array('nombre, apellido','CRegularExpressionValidator', 'pattern'=>'/^[a-zA-z]{1,}$/','message'=>"{attribute} tiene que ser solo texto."),
                        array('dni', 'unique', 'on' => 'insert, update','message'=>'El {attribute} "{value}" ya existe.'),
			array('dni', 'length', 'max'=>11),
			array('direccion', 'length', 'max'=>40),
			array('email', 'length', 'max'=>50),
                        array('email', 'email','message'=>"El mail no es correcto"),
			array('sexo', 'length', 'max'=>9),
			array('id_persona, fecha_nac', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_persona, nombre, apellido, dni, estado, direccion, email, telefono, fecha_nac, sexo', 'safe', 'on'=>'search'),
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
			'empleados' => array(self::HAS_MANY, 'Empleado', 'id_persona'),
			'socios' => array(self::HAS_MANY, 'Socio', 'id_persona'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_persona' => 'Id Persona',
			'nombre' => 'Nombre',
			'apellido' => 'Apellido',
			'dni' => 'Dni',
			'estado' => 'Estado',
			'direccion' => 'Direccion',
			'email' => 'Email',
			'telefono' => 'Telefono',
			'fecha_nac' => 'Fecha Nac',
			'sexo' => 'Sexo',
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

		$criteria->compare('id_persona',$this->id_persona);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('apellido',$this->apellido,true);
		$criteria->compare('dni',$this->dni,true);
		$criteria->compare('estado',$this->estado);
		$criteria->compare('direccion',$this->direccion,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('telefono',$this->telefono,true);
		$criteria->compare('fecha_nac',$this->fecha_nac,true);
		$criteria->compare('sexo',$this->sexo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Persona the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        protected function beforeSave(){ 
            $this->fecha_nac=Yii::app()->dateFormatter->format("yyyy-MM-dd", $this->fecha_nac);
            return parent::beforeSave();
        }
        protected function afterFind(){
            $this->fecha_nac=Yii::app()->dateFormatter->format("dd-MM-yyyy", $this->fecha_nac); 
            return parent::afterFind();
        }
}
