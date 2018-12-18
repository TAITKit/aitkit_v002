<?php 

$praetor = new Praetor();
$sql = "SELECT AID FROM algorithm WHERE Abbreviation=%s_Abbreviation";
$data = $praetor->custosql($sql, array('Abbreviation'=>$_POST['abbr']));
print_r($_POST['abbr']);
print_r($data);
$where = new WhereClause('and'); 
$where->add('algorithmID=%s', $data[0]['AID']);
$praetor->custodelete('dataset', "%l", $where);
$praetor->custodelete('link', "%l", $where);
$praetor->custodelete('tag', "%l", $where);
$praetor->custodelete('paremeter', "%l", $where);
$praetor->custodelete('algorithm', "AID=%s_AID", array('AID'=>$data[0]['AID']));
echo "<script>location.href='index.php?item=praetorLogin';</script>";
?>