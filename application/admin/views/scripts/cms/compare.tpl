<?php if (isset($this->error)): ?>

    <?php echo $this->error ?>
    <button spa="link" href="dialogcontent://" class="button1" target="_parent">Try again</button>

<?php elseif (count($this->data['needUpdate']) == 0 AND count($this->data['old']) == 0): ?>
    <h1>Import</h1>
    No data for update
<?php
else: ?>

    <form action="<?php echo $this->configUrl . "&type=import" ?>" type="POST" class='form-light' target="_self">
        <input type="hidden" name="type" value="<?php echo $this->type ?>"/>
        <h1>Importing conflicts</h1>
        <?php if(isset($this->data['countNotUpdate'])) ?>
            Numbers of copy wich not need update:
        <?php echo $this->data['countNotUpdate']; ?>
        <br/>
        <?php ?>
        <div class="col-header">
            <div class="import-col">Imported file</div>
            <div class="server-col">Current</div>
        </div>
        <?php if (count($this->data['needUpdate'])): ?>
            <h2 class="title">Need update</h2>
            <ul class="need-update">
                <?php foreach ($this->data['needUpdate'] as $messageid => $update): ?>
                    <li>
                        <label class="main-lable">
                            <?php if (isset($update['create'])): ?>
                                <input type="checkbox" name="needCreate[<?php echo $update['import']['key'] ?>]" checked="checked"
                                       id="UpdateCheckbox<?php echo $update['import']['key'] ?>"
                                       onchange="importSelect(this)"
                                       value="<?php echo $update['import']['message'] ?>"/>
                            <?php else: ?>
                                <input type="checkbox" name="needUpdate[<?php echo $update['import']['key'] ?>]" checked="checked"
                                       id="UpdateCheckbox<?php echo $update['import']['key'] ?>"
                                       onchange="importSelect(this)"
                                       value="<?php echo $update['import']['message'] ?>"/>
                            <?php endif; ?>

                            <?php echo $update['import']['label'] ?> <br/>
                        </label>

                        <div class="import-col i-col ">
                            <label for="UpdateCheckbox<?php echo $update['import']['key'] ?>">
                                <?php echo $update['import']['message'] ?>
                            </label>
                        </div>
                        <div class="server-col i-col ">
                            <label>
                                <div class="selected-update cross-text">
                                    <?php if (!isset($update['create'])): ?>
                                        <?php echo $update['server']['message'] ?>
                                    <?php endif; ?>
                                </div>
                                <div class="update-result"><?php echo $update['import']['message'] ?></div>
                            </label>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif; ?>

        <?php if (count($this->data['old'])): ?>
            <h2 class="title">Data in imported file is older than current</h2>
            <ul class="need-update-old">
                <?php foreach ($this->data['old'] as $messageid => $update): ?>
                    <li>

                        <label class="main-lable">
                            <input type="checkbox" id="UpdateOldCheckbox<?php echo $update['import']['key'] ?>"
                                   onchange="importSelect(this)" name="old[<?php echo $update['import']['key'] ?>]"
                                   value="<?php echo $update['import']['message'] ?>"/>
                            <?php echo $update['import']['label'] ?> <br/>
                        </label>

                        <div class="import-col i-col">
                            <label for="UpdateOldCheckbox<?php echo $update['import']['key'] ?>">
                                <?php echo $update['import']['message'] ?>
                            </label>
                        </div>


                        <div class="server-col i-col">
                            <label>
                                <div class="selected-update">
                                    <?php echo $update['server']['message'] ?>
                                </div>
                                <div class="update-result"></div>
                            </label>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif; ?>
        <button class='button1 button-next' spa='submit'>Next -></button>
        <div class="clear-both"></div>
    </form>
    <style>
        ul {
            padding: 0 0 5px 0;
        }

        li {
            list-style: none;
            margin-bottom: 10px;
            border-top: 1px solid #B8B8B8;
        }

        li:first-child {
            border: none;
        }

        .main-lable {
            display: block;
            font-weight: bold;
        }

        .cross-text {
            text-decoration: line-through;
        }

        .update-result {
            text-decoration: none;
        }

        .col-header {
            margin: 15px 0 25px 0;
            font-size: 16px;
        }

        .title {
            color: #8B8970;
            margin: 15px 0 5px 0;
        }

        .need-update, .need-update-old {
            border-radius: 5px;
        }

        .need-update {
            background: #F0FFF0;
        }

        .need-update-old {
            background: #CFCFCF;
        }

        .import-col, .server-col, .i-col {
            width: 350px;
            display: inline-block;
        }

        .i-col {
            height: 54px;
            overflow: hidden;
            vertical-align: top;
            position: relative;
        }

        .i-col:after {
            bottom: -1px;
            width: 100%;
            content: '';
            height: 13px;
            display: block;
            box-shadow: inset 0 -13px 9px -4px #fff;
            position: absolute;
        }

        .need-update .i-col:after {
            box-shadow: inset 0 -13px 9px -4px #F0FFF0;
        }

        .need-update-old .i-col:after {
            box-shadow: inset 0 -13px 9px -4px #CFCFCF;
        }

        .import-col {

        }

        .server-col {
            margin: 0 0 0 50px;
        }

        .button-next {
            float: right;
            margin: 25px 25px 0 0;
        }

        .clear-both {
            clear: both;
        }
    </style>
    <script type="text/javascript">
        function importSelect(elem) {
            var checkbox = $(elem);
            var update = checkbox.parent().next();
            var text = update.text();
            var next = update.next().children();
            var oldText = next.children('.selected-update');
            var newText = next.children('.update-result');
            if (checkbox.attr('checked') === 'checked') {
                oldText.addClass('cross-text');
                newText.text(text);
            } else {
                oldText.removeClass('cross-text');
                newText.empty();
            }

            resize(update.next());
        }

        function resize(elem){
            var heightElem = elem.children().height();
            elem.animate({'height': heightElem + 20 + 'px'}, 'fast');
        }

        (function () {
            $('.i-col').mouseover(function () {
                var elem = $(this);

                if (elem.hasClass('import-col'))
                    var elem2 = elem.next();
                else
                    var elem2 = elem.prev();
                var timer = setTimeout(function () {
                    clearTimeout(timer);
                    var heightElem2 = elem2.children().height();
//                    elem2.animate({'height': heightElem2 + 20 + 'px'}, 'fast');
                    elem2.slideDown(heightElem2 + 20);
                    resize(elem);

                }, 400);
                elem.mouseleave(function () {
                    clearTimeout(timer);
                    elem.animate({'height': '54px'}, 'fast');
                    elem2.animate({'height': '54px'}, 'fast');
                    elem.unbind('mouseleave');
                });

            });


        })();
    </script>
<?php endif; ?>