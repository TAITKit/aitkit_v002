<?php
//由view/editAlgorithm.html呼叫
//基本上同model/addDataSet.php，只是這是編集，algorithmID已經有值
include('../cfg/cfg.inc.php');
include('../cls/meekrodb.cls.php');
include('../cls/Praetor.cls.php');
            $praetor = new Praetor();
            $sql = "SELECT no FROM tag WHERE algorithmID=%d_algorithmID ORDER BY no desc";
            $data = $praetor->custosql($sql, array('algorithmID'=>$_POST['AID']));
            if ($data)
            {
            	$number = $data[0]['no'] + 1;
            }
            else
            {
            	$number = 0;
            }
            $praetor->custoinsert('tag', array('algorithmID'=>$_POST['AID'], 'no'=> $number, 'ownerID'=>$_POST['pid']));
            echo '<div class="tag'.$number.'">
                            <input type="text" id="issueinput1" class="form-control" name="tagName'.$number.'" placeholder="EX:NLP">
<button type="button" class="deleteTag '.$number.'" value="'.$number.'">
刪除
</button>  
                          </div>';//用ajax傳回view/editAlgorithm.html
?>