<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $article
 * @property string $name
 * @property int $remainder
 * @property string $unit
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article', 'name', 'remainder', 'unit'], 'required'],
            [['remainder'], 'default', 'value' => null],
            [['remainder'], 'integer'],
            [['article', 'name', 'unit'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Артикул',
            'name' => 'Наименование',
            'remainder' => 'Остаток',
            'unit' => 'Ед. Изм.',
        ];
    }
}
