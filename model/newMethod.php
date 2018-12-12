<?php
//當view/NewMpthod.html，即新增演算法的form的submit按下之後會POST到本檔案
//主要功能是將form的資訊存到資料庫
//同時包括資料集和參數的處理

//有要上傳檔案的話反註解
// $uploaddir = 'upload/';
// $uploadfile = $uploaddir.basename($_FILES['myfile']['name']);


// if(file_exists(iconv("utf-8", "big5", $uploadfile)))
// {
//   echo "<script>alert('檔案已存在！');</script>";
//   echo "<script>location.href='index.php?item=NewMethod';</script>";
// }else
// {
//  	if (move_uploaded_file($_FILES['myfile']['tmp_name'], iconv("utf-8", "big5", $uploadfile))) 
// 	{

// 	}
// 	else 
// 	{
//     	echo "<script>alert('檔案上傳失敗！');</script>";
//   		echo "<script>location.href='index.php?item=NewMethod';</script>";
// 	}
// }
$praetor = new Praetor();
	
  $portNumberSql = "SELECT Portnumber FROM algorithm order by Portnumber desc";
  $data = $praetor->custosql($portNumberSql, array());
  $algorithmID = $data[0]['Portnumber'] + 1;
  $NeedParameter = 'YES';
  if ($_POST['acceptParemeter'] == 0)
  {
    $where = new WhereClause('and'); 
            $where->add('algorithmID=%s', '-1');
            $where->add('ownerID=%d', $_POST['pid']);
            $praetor->custodelete('paremeter', "%l", $where);
    $NeedParameter = 'NO';
  }
	$praetor->custoinsert('algorithm', array('AID'=> $algorithmID, 'OwnerPID'=>$_SESSION['user']['PID'], 'AlgorithmTitle'=>$_POST['titleInfo'],'GitHub'=>$_POST['github'],'Abbreviation'=>$_POST['titleAbbre'], 'Input'=>$_POST['inputFormat'], 'Output'=>$_POST['outputFormat'], 'authorName'=>$_POST['authorName'], 'authorUnit'=>$_POST['authorUnit'], 'functionEnglish'=>$_POST['functionEnglish'], 'functionChinese'=>$_POST['functionChinese'], 'functionDescription'=>$_POST['functionDescription'], 'systemEnvironment'=>$_POST['systemEnvironment'], 'package'=>$_POST['package'], 'allowParemeter'=>$_POST['acceptParemeter']));

  //把tag另外儲存到一個資料表
  // $tagLenth = strlen($_POST['classification']);
  // $n = 0;
  // $tag = '';
  // $tagJson = array();
  // while ($n < $tagLenth) {
  //  if ($_POST['classification'][$n] != ',')
  //  {
  //    $tag = $tag.$_POST['classification'][$n];
  //  }
  //  else if ($_POST['classification'][$n] == ',')
  //  {
  //     $praetor->custoinsert('tag', array('tagName'=>$tag, 'algorithmID'=>$algorithmID, 'abbreviation'=>$_POST['titleAbbre'], 'ownerID'=>$_SESSION['user']['PID']));
  //     $tagJson[] = $tag;
  //     $tag = '';
  //  }
  //  if ($n == $tagLenth - 1)
  //  {
  //     $praetor->custoinsert('tag', array('tagName'=>$tag, 'algorithmID'=>$algorithmID, 'abbreviation'=>$_POST['titleAbbre'], 'ownerID'=>$_SESSION['user']['PID']));
  //     $tagJson[] = $tag;
  //     $tag = '';
  //  }
  //  $n = $n + 1;
  // }
	
  $githubLenth = strlen($_POST['github']);
  $n = 0;
  $sourceCode = '';
  $sourceCodeJson = array();
  while ($n < $githubLenth) {
   if ($_POST['github'][$n] != ',')
   {
     $sourceCode = $sourceCode.$_POST['github'][$n];
   }
   else if ($_POST['github'][$n] == ',')
   {
      $sourceCodeJson[] = $sourceCode;
      $sourceCode = '';
   }
   if ($n == $githubLenth - 1)
   {
      $sourceCodeJson[] = $sourceCode;
      $sourceCode = '';
   }
   $n = $n + 1;
  }

  $envLenth = strlen($_POST['systemEnvironment']);
  $n = 0;
  $env = '';
  $envPackageJson = array();
  while ($n < $envLenth) {
   if ($_POST['systemEnvironment'][$n] != ',')
   {
     $env = $env.$_POST['systemEnvironment'][$n];
   }
   else if ($_POST['systemEnvironment'][$n] == ',')
   {
      $envPackageJson[] = $env;
      $env = '';
   }
   if ($n == $envLenth - 1)
   {
      $envPackageJson[] = $env;
      $env = '';
   }
   $n = $n + 1;
  }

  $packageLenth = strlen($_POST['package']);
  $n = 0;
  $package = '';
  
  while ($n < $packageLenth) {
   if ($_POST['package'][$n] != ',')
   {
     $package = $package.$_POST['package'][$n];
   }
   else if ($_POST['package'][$n] == ',')
   {
      $envPackageJson[] = $package;
      $package = '';
   }
   if ($n == $packageLenth - 1)
   {
      $envPackageJson[] = $package;
      $package = '';
   }
   $n = $n + 1;
  }

  

	$licenseKey = strtoupper($_SESSION['user']['PMName'][0].substr($_SESSION['user']['PMName'], strpos($_SESSION['user']['PMName'], '-') + 1, 1).substr($_SESSION['user']['PMName'], strpos($_SESSION['user']['PMName'], ' ') + 1));
	if (strpos($_POST['titleAbbre'], ' ')  !== false)
	{
		$licenseKey = $licenseKey.'_'.strtoupper(substr($_POST['titleAbbre'], 0, strpos($_POST['titleAbbre'], ' ')));
	}
	else
	{
		$licenseKey = $licenseKey.'_'.strtoupper($_POST['titleAbbre']);
	}

	$sql = "SELECT * FROM algorithm WHERE AID=%s_AID";
	$data = $praetor->custosql($sql, array('AID'=>$algorithmID));
	$licenseKey = $licenseKey.'@'.$data[0]['Portnumber'];
	$praetor->custoupdate('algorithm', array('LicenseKey'=>$licenseKey), "AID=%s", $algorithmID);
	
	//print_r($licenseKey);
	//echo "<script>alert('".$licenseKey."');</script>";
	//echo "<script>location.href='index.php?item=praetorLogin';</script>";
  $linkJson = array();
  $tagJson = array();
  $dataSetJson = array();
  $paremeterJson = array();

  $sql = "SELECT no FROM link WHERE algorithmID=%d_algorithmID ORDER BY no desc";
            $linkData = $praetor->custosql($sql, array('algorithmID'=>'-1'));
            if ($linkData)
            {
              $n = 0;
              while ($n <= $linkData[0]['no'])//透過order by desc取得的no.值為資料個數+1
              {
                $linkWhere = new WhereClause('and'); // create a WHERE statement of pieces joined by ANDs
          $linkWhere->add('algorithmID=%s', '-1');//未處理過的資料algorithmID都是-1
          $linkWhere->add('no=%d', $n);
          $linkWhere->add('ownerID=%d', $_SESSION['user']['PID']);
                $praetor->custoupdate('link', array('linkUrl'=>$_POST['linkUrl'.$n]), "%l", $linkWhere);
                $linkJson[] = $_POST['linkUrl'.$n];
                $n = $n + 1;
              }

                $praetor->custoupdate('link', array('algorithmID'=>$algorithmID), "algorithmID=%s", '-1');
                //處理完的資料把alogorithmID從-1改成上面的值
            }

  $sql = "SELECT no FROM tag WHERE algorithmID=%d_algorithmID ORDER BY no desc";
            $tagData = $praetor->custosql($sql, array('algorithmID'=>'-1'));
            if ($tagData)
            {
              $n = 0;
              while ($n <= $tagData[0]['no'])//透過order by desc取得的no.值為資料個數+1
              {
                $tagWhere = new WhereClause('and'); // create a WHERE statement of pieces joined by ANDs
          $tagWhere->add('algorithmID=%s', '-1');//未處理過的資料algorithmID都是-1
          $tagWhere->add('no=%d', $n);
          $tagWhere->add('ownerID=%d', $_SESSION['user']['PID']);
                $praetor->custoupdate('tag', array('tagName'=>$_POST['tagName'.$n], 'abbreviation'=>$_POST['titleAbbre']), "%l", $tagWhere);
                $tagJson[] = $_POST['tagName'.$n];
                $n = $n + 1;
              }

                $praetor->custoupdate('tag', array('algorithmID'=>$algorithmID), "algorithmID=%s", '-1');
                //處理完的資料把alogorithmID從-1改成上面的值
            }

	$sql = "SELECT no FROM dataSet WHERE algorithmID=%d_algorithmID ORDER BY no desc";
            $dataSetData = $praetor->custosql($sql, array('algorithmID'=>'-1'));
            if ($dataSetData)
            {
            	$n = 0;
            	while ($n <= $dataSetData[0]['no'])//透過order by desc取得的no.值為資料個數+1
            	{
            		$dataSetWhere = new WhereClause('and'); // create a WHERE statement of pieces joined by ANDs
					$dataSetWhere->add('algorithmID=%s', '-1');//未處理過的資料algorithmID都是-1
					$dataSetWhere->add('no=%d', $n);
          $dataSetWhere->add('ownerID=%d', $_SESSION['user']['PID']);
            		$praetor->custoupdate('dataSet', array('dataSetName'=>$_POST['dataSetName'.$n], 'url'=>$_POST['dataSetURL'.$n], 'fee'=>$_POST['dataSetFee'.$n]), "%l", $dataSetWhere);
                $dataSetJson[] = array("Name" => $_POST['dataSetName'.$n],
       "Url" => $_POST['dataSetURL'.$n],
       "Fee" => $_POST['dataSetFee'.$n]);
            		$n = $n + 1;
                
            	}

            		$praetor->custoupdate('dataSet', array('algorithmID'=>$algorithmID), "algorithmID=%s", '-1');
                //處理完的資料把alogorithmID從-1改成上面的值
            }

            $sql = "SELECT no FROM Paremeter WHERE algorithmID=%d_algorithmID ORDER BY no desc";
            $paremeterData = $praetor->custosql($sql, array('algorithmID'=>'-1'));
            if ($paremeterData)
            {
            	$n = 0;
            	while ($n <= $paremeterData[0]['no'])
            	{
            		$paremeterWhere = new WhereClause('and'); // create a WHERE statement of pieces joined by ANDs
					$paremeterWhere->add('algorithmID=%s', '-1');
					$paremeterWhere->add('no=%d', $n);
          $paremeterWhere->add('ownerID=%d', $_SESSION['user']['PID']);
            		$praetor->custoupdate('Paremeter', array('paremeter'=>$_POST['paremeterName'.$n], 'paremeterRange'=>$_POST['paremeterRange'.$n], 'function'=>$_POST['paremeterDescription'.$n], 'format'=>$_POST['paremeterFormat'.$n]), "%l", $paremeterWhere);
                $paremeterJson[] = array("function"=>$_POST['paremeterDescription'.$n],"name"=>$_POST['paremeterName'.$n],"range"=>$_POST['paremeterRange'.$n],"format"=>$_POST['paremeterFormat'.$n]);
            		$n = $n + 1;
            	}
            		$praetor->custoupdate('Paremeter', array('algorithmID'=>$algorithmID), "algorithmID=%s", '-1');
            }


            $CanExecute = 'NO';
            if ($_POST['inputFormat'] && $_POST['outputFormat'])
            {
                $CanExecute = 'YES';
            }

