<?php
ob_start();
include 'includes/functions/checksession.php';
/*
session_start();
if (!isset($_SESSION['UserName']) || $_SESSION['UserName'] == "") {
    if (!isset($_COOKIE['UserName'])) {
        header("location: index.php");
    }
}
*/

$pageTitle = 'Dashbord';
include 'init.php';
$totalMember = countItem('UserId', 'users WHERE GroupId !=1');
$pendingMember = countItem('RegStates', 'users WHERE RegStates = 0');
$totalItems = countItem('itemId', 'items_tb ');
$totalcom = countItem('comId', 'comments ');
$latestUser = (isset($num)) ? $num : 5;
$latestItems = (isset($num)) ? $num : 5;
$theLast = getLast("*", "users WHERE GroupId !=1", "UserId", $latestUser);
$theLastItem = getLast("*", "items_tb", "itemId", $latestItems);


?>
<div class="dashome">
    <div class="container">
        <div class="dashbord">
            <h4><i class="fas fa-tachometer-alt"></i> Dashbord</h4>
        </div>
    </div>
    <div class="container text-center">
        <div class="row">
            <div class="col-6 col-sm-6 col-md-3 ">
                <div class="stat">
                    Total Members
                    <span><a href="member.php"><?php echo $totalMember; ?></a></span>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-3 ">
                <div class="stat">
                    Pending Member
                    <span><a href="member.php?reg=pending"><?php echo $pendingMember; ?></a></span>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-3 ">
                <div class="stat">
                    Total Item
                    <span><a href="items.php"><?php echo $totalItems; ?></a></span>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-3 ">
                <div class="stat">
                    Total Comments
                    <span><a href="comments.php"><?php echo $totalcom; ?></a></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="latest">
    <div class="container">
        <div class="row mt-1">
            <div class="col-md-6 col-sm-12">
                <div class="card bg-light ">
                    <div class="card-header"> <i class="fa fa-users"></i> Latest <span style=" font-weight: bold;"><?php echo count($theLast); ?> </span>Users</div>
                    <div class="card-body m-0 p-2">
                        <h5 class="card-title" style="color: darkviolet;">Light card title</h5>
                        <ul class="list-unstyled m-0 last-user">
                            <?php
                             
                            for ($i = 1; $i < count($theLast) + 1; $i++) {
                                foreach ($theLast as $user) {
                                    $ask= 'WHERE user_ID =' . $user['UserId'];
                                    $usercom=selectfrom( '*' , 'comments ' , $ask  );
                                    $countusercom= countItem('comId', 'comments '.$ask) ; 
                                    echo '<div class="">
                                            <li class="m-0 p-1 list-group-item-action "><div data-toggle="collapse" href="#multiCollapseExample1" class="d-flex justify-content-between align-items-center viewInfo">' .  $i++ . ' - ' . $user['FullName'] .
                                                '<div class="btn-group viewInfo" role="group">
                                                    <a class="btn btn-success btn-sm badge" href="member.php?do=Edit&userid=' . sha1($user['UserId']) . '">
                                                    <i class="fa fa-edit"></i> Edit</a>' . dashlist($user['RegStates'], 'member.php?do=echeck&userid=', sha1($user['UserId'])) . '
                                                    <a class="btn btn-danger btn-sm badge confirm" href="member.php?do=Delete&userid=' . sha1($user['UserId']) . '">
                                                    <i class="fa fa-times"></i> Delete</a><i class="fas fa-sort-down px-2 "></i>
                                                </div></div>
                                        
                                            <div class="viewAll collapse multi-collapse" id="#multiCollapseExample1">
                                                <div >
                                                ' . $countusercom . ' comments ';  
                                                if ($countusercom > 0){
                                                    foreach ($usercom as $userc) {
                                                    echo '<ul ><li class="d-flex justify-content-between align-items-center " style="font-size:12px;"><span>'.$userc['comment'].'</span><span>'.$userc['comTime'].'</span>
                                                    <div class="btn-group viewInfo" role="group">
                                                    <a class="btn btn-success btn-sm badge" href="comments.php?com=Edit&comId=' . $userc['comId'] . '">
                                                    <i class="fa fa-edit"></i> Edit</a>' . dashlist($userc['status'], 'comments.php?com=echeck&comId=', $userc['comId']) . '
                                                    <a class="btn btn-danger btn-sm badge confirm" href="comments.php?com=Delete&comId=' . $userc['comId'] . '">
                                                    <i class="fa fa-times"></i> Delete</a></div></li></ul>';
                                                }}
                                                '
                                                </div>
                                                <div>
                                                Items
                                                </div>
                                            </div>
                                            </li>
                                        </div>'
                                 ;
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="card bg-light ">
                    <div class="card-header"><i class="fa fa-tag"></i> Latest Items</div>
                    <div class="card-body m-0 p-2">
                        <h5 class="card-title" style="color: darkviolet;">Light card title</h5>
                        <ul class="list-unstyled m-0 ">
                            <?php
                            for ($i = 1; $i < count($theLastItem) + 1; $i++) {
                                foreach ($theLastItem as $item) {
                                    echo '<li class="m-0 p-1 list-group-item-action d-flex justify-content-between align-items-center">' .  $i++ . ' - ' . $item['itemName'] .
                                        '<div class="btn-group" role="group">
                                 <a class="btn btn-success btn-sm badge" href="items.php?item=Edit&itemId=' . $item['itemId'] . '">
                                 <i class="fa fa-edit"></i> Edit</a>' . dashlist($item['Approve'], 'items.php?item=echeck&itemId=', $item['itemId']) . '
                                 <a class="btn btn-danger btn-sm badge confirm" href="items.php?item=Delete&itemId=' . $item['itemId'] . ' ">
                                 <i class="fa fa-times"></i> Delete</a></div></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php


    

/*echo '<pre style="color:red;">' ;
$stmt = $con->prepare("SELECT * FROM users ORDER BY UserId DESC   ");
$stmt->execute();
$row = $stmt->fetchAll();
print_r($row);
print_r(getLast("*" , "users", "UserId" , "3" ));

echo '</pre>';*/

// print_r($_SESSION);
include $tpl . 'footer.php';
ob_end_flush();
?>