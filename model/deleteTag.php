<?php
//由model/editAlgorithm.php呼叫
//只有在修改algorithm資料的時候會用到。目的是刪除一組參數
include('../cfg/cfg.inc.php');
include('../cls/meekrodb.cls.php');
include('../cls/Praetor.cls.php');
            $praetor = new Praetor();
            $where = new WhereClause('and'); 
            $where->add('algorithmID=%s', $_POST['AID']);
            $where->add('no=%d', $_POST['numberValue']);
            $where->add('ownerID=%d', $_POST['pid']);
            $praetor->custodelete('tag', "%l", $where);
?>