//產生Json檔案
            $json = array("Abbr" => $_POST['titleAbbre'],"FullName" => $_POST['titleInfo'],  
    "Author" => $_POST['authorName'], 
    "Unit" => $_POST['authorUnit'],
    "ProgramGoal" => array($_POST['functionEnglish'], $_POST['functionChinese']),
    "Describtion" => array("Abstract" => $_POST['functionDescription'],
      "Links" => $linkJson ),
  "SourceCode" => $sourceCodeJson,
  "Category" => $tagJson,
  "DataSet" => $dataSetJson,
  "EnvPackage" => $envPackageJson,
  "CanExecute" => $CanExecute,
  "InputType" => $_POST['inputFormat'],      
    "OutputType" => $_POST['outputFormat'],
    "NeedParameter" => $NeedParameter,
    "Parameter" => $paremeterJson);
    $json = json_encode($json, JSON_UNESCAPED_UNICODE);

           //上傳json檔案 
    $myfile = fopen('json/'.$algorithmID.'_'.$_POST['titleAbbre'].'.json', "w");
fwrite($myfile, $json);
fclose($myfile);

	        

	        //傳送email給老師
	require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/POP3.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/OAuth.php';
require 'PHPMailer/language/phpmailer.lang-ja.php';

//公式通り
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//require_once ( 'PHPMailer/PHPMailerAutoload.php' );
$subject = "您已成功上傳演算法，以下為您的上傳資訊";
$from = "yuzuriha4nerine@gmail.com";
$smtp_user = "yuzuriha4nerine@gmail.com";
$smtp_password = "@1a1b1c1d";

