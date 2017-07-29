<?php

namespace www\controllers;

use common\services\UserService;
use www\filters\UserLoginFilter;
use Yii;
use yii\web\Response;

class LoginController extends BaseController {
    protected $userService;

    public function __construct(
        $id,
        $module,
        UserService $userService,
        $config = []
    ) {
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }


    public function behaviors() {
        return [
            ['class' => UserLoginFilter::className(), 'only' => ['auth']],
        ];
    }


    public function actionIndex() {
        $this->layout = 'login';
        return $this->render('index');
    }


    public function actionAuth() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');

        $user = $this->userService->getUserByEmail($email);
        if (is_null($user)) {
            return [
                'message' => "user doesn't exist",
                'code' => 1
            ];
        }

        if ($this->userService->validateUserPassword($password)) {
            Yii::$app->session->set('logged_in', 1);

            return [
                'message' => 'OK',
                'code' => 0
            ];
        } else {
            return [
                'message' => 'username and/or password not match',
                'code' => 2
            ];
        }
    }
}
