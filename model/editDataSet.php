<?php
//由view/editAlgorithm.html呼叫
//基本上同model/addDataSet.php，只是這是編集，algorithmID已經有值
include('../cfg/cfg.inc.php');
include('../cls/meekrodb.cls.php');
include('../cls/Praetor.cls.php');
            $praetor = new Praetor();
            $sql = "SELECT no FROM dataSet WHERE algorithmID=%d_algorithmID ORDER BY no desc";
            $data = $praetor->custosql($sql, array('algorithmID'=>$_POST['AID']));
            if ($data)
            {
            	$number = $data[0]['no'] + 1;
            }
            else
            {
            	$number = 0;
            }
            $praetor->custoinsert('dataSet', array('algorithmID'=>$_POST['AID'], 'no'=> $number, 'ownerID'=>$_POST['pid']));
            echo '<div>
                            <table border="1" width="500" cellspacing="0" cellpadding="5" bordercolor="#333333" class= "dataSetTable'.$number.'">
<tr>
<th bgcolor="grey" width="400">名稱</th>
<th bgcolor="grey" >連結</th>
<th bgcolor="grey" >收費</th>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="left" nowrap><input type="text" id="issueinput1" class="form-control" name="dataSetName'.$number.'" placeholder="EX:WordNet"></td>
<td bgcolor="#FFFFFF" align="left" nowrap><input type="text" id="issueinput1" class="form-control" name="dataSetURL'.$number.'" placeholder="EX:https://wordnet.princeton.edu/"></td>
<td bgcolor="#FFFFFF" align="left" nowrap><input type="text" id="issueinput1" class="form-control" name="dataSetFee'.$number.'" placeholder="EX:免費"></td>
<td bgcolor="#FFFFFF" align="left" nowrap> <button type="button" class="deleteDataSet '.$number.'" value="'.$number.'">
刪除
</button>  
</td>
</tr>


</table>
                          </div>';
?>