$mail = new PHPMailer(true);
$mail->IsSMTP();
//$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true;
$mail->CharSet = 'utf-8';
$mail->SMTPSecure = 'tls';
$mail->Host = "smtp.gmail.com";
$mail->Port = 587;
$mail->IsHTML(true);
$mail->Username = $smtp_user;
$mail->Password = $smtp_password; 
$mail->SetFrom($smtp_user);
$mail->From     = "yuzuriha4nerine@gmail.com";
$mail->Subject = $subject;
$mailBody = "<div><label>您已成功上傳演算法，以下為您的上傳資訊：</label></div><fieldset>
      <legend>程式名稱:</legend>
	<div>
                              <label>簡寫:</label>
                              <label>".$_POST['titleAbbre']."</label>
                          </div>
                          <div>
                              <label>全名:</label>
                              <label>".$_POST['titleInfo']."</label>
                          </div>
                          </fieldset>
                          <fieldset>
      <legend>作者/單位:</legend>
  <div>
                              <label>作者英文名:</label>
                              <label>".$_POST['authorName']."</label>
                          </div>
                          <div>
                              <label>單位:</label>
                              <label>".$_POST['authorUnit']."</label>
                          </div>
                          </fieldset>
                          <fieldset>
      <legend>程式功能:</legend>
  <div>
                              <label>英文:</label>
                              <label>".$_POST['functionEnglish']."</label>
                          </div>
                          <div>
                              <label>中文:</label>
                              <label>".$_POST['functionChinese']."</label>
                          </div>
                          </fieldset>
                          <fieldset>
      <legend>程式功能說明-文字描述(中英文皆可)</legend>
  <div>
    <textarea>".$_POST['functionDescription']."</textarea>
                          </div>
                         
                          </fieldset>
                          <fieldset>
      <legend>程式源碼:</legend>
  <div>
                              <label>".$_POST['github']."</label>
                          </div>
                          </fieldset>
                          <fieldset>
      <legend>程式類別:</legend>
  <div>
                              <label>".$_POST['classification']."</label>
                          </div>
                          </fieldset>";
                          if ($dataSetData)
                          {
                          		$mailBody = $mailBody."<fieldset>
      <legend>執行程式所需要的資料集，語料庫等等的資源:</legend>
    <div>";
                          		$n = 0;
                          	while ($n <= $dataSetData[0]['no'])
                          	{
                          		$mailBody = $mailBody.'<table border="1" width="500" cellspacing="0" cellpadding="5" bordercolor="#333333">
<tr>
<th bgcolor="grey" >名稱</th>
<th bgcolor="grey" >連結</th>
<th bgcolor="grey" >收費</th>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$_POST['dataSetName'.$n].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$_POST['dataSetURL'.$n].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$_POST['dataSetFee'.$n].'</td>
</tr>

</table>';
$n = $n + 1;
                          	}
                          	$mailBody = $mailBody.'</div></fieldset>';
                          }
                          $mailBody = $mailBody.'<fieldset>
      <legend>執行程式所需要的系統環境及套件:</legend>
  <div>
                              <label>系統環境:</label>
                              <label>'.$_POST['systemEnvironment'].'</label>
                            </div>
                            <div>
                              <label>套件:</label>
                              <label>'.$_POST['package'].'</label>
                          </div>
                          </fieldset>';
                          if ($_POST['inputFormat'])
                          {
                          		$mailBody = $mailBody.'<fieldset>
      <legend>程式接受的輸入/輸出格式:</legend>
  <div>
                            <label>輸入格式:</label>
                <label>'.$_POST['inputFormat'].'</label>
                          </div>
                          <div>
                            <label>輸出格式:</label>
                <label>'.$_POST['outputFormat'].'</label>
                          </div>
                          </fieldset>';
                          }
                          if ($paremeterData)
                          {
                          		$mailBody = $mailBody.'<fieldset>
      <legend>參數的功能說明以及參數是否是有範圍(Range):</legend>

                       <div>';
                       $n = 0;
      	while ($n <= $paremeterData[0]['no'])
      	{
      			$mailBody = $mailBody.'<table border="1" width="500" cellspacing="0" cellpadding="5" bordercolor="#333333">
<tr>
<th bgcolor="grey" >參數名稱</th>
<th bgcolor="grey" >範圍</th>
<th bgcolor="grey" >格式</th>
<th bgcolor="grey" >功能說明</th>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$_POST['paremeterName'.$n].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$_POST['paremeterRange'.$n].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$_POST['paremeterFormat'.$n].'</td>
<td bgcolor="#FFFFFF" align="left" nowrap>'.$_POST['paremeterDescription'.$n].'</td>
</tr>

</table>';
$n = $n + 1;
      	}
      	$mailBody = $mailBody.'</div>               
                          </fieldset>';
                          }
                         
                      $mailBody = $mailBody.'<fieldset>
      <legend>License key:</legend>
  <div>
                              <label>'.$licenseKey.'</label>
                          </div>
                          </fieldset>';
                      
