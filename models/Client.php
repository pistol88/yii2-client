<?php
namespace pistol88\client\models;

use Yii;
use yii\helpers\Url;
use pistol88\client\models\Category;
use pistol88\client\models\Price;
use pistol88\client\models\client\ClientQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

class Client extends \yii\db\ActiveRecord
{
    function behaviors()
    {
        return [
            'images' => [
                'class' => 'pistol88\gallery\behaviors\AttachImages',
            ],
            'field' => [
                'class' => 'pistol88\field\behaviors\AttachFields',
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%client_client}}';
    }
    
    public static function Find()
    {
        $return = new ClientQuery(get_called_class());
        $return = $return->with('category');
        
        return $return;
    }
    
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['category_id', 'sort', 'persent'], 'integer'],
            [['text', 'status', 'phone', 'email', 'birthday', 'comment', 'promocode'], 'string'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'status' => 'Статус',
            'pay_type' => 'Тип выплат',
            'session' => 'Рабочая сессия',
            'name' => 'Имя',
            'promocode' => 'Промокод',
            'images' => 'Картинки',
            'persent' => 'Процент скидки',
            'image' => 'Фото',
            'sort' => 'Сортировка',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'birthday' => 'День рождения',
            'comment' => 'Комментарий',
        ];
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getUsername() {
        return $this->name;
    }
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
