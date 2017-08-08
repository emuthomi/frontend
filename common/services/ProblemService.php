<?php

namespace common\services;

use common\models\Problem;
use common\models\Submission;
use Yii;

class ProblemService {
    /**
     * @author  liuchao
     * @mail    i@liuchao.me
     * @param   int $id
     * @return  \common\models\Problem
     * @desc
     */
    public function getProblemByID(int $id) {
        return Problem::findOne($id);
    }


    /**
     * @author  liuchao
     * @mail    i@liuchao.me
     * @param   string $code
     * @param   string $title
     * @return  yii\db\ActiveQuery
     * @desc
     */
    public function getProblemsWithConditions(?string $code, ?string $title) {
        return Problem::find()
            ->andFilterWhere(['t_problem.id' => $code])
            ->andFilterWhere([
                'LIKE',
                't_problem.title',
                $title
            ]);
    }


    /**
     * @author  liuchao
     * @mail    i@liuchao.me
     * @param   \yii\db\ActiveQuery $query
     * @param   int $offset
     * @param   int $limit
     * @return  array
     * @desc
     */
    public function getProblemsWithStatus($query, int $offset, int $limit) {
        $uid = Yii::$app->session->get(Yii::$app->params['userIdKey']);

        return array_map(function($record) use ($uid) {
            if (!is_null(Submission::findOne([
                'problem_id' => $record->id,
                'user_id' => $uid,
                'status' => Problem::STATUS_SOLVED
            ]))) {
                $status = Problem::STATUS_SOLVED;
            } elseif (
                Yii::$app->redis->getbit(Yii::$app->params['userTriedCountKey'] . $uid,
                    $record->id) == Problem::STATUS_TRIED
            ) {
                $status = Problem::STATUS_TRIED;
            } else {
                $status = Problem::STATUS_UNSOLVED;
            }

            return [
                'id' => $record->id,
                'title' => $record->title,
                'accepted' => $record->getAcceptedCount(),
                'total' => $record->getSubmissionCount(),
                'level' => $record->level,
                'status' => $status
            ];
        }, $query->offset($offset)->limit($limit)->all());
    }


    /**
     * @author  liuchao
     * @mail    i@liuchao.me
     * @param   int $problem_id
     * @desc
     * @return \yii\db\ActiveQuery
     */
    public function findSubmissionsByProblemID(int $problem_id) {
        return Submission::find()->where(['problem_id' => $problem_id])->orderBy(['id' => SORT_DESC]);
    }


    /**
     * @author  liuchao
     * @mail    i@liuchao.me
     * @param   int $problem_id
     * @param   int $language
     * @param   string $code
     * @desc
     * @return int
     */
    public function submit(int $problem_id, int $language, string $code) {
        $user_id = Yii::$app->session->get(Yii::$app->params['userIdKey']);

        $submission = new Submission();
        $submission->user_id = $user_id;
        $submission->problem_id = $problem_id;
        $submission->language = $language;
        $submission->code = $code;
        $submission->save();

        // add tried record for current user
        Yii::$app->redis->setbit(
            Yii::$app->params['userTriedCountKey'] . $user_id,
            $problem_id,
            Problem::STATUS_TRIED
        );

        // push to rabbitMQ
        Yii::$app->rabbitMQ->send(['id' => $submission->id]);

        return $submission->id;
    }
}