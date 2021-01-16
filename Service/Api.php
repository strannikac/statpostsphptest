<?php 

namespace Service;

class Api {

    private $apiUrl = 'https://api.supermetrics.com/assignment/';

    private $actions = [
        'token' => 'register', 
        'posts' => 'posts'
    ];

    private $clientId = 'ju16a6m81mhid5ue1z3v2g0uh';
    private $clientEmail = 'john.smith@test.com';
    private $clientName = 'John Smith';

    private $allowedMethods = ['GET', 'POST'];

    private $postsPageMin = 1;
    private $postsPageMax = 10;

    public function __construct(int $pageMin = 1, int $pageMax = 10, String $id = '', String $email = '', String $name = '') {
        if($pageMin > 0) {
            $this->postsPageMin = $pageMin;
        }

        if($pageMax > $this->postsPageMin) {
            $this->postsPageMax = $pageMax;
        } else {
            $this->postsPageMax = $this->postsPageMin + 1;
        }

        if($id != '') {
            $this->clientId = $id;
        }


        if($email != '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->clientEmail = $email;
        }

        if($name != '') {
            $this->clientName = $email;
        }
    }
    
    public function registerToken() {
        $data = ['client_id' => $this->clientId, 'email' => $this->clientEmail, 'name' => $this->clientName];

        $url = $this->apiUrl . $this->actions['token'];

        $response = $this->request($url, 'POST', $data);

        if($response && !empty($response->data->sl_token)) {
            return $response->data->sl_token;
        }

        return false;
    }
    
    public function getPosts(String $token, int $page = 1) {
        if($token != '') {
            if($page < $this->postsPageMin) {
                $page = $this->postsPageMin;
            } elseif($page > $this->postsPageMax) {
                $page = $this->postsPageMax;
            }

            $params = '?sl_token=' . $token . '&page=' . $page;

            $url = $this->apiUrl . $this->actions['posts'] . $params;

            $response = $this->request($url);

            if($response && !empty($response->data->posts)) {
                return $response->data->posts;
            }
        }

        return false;
    }

    private function request(String $url, String $method = 'GET', array $data = []) {
        if($url != '' && in_array($method, $this->allowedMethods)) {
            $options = [
                'http' => [
                    'method'  => $method,
                    'header'=>  "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"
                ]
            ];

            if(count($data) > 0) {
                $options['http']['content'] = json_encode( $data );
            }

            $context  = stream_context_create( $options );
            $result = file_get_contents( $url, false, $context );
            return json_decode( $result );
        }

        return false;
    }
}

?>