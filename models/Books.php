<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property int $ISBN
 * @property string $author
 * @property string $title
 * @property string $img
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ISBN', 'author', 'title'], 'required'],
            [['ISBN'], 'integer'],
			[['ISBN'], 'unique'],
            [['author', 'title'], 'string'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ISBN' => 'ISBN',
            'author' => 'Author',
            'title' => 'Title',
        ];
    }
}
