<?php

namespace App;

use Service\Db;
use Service\Api;
use Service\Stat;
use Model\Model;
use Model\PostModel;

class App {
	private $db;
	private $api;
    private $stat;

	private $postModel;

    private $pageMin = 1;
    private $pageMax = 10;

    public function __construct() {
        session_start();

        $this->start();
    }

    private function start() {
        $this->db = Db::getInstance();
        $this->api = new Api();
        $this->stat = new Stat();

        $this->postModel = new PostModel();

        //check table (posts)
        if( !$this->postModel->checkTable() ) {
        	$arr = [
        		'id VARCHAR(35) PRIMARY KEY', 
                'from_name VARCHAR(75) NOT NULL', 
                'from_id VARCHAR(35) NOT NULL', 
                'message TEXT NOT NULL', 
                '`type` VARCHAR(25) NOT NULL', 
                '`length` INT NOT NULL', 
        		'created_time DATETIME'
        	];
    		$this->postModel->createTable('', $arr);
        }

        //chek token in session
        if(!isset($_SESSION['clientToken'])) {
        	$token = $this->api->registerToken();

        	if($token) {
        		$_SESSION['clientToken'] = $token;
        	}
        }

        if(!empty($_SESSION['clientToken'])) {
        	//get posts
            for($i = $this->pageMin; $i <= $this->pageMax; $i++) {
            	$result = $this->api->getPosts($_SESSION['clientToken'], $i);
                if($result) {
                    $this->addPosts($result);
                }
            }

            //get stat
            $this->showStat();
        }
    }

    /* add posts in database
     * 
    */
    private function addPosts(array $rows) {
        $count = count($rows);

        if($count > 0) {
            for($i = 0; $i < $count; $i++) {
                $arr = [
                    'id' => $rows[$i]->id, 
                    'from_name' => $rows[$i]->from_name, 
                    'from_id' => $rows[$i]->from_id, 
                    'message' => $rows[$i]->message, 
                    'type' => $rows[$i]->type, 
                    'length' => mb_strLen($rows[$i]->message), 
                    'created_time' => $rows[$i]->created_time
                ];

                $this->postModel->insert($arr);
            }
        }
    }

    /* get statistic for posts and show it
     * 
    */
    private function showStat() {
        $rows = $this->postModel->get();
        $this->stat->get($rows);
        $this->stat->show();
    }
}

?>