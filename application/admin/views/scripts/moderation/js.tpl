<?php
$configUrl = $this->configUrl . '&filter=' . $this->filter . '&sort=' . $this->sort . '&search=' . $this->search;
$statuses = array(
    'pending'=>\Entity\BaseModerable::STATUS_PENDING,
    'approve'=>\Entity\BaseModerable::STATUS_APPROVED,
    'reject'=>\Entity\BaseModerable::STATUS_REJECTED,
    'waiting'=>\Entity\LwModerable::STATUS_WAITING_TO_SEND
);
foreach ( $statuses as $title => $status ):
?>
<script id="cf_<?php echo $title;?>" type="text/tmpl">
        <a href="<?php echo $configUrl;?>&type=updatecancel&id=${id}&property=status&value=<?php echo $status;?>" class="button1 button-orange" spa="link">Cancel</a>
        <span>or</span>
        <a href="<?php echo $configUrl;?>&type=update&id=${id}&property=status&value=<?php echo $status;?>" class="button1 button-red"
        spa="link">Confirm</a>
</script>

<script id="cf_<?php echo $status;?>_cancel" type="text/tmpl">
        <a href="cf_<?php echo $status;?>://?id=${id}" class="button1"
        spa="link" target="_parent"><?php echo ucfirst($title);?></a>
</script>
<?php endforeach;?>


<script id="cf_delete" type="text/tmpl">
        <a href="cf_delete_cancel://?id=${id}" spa="link" target="_parent" class="button1">Cancel</a>
        <span>or</span>
        <a href="<?php echo $configUrl;?>&type=delete&id=${id}" class="button1 button-red"
        spa="link">Confirm</a>
</script>

<script id="cf_delete_cancel" type="text/tmpl">
        <a href="cf_approve://?id=${id}" class="button1 button-red"
        spa="link" target="_parent">Delete</a>
</script>

