<?php
namespace pistol88\client\models;

use Yii;
use yii\helpers\Url;
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
    
    public function setStatus($status)
    {
        $this->status = $status;
        
        return $this->save(false);
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
            [['code'], 'unique'],
            [['category_id', 'sort', 'persent', 'organization_id', 'user_id'], 'integer'],
            [['text', 'status', 'phone', 'email', 'birthday', 'comment', 'promocode'], 'string'],
            [['name', 'code'], 'string', 'max' => 200],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Идентификатор',
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
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата редактирования',
            'organization_id' => 'Организация',
            'user_id' => 'Пользователь',
        ];
    }
    
    public function getUser()
    {
        $userModel = yii::$app->user->identityClass;
        
        return $userModel::find()->where(['id' => $this->user_id]);
    }
    
    public function getCalls()
    {
        return $this->haMany(Call::className(), ['client_id' => 'id']);
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
    
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
     
        if($userId = yii::$app->getModule('client')->registerUser($this)) {
            $this->user_id = $userId;
            $this->save(false);
        }
    }
}
