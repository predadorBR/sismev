<?php

namespace app\models;

use app\components\traits\FilterTrait;
use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "payment_method".
 *
 * @property int $id
 * @property string $name
 * @property int $installment_limit
 * @property int|null $is_deleted
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property int $company_id
 *
 * @property Pay[] $pays
 * @property Company $company
 */
class PaymentMethod extends ActiveRecord
{
    use FilterTrait;

    const JOINS = [
        [
            'table' => 'company',
            'on' => 'payment_method.company_id = company.id'
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                    SoftDeleteBehavior::EVENT_BEFORE_SOFT_DELETE => ['deleted_at']
                ]
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'is_deleted' => true
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'installment_limit', 'company_id'], 'required'],
            [['installment_limit', 'is_deleted', 'company_id'], 'integer'],
            [['installment_limit'], 'integer', 'min' => 1],
            [['name'], 'unique'],
            [['is_deleted'], 'boolean'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'installment_limit' => Yii::t('app', 'Installment Limit'),
            'is_deleted' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'company_id' => Yii::t('app', 'Company'),
        ];
    }

    /**
     * Gets query for [[Pays]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPays()
    {
        return $this->hasMany(Pay::class, ['payment_method_id' => 'id']);
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }
}
