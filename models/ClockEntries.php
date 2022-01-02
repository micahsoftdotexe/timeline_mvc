<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clock_entries".
 *
 * @property int $id
 * @property int $user_id
 * @property string $clock_in_time
 * @property string|null $clock_out_time
 *
 * @property User $user
 */
class ClockEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clock_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'clock_in_time'], 'required'],
            [['user_id'], 'integer'],
            [['clock_in_time', 'clock_out_time'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'clock_in_time' => 'Clock In Time',
            'clock_out_time' => 'Clock Out Time',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
