<?php
//本檔案是在當頁面停留在DeepLearningMethods時按下左方導覽列的演算法時會以ajax的方式被view/deepLearningMethods.html呼叫
//做的事情主要是顯示被選到的演算法的內容
	include('../cfg/cfg.inc.php');
	include('../cls/meekrodb.cls.php');
	include('../cls/Praetor.cls.php');
    $praetor = new Praetor();
    $sql = "SELECT * FROM algorithm WHERE Abbreviation=%s_Abbreviation";
    $data = $praetor->custosql($sql, array('Abbreviation'=>$_POST['abbre']));
    $sql2 = "SELECT * FROM dataSet WHERE algorithmID=%d_algorithmID ORDER BY no desc";
    $dataSetData = $praetor->custosql($sql2, array('algorithmID'=>$data[0]['AID']));
    $sql3 = "SELECT * FROM Paremeter WHERE algorithmID=%d_algorithmID ORDER BY no desc";
    $paremeterData = $praetor->custosql($sql3, array('algorithmID'=>$data[0]['AID']));
    $sql4 = "SELECT * FROM link WHERE algorithmID=%d_algorithmID ORDER BY no desc";
    $linkData = $praetor->custosql($sql4, array('algorithmID'=>$data[0]['AID']));
    $sql5 = "SELECT * FROM tag WHERE algorithmID=%d_algorithmID ORDER BY no desc";
    $tagData = $praetor->custosql($sql5, array('algorithmID'=>$data[0]['AID']));
    $content = '<legend>程式名稱:</legend>
    <fieldset>

  <div>
        
                              <label for="issueinput1">簡寫/Abbre:</label>
                              <label>'.$data[0]['Abbreviation'].'</label>
                          </div>
                          <div>
                              <label for="issueinput5">全名/FullName:</label>
                              <label>'.$data[0]['AlgorithmTitle'].'</label>
                          </div>
                          </fieldset>
                          <legend>作者/單位:</legend>
                          <fieldset>

      
  <div>
                            
                              <label for="issueinput1">作者英文名/Author:</label>
                              <label>'.$data[0]['authorName'].'</label>
                          </div>
                          <div>
                              <label for="issueinput5">單位/Unit:</label>
                              <label>'.$data[0]['authorUnit'].'</label>
                          </div>
                          </fieldset>
                          <legend>程式功能:</legend>
                          <fieldset>
                          
      
  <div>
  
                              <label for="issueinput1">英文/ProgramGoal:</label>
                              <label>'.$data[0]['functionEnglish'].'</label>
                          </div>
                          <div>
                              <label for="issueinput5">中文/ProgramGoal:</label>
                              <label>'.$data[0]['functionChinese'].'</label>
                          </div>
                          </fieldset>
                                <legend>程式功能說明-文字描述/Desciption:</legend>
                          <fieldset>

  <div>
  
    '.$data[0]['functionDescription'].'
                          </div>
                          ';
                          if ($linkData[0])
                          {
                                $n = 0;
                                $content = $content.'<br><div><legend>說明連結/Link:</legend>';
                            while ($n <= $linkData[0]['no'])
                            {
                              if ($linkData[$linkData[0]['no'] - $n]['linkUrl'])
                              {

                                  $content = $content.'<a href="'.$linkData[$linkData[0]['no'] - $n]['linkUrl'].'">'.$linkData[$linkData[0]['no'] - $n]['linkUrl'].'</a><br>';

                                  
                              }
                              $n = $n + 1;
                            }
                          }
                            $content = $content.'
                          </div>
                          </fieldset>
                          <legend>程式源碼/SourceCode:</legend>
                          <fieldset>
                          
      
  <div>
  
                              <a href="'.$data[0]['GitHub'].'">'.$data[0]['GitHub'].'</a>
                          </div>
                          </fieldset>';
                          if ($tagData[0])
                          {
                              $n = 0;
                              $content = $content.'<legend>程式類別/Category:</legend><fieldset><div>';
                          while ($n <= $tagData[0]['no'])
                            {
                              if ($tagData[$tagData[0]['no'] - $n]['tagName'])
                              {

                                  $content = $content.$tagData[$tagData[0]['no'] - $n]['tagName'].'<br>';

                              }
                              $n = $n + 1;
                            }
                            $content = $content.'</div></fieldset>';
                          }
                          if ($dataSetData[0])
                          {
                              $n = 0;
                              $content = $content.'<legend>執行程式所需要的資料集，語料庫等等的資源/DataSet:</legend><fieldset><div>';
                          while ($n <= $dataSetData[0]['no'])
                            {
                              if ($dataSetData[$dataSetData[0]['no'] - $n]['dataSetName'] || $dataSetData[$dataSetData[0]['no'] - $n]['url'] || $dataSetData[$dataSetData[0]['no'] - $n]['fee'])
                              {

                                  $content = $content.'<table border="1" width="500" cellspacing="0" cellpadding="5" bordercolor="#333333">
<tr>
<th bgcolor="grey" >名稱</th>
<th bgcolor="grey" >連結</th>
<th bgcolor="grey" >收費</th>
</tr>
<tr>
  <td bgcolor="#FFFFFF" align="left" nowrap>'.$dataSetData[$dataSetData[0]['no'] - $n]['dataSetName'].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap><a href="'.$dataSetData[$dataSetData[0]['no'] - $n]['url'].'">'.$dataSetData[$dataSetData[0]['no'] - $n]['url'].'</a></td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$dataSetData[$dataSetData[0]['no'] - $n]['fee'].'</td>

</tr>

</table>';

                              }
                              $n = $n + 1;
                            }
                            $content = $content.'</div></fieldset>';
                          }
                          $content = $content.'<legend>執行程式所需要的系統環境及套件/EnvPackage:</legend><fieldset>

      
  <div>
                            
                              <label for="issueinput1">系統環境:</label>
                              <label>'.$data[0]['systemEnvironment'].'</label>
                          </div>
                          <div>
                              <label for="issueinput5">套件:</label>
                              <label>'.$data[0]['package'].'</label>
                          </div>
                          </fieldset>';
                          if ($data[0]['Input'] || $data[0]['Output'])
                          {
                              $content = $content.'<legend>程式接受的輸入/輸出格式:</legend><fieldset>

      
  <div>
                            
                              <label for="issueinput1">輸入格式/InputType:</label>
                              <label>'.$data[0]['Input'].'</label>
                          </div>
                          <div>
                              <label for="issueinput5">輸出格式/OutputType:</label>
                              <label>'.$data[0]['Output'].'</label>
                          </div>
                          </fieldset>';
                          }

                          if ($paremeterData[0])
                          {
                              $n = 0;
                              $content = $content.'<legend>參數的功能說明以及參數是否是有範圍(Range):</legend><fieldset><div>';
                          while ($n <= $paremeterData[0]['no'])
                            {
                              if ($paremeterData[$paremeterData[0]['no'] - $n]['paremeter'] || $paremeterData[$paremeterData[0]['no'] - $n]['paremeterRange'] || $paremeterData[$paremeterData[0]['no'] - $n]['format'] || $paremeterData[$paremeterData[0]['no'] - $n]['function'])
                              {

                                  $content = $content.'<table border="1" width="500" cellspacing="0" cellpadding="5" bordercolor="#333333">
<tr>
<th bgcolor="grey" >參數名稱</th>
<th bgcolor="grey" >範圍</th>
<th bgcolor="grey" >格式</th>
<th bgcolor="grey" >功能說明</th>
</tr>
<tr>
  <td bgcolor="#FFFFFF" align="left" nowrap>'.$paremeterData[$paremeterData[0]['no'] - $n]['paremeter'].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$paremeterData[$paremeterData[0]['no'] - $n]['paremeterRange'].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$paremeterData[$paremeterData[0]['no'] - $n]['format'].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$paremeterData[$paremeterData[0]['no'] - $n]['function'].'</td>

</tr>

</table>';

                              }
                              $n = $n + 1;
                            }
                            $content = $content.'</div></fieldset>';
                          }               
echo $content;

?>
