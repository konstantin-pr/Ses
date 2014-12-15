<?php $item = $this->item?>
<?php if(empty($this->noConteiner)): ?> <div class="col dark cf-item" spa-container="true"><?php endif;?>
    <h2># <?php echo $item['id']?></h2>
    <br>
    <div>
        <span>
        <?php foreach($this->columns as $column):?>
        <b><?php echo $column?>:</b>
        <?php
        if($item[$column] instanceof \DateTime){
            echo $item[$column]->format('Y-m-d H:i:s');
        }
        elseif($column == 'status') {
            echo $item->getModerationStatus();
        }
        else echo $item[$column]
        ?>
        <br/>
        <?php endforeach; ?>

        <div style="margin-top: 10px">
        <?php if($item['status'] == $item::STATUS_APPROVED):?>
            <i style="color:#0f0">approved</i>
            <?php elseif($item['status'] == $item::STATUS_REJECTED):?>
            <i>rejected</i>
            <?php endif?>
            <?php if($item['status'] == $item::STATUS_APPROVED or $item['status'] == $item::STATUS_REJECTED):?>
            <a href="cf_pending://?id=<?php echo $item['id']?>" spa="link" target="_parent" class="button1 button-white">Pending</a>
            <?php if($item instanceof \Entity\LwModerable): ?><a href="cf_waiting://?id=<?php echo $item['id']?>" spa="link" target="_parent" class="button1 button-white">Waiting</a><?php endif;?>
            <?php else:?>
            <a href="cf_approve://?id=<?php echo $item['id']?>" spa="link" target="_parent" class="button1 button-green">Approve</a>
            <a href="cf_reject://?id=<?php echo $item['id']?>" spa="link" target="_parent" class="button1 button-orange">Reject</a>
            <?php endif?>
            <!-- <a href="cf_delete://?id=<?php echo $item['id']?>" spa="link" target="_parent" class="button1 button-red">Delete</a>  -->
        </div>
        </span>
    </div>
<?php if(empty($this->noConteiner)): ?></div><?php endif;?>
