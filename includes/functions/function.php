<?php

/** --- Title Function--- **/ /// v-1.0
function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo 'CRM' . ' | ' . $pageTitle;
    } else {
        echo 'CRM';
    }
}

/// v-1.0 
/*
**_____________ DIRECT ERROR MASAGE ______________ 

*/
function redirectHome($theMsg, $url= null, $seconds = 3){
    
    if ($url === null){
        $url= 'index.php';
    }else{
        $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=='' ? $_SERVER['HTTP_REFERER'] : 'index.php';
    }
    
    echo $theMsg;
    header("refresh:$seconds;url=$url");
    exit();
}

    

/*
//////  Check Item FUNCTION /// V-1.0
**FUNCTION TO CHECK ITEM IN DATABASE [ACCEPTABLE PARAMETERS]
**$SELECT = THE ITEM TO SELECT [Ex: USERS, ITEMS , CATEGORIES]
**$FROM = THE TABLE TO SELECT FROM DATABASE [Ex: USERS, ITEMS , CATEGORIES]
**$VALUE =  THE VALUE OF SELECT [Ex: VALUE, PHONE , NAME]
*/
function checkItem($select, $from, $value)
{
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select= ? ");
    $statement->execute(array($value));
    $row = $statement->fetchAll();
    $count = $statement->rowCount();
    if ($count > 0) {
        return $count;
    }
}
/**********create hash */
function wizardhash($select, $from, $value)
{
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE sha1($select) = ?");
    $statement->execute(array($value));
    $row = $statement->fetch();
    $count = $statement->rowCount();
    if ($count > 0) {
        
        $id = $row['UserId'];
        return $id;
    }
}

function selectfrom($select , $from , $ask  )
{
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from $ask ");
    $statement->execute();
    $rows = $statement->fetchAll();
    $count = $statement->rowCount();
    if ($count > 0) {
     return $rows;
     return $count;

    }
       
}
/// COUNT NUMBER FOR [ITEMS, USERS , ...]_____\\\
function countItem($item, $table)
{
    global $con;
    $statc = $con->prepare("SELECT COUNT($item) FROM $table");
    $statc->execute();
    return $statc->fetchColumn();
}

/****** Change chick box Enable or Disable v-2 */

function checkBox( $reg , $link ,  $id )
{
    if($reg==1){
        echo '<a type="button" title="click to Deactivate" class="btn btn-outline-primary" href="?'.$link .$id ."&reg=0".'"><i class="fas fa-check-square"></i></a>';
    }else{
        echo '<a type="button" title="click to Activate" class="btn btn-outline-secondary " href="?'.$link .$id ."&reg=1".'"><i class="fa fa-ban"></i></a>';
    }
    return ;
}

/****** Change chick box Enable or Disable v-1 */
/*
function checkBox($reg, $id )
{
    if($reg==1){
        echo '<a type="button" title="click to Deactivate" class="btn btn-outline-primary" href="?do=echeck&userid='.sha1($id)."&reg=0".'"><i class="fas fa-check-square"></i></a>';
    }else{
        echo '<a type="button" title="click to Activate" class="btn btn-outline-secondary" href="?do=echeck&userid='.sha1($id)."&reg=0".'"><i class="fa fa-ban"></i></a>';
    }
    return ;
}
*/
////////////////////////////////////////////////////////
function dashlist($reg,$link, $id){
if($reg==1){
    $st= 
        '<a title="click to Deactivate" class="btn btn-primary btn-sm badge" href="'.$link .$id."&reg=0".'">
        <i class="fas fa-check"></i> Active</a>';

}else{
    $st=
    '<a title="click to Activate" class="btn btn-secondary btn-sm badge" href="'.$link.$id."&reg=1".'">
        <i class="fa fa-ban"></i> Deactivated</a>';
    }
    return $st;
}
/***GET Last 5 */
function getLast($select , $table , $last , $limit = 5){
    global $con;
    $stmt = $con->prepare("SELECT $select FROM $table  ORDER BY $last DESC  LIMIT $limit ");
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;

}
/*** End GET Last 5 ********/
/***** Start Get Allow Categories status ***** */
function allowStatus($status){
    if ($status == 0){
        $status = 'Yes';
    }else {
        $status = 'No';
    }
    return $status;
}

/***** End Get Allow Categories status ***** */
//////////////// SUCCESS MESSAGE //////////////
function message($msg , $actions){
    global $theMsg;
    if (isset($_GET[$msg]) && $_GET[$msg] == $actions ){
        $theMsg = "
        <div class='alert alert-success '>
        <h5>". $actions." Category Successfull</h5>
        </div>
        ";
        return $theMsg;
        exit();
    }
}
///////////// End Success MSG //////////
