<div id="CmsContentBox">
    <div class="lefttab">
        <div><a href="<?php echo $this->configUrl ?>&type=winners" spa="link" target="ContentBox"
                ondata="selectTab(this);">Winners</a></div>
    </div>
    <div class="lefttab">
        <div><a href="<?php echo $this->configUrl ?>&type=exportusers" spa="link" target="ContentBox"
                ondata="selectTab(this);">Export Users</a></div>
    </div>


    <div class="container0" id="ContentBox" spa-container="true">
        <?php echo $this->render('winners.tpl'); ?>
    </div>


    <script type="text/javascript">
        function selectTab(item) {
            $('.lefttab:not(.tabdeactive)').addClass('tabdeactive');
            $(item).parent().parent().removeClass('tabdeactive');
        }
    </script>
</div>