$mail->Body = $mailBody;
$mail->AddAddress($_SESSION['user']['Email']);

if( !$mail -> Send() ){
    $message  = "Message was not sent<br/ >";
    $message .= "Mailer Error: " . $mailer->ErrorInfo;
} else {
    $message  = "Message has been sent";
}


?>

<!DOCTYPE html>
<style type="text/css">
  form {
  margin: 0 auto;
  width: 1400px;
  padding-top: 20px;
  padding-bottom: 20px;
}

  fieldset {
  background-color: #F6FEE1;
    margin-bottom: 20px;
    max-height: 300px;
    overflow: auto;
    padding: 10px;
    border: 1px solid #C3CF88;
}

form div + div {
  margin-top: 1em;
}

label {
  display: inline-block;
  text-align: right;
  width: 150px;
}

input, textarea {
  vertical-align: top;
  font: 1em sans-serif;
  width: 300px;
  box-sizing: border-box;
  border: 1px solid #999;

}

input:focus, textarea:focus {
  border-color: #000;
}

textarea {
  vertical-align: top;
  height: 10em;
  width: 40em;
}

button {
  margin-left: .5em;
}

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}

</style>


<html>
<div class="form-group">
	<form action="index.php?item=praetorLogin&tag=all" method="post">
		<h3 class="Topic">您已成功上傳演算法，以下為您的上傳資訊：</h3>
		<legend>程式名稱:</legend>
	<fieldset>
      
	<div>
    
                              <label for="issueinput1">簡寫/Abbr:</label>
                              <?php echo $_POST['titleAbbre'];?>
                          </div>
                          <div>
                              <label for="issueinput5">全名/FullName:</label>
                              <?php echo $_POST['titleInfo'];?>
                          </div>
                          </fieldset>
                          <legend>作者/單位:</legend>
                          <fieldset>
      
  <div>
    
                              <label for="issueinput1">作者英文名/Author:</label>
                              <?php echo $_POST['authorName'];?>
                          </div>
                          <div>
                              <label for="issueinput5">單位/Unit:</label>
                              <?php echo $_POST['authorUnit'];?>
                          </div>
                          </fieldset>
                          <legend>程式功能:</legend>
                          <fieldset>
      
  <div>
    
                              <label for="issueinput1">英文/ProgramGoal:</label>
                              <?php echo $_POST['functionEnglish'];?>
                          </div>
                          <div>
                              <label for="issueinput5">中文/ProgramGoal:</label>
                              <?php echo $_POST['functionChinese'];?>
                          </div>
                          </fieldset>
                          <legend>程式功能說明-文字描述(中英文皆可)/Description</legend>
                          <fieldset>
      
  <div>
    
    <textarea><?php echo $_POST['functionDescription'];?></textarea>
                          </div>
                          <div>
                            <br>
    <legend>說明連結/Link:</legend>
    <?php
        $n = 0;
                            while ($n <= $linkData[0]['no'])
                            {
      ?>
                              <?php echo $_POST['linkUrl'.$n];?>
                              <br>
                              <?php
  $n = $n + 1;
                      }?>
                          </div>

                         
                          </fieldset>
                          <legend>程式源碼/SourceCode:</legend>
                          <fieldset>
      
  <div>
    
                              <?php echo $_POST['github'];?>
                          </div>
                          </fieldset>
                          <legend>程式類別/Category:</legend>
                          <fieldset>
      
  <div>
    
    <?php
        $n = 0;
                            while ($n <= $tagData[0]['no'])
                            {
      ?>
                              <?php echo $_POST['tagName'.$n];?>
                              <br>
                              <?php
  $n = $n + 1;
                      }?>
                          </div>
                         
                          </fieldset>
                          
                          <?php if ($dataSetData)
                          {
                          	?>
                            <legend>執行程式所需要的資料集，語料庫等等的資源/DataSet:</legend>
                          <fieldset>

    <div>
            
    	<?php
    		$n = 0;
                          	while ($n <= $dataSetData[0]['no'])
                          	{
    	?>
                            <table border="1" width="500" cellspacing="0" cellpadding="5" bordercolor="#333333">
<tr>
<th bgcolor="grey" >名稱</th>
<th bgcolor="grey" >連結</th>
<th bgcolor="grey" >收費</th>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="left" nowrap><?php echo $_POST['dataSetName'.$n];?></td>
<td bgcolor="#FFFFFF" align="left" nowrap><?php echo $_POST['dataSetURL'.$n];?></td>
<td bgcolor="#FFFFFF" align="left" nowrap><?php echo $_POST['dataSetFee'.$n];?></td>
</tr>

</table>
<?php
	$n = $n + 1;
                      }?>
                          </div>                 
                          
                          
                          </fieldset>
                          <?php
                      }?>
                      <legend>執行程式所需要的系統環境及套件/EnvPackage:</legend>
                          <fieldset>
      
  <div>
    
                              <label for="issueinput10">系統環境:</label>
                              <?php echo $_POST['systemEnvironment'];?>
                            </div>
                            <div>
                              <label for="issueinput5">套件:</label>
                              <?php echo $_POST['package'];?>
                          </div>
                          </fieldset>
                          <?php if ($_POST['inputFormat'])
                          {
                          	?>
                            <legend>程式接受的輸入/輸出格式:</legend>
                          <fieldset class="inputAllowed">
      
  <div>
    
                            <label for="input">輸入格式/InputType:</label>
                <?php echo $_POST['inputFormat'];?>
                          </div>
                          <div>
                            <label for="output">輸出格式/OutputType:</label>
                <?php echo $_POST['outputFormat'];?>
                          </div>
                          </fieldset>
                          <?php
                      }?>
                      <?php
                      		if ($paremeterData)
                      		{
                      ?>
                      <legend>參數的功能說明以及參數是否是有範圍(Range)/NeedParameter:</legend>
                          <fieldset  class="inputAllowed paremeterAllowed">
      

                       <div>
                        
                       	<?php 
      	$n = 0;
      	while ($n <= $paremeterData[0]['no'])
      	{
      ?>
                            <table border="1" width="500" cellspacing="0" cellpadding="5" bordercolor="#333333">
<tr>
<th bgcolor="grey" >參數名稱</th>
<th bgcolor="grey" >範圍</th>
<th bgcolor="grey" >格式</th>
<th bgcolor="grey" >功能說明</th>
</tr>
<tr>
<td bgcolor="#FFFFFF" align="left" nowrap><?php echo $_POST['paremeterName'.$n];?></td>
<td bgcolor="#FFFFFF" align="left" nowrap><?php echo $_POST['paremeterRange'.$n];?></td>
<td bgcolor="#FFFFFF" align="left" nowrap><?php echo $_POST['paremeterFormat'.$n];?></td>
<td bgcolor="#FFFFFF" align="left" nowrap><?php echo $_POST['paremeterDescription'.$n];?></td>
</tr>

</table>
<?php
	$n = $n + 1;
}
?>
                          </div>               
                          </fieldset>
                          <?php
                      }?>
                      <legend>License key:</legend>
                      <fieldset>
      
  <div>
    
                              <?php echo $licenseKey;?>
                          </div>
                          </fieldset>
                          <div>
                          <button type="submit" class="button">
        					<i class="add button"></i> 確定
    					</button>
    				</div>

                      </form>
</div>
</html>



