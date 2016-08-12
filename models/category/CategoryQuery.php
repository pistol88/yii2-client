<?php
namespace pistol88\client\models\category;

use yii\db\ActiveQuery;
class CategoryQuery extends ActiveQuery
{
    public function popular()
    {
         return $this->andWhere(['is_popular' => 'yes']);
    }
}