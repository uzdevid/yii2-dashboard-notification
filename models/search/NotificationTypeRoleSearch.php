<?php

namespace uzdevid\dashboard\notification\models\search;

use uzdevid\dashboard\notification\models\NotificationTypeRole;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NotificationTypeRoleSearch represents the model behind the search form of `uzdevid\dashboard\models\NotificationTypeRole`.
 */
class NotificationTypeRoleSearch extends NotificationTypeRole {
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'notification_type_id', 'role_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = NotificationTypeRole::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'notification_type_id' => $this->notification_type_id,
            'role_id' => $this->role_id,
        ]);

        return $dataProvider;
    }
}
