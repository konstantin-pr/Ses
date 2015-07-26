<?php 
/* Template Name: Natasha */
   get_header(); ?>

<div id="main-content" class="main-content">

<?php
   get_sidebar(); 
?>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
 <?php
             query_posts( array( 'post_type' => array( 'easy-rooms', 'easy-offers' ),    
             'showposts' => 115 )
             );  


         $args = array(
          'posts_per_page'   => 115,
          'offset'           => 0,
          'category'         => '',
          'category_name'    => '',
          'orderby'          => 'date',
          'order'            => 'DESC',
          'include'          => '',
          'exclude'          => '',
          'meta_key'         => '',
          'meta_value'       => '',
          'post_type'        => array( 'easy-rooms', 'easy-offers' ),
          'post_mime_type'   => '',
          'post_parent'      => '',
          'author'     => '',
          'post_status'      => 'publish',
          'suppress_filters' => true 
        );

$posts_array = get_posts( $args ); 

foreach ( $posts_array as $post ) : setup_postdata( $post ); ?>
  <div>
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
    <div><?php the_content(); ?></div>  
  </div>
<?php endforeach; 
 





                // // Start the Loop.
                // while ( have_posts() ) : the_post();

                //     // Include the page content template.
                //     get_template_part( 'content', 'page' );

                //     // If comments are open or we have at least one comment, load up the comment template.
                //     if ( comments_open() || get_comments_number() ) {
                //         comments_template();
                //     }
                // endwhile;
            ?>
        </div><!-- #content -->
    </div><!-- #primary -->
</div><!-- #main-content -->




<form name="CalendarFormular" id="CalendarFormular-84781" style="width:100%" ;margin:0px;padding:0px;display:inline-block;=""><div id="showCalender" style="margin-right:auto;margin-left:auto;vertical-align:middle;padding:0;width:100%"><table class="calendar-table" cellpadding="0" cellspacing="0"><thead><tr class="calendarheader"><th class="calendar-header-month-prev" onclick="easyCalendars[84781].change('date', '-1');">prev</th><th colspan="5" class="calendar-header-show-month">July 2015</th><th class="calendar-header-month-next" onclick="easyCalendars[84781].change('date', '1');">next</th></tr></thead><tbody style="text-align:center;white-space:nowrap;padding:0px"><tr><td colspan="7" style="white-space:nowrap;padding:0px;margin:0px"><table class="calendar-direct-table " style="width:100%;margin:0px;"><thead><tr><th class="calendar-header-cell">Mo</th><th class="calendar-header-cell">Tu</th><th class="calendar-header-cell">We</th><th class="calendar-header-cell">Th</th><th class="calendar-header-cell">Fr</th><th class="calendar-header-cell">Sa</th><th class="calendar-header-cell">Su</th></tr></thead><tbody style="text-align:center;padding;0px;margin:0px"><tr style="text-align:center"><td class="calendar-cell calendar-cell-last"><span>29</span></td><td class="calendar-cell calendar-cell-last"><span>30</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-1-201507" axis="1">1<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-2-201507" axis="2">2<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-3-201507" axis="3">3<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-4-201507" axis="4">4<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-5-201507" axis="5">5<span class="calendar-cell-price">120$</span></td></tr><tr style="text-align:center"><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-6-201507" axis="6">6<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-7-201507" axis="7">7<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-8-201507" axis="8">8<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-9-201507" axis="9">9<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-10-201507" axis="10">10<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-11-201507" axis="11">11<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-12-201507" axis="12">12<span class="calendar-cell-price">120$</span></td></tr><tr style="text-align:center"><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-13-201507" axis="13">13<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-14-201507" axis="14">14<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-15-201507" axis="15">15<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-16-201507" axis="16">16<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-17-201507" axis="17">17<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-18-201507" axis="18">18<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-19-201507" axis="19">19<span class="calendar-cell-price">120$</span></td></tr><tr style="text-align:center"><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-20-201507" axis="20">20<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-21-201507" axis="21">21<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-22-201507" axis="22">22<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-23-201507" axis="23">23<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-24-201507" axis="24">24<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" style="cursor:default" id="easy-cal-84781-25-201507" axis="25">25<span class="calendar-cell-price">120$</span></td><td class="calendar-cell today calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" date="26.07.2015" id="easy-cal-84781-26-201507" axis="26">26<span class="calendar-cell-price">120$</span></td></tr><tr style="text-align:center"><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" date="27.07.2015" id="easy-cal-84781-27-201507" axis="27">27<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" date="28.07.2015" id="easy-cal-84781-28-201507" axis="28">28<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" date="29.07.2015" id="easy-cal-84781-29-201507" axis="29">29<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" date="30.07.2015" id="easy-cal-84781-30-201507" axis="30">30<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-empty calendar-cell-empty2 calendar-cell-halfstart" date="31.07.2015" id="easy-cal-84781-31-201507" axis="31">31<span class="calendar-cell-price">120$</span></td><td class="calendar-cell calendar-cell-last calendar-cell-lastfixer"><div>&nbsp;</div><span>1</span><span class="calendar-cell-price">&nbsp;</span></td><td class="calendar-cell calendar-cell-last"><div>&nbsp;</div><span>2</span><span class="calendar-cell-price">&nbsp;</span></td></tr></tbody></table></td></tr></tbody></table></div></form> 
<div class="easyFrontendFormular" id="easy-form-83477" style="width:100%"><form method="post" id="easyFrontendFormular" name="easyFrontendFormular"><input name="easynonce" type="hidden" value="0c008d59e9"><input name="pricenonce" type="hidden" value="6a2d04e51e"><div class="easy-show-error-div hide-it" id="easy-show-error-div" style=""><h2>Errors found in the form</h2>There is a problem with the form, please check and correct the following:<ul id="easy-show-error"></ul></div>
<h1>Reserve now!<span class="easy-form-price" title="" style="float:right;"><span id="showPrice">120,00 $</span></span></h1>
<h2>General information</h2>

