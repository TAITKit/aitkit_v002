<?php
include('../cfg/cfg.inc.php');
include('../cls/meekrodb.cls.php');
include('../cls/Praetor.cls.php');

$praetor = new Praetor();
            $sql = "SELECT tagName FROM tag GROUP BY tagName";
            $data = $praetor->custosql($sql, array());
            //print_r($data);
            foreach ($data as $key => $value) {
            	$sql2 = "SELECT no FROM tag WHERE tagName = %s_tagName";
            	$data2 = $praetor->custosql($sql2, array('tagName'=>$value['tagName']));
            	//print_r($data2);
            	$tagSortArray[$value['tagName']] = sizeof($data2);
            }
            arsort($tagSortArray);
            $topOneTwo = array_keys($tagSortArray);
            echo $topOneTwo[0].'|'.$topOneTwo[1];
?>