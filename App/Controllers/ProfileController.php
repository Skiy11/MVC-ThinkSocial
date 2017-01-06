<?php
namespace App\Controllers;
/**
 * Created by PhpStorm.
 * User: bond
 * Date: 12.12.16
 * Time: 13:31
 */

Class ProfileController Extends PageController
{
    protected function getTemplateNames()
    {
        $this->templateInfo['templateNames'] = array('head', 'navbar', 'leftcolumn', 'middleprofile', 'rightcolumn', 'footer');
    }
    protected function getTitle()
    {
        switch ($this->modelsAction) {
            case "show":
                $this->templateInfo['title'] = 'Профиль';
                break;
            case "edit":
                $this->templateInfo['title'] = 'Редактирование профиля';
                break;
            case "input":
                $this->templateInfo['title'] = 'Добавление телефона';
                break;
        }
    }
    protected function getControllerVars()
    {
        $userId = \App\Models\User::checkLogged();

        switch ($this->modelsAction) {
            case "show":
                $result = array('submitAction' => 'edit',
                           'submitValue' => 'Редактировать',
                           'submitPhoneAction' => 'input',
                           'submitPhoneValue' => 'Добавить',
                           'allowEdit' => 'disabled');
                $userPhones = \App\Models\Phone::getByForeign(array('user_id' => $userId), "");
                return array_merge($result, parent::getControllerVars(), array("userPhones" => $userPhones));
            case "edit":
                $result = array('submitAction' => 'update',
                           'submitValue' => 'Подтвердить',
                           'submitPhoneAction' => 'input',
                           'submitPhoneValue' => 'Добавить',
                           'allowEdit' => 'enabled');
                $userPhones = \App\Models\Phone::getByForeign(array('user_id' => $userId), "");
                return array_merge($result, parent::getControllerVars(), array("userPhones" => $userPhones));
            case "input":
                $result = array('submitAction' => 'edit',
                            'submitValue' => 'Редактировать',
                            'submitPhoneAction' => 'insert',
                            'submitPhoneValue' => 'OK',
                            'allowEdit' => 'disabled');
                $userPhones = \App\Models\Phone::getByForeign(array('user_id' => $userId), "");
                return array_merge($result, parent::getControllerVars(), array("userPhones" => $userPhones));
            case "update":
                $user = \App\Models\User::getByID($userId);
                foreach ($_POST as $field => $value) {
                    $user->{$field} = $value;
                }
                $user->update();
                header('Location: http://ts.local/profile/show/');
                break;
            case "insert":
                $newPhone = new \App\Models\Phone();
                $newPhone->user_id = $userId;
                $newPhone->phone = $_POST['newPhone'];
                $newPhone->insert();
                header('Location: http://ts.local/profile/show/');
                break;
            case "delete":
                \App\Models\Phone::delete($_POST['phoneID']);
                header('Location: http://ts.local/profile/show/');
                break;
        }
    }
}