
<?php

include 'includes/functions/checksession.php';
$pageTitle = 'update proccess';
include 'init.php';

?>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
                if (isset($_GET['success']) && $_GET['success'] == 1) :
                ?>
                    <div class="alert alert-success" role="alert"><strong> Success Update </strong></div>
                <?php
                else: echo ' <div class="alert alert-success" role="alert"><strong> Success Update </strong></div>';
            endif;
             ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

<?php


include $tpl . 'footer.php';
?>