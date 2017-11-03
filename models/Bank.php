<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $sum
 * @property string $date
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sum'], 'required'],
            [['user_id'], 'integer'],
			[['parent_id'], 'integer'],
            [['sum'], 'number'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'sum' => 'Sum',
            'date' => 'Date',
        ];
    }

	/**
	 * Get necessary row from db by id
	 *
	 * @param $id
	 * @return static
	 */
	public function findId($id)
	{
		return static::findOne(['id' => $id]);
	}


}
