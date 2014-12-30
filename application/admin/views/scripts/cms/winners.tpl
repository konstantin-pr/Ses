<?php if ($this->userIsAdmin): ?>
    <h1 class="header">Gifts Available:</h1>
    <br/>
    <div class="dark">
        <form class="form-dark" action="<?php echo $this->configUrl; ?>&amp;type=winners&amp;act=generate">
            <fieldset>
            <legend spa="toggle">Obtain Random valuses from random.org</legend>
            <table ><tbody><tr><th><label>Game starts :</label></th><td>
                <input type="date" name="gameStartDate" value="<?php new DateTime("now");?>">
                <td><a class="button0 button-red" spa="submit">Submit</a></td>
            </td></tr></tbody></table>
            </fieldset>
        </form>
    </div>

    <?php if($this->error) { ?>
    <div class="container" style="border:2px solid #FF0000;padding:5px;font-weight:bold;">
        <?php echo $this->error; ?>
    </div>
    <br>
    <?php } ?>


    <table class="grid">
        <tbody>
	        <tr>
                <th style="width:40px; min-width:40px;text-align:left;">#</th>
        	    <th style="text-align:left;"><label>Winning Time</label></th>
        		<th style="text-align:left;"><label>Win Time</label></th>
                <th style="text-align:left;"><label>Winner's Email</label></th>
            </tr>
            <?php $i=1?>
            <?php foreach($this->gifts as $gift): ?>
            <tr>
                <td style="width:40px; min-width:40px;"><?php echo $i++; ?></td>
                <td><?php echo $gift['winning_time']->format('Y-m-d H:i:s a') ?></td>
    		    <td><?php 
                    $cur_date = new DateTime("now");
                    if ($gift['user']['date_win']){
                        echo $gift['user']['date_win']->format('Y-m-d H:i:s a'); 
                    }
                    elseif ($gift['winning_time'] >= $cur_date) {   
                        echo "Available";
                    }
                    else {
                        echo "Outdated";   
                    }
                ?></td>
                <td><?php echo $gift['user']['email'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h1 class="header">

    <script type="text/javascript">
        var copy_import = {
            "result": function (data) {
                console.log(data);
            }
        }
    </script>

    <script id="dialogcontent" type="text/tmpl">
<?php endif; ?>
