<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $registerDate
 * @property string $lastvisitDate
 * @property string $activation
 * @property integer $group_id
 * @property integer $status
 */
class User extends CActiveRecord {

    public $status;
    public $title;
    public $UserProfile = array();
    public $verifyPassword;
    public $back_pwd;
    //public $tos;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, username, email, password', 'required'),
            array('group_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('username', 'length', 'max' => 150),
            array('username', 'unique'),
            array('email', 'unique'),
            array('email, password, activation', 'length', 'max' => 100),
            array('registerDate, lastvisitDate, user_type', 'safe'),
            array('name', 'ext.alpha', 'allowNumbers' => true, 'allowSpaces' => true, 'minChars' => 3, 'extra' => array('.', '-', "'", '"', ':'), 'message' => 'Not valid. Non alpha-numeric characters (like @,#,$,_ etc.) are not allowed'),
            array('username', 'ext.alpha', 'allowNumbers' => true, 'extra' => array('.', '-', '_', "'", '"', ':'), 'minChars' => 3, 'message' => 'User name not valid'),
            array('email', 'email', 'checkMX' => true),
            array('verifyPassword', 'required', 'on' => 'insert, changePassword'),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => "Retype Password is incorrect.", 'on' => 'insert, changePassword'),
            //array( 'tos', 'compare', 'on'=>'register', 'compareValue' => true, 'message' => 'You must agree to the terms and conditions' ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, username, email, password, registerDate, lastvisitDate, activation,group_id,status,title', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'UserStatus' => array(self::BELONGS_TO, 'UserStatus', 'status'),
            'UserGroup' => array(self::BELONGS_TO, 'UserGroup', 'group_id'),
            'UserProfile' => array(self::HAS_ONE, 'UserProfile', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'registerDate' => 'Register Date',
            'lastvisitDate' => 'Lastvisit Date',
            'activation' => 'Activation',
            'group_id' => 'Group',
            'status' => 'Status',
            'verifyPassword' => "Retype Password",
            'user_type' => 'User Type',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('registerDate', $this->registerDate, true);
        $criteria->compare('lastvisitDate', $this->lastvisitDate, true);
        $criteria->compare('activation', $this->activation, true);
        $criteria->compare('group_id', $this->group_id);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
             'sort' => array('defaultOrder' => 'registerDate DESC, id DESC')
        ));
    }

    public function getGroupName($id) {
        $returnValue = Yii::app()->db->createCommand()
                ->select('title')
                ->from('{{user_group}}')
                ->where('id=:id', array(':id' => $id))
                ->queryScalar();

        return $returnValue;
    }

    //get name
    public static function getName($id) {
        $value = User::model()->findByAttributes(array('id' => $id));
        if (!empty($value->name)) {
            return $value->name;
        } else {
            return null;
        }
    }

    //get email
    public static function getEmail($id) {
        $value = User::model()->findByAttributes(array('id' => $id));
        if (!empty($value->email)) {
            return $value->email;
        } else {
            return null;
        }
    }

    public static function get_user_name($user_id) {
        $value = User::model()->findByAttributes(array('id' => $user_id));
        if (empty($value->name)) {
            return 'Not set!';
        } else {
            return $value->name;
        }
    }
    
    /**
     * Send mail method
     */
    public static function sendMail($to, $subject, $message, $fromName, $fromMail) {
        $headers = "From: " . $fromName . "<" . $fromMail . "> \r\nX-Mailer: php\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";        
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $message, $headers);
    }

}
