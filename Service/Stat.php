<?php 

namespace Service;

class Stat {

    private $statMonth = [];
    private $statWeek = [];

    public function get(array $rows) {
        $statMonth = [];
        $statWeek = [];
        $count = count($rows);

        if($count > 0) {
            for($i = 0; $i < $count; $i++) {
                $monthDate = substr($rows[$i]['created_time'], 0, 7);

                //get week number 
                $week = date('W', strtotime($rows[$i]['created_time']));

                //stat by month
                if(isset( $statMonth[$monthDate] )) {
                    $statMonth[$monthDate]['count']++;
                    $statMonth[$monthDate]['length'] += $rows[$i]['length'];

                    if($rows[$i]['length'] > $statMonth[$monthDate]['longest']['length']) {
                        $statMonth[$monthDate]['longest'] = $rows[$i];
                    }
                } else {
                    $statMonth[$monthDate]['count'] = 1;
                    $statMonth[$monthDate]['length'] = $rows[$i]['length'];
                    $statMonth[$monthDate]['longest'] = $rows[$i];
                }

                //stat by month and customer
                if(isset($statMonth[$monthDate]['customers'][$rows[$i]['from_id']])) {
                    $statMonth[$monthDate]['customers'][$rows[$i]['from_id']]['count']++;
                } else {
                    $statMonth[$monthDate]['customers'][$rows[$i]['from_id']]['count'] = 1;
                }

                //stat by week
                if(isset( $statWeek[$week] )) {
                    $statWeek[$week]['count']++;
                } else {
                    $statWeek[$week]['count'] = 1;
                }
            }

            $this->statMonth = $statMonth;
            $this->statWeek = $statWeek;
        }
    }

    public function show() {
        $html = [
            'averageLengthMonth' => '<h3>Average character length of posts per month</h3>', 
            'longestPostMonth' => '<h3>Longest post by character length per month</h3>', 
            'countPostsWeek' => '<h3>Total posts split by week number</h3>', 
            'averageCustomerPostsMonth' => '<h3>Average number of posts per user per month</h3>'
        ];

        $json = [
            'averageLengthMonth' => [], 
            'longestPostMonth' => [], 
            'countPostsWeek' => [], 
            'averageCustomerPostsMonth' => []
        ];

        if(!empty($this->statMonth) && is_array($this->statMonth)) {
            foreach($this->statMonth as $monthDate => $arrMonth) {
                $month = date('F Y', strtotime($monthDate . '-01'));

                $json['averageLengthMonth'][] = [
                    'month' => $month, 
                    'length' => round($arrMonth['length'] / $arrMonth['count'])
                ];

                $json['longestPostMonth'][] = [
                    'month' => $month, 
                    'longestPost' => $arrMonth['longest']
                ];

                $countCustomers = 0;
                $countPosts = 0;

                foreach($arrMonth['customers'] as $customerId => $arrCustomer) {
                    $countCustomers++;
                    $countPosts += $arrCustomer['count'];
                }

                $json['averageCustomerPostsMonth'][] = [
                    'month' => $month, 
                    'postsPerCustomer' => round($countPosts / $countCustomers, 2)
                ];
            }

            $html['averageLengthMonth'] .= json_encode($json['averageLengthMonth']);
            $html['longestPostMonth'] .= json_encode($json['longestPostMonth']);
            $html['averageCustomerPostsMonth'] .= json_encode($json['averageCustomerPostsMonth']);

            echo $html['averageLengthMonth'];
            echo $html['longestPostMonth'];
        }

        if(!empty($this->statWeek) && is_array($this->statWeek)) {
            foreach($this->statWeek as $week => $arrWeek) {
                $json['countPostsWeek'][] = [
                    'week' => $week, 
                    'count' => $arrWeek['count']
                ];
            }

            $html['countPostsWeek'] .= json_encode($json['countPostsWeek']);

            echo $html['countPostsWeek'];
        }

        if(!empty($this->statMonth) && is_array($this->statMonth)) {
            echo $html['averageCustomerPostsMonth'];
        }
    }
}

?>