<label>Arrival Date
<span class="small">When will you come?</span>
</label><span class="row"><input id="easy-form-from" type="text" name="from" value="27.07.2015" title="" style="width:75px" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');" class="hasDatepicker"> <select id="date-from-hour" name="date-from-hour" title="" style="width:50px" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12" selected="selected">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select>:<select id="date-from-min" name="date-from-min" title="" style="width:50px" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select></span>

<label>Departure Date
<span class="small">When will you go?</span>
</label><span class="row"><input id="easy-form-to" type="text" name="to" value="28.07.2015" title="" style="width:75px" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');" class="hasDatepicker"> <select id="date-to-hour" name="date-to-hour" title="" style="width:50px" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12" selected="selected">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select>:<select id="date-to-min" name="date-to-min" title="" style="width:50px" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select></span>

<label>Resource
<span class="small">What do you want?</span>
</label><select name="easyroom" style="" id="form_room" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');"><option value="4" selected="selected">Portoroz Hotelname Appartment ( 2 rooms )</option><option value="5">IbizaHotelName Appartment 6 rooms</option><option value="33">Квартира на офицерской</option></select>

<label>Adults
<span class="small">How many guests?</span>
</label><select id="easy-form-persons" name="persons" style="" title="" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select>

<label>Children’s   
<span class="small">With children?</span>
</label><select name="childs" style="" title="" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select>

<h2>Personal information</h2>

<label>Name
<span class="small">What is your name?</span>
</label><input type="text" id="easy-form-thename" name="thename" value="" style="" title="" onchange="easyreservations_send_validate(false,'easy-form-83477');">

<label>Email
<span class="small">What is your email?</span>
</label><input type="text" id="easy-form-email" name="email" value="" title="" style="" onchange="easyreservations_send_price('easy-form-83477');easyreservations_send_validate(false,'easy-form-83477');">

<label>Country
<span class="small">Your country?</span>
</label><select id="easy-form-country" name="country" title="" style=""><option value="AF">Afghanistan</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua And Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia And Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Cote D'Ivorie (Ivory Coast)</option><option value="HR">Croatia (Hrvatska)</option><option value="CU">Cuba</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="CD">Democratic Republic Of Congo (Zaire)</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="TP">East Timor</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands (Malvinas)</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="FX">France, Metropolitan</option><option value="GF">French Guinea</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard And McDonald Islands</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Laos</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macau</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar (Burma)</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="AN">Netherlands Antilles</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="KP">North Korea</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts And Nevis</option><option value="LC">Saint Lucia</option><option value="PM">Saint Pierre And Miquelon</option><option value="VC">Saint Vincent And The Grenadines</option><option value="SM">San Marino</option><option value="ST">Sao Tome And Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovak Republic</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia And South Sandwich Islands</option><option value="KR">South Korea</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard And Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syria</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad And Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks And Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="UK">United Kingdom</option><option value="US">United States</option><option value="UM">United States Minor Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VA">Vatican City (Holy See)</option><option value="VE">Venezuela</option><option value="VN">Vietnam</option><option value="VG">Virgin Islands (British)</option><option value="VI">Virgin Islands (US)</option><option value="WF">Wallis And Futuna Islands</option><option value="EH">Western Sahara</option><option value="WS">Western Samoa</option><option value="YE">Yemen</option><option value="YU">Yugoslavia</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select>

