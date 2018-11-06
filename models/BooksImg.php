<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books_img".
 *
 * @property int $id
 * @property int $book_id
 * @property string $img
 */
class BooksImg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books_img';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id'], 'required'],
            [['book_id'], 'integer'],
            [['img'], 'file', 'extensions'=>'jpg,png,bmp','maxFiles'=>5,'skipOnEmpty'=>'false','maxSize' => 512000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'img' => 'Covers',
        ];
    }
}
