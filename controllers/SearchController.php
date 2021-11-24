<?php

class SearchController
{
    public function actionUserSearch()
    {
        $name = $_GET['name'] ?? '';
        if($name != ''){
            $people = User::searchPeople($name);
        } else {
            $people = User::getUsers();
        }
        if(empty($people)){
            echo json_encode(["No Result"]);
            exit;
        }
        echo (json_encode([$people, $_SESSION['user']]));
        exit;
    }
}