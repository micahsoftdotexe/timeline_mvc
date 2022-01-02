<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $first_name
 * @property string $last_name
 */
    // public $id;
    // public $username;
    // public $password;
    // public $authKey;
    // public $accessToken;

    // private static $users = [
    //     '100' => [
    //         'id' => '100',
    //         'username' => 'admin',
    //         'password' => 'admin',
    //         'authKey' => 'test100key',
    //         'accessToken' => '100-token',
    //     ],
    //     '101' => [
    //         'id' => '101',
    //         'username' => 'demo',
    //         'password' => 'demo',
    //         'authKey' => 'test101key',
    //         'accessToken' => '101-token',
    //     ],
    // ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }


   /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['username','password', 'first_name', 'last_name'], 'required'],
            [['username'], 'unique'],
            [['wage'], 'number'],
            [['username','password','first_name','last_name'], 'string', 'max' => 250],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->password)) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // foreach (self::$users as $user) {
        //     if ($user['accessToken'] === $token) {
        //         return new static($user);
        //     }
        // }

        // return null;
        $user = self::find()->where(["access_token" => $token])->one();
        if (empty($user)) {
            return null;
        }
        return new static($user);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // foreach (self::$users as $user) {
        //     if (strcasecmp($user['username'], $username) === 0) {
        //         return new static($user);
        //     }
        // }

        // return null;
        return self::findOne(["username" => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
    * Validates password
    *
    * @param string $password password to validate
    * @return bool if password provided is valid for current user
    */
    public function validatePassword($password)
    {
        //return $this->password === $password;                    // plain text password
        //return $this->password ===  md5($password);              // md5 password
        //return password_verify($password, $this->passwordHash);  // password hash (recommended)
        return Yii::$app->security->validatePassword($password, $this->password);  // password hash (recommended)
    }

     /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function setPassword($password)
    {
        //$this->password_hash = password_hash($password, PASSWORD_DEFAULT);  // hash
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getCheckClockIn()
    {
        $lastClock = ClockEntries::find()->where(['id' => ClockEntries::find()->where(['user_id' =>$this->id])->max('id')])->one();
        if ( $lastClock ) {
            //$lastClock->one();
            if ($lastClock->clock_in_time!=null && $lastClock->clock_out_time!=null) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function getCheckClockOut()
    {
        $lastClock = ClockEntries::find()->where(['id' => ClockEntries::find()->where(['user_id' =>$this->id])->max('id')])->one();
        // Yii::debug('$lastClock', 'dev');
        if ($lastClock) {
            //$lastClock->one();
            if ($lastClock->clock_in_time!=null && $lastClock->clock_out_time == null) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // /**
    //  * Validates password
    //  *
    //  * @param string $password password to validate
    //  * @return bool if password provided is valid for current user
    //  */
    // public function validatePassword($password)
    // {
    //     return $this->password === $password;
    // }
}
