<style>
.cf-point {
	position: relative !important;
	height: 0px !important;
}

a.cf-photos-left {
	position: absolute;
	font-size: 26px;
	font-weight: bold;
	text-decoration: none;
	cursor: pointer;
	top: -180px;
	left: 2px;
}

a.cf-photos-right {
	position: absolute;
	font-size: 26px;
	font-weight: bold;
	text-decoration: none;
	cursor: pointer;
	top: -180px;
	left: 330px;
}

.cf-item-holder {
	float: left;
	margin: 1px;
	padding-bottom: 0px;
	display: inline-block;
}

.cf-item {
	position: relative; width : 250px;
	min-height: auto;
	padding-bottom: 0px;
	color: #FFFFFF;
	margin: 4px;
	width: 244px;
}

.cf-item>div {
	margin: 10px;
}

.cf-item.preloader1 {
	background-image: url(img/preloader2.gif);
}

.cf-item .preloader1 {
	background-image: url(img/preloader4.gif);
}

.cf-item * {
	color: #FFFFFF;
}

.cf-item a {
	color: #95C8F8;
}

.cf-item i {
	position: absolute;
	display: inline-block;
	left: 50px;
	top: 50%;
	margin-top: -15px;
	text-transform: uppercase;
	color: #FF0000;
	font-size: 30px;
	font-style: normal;
	font-weight: bold;
	filter: alpha(opacity =   50);
	opacity: 0.5;
	-webkit-transform: rotate(20deg);
	-khtml-transform: rotate(20deg);
	-moz-transform: rotate(20deg);
	-ms-transform: rotate(20deg);
	-o-transform: rotate(20deg);
	transform: rotate(20deg);
}

.cf-item-content {
	padding: 4px;
}

.cf-item-user {
	margin-top: 10px;
	overflow: auto;
}

.cf-item-user>* {
	float: left;
	margin-right: 5px;
}

.cf-item-border {
	background-color: #698EB3;
	padding: 4px;
	display: inline-block;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	border-radius: 2px;
}

.cf-item-border img {
	border: #192F3B 1px solid;
	background-color: #FFFFFF;
}

.cf-item-caption {
	padding: 0px 5px 5px 5px;
	width: 338px;
	display: block;
	text-align: justify;
	background-color: #000000;
	font-size: 12px;
	position: relative;
}

.cf-photos-container {
	position: relative !important;
	width: 242px !important;
	overflow: hidden !important;
	background: none !important;
}

.cf-photos-items {
	width: 2000px !important;
	position: relative !important;
	background: none !important;
}

.cf-photo-item {
	width: 242px !important;
	/*height: 270px !important;*/
	float: left !important;
}

.cf-photo-item img {
	display: block !important;
	margin: 0px auto !important;
}

.cf-item-img {
	width: 100%;
	/*height: 270px;*/
	padding-top: 10px;
	display: block;
	text-align: center;
	white-space: nowrap;
	background-color: #000000;
	font-size: 0px;
	position: relative;
}

.cf-item-img div {
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%; //
	background: transparent url(img/rejected.png) no-repeat center center;
}

.cf-item-img img {
	border: none;
	max-width: 348px;
	max-height: 250px;
	vertical-align: middle;
}

.cf-item-img span {
	/*height: 250px;*/
	width: 0px;
	font-size: 0px;
	overflow: hidden;
	display: inline-block;
	vertical-align: middle;
}

.cf-item-buttons {
	margin-top: 10px;
}

.cf-item-tool {
	float: right;
	margin-top: 5px;
	margin-right: 10px;
}

.cf-item-tool a {
	margin-left: 5px;
}

.cf-item-tool a img {
	border: none;
}

.cf-item-count {
	background-color: #00376A;
	border-radius: 2px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	color: white;
	font-size: 9px;
	font-weight: bold;
	padding-bottom: 1px;
	position: absolute;
	right: 0;
	top: 5px;
	z-index: 101;
}

.cf-item-count span {
	background-color: #F03D25;
	border-color: #DD3822;
	border-radius: 2px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	border-right: 1px solid #DD3822;
	border-style: none solid solid;
	border-width: 0 1px 1px;
	display: block;
	padding: 1px 1px 0;
}

.cf-item-mark {
	background: transparent url(img/check.png) no-repeat left 3px;
	padding-left: 25px;
	display: inline-block;
	line-height: 32px;
	height: 32px;
	white-space: nowrap;
}

.cf-item-mark.active {
	background-position: left -22px;
}

.cf-item-filter {
	overflow: auto;
}

.cf-item-filter>* {
	float: left;
	margin-right: 5px;
}

.cf-item-filter #mod_search {
	margin: 0px !important;
}

.cf-item-filter .cf_button_search {
	min-height: 0px !important;
	height: 22px;
	line-height: 23px;
}

.cf-item-pages {
	overflow: auto;
	padding: 5px;
	margin-top: 5px;
	background-color: #0f1922;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	color: #FFFFFF;
}

.cf-entries-wrap {
	margin-top: 10px;
}

.cf-item-pages span>a {
	margin-left: 5px;
	float: left;
	color: #FFFFFF;
	font-weight: bold;
	display: inline-block;
	padding-left: 5px;
	padding-right: 5px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	border-radius: 2px;
}

.cf-item-pages>span {
	float: left;
}

.cf-item-pages span>a.next {
	font-weight: normal;
	font-size: 12px;
}

.cf-item-pages span>a.active {
	background-color: #FFFFFF;
	color: #0f1922;
}

.cf-item-pages span>a:hover {
	background-color: #698EB3;
	color: #FFFFFF;
}

.star {
	display: inline-block;
	background: url(img/star-spr.png) no-repeat center bottom;
	width: 16px;
	height: 16px;
}

.star.star-active {
	background-position: center top;
}

.cf-item-img.cf-item-generated {
	background-color: #263755;
}

.cf-item-img.cf-item-generated:hover img {
	-moz-opacity: 0.8;
	filter: alpha(opacity =       80);
	opacity: 0.8;
}

.remove_finalist {
	color: #FF0000 !important;
	border-color: #FF0000 !important;
}

#admin_edit_message {
	position: absolute;
	width: 100%;
	height: 100%;
	left: 0px;
	top: 0px;
	background-color: #fff;
	z-index: 200;
}

#admin_edit_message>div {
	position: absolute;
	width: 300px;
	left: 50%;
	top: 50%;
	padding-top: 20px;
	padding-bottom: 20px;
	margin-left: -150px;
	margin-top: 500px;
	text-align: center;
	vertical-align: middle;
	background-color: #263755;
	color: #ffffff;
	font-size: 13px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
}

.red {
	color: #faa;
}

.gre {
	color: #afa;
}

.blu {
	color: #aaf;
}

.yel {
	color: #ffa;
}
</style>