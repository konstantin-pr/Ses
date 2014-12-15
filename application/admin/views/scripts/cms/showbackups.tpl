<div class="container0">
    <h1>
        Restore from backup
    </h1><br/>
    <?php if (is_array($this->data) AND count($this->data)): ?>
        <form action="<?php echo $this->configUrl . "&type=compare" ?>" type="POST" class='form-light' target="_self">
            <span id="showSelectId" class="admin-drop">
                <span class="admin-triangle"></span>
                <span >Select backup</span>

                <div class="container3">
                    <?php foreach ($this->data as $data): ?>
                        <a href="localcontent://?id=<?php echo $data['id'] ?>&data=<?php echo $data['backup_time']->format("Y-m-d h:i a") ?>" class="button1" spa="link" target="selectedBackId">
                            <?php echo $data['backup_time']->format("Y-m-d h:i a") ?>
                        </a>
                    <?php endforeach ?>
                </div>
            </span>
            <span id="selectedBackId"></span>
            <br/>
            <button class="button0 btn-next" spa='submit'>Next -></button>
            <div class="clear"></div>
        </form>
    <?php else: ?>
        no backups
    <?php endif; ?>
</div>
<script id="localcontent" type="text/tmpl">
    <input type="hidden" value="${id}" name="backupId"/>
    (${data}) <a href="<?php echo '/app/cms/' . $this->configUrl . "&type=export&get=1&backup=" ?>${id}" class="button1">get backup</a>
</script>
<style>
    .btn-next {
        float: right;
    }

    .clear {
        clear: both;
    }

    .admin-triangle {
        display: inline-block;
        width: 0px;
        height: 0px;
        border: 8px #0F1922 solid;
        border-right-color: transparent;
        border-left-color: transparent;
        border-bottom-color: transparent;
        margin-bottom: -8px;
        margin-left: 5px;
        margin-right: 5px;
    }
    .admin-drop{
        position: relative;
        width: 60px;
    }
    .admin-drop .container3{
        display: none;
    }
    .admin-drop:hover .container3{
        width: 600px;
        display: block;
        position: absolute;
        background-color: #FFFFFF;
        top: 0;
        left: 0;
    }
    .admin-drop a {
        margin: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position: relative;
    }
</style>