<label>Captcha
<span class="small">Type in code</span>
</label><span class="row"><input type="text" title="" name="captcha_value" id="easy-form-captcha" style="width:40px;"><img id="easy-form-captcha-img" style="vertical-align:middle;margin-top: -5px;" src="http://localhost/be-better/wp-content/plugins/easyreservations/lib/captcha/tmp/1347049498.png"><input type="hidden" value="1347049498" name="captcha_prefix"></span>

<div style="text-align:center;"><input type="submit" title="" style="" class="easy-button" value="Send" onclick="easyreservations_send_validate('send','easy-form-83477'); return false"><span id="easybackbutton"></span></div><!-- Provided by easyReservations free Wordpress Plugin http://www.easyreservations.org --></form></div>

        </div><!-- #content -->
    </div><!-- #primary -->
</div><!-- #main-content -->


<!--- Insert styles --> 
<link rel='stylesheet' id='easy-cal-1-css'  href='http://localhost/be-better/wp-content/plugins/easyreservations/css/calendar/style_1.css?ver=3.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='datestyle-css'  href='http://localhost/be-better/wp-content/plugins/easyreservations/css/jquery-ui.css?ver=3.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='easy-frontend-css'  href='http://localhost/be-better/wp-content/plugins/easyreservations/css/frontend.css?ver=3.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='easy-form-none-css'  href='http://localhost/be-better/wp-content/plugins/easyreservations/css/forms/form_none.css?ver=3.4.2' type='text/css' media='all' />
<script type='text/javascript' src='http://localhost/be-better/wp-includes/js/admin-bar.min.js?ver=3.9.3'></script>
<script type='text/javascript' src='http://localhost/be-better/wp-includes/js/comment-reply.min.js?ver=3.9.3'></script>
<script type='text/javascript' src='http://localhost/be-better/wp-content/themes/twentyfourteen/js/functions.js?ver=20140319'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var easyAjax = {"ajaxurl":"http:\/\/localhost\/be-better\/wp-admin\/admin-ajax.php","plugin_url":"http:\/\/localhost\/be-better\/wp-content\/plugins","interval":"{\"4\":86400,\"5\":86400,\"33\":\"86400\"}"};
/* ]]> */
</script>
<script type='text/javascript' src='http://localhost/be-better/wp-content/plugins/easyreservations/js/ajax/send_calendar.js?ver=3.4.2'></script>
<script type='text/javascript' src='http://localhost/be-better/wp-includes/js/jquery/ui/jquery.ui.core.min.js?ver=1.10.4'></script>
<script type='text/javascript' src='http://localhost/be-better/wp-includes/js/jquery/ui/jquery.ui.datepicker.min.js?ver=1.10.4'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var easyDate = {"ajaxurl":"http:\/\/localhost\/be-better\/wp-admin\/admin-ajax.php","currency":{"sign":"#36","whitespace":1,"decimal":"1","divider1":".","divider2":",","place":"0"},"easydateformat":"d.m.Y","interval":"{\"4\":86400,\"5\":86400,\"33\":\"86400\"}"};
/* ]]> */
</script>
<script type='text/javascript' src='http://localhost/be-better/wp-content/plugins/easyreservations/js/ajax/form.js?ver=3.4.2'></script>
<script type='text/javascript' src='http://localhost/be-better/wp-content/plugins/easyreservations/js/ajax/data.js?ver=3.4.2'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var easyAjax = {"ajaxurl":"http:\/\/localhost\/be-better\/wp-admin\/admin-ajax.php","plugin_url":"http:\/\/localhost\/be-better\/wp-content\/plugins","interval":"{\"4\":86400,\"5\":86400,\"33\":\"86400\"}"};
/* ]]> */
</script>
<script type='text/javascript' src='http://localhost/be-better/wp-content/plugins/easyreservations/js/ajax/send_validate.js?ver=3.4.2'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var easyAjax = {"ajaxurl":"http:\/\/localhost\/be-better\/wp-admin\/admin-ajax.php","plugin_url":"http:\/\/localhost\/be-better\/wp-content\/plugins","interval":"{\"4\":86400,\"5\":86400,\"33\":\"86400\"}"};
/* ]]> */
</script>
<script type='text/javascript' src='http://localhost/be-better/wp-content/plugins/easyreservations/js/ajax/send_price.js?ver=3.4.2'></script>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var dates = jQuery( "#easy-form-from,#easy-form-to" ).datepicker({
                    dateFormat: 'dd.mm.yy',
                    minDate: 0,
                    beforeShowDay: function(date){
                        if(2 == 2 && window.easydisabledays ){
                                return easydisabledays(date, jQuery(this).parents("form:first").find( "[name=easyroom],#room" ).val());
                        } else {
                            return [true];
                        }
                    },
                    dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            dayNamesShort: ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
            dayNamesMin: ["Su","Mo","Tu","We","Th","Fr","Sa"],
            monthNames: ["January","February","March","April","May","June","July","August","September","October","November","December"],
            monthNamesShort: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                    firstDay: 1,
                    onSelect: function( selectedDate ){
                        if(this.id == 'easy-form-from'){
                            var option = this.id == "easy-form-from" ? "minDate" : "maxDate",
                            instance = jQuery( this ).data( "datepicker" ),
                            date = jQuery.datepicker.parseDate( instance.settings.dateFormat || jQuery.datepicker._defaults.dateFormat, selectedDate, instance.settings );
                            dates.not( this ).datepicker( "option", option, date );
                        }
                        if(window.easyreservations_send_validate) easyreservations_send_validate(false, 'easyFrontendFormular');
                        if(window.easyreservations_send_price) easyreservations_send_price('easyFrontendFormular');
                    }
                });
            });
        </script><script type="text/javascript">if(window.easyCalendar) new easyCalendar("16c1129c84", {"room":0,"date":0,"resource":"4","width":100,"style":1,"price":"1","header":0,"req":0,"interval":1,"monthes":1,"select":2,"id":84781}, "shortcode"); else jQuery(window).ready(function(){new easyCalendar("16c1129c84", {"room":0,"date":0,"resource":"4","width":100,"style":1,"price":"1","header":0,"req":0,"interval":1,"monthes":1,"select":2,"id":84781}, "shortcode");});if(window.easyreservations_send_price) easyreservations_send_price('easy-form-83477'); else jQuery(document).ready(function(){easyreservations_send_price('easy-form-83477');});var easyReservationAtts = {"room":0,"resource":"4","price":"1","multiple":"full","resourcename":"Room","cancel":"Cancel","credit":"\u041a\u0432\u0430\u0440\u0442\u0438\u0440\u0430 \u0432\u0430\u0448\u0430!","submit":"\u0417\u0430\u043f\u0440\u043e\u0441 \u0443\u0441\u043f\u0435\u0448\u043d\u043e \u043e\u0442\u043f\u0440\u0430\u0432\u043b\u0435\u043d","validate":"Reservation successfully verified","subcredit":"\u0412\u044b \u043f\u043e\u043b\u0443\u0447\u0438\u0442\u0435 \u043f\u0438\u0441\u044c\u043c\u043e \u043d\u0430 \u0432\u0430\u0448 \u0430\u0434\u0440\u0435\u0441","discount":100,"subsubmit":"\u041f\u043e\u0436\u0430\u043b\u0443\u0439\u0441\u0442\u0430, \u0440\u0430\u0441\u043f\u043b\u0430\u0442\u0438\u0442\u0435\u0441\u044c","subvalidate":"Either make additional reservations or submit","reset":1,"style":"none","width":100,"bg":"#fff","pers":"1","payment":1,"datefield":""};var easyInnerlayTemplate = "<span class=\"easy_validate_message\">Reservation successfully verified</span><span class=\"easy_validate_message_sub\">Either make additional reservations or submit</span><table id=\"easy_overlay_table\"><thead><tr><th>Time</th><th>Room</th><th>Persons</th><th>Price</th><th></th></tr></thead><tbody id=\"easy_overlay_tbody\"></tbody></table><input type=\"button\" onclick=\"easyAddAnother();\" value=\"Add another reservation\"><input class=\"easy_overlay_submit\"  type=\"button\" onclick=\"easyFormSubmit(1);\" value=\"Submit all reservations\">";var all_resoures_array={"4":{"post_title":"Portoroz Hotelname Appartment ( 2 rooms )"},"5":{"post_title":"IbizaHotelName Appartment 6 rooms"},"33":{"post_title":"\u041a\u0432\u0430\u0440\u0442\u0438\u0440\u0430 \u043d\u0430 \u043e\u0444\u0438\u0446\u0435\u0440\u0441\u043a\u043e\u0439"}};</script> <script type="text/javascript">
        (function() {
            var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');

            request = true;

            b[c] = b[c].replace( rcs, ' ' );
            b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;
        }());
    </script>


 
<?php
get_footer();
?>
