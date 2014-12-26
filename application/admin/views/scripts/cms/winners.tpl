<?php if ($this->userIsAdmin): ?>
    <h1 class="header">Gifts Available:</h1>
 
    <br/>

        <table class="grid">
            <tbody>
	        <tr>
                <th>#</th>
        	    <th><label>Winning Time</label></th>
        		<th><label>Win Time</label></th>
                <th><label>Winner's Email</label></th>
            </tr>
            <?php $i=1?>
            <?php foreach($this->gifts as $gift): ?>
            <tr>
                <td><?php echo $i++; ?></td>
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