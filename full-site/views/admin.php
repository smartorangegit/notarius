<!DOCTYPE html>
<html>
<head>
    <title><?//=Config::get('site_name')?>  <?=__('lng.admin-title')?> - <?=App::getRouter()->getAction();?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/admin-style.css">
</head>
<body>

<div class="new-menu">
	<div class='swanky'>
	  <!-- /////////// Begin Dropdown //////////// -->
	  <div class='swanky_wrapper'>
		<!-- <input id='Home' name='radio' type='radio'> -->
	    <label>
	      <img src='/img/icons/dash.png'>
	      	<a class="menu__link" href="/admin/"><?=__('lng.admin')?></a>
	      <div class='bar'></div>
	    </label>
		
		<?php if ( Session::get('login') ) { ?>
	    <label <?php if( App::getRouter()->getController() == 'pages' ) {?>class="active"<?php } ?>>
	      <img src='/img/icons/dash.png'>
	      	<a class="menu__link" href="/admin/pages/"><?=__('lng.pages')?></a>
	      <div class='bar'></div>
	    </label>

	    <label <?php if( App::getRouter()->getController() == 'contacts' ) {?>class="active"<?php } ?>>
	      <img src='/img/icons/mess.png'>
	      	<a class="menu__link" href="/admin/contacts/"><?=__('lng.queries')?></a>
	      <div class='bar'></div>
	    </label>

         <label <?php if( App::getRouter()->getController() == 'contacts' ) {?>class="active"<?php } ?>>
	      <img src='/img/icons/mess.png'>
	      	<a class="menu__link" href="/admin/contacts/info/">Контактная информация в футере</a>
	      <div class='bar'></div>
	    </label>
		
		<input id='Dashboard' name='radio' type='radio'>
	    <label class="drop-menu-item-hide" for='Dashboard' <?php if( App::getRouter()->getController() == 'dashboard' ) {?>class="active"<?php } ?>>
	      <img src='/img/icons/dash.png'>
	      <span class="menu__link"><?=__('lng.dashboard')?></span>
	      <div class='lil_arrow'></div>
	      <div class='bar'></div>
	      <div class='swanky_wrapper__content'>
	        <ul>
	          <li <?php if( App::getRouter()->getAction() == 'all' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/dashboard/all"><?=__('lng.dashboard-all')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getAction() == 'processed' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/dashboard/processed"><?=__('lng.dashboard-processed')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getAction() == 'appointed' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/dashboard/appointed"><?=__('lng.dashboard-appointed')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getAction() == 'done' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/dashboard/done"><?=__('lng.dashboard-done')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getAction() == 'canceled' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/dashboard/canceled"><?=__('lng.dashboard-canceled')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getController() == 'dashboard' && App::getRouter()->getAction() == 'index' ) {?>class="active"<?php } ?>>
	            <a class="menu__link" href="/admin/dashboard/"><?=__('lng.dashboard-new')?></a>
	          </li>
	        </ul>
	      </div>
	    </label>

	    <input id='List' name='radio' type='radio'>
	    <label class="drop-menu-item-hide" for='List'>
	     <img src='/img/icons/users.png'>
	      <span class="menu__link"><?=__('lng.users-list')?></span>
	      <div class='lil_arrow'></div>
	      <div class='bar'></div>
	      <div class='swanky_wrapper__content'>
	        <ul>
	          <li <?php if( App::getRouter()->getAction() == 'list_c_users' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/users/list_c_users/"><?=__('lng.list-c-users')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getAction() == 'list_notary' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/users/list_notary/"><?=__('lng.list-notaries')?></a>
	          </li>
	        </ul>
	      </div>
	    </label>

		<label  <?php if( App::getRouter()->getAction() == 'transactionscat' ) {?>class="active"<?php } ?>>
	      <img src='/img/icons/dash.png'>
	      	<a class="menu__link" href="/admin/users/transactionscat/"><?=__('lng.transactionscat')?></a>
	      <div class='bar'></div>
	    </label>

            <label  <?php if( App::getRouter()->getController() == 'discounts' ) {?>class="active"<?php } ?>>
                <img src='/img/icons/dash.png'>
                <a class="menu__link" href="/admin/discounts/">Скидки и Акции</a>
                <div class='bar'></div>
            </label>


	    <label  <?php if( App::getRouter()->getAction() == 'services' ) {?>class="active"<?php } ?>>
	      <img src='/img/icons/dash.png'>
	      	<a class="menu__link" href="/admin/users/services/"><?=__('lng.services')?></a>
	      <div class='bar'></div>
	    </label>

	    <label  <?php if( App::getRouter()->getAction() == 'complementary' ) {?>class="active"<?php } ?>>
	      <img src='/img/icons/dash.png'>
	      	<a class="menu__link" href="/admin/users/complementary/"><?=__('lng.complementary')?></a>
	      <div class='bar'></div>
	    </label>

	    <label <?php if( App::getRouter()->getAction() == 'tax' ) {?>class="active"<?php } ?>>
	      <img src='/img/icons/dash.png'>
	      	<a class="menu__link" href="/admin/users/tax/"><?=__('lng.tax')?></a>
	      <div class='bar'></div>
	    </label>

	    <input id='Locations' name='radio' type='radio'>
	    <label class="drop-menu-item-hide" for='Locations'>
	      <img src='/img/icons/dash.png'>
	      <span class="menu__link"><?=__('lng.location-title')?></span>
	      <div class='lil_arrow'></div>
	      <div class='bar'></div>
	      <div class='swanky_wrapper__content'>
	        <ul>
	          <li <?php if( App::getRouter()->getAction() == 'locations' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/users/locations/"><?=__('lng.location')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getAction() == 'metro' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/users/metro/"><?=__('lng.metro')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getAction() == 'area' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/users/area/"><?=__('lng.area')?></a>
	          </li>
	          <li <?php if( App::getRouter()->getAction() == 'mikro_area' ) {?>class="active"<?php } ?>>
	          	<a class="menu__link" href="/admin/users/mikro_area/"><?=__('lng.mikro_area')?></a>
	          </li>
	        </ul>
	      </div>
	    </label>

         <label <?php if( App::getRouter()->getController() == 'rating' ) {?>class="active"<?php } ?>>
                <img src='/img/icons/dash.png'>
                <a class="menu__link" href="/admin/rating/"><?=__('lng.rating')?></a>
                <div class='bar'></div>
        </label>

        <label <?php if( App::getRouter()->getAction() == 'groups' ) {?>class="active"<?php } ?>>
            <img src='/img/icons/dash.png'>
            <a class="menu__link" href="/admin/users/groups/"><?=__('lng.groups')?></a>
            <div class='bar'></div>
        </label>
	    <label>
	      <img src='/img/icons/dash.png'>
	      	<a class="menu__link" href="/admin/users/logout"><?=__('lng.logout')?></a>
	      <div class='bar'></div>
	    </label>
	    <?php } ?>
		</div>
	</div>
</div>

<div class="wrapper">
    <div class="starter-template">
        <?php if( Session::hasFlash() ){ ?>
            <div class="alert alert-info" role="alert">
                <?php Session::flash(); ?>
            </div>
        <?php } ?>
        <?=$data['content']?>
    </div>
</div>





<? /*<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbars">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> 
            </button>
            <a class="navbar-brand" href="/admin/"><?=Config::get('site_name')?> - <?=__('lng.admin')?></a>
        </div>
        <div id="navbars" class="collapse navbar-collapse">
            <?php if ( Session::get('login') ) { ?>
                <ul class="nav navbar-nav">
                    <li <?php if( App::getRouter()->getController() == 'pages' ) {?>class="active"<?php } ?>><a href="/admin/pages/">Pages</a></li>
                    <li <?php if( App::getRouter()->getController() == 'contacts' ) {?>class="active"<?php } ?>><a href="/admin/contacts/">Queries List</a></li>
                    <li class="dropdown"<?php if( App::getRouter()->getController() == 'dashboard' ) {?>class="active"<?php } ?>>
                        <a href="/admin/dashboard/" class="dropdown-toggle" type="button" data-toggle="dropdown">Dashboard<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li <?php if( App::getRouter()->getAction() == 'all' ) {?>class="active"<?php } ?>><a href="/admin/dashboard/all">All</a></li>
                            <li <?php if( App::getRouter()->getAction() == 'processed' ) {?>class="active"<?php } ?>><a href="/admin/dashboard/processed">Processed</a></li>
                            <li <?php if( App::getRouter()->getAction() == 'appointed' ) {?>class="active"<?php } ?>><a href="/admin/dashboard/appointed">Appointed</a></li>
                            <li <?php if( App::getRouter()->getAction() == 'done' ) {?>class="active"<?php } ?>><a href="/admin/dashboard/done">Done</a></li>
                            <li <?php if( App::getRouter()->getAction() == 'canceled' ) {?>class="active"<?php } ?>><a href="/admin/dashboard/canceled">Canceled</a></li>
                            <li <?php if( App::getRouter()->getController() == 'dashboard' && App::getRouter()->getAction() == 'index' ) {?>class="active"<?php } ?>>
                                <a href="/admin/dashboard/">New</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown"<?php if( App::getRouter()->getAction() == 'list' ) {?>class="active"<?php } ?>>
                        <a href="#" class="dropdown-toggle" type="button" data-toggle="dropdown">Users List <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li <?php if( App::getRouter()->getAction() == 'list_c_users' ) {?>class="active"<?php } ?>><a href="/admin/users/list_c_users/">Users List</a></li>
                            <li <?php if( App::getRouter()->getAction() == 'list_notary' ) {?>class="active"<?php } ?>><a href="/admin/users/list_notary/">Notary List</a></li>
                        </ul>
                    </li>
                    <li <?php if( App::getRouter()->getAction() == 'transactionscat' ) {?>class="active"<?php } ?>><a href="/admin/users/transactionscat/">Category of services</a></li>
                    <li <?php if( App::getRouter()->getAction() == 'services' ) {?>class="active"<?php } ?>><a href="/admin/users/services/">Services</a></li>
                    <li <?php if( App::getRouter()->getAction() == 'complementary' ) {?>class="active"<?php } ?>><a href="/admin/users/complementary/">Complementary Services</a></li>
                    <li <?php if( App::getRouter()->getAction() == 'tax' ) {?>class="active"<?php } ?>><a href="/admin/users/tax/">Tax</a></li>
                    <li class="dropdown"<?php if( App::getRouter()->getAction() == 'locations' ) {?>class="active"<?php } ?>>
                        <a href="#" class="dropdown-toggle" type="button" data-toggle="dropdown">Locations<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li <?php if( App::getRouter()->getAction() == 'locations' ) {?>class="active"<?php } ?>><a href="/admin/users/locations/">City</a></li>
                            <li <?php if( App::getRouter()->getAction() == 'metro' ) {?>class="active"<?php } ?>><a href="/admin/users/metro/">metro</a></li>
                            <li <?php if( App::getRouter()->getAction() == 'area' ) {?>class="active"<?php } ?>><a href="/admin/users/area/">area</a></li>
                            <li <?php if( App::getRouter()->getAction() == 'mikro_area' ) {?>class="active"<?php } ?>><a href="/admin/users/mikro_area/">mikro area</a></li>
                        </ul>
                    </li>
                    <li><a href="/admin/users/logout">Logout</a></li>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>*/?>

<? php /*<nav class="navbar navbar-inverse navbar-fixed-bottom">
    <div class="container-fluid">
        <footer class="footer">
            <div class="container">
                <!--                <strong> </br> <p class="text-muted"> Copyright .Mate 2015</p></strong>-->
                <span style="color: lightgrey; font-size: 15px; padding: 13px;">Сайт разработан</span>
                <a href="http://smartorange.com.ua/" ><img src="/webroot/img/icons/logo-smart.png" style="width: 111px;"></a>

            </div>
        </footer>
    </div>
</nav> */?>




<?if(App::getRouter()->getAction() == 'list_notary' ){?>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhMNSbFVMdX7nG3FT8okq6bgnQVja33ug&libraries=places&callback=initMap">
</script>
<?}?>
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="/js/admin.js"></script>

</body>
</html>