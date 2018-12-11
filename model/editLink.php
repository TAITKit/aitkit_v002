<?php
//由view/editAlgorithm.html呼叫
//基本上同model/addDataSet.php，只是這是編集，algorithmID已經有值
include('../cfg/cfg.inc.php');
include('../cls/meekrodb.cls.php');
include('../cls/Praetor.cls.php');
            $praetor = new Praetor();
            $sql = "SELECT no FROM link WHERE algorithmID=%d_algorithmID ORDER BY no desc";
            $data = $praetor->custosql($sql, array('algorithmID'=>$_POST['AID']));
            if ($data)
            {
            	$number = $data[0]['no'] + 1;
            }
            else
            {
            	$number = 0;
            }
            $praetor->custoinsert('link', array('algorithmID'=>$_POST['AID'], 'no'=> $number, 'ownerID'=>$_POST['pid']));
            echo '<div class="link'.$number.'">
                            <input type="text" id="issueinput1" class="form-control" name="linkUrl'.$number.'" placeholder="EX:http://nlg18.csie.ntu.edu.tw/discourse_parser">
<button type="button" class="deleteLink '.$number.'" value="'.$number.'">
刪除
</button>  
                          </div>';//用ajax傳回view/editAlgorithm.html
?>