<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
<!--    <link rel="stylesheet" href="/css/plug/main.css">-->
    <link rel="import" href="symbol-defs.html">
    <title><?=Config::get('site_name')?> - <?=App::getRouter()->getController()?></title>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.13/jquery.mask.js"></script>
</head>
<body>
<!-- include SVG-sprite -->
<? include(ROOT.DS.'webroot'.DS.'sprite.html');?>

<div w3-include-html="/webroot/sprite.html"></div>
<!-- /// -->

<!-- HEAD -->

<?
$param = App::getRouter()->getParams();
$contr = App::getRouter()->getController();

                try {
                    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
                    $dbh -> exec("SET CHARACTER SET utf8");
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sth = $dbh->query("select * from se_services WHERE `top`='1'");
                    $gsh = $sth->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    $status = 'Fail: ' . $e->getMessage();
                }

if(strlen($param[0])==0 && $contr == 'main' ){ ?>
    <section class="head">
    <div class="head-content">

    <section class="popup-form">
        <div class="form-wrap popup-form-wrap">
            <form action="" class="form form_vertical" method="post">
              <div class="form-container popup-form-container">
                <div class="form__select-wrap popup-form-input">
                  <input type="text" id="select" name="service" class="form__input form__select-info" placeholder="Выберите услугу" style="display: none">
                  <input id="form__select-label_popup" type="text" class="form__input form__select popup-form-input__input" placeholder="Выберите услугу" required disabled>
                  <label for="form__select-label_popup" class="form__label"></label>
                </div>
                <div class="form__date-wrap popup-form-input">
                  <input id="form__date-label_popup" class="form__input form__date popup-form-input__input" type="date" name="dateOf" placeholder="Выберите дату" required>
                  <label for="form__date-label_popup" class="form__label"></label>
                </div>
                <div class="form__time-wrap popup-form-input">
                  <input id="form__time-label_popup" class="form__input form__time popup-form-input__input" type="time" name="timeOf" placeholder="Выберите время" required>
                  <label for="form__time-label_popup" class="form__label"></label>
                </div>
                <input class="form__input form__phone popup-form-input popup-form-input__phone" type="text" placeholder="Укажите Ваш телефон" name="regNum" required>
              </div>
              <div class="popup-form__checkbox-wrap">
                <input type="checkbox" id="popup-form__checkbox" class="popup-form__checkbox form__checkbox" name="homeCheck">
                <label for="popup-form__checkbox" class="form__checkbox-label"></label>
                <p class="form__text">С выездом</p>
              </div>
              <button class="form__button popup-form__button" type="submit">Подобрать нотариуса</button>
            </form>
            <div class="select-menu form-select-menu" style="top: 100px!important;">
                <ul class="select-menu-list">
                    <?foreach($gsh as $item){?>
                        <li id="<?=$item['id'];?>" class="select-menu-list__item">
                            <?=$item['name'];?>
                        </li>
                    <?}?>
                </ul>
            </div>
            <img src="/img/cancel.png" alt="cansel" class="popup-form__icon">
        </div>
    </section>

    <div class="header-wrap">
    <div class="header-container">
	    <header class="header">
	        <svg class="hamburger-open""><use xlink:href="#menu"></use></svg>
	        <a href="/" class="header__link"><img src="/img/zavireno.png" alt="image" class="header__image"></a>
	        <div class="hamburger-content">
	            <ul class="menu header__menu">
	                <li class="menu__item">
	                    <a href="/pages/view/about-us/" class="menu__link <?if($param[0]=='about-us'){echo 'menu__link_active';}?>">
	                        <?=__('lng.about-us')?></a>
	                </li>
	                <li class="menu__item menu__item-drop">
	                    <a href="/pages/view/how-it-works/" class="menu__link menu__link-drop <?if($param[0]=='how-it-works'){echo 'menu__link_active';}?>">
	                        <?=__('lng.how-it-works')?></a>
	                    <ul class="menu-drop menu-drop_border_gray">
	                        <li class="menu-drop__item">
	                            <a href="#tutorial" class="menu-drop__link">для клиентов</a>
	                        </li>
	                        <li class="menu-drop__item">
	                            <a href="/pages/view/how-it-works/" class="menu-drop__link">для нотариусов</a>
	                        </li>
	                    </ul>
	                </li>
	                <li class="menu__item">
	                    <a href="/pages/view/advantages/" class="menu__link <?if($param[0]=='advantages'){echo 'menu__link_active';}?>">
	                        <?=__('lng.advantages')?></a>
	                </li>
	                <li class="menu__item">
	                    <a href="/services/" class="menu__link <?if($contr=='services'){echo 'menu__link_active';}?>">
	                        <?=__('lng.about')?></a>
	                </li>
	                <li class="menu__item">
	                    <a href="/notaries/" class="menu__link <?if($contr=='notaries'){echo 'menu__link_active';}?>">
	                        <?=__('lng.notaries')?></a>
	                </li>
	                <li class="menu__item">
	                    <a href="/pages/view/FAQ/" class="menu__link <?if($param[0]=='FAQ'){echo 'menu__link_active';}?>">
	                        <?=__('lng.faq')?></a>
	                </li>
	                <li class="menu__item">
	                    <a href="/pages/view/contacts/" class="menu__link <?if($param[0]=='contacts'){echo 'menu__link_active';}?>">
	                        <?=__('lng.contacts')?></a>
	                </li>
	            </ul>
                <button class="header__button header__button-question">Подобрать нотариуса</button>
                <div class="social header__social">
                    <ul class="social-list">
                        <li class="social-list__item">
                            <a href="#" class="social-list__link">
                                <svg class="social-list__icon social-list__icon_white social-list__icon_viber"><use xlink:href="#viber"></use></svg>
                            </a>
                        </li>
                        <li class="social-list__item">
                            <a href="#" class="social-list__link">
                                <svg class="social-list__icon social-list__icon_white social-list__icon_facebook"><use xlink:href="#facebook"></use></svg>
                            </a>
                        </li>
                        <li class="social-list__item">
                            <a href="#" class="social-list__link">
                                <svg class="social-list__icon social-list__icon_white social-list__icon_telegram"><use xlink:href="#telegram"></use></svg>
                            </a>
                        </li>
                    </ul>
                </div>
	            <svg class="hamburger-cansel"><use xlink:href="#cancel"></use></svg>
	        </div>
	        <button class="header__button header__button-enter">
	            <? $name=Session::get('name');
	            if(!empty($name)){?>
	                <a href="/<?=Session::get('role')?>/"><?=Session::get('name')?></a>
	            <?}else{echo 'Вход';}?>
	        </button>

	        <div class="sign-in">
	            <div class="sign-in__button-wrap">
	                <button class="sign-in__button sign-in-user__button sign-in__button_active">Клиент</button>
	                <button class="sign-in-notary__button sign-in__button">Нотариус</button>
	            </div>

	            <form action="" method="post">
	            <div class="sign-in-user-form-wrap">
	                <label for="sign-in-user-login"  class="sign-in__label">Логин</label>
	                <input id="sign-in-user-login"  name="login" class="sign-in__input" type="text" required>

	                <label for="sign-in-user-password" class="sign-in__label">Пароль</label>
	                <input id="sign-in-user-password" name="password" class="sign-in__input" type="password" required>
	                <input type="hidden" name="type-user" value="user">

	                <div class="sign-in-regist">
	                    <button type="submit" class="site-button sign-in-regist__button">Войти</button>
	                    <a href="" class="sign-in-regist__link">или зарегестрироватся</a>
	                    <a href="/user/users/recovery/" class="sign-in__link">Забыли пароль?</a>
	                </div>
	            </div>
	            </form>
	            <form action="" method="post">
	            <div class="sign-in-notary-form-wrap">
	                <label for="sign-in-notary-login" class="sign-in__label">Логин</label>
	                <input id="sign-in-notary-login"  name="login" class="sign-in__input" type="text" required>

	                <label for="sign-in-notary-password" class="sign-in__label">Пароль</label>
	                <input id="sign-in-notary-password" name="password" class="sign-in__input" type="password" required>
	                <input type="hidden" name="type-user" value="notary">

	                <div class="sign-in-regist">
	                    <button type="submit" class="site-button sign-in-regist__button">Войти</button>
	                    <a href="/notary/users/register/" class="sign-in-regist__link" id="mnr-js">или зарегестрироватся</a>
	                    <a href="/notary/users/recovery/" class="sign-in__link">Забыли пароль?</a>
	                </div>
	            </div>
	            </form>
	        </div>

	        <div class="log-in">
	            <form method="post" action="">
	            <div class="log-in__button-wrap">
	                <button class="log-in__button log-in__button_active">Клиент</button>
	                <button class="log-in__button"><a href="/notary/users/register/">Нотариус</a></button>
	            </div>

	            <label for="log-in-login" class="log-in__label">Логин</label>
	            <input id="log-in-login" name="login"  class="log-in__input" type="text" required>
	            <input type="hidden" id="fast-reg" name="fast-reg" class="form-control" value="1">
	            <span class="descript__text_bold" style="color: #4d4d4d;">Для быстрой регистрации введите ваш номер</span>


	<!--            <label for="log-in-login" class="log-in__label">Пароль</label>-->
	<!--            <input id="log-in-login" class="log-in__input" type="text">-->

	            <div class="log-in-regist">
	                <button type="submit" class="site-button log-in-regist__button">Регистрация</button>
	            </div>
	            </form>
	        </div>

	    </header>
	</div>
	</div>
    <div class="head__container">
    <div class="container">
<?}else{?>
    <section class="head gray-head">
    <?/*     <div class="header-wrap">
        <div class="container">
            <header class="header services-page-header">
                <svg class="hamburger-open hamburger-open_gray""><use xlink:href="#menu"></use></svg>
                <a href="/" class="header__link"><img src="/img/zavireno_gray.png" alt="image" class="header__image"></a>
                <div class="hamburger-content">
                <button class="header__button header__button-question header__button_gray">Подобрать нотариуса</button>
                    <div class="social header__social">
                        <ul class="social-list">
                            <li class="social-list__item">
                                <a href="#" class="social-list__link">
                                    <svg class="social-list__icon social-list__icon_viber"><use xlink:href="#viber"></use></svg>
                                </a>
                            </li>
                            <li class="social-list__item">
                                <a href="#" class="social-list__link">
                                    <svg class="social-list__icon social-list__icon_facebook"><use xlink:href="#facebook"></use></svg>
                                </a>
                            </li>
                            <li class="social-list__item">
                                <a href="#" class="social-list__link">
                                    <svg class="social-list__icon social-list__icon_telegram"><use xlink:href="#telegram"></use></svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <ul class="menu header__menu">
                        <li class="menu__item">
                            <a href="/pages/view/about-us/" class="menu__link menu__link_gray <?if($param[0]=='about-us'){echo 'menu__link_active';}?> "><?=__('lng.about-us')?></a>
                        </li>
                        <li class="menu__item menu__item-drop menu__item-drop_gray">
                            <a href="/pages/view/how-it-works/" class="menu__link menu__link-drop menu__link_gray
                            <?if($param[0]=='how-it-works'){echo 'menu__link_active';}?>"><?=__('lng.how-it-works')?></a>
                            <ul class="menu-drop menu-drop_border_gray">
                                <li class="menu-drop__item menu-drop__item_border_gray">
                                    <a href="/#tutorial"  class="menu-drop__link">для клиентов</a>
                                </li>
                                <li class="menu-drop__item">
                                    <a href="/pages/view/how-it-works/" class="menu-drop__link">для нотариусов</a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu__item">
                            <a href="/pages/view/advantages/" class="menu__link menu__link_gray <?if($param[0]=='advantages'){echo 'menu__link_active';}?>">
                                <?=__('lng.advantages')?></a>
                        </li>
                        <li class="menu__item">
                            <a href="/services/" class="menu__link menu__link_gray <?if($contr=='services'){echo 'menu__link_active';}?>">
                                <?=__('lng.about')?></a>
                        </li>
                        <li class="menu__item">
                            <a href="/notaries/" class="menu__link menu__link_gray <?if($contr=='notaries'){echo 'menu__link_active';}?>">
                                <?=__('lng.notaries')?></a>
                        </li>
                        <li class="menu__item">
                            <a href="/pages/view/FAQ/" class="menu__link menu__link_gray <?if($param[0]=='FAQ'){echo 'menu__link_active';}?>" >
                                <?=__('lng.faq')?></a>
                        </li>
                        <li class="menu__item">
                            <a href="/pages/view/contacts/" class="menu__link menu__link_gray <?if($param[0]=='contacts'){echo 'menu__link_active';}?>">
                                <?=__('lng.contacts')?></a>
                        </li>
                    </ul>
                    <svg class="hamburger-cansel"><use xlink:href="#cancel"></use></svg>
                </div>
                <button class="header__button header__button-enter header__button_gray">
                    <? $name=Session::get('name');
                    if(!empty($name)){?>
                        <a href="/<?=Session::get('role')?>/"><?=Session::get('name')?></a>
                    <?}else{echo 'Вход';}?>
                </button>

                <div class="sign-in sign-in_border_gray header__sign-in">
                  <div class="sign-in__button-wrap">
                    <button class="sign-in__button sign-in-user__button sign-in__button_active">Клиент</button>
                    <button class="sign-in-notary__button sign-in__button">Нотариус</button>
                  </div>

                  <div class="sign-in-user-form-wrap">
                      <form method="post" action="" >
                        <label for="sign-in-user-login" class="sign-in__label">Логин</label>
                        <input id="sign-in-user-login" name="login" class="sign-in__input" type="text" required>

                        <label for="sign-in-user-password" class="sign-in__label">Пароль</label>
                        <input id="sign-in-user-password" name="password" class="sign-in__input" type="password" required>
                        <input type="hidden" name="type-user" value="user">

                        <div class="sign-in-regist">
                          <button type="submit" class="site-button sign-in-regist__button">Войти</button>
                          <a href="" class="sign-in-regist__link">или зарегестрироватся</a>
                          <a href="/user/users/recovery/" class="sign-in__link">Забыли пароль?</a>
                        </div>
                      </form>
                  </div>

                  <div class="sign-in-notary-form-wrap">
                      <form method="post" action="">
                        <label for="sign-in-notary-login" class="sign-in__label">Логин</label>
                        <input id="sign-in-notary-login" name="login" class="sign-in__input" type="text" required>

                        <label for="sign-in-notary-password" class="sign-in__label">Пароль</label>
                        <input id="sign-in-notary-password" name="password" class="sign-in__input" type="password" required>
                        <input type="hidden" name="type-user" value="notary">

                        <div class="sign-in-regist">
                          <button type="submit" class="site-button sign-in-regist__button">Войти</button>
                          <a href="/notary/users/register/" class="sign-in-regist__link" id="mnr-js">или зарегестрироватся</a>
                          <a href="/notary/users/recovery/" class="sign-in__link">Забыли пароль?</a>
                        </div>
                      </form>
                  </div>
                </div>

                <div class="log-in sign-in_border_gray">
                    <div class="log-in__button-wrap">
                        <button class="log-in__button log-in__button_active">Клиент</button>
                        <button class="log-in__button"><a href="/notary/users/register/">Нотариус</a></button>
                    </div>
                        <form action="" method="post">
                            <label for="log-in-login" class="log-in__label">Логин</label>
                            <input id="log-in-login" name="login" class="log-in__input" type="text" required>
                            <span class="descript__text_bold" style="color: #4d4d4d;">Для быстрой регистрации введите ваш номер</span>

<!--                            <label for="log-in-login" class="log-in__label">Пароль</label>
<!--                            <input id="log-in-login" name="password" class="log-in__input" type="text">-->

                            <input type="hidden" id="fast-reg" name="fast-reg" class="form-control" value="1">
                            <div class="log-in-regist">
                                <button type="submit" class="site-button log-in-regist__button">Регистрация</button>
                            </div>
                        </form>
            </header>
        </div>
        </div> */?>
        <section class="popup-form">
        <div class="form-wrap popup-form-wrap">
            <form action="" class="form form_vertical" method="post">
              <div class="form-container popup-form-container">
                <div class="form__select-wrap popup-form-input">
                  <input type="text" id="select" name="service" class="form__input form__select-info" placeholder="Выберите услугу" style="display: none">
                  <input id="form__select-label_popup" type="text" class="form__input form__select popup-form-input__input" placeholder="Выберите услугу" required disabled>
                  <label for="form__select-label_popup" class="form__label"></label>
                </div>
                <div class="form__date-wrap popup-form-input">
                  <input id="form__date-label_popup" class="form__input form__date popup-form-input__input" type="date" name="dateOf" placeholder="Выберите дату" required>
                  <label for="form__date-label_popup" class="form__label"></label>
                </div>
                <div class="form__time-wrap popup-form-input">
                  <input id="form__time-label_popup" class="form__input form__time popup-form-input__input" type="time" name="timeOf" placeholder="Выберите время" required>
                  <label for="form__time-label_popup" class="form__label"></label>
                </div>
                <input class="form__input form__phone popup-form-input popup-form-input__phone" type="text" placeholder="Укажите Ваш телефон" name="regNum" required>
              </div>
              <div class="popup-form__checkbox-wrap">
                <input type="checkbox" id="popup-form__checkbox" class="popup-form__checkbox form__checkbox" name="homeCheck">
                <label for="popup-form__checkbox" class="form__checkbox-label"></label>
                <p class="form__text">С выездом</p>
              </div>
              <button class="form__button popup-form__button" type="submit">Подобрать нотариуса</button>
            </form>
            <div class="select-menu form-select-menu" style="top: 100px!important;">
                <ul class="select-menu-list">
                    <?foreach($gsh as $item){?>
                        <li id="<?=$item['id'];?>" class="select-menu-list__item">
                            <?=$item['name'];?>
                        </li>
                    <?}?>
                </ul>
            </div>
            <img src="/img/cancel.png" alt="cansel" class="popup-form__icon">
        </div>
    </section>

    <div class="header-wrap">
    <div class="header-container">
        <header class="header">
            <svg class="hamburger-open""><use xlink:href="#menu"></use></svg>
            <a href="/" class="header__link"><img src="/img/zavireno.png" alt="image" class="header__image"></a>
            <div class="hamburger-content">
                <ul class="menu header__menu">
                    <li class="menu__item">
                        <a href="/pages/view/about-us/" class="menu__link <?if($param[0]=='about-us'){echo 'menu__link_active';}?>">
                            <?=__('lng.about-us')?></a>
                    </li>
                    <li class="menu__item menu__item-drop">
                        <a href="/pages/view/how-it-works/" class="menu__link menu__link-drop <?if($param[0]=='how-it-works'){echo 'menu__link_active';}?>">
                            <?=__('lng.how-it-works')?></a>
                        <ul class="menu-drop menu-drop_border_gray">
                            <li class="menu-drop__item">
                                <a href="#tutorial" class="menu-drop__link">для клиентов</a>
                            </li>
                            <li class="menu-drop__item">
                                <a href="/pages/view/how-it-works/" class="menu-drop__link">для нотариусов</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu__item">
                        <a href="/pages/view/advantages/" class="menu__link <?if($param[0]=='advantages'){echo 'menu__link_active';}?>">
                            <?=__('lng.advantages')?></a>
                    </li>
                    <li class="menu__item">
                        <a href="/services/" class="menu__link <?if($contr=='services'){echo 'menu__link_active';}?>">
                            <?=__('lng.about')?></a>
                    </li>
                    <li class="menu__item">
                        <a href="/notaries/" class="menu__link <?if($contr=='notaries'){echo 'menu__link_active';}?>">
                            <?=__('lng.notaries')?></a>
                    </li>
                    <li class="menu__item">
                        <a href="/pages/view/FAQ/" class="menu__link <?if($param[0]=='FAQ'){echo 'menu__link_active';}?>">
                            <?=__('lng.faq')?></a>
                    </li>
                    <li class="menu__item">
                        <a href="/pages/view/contacts/" class="menu__link <?if($param[0]=='contacts'){echo 'menu__link_active';}?>">
                            <?=__('lng.contacts')?></a>
                    </li>
                </ul>
                <button class="header__button header__button-question">Подобрать нотариуса</button>
                <div class="social header__social">
                    <ul class="social-list">
                        <li class="social-list__item">
                            <a href="#" class="social-list__link">
                                <svg class="social-list__icon social-list__icon_white social-list__icon_viber"><use xlink:href="#viber"></use></svg>
                            </a>
                        </li>
                        <li class="social-list__item">
                            <a href="#" class="social-list__link">
                                <svg class="social-list__icon social-list__icon_white social-list__icon_facebook"><use xlink:href="#facebook"></use></svg>
                            </a>
                        </li>
                        <li class="social-list__item">
                            <a href="#" class="social-list__link">
                                <svg class="social-list__icon social-list__icon_white social-list__icon_telegram"><use xlink:href="#telegram"></use></svg>
                            </a>
                        </li>
                    </ul>
                </div>
                <svg class="hamburger-cansel"><use xlink:href="#cancel"></use></svg>
            </div>
            <button class="header__button header__button-enter">
                <? $name=Session::get('name');
                if(!empty($name)){?>
                    <a href="/<?=Session::get('role')?>/"><?=Session::get('name')?></a>
                <?}else{echo 'Вход';}?>
            </button>

            <div class="sign-in">
                <div class="sign-in__button-wrap">
                    <button class="sign-in__button sign-in-user__button sign-in__button_active">Клиент</button>
                    <button class="sign-in-notary__button sign-in__button">Нотариус</button>
                </div>

                <form action="" method="post">
                <div class="sign-in-user-form-wrap">
                    <label for="sign-in-user-login"  class="sign-in__label">Логин</label>
                    <input id="sign-in-user-login"  name="login" class="sign-in__input" type="text" required>

                    <label for="sign-in-user-password" class="sign-in__label">Пароль</label>
                    <input id="sign-in-user-password" name="password" class="sign-in__input" type="password" required>
                    <input type="hidden" name="type-user" value="user">

                    <div class="sign-in-regist">
                        <button type="submit" class="site-button sign-in-regist__button">Войти</button>
                        <a href="" class="sign-in-regist__link">или зарегестрироватся</a>
                        <a href="/user/users/recovery/" class="sign-in__link">Забыли пароль?</a>
                    </div>
                </div>
                </form>
                <form action="" method="post">
                <div class="sign-in-notary-form-wrap">
                    <label for="sign-in-notary-login" class="sign-in__label">Логин</label>
                    <input id="sign-in-notary-login"  name="login" class="sign-in__input" type="text" required>

                    <label for="sign-in-notary-password" class="sign-in__label">Пароль</label>
                    <input id="sign-in-notary-password" name="password" class="sign-in__input" type="password" required>
                    <input type="hidden" name="type-user" value="notary">

                    <div class="sign-in-regist">
                        <button type="submit" class="site-button sign-in-regist__button">Войти</button>
                        <a href="/notary/users/register/" class="sign-in-regist__link" id="mnr-js">или зарегестрироватся</a>
                        <a href="/notary/users/recovery/" class="sign-in__link">Забыли пароль?</a>
                    </div>
                </div>
                </form>
            </div>

            <div class="log-in">
                <form method="post" action="">
                <div class="log-in__button-wrap">
                    <button class="log-in__button log-in__button_active">Клиент</button>
                    <button class="log-in__button"><a href="/notary/users/register/">Нотариус</a></button>
                </div>

                <label for="log-in-login" class="log-in__label">Логин</label>
                <input id="log-in-login" name="login"  class="log-in__input" type="text" required>
                <input type="hidden" id="fast-reg" name="fast-reg" class="form-control" value="1">
                <span class="descript__text_bold" style="color: #4d4d4d;">Для быстрой регистрации введите ваш номер</span>


    <!--            <label for="log-in-login" class="log-in__label">Пароль</label>-->
    <!--            <input id="log-in-login" class="log-in__input" type="text">-->

                <div class="log-in-regist">
                    <button type="submit" class="site-button log-in-regist__button">Регистрация</button>
                </div>
                </form>
            </div>

        </header>
    </div>
    </div>
    </section>
<?}?>
<div class="wrapper">

    <div class="starter-template">
        <?php if( Session::hasFlash() ){ ?>
            <div class="alert alert-info" role="alert" style="top: 15%;">
                <?php Session::flash(); ?>
                <script>
                    function func() {
                        $(".alert.alert-info").css( "display","none" );
//                        location.replace('<?//echo $_SERVER['REQUEST_URI']?>//');
                    }
                    setTimeout(func, 3000);
                </script>
            </div>
        <?php
        }
        ?>
        <?=$data['content'];?>
    </div>

</div>

<!-- FOOTER -->
<div class="footer-clear"></div>

<footer class="footer">
    <div class="container">
        <div class="footer-list-wrap">
            <ul class="footer-list footer-services-list">
                <li class="footer-list__item footer-list__heading footer-services-list__heading">
                    Сервис
                </li>
                <li class="footer-list__item">
                    <a href="/pages/view/about-us/" class="footer-list__link">О нас</a>
                </li>
                <li class="footer-list__item">
                    <a href="/pages/view/about-rating/" class="footer-list__link">О рейтинге</a>
                </li>
                <li class="footer-list__item">
                    <a href="/pages/view/for-partners/" class="footer-list__link">Партнерам</a>
                </li>
                <li class="footer-list__item">
                    <a href="/pages/view/terms-of-service/" class="footer-list__link">Правила сервиса</a>
                </li>
                <li class="footer-list__item">
                    <a href="/pages/view/privacy-policy/" class="footer-list__link">Политика конфиденциальности</a>
                </li>
            </ul>

            <ul class="footer-list footer-client-list">
                <li class="footer-list__item footer-list__heading footer-client-list__heading">
                    Клентам
                </li>
                <li class="footer-list__item">
                    <a href="/notaries/" class="footer-list__link">Нотариусы</a>
                </li>
                <li class="footer-list__item">
                    <a href="/pages/view/promotions-and-discounts/" class="footer-list__link">Акции и скидки</a>
                </li>
                <li class="footer-list__item">
                    <a href="/services/" class="footer-list__link">Нотариальные услуги</a>
                </li>
            </ul>

            <ul class="footer-list footer-phone-list">
                <?php
                try {
                    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
                    $dbh -> exec("SET CHARACTER SET utf8");
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sth = $dbh->query("SELECT contact_info.text FROM contact_info");
                    $contInf = $sth->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    $status = 'Fail: ' . $e->getMessage();
                }
                    print_r($contInf[0]['text']);
                ?>
            </ul>
        </div>

        <p class="footer__text">Использование материалов разрешено только при наличии активной ссылки на источник</p>
        <p class="footer__copyright">Zavireno, 2018 Все права защищены.</p>
        <div class="smart-orange">
            <p class="smart-orange__text">Сайт разработан компанией</p>
            <a href="http://smartorange.com.ua/" target="_blanc" class="smart-orange__link">
                <img src="/img/logo1.svg" alt="smart-orange" class="smart-orange__image">
            </a>
        </div>
    </div>
</footer>
<!-- END__FOOTER -->
</body>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
    (function(){ var widget_id = 'NcUNCQRxc3';var d=document;var w=window;function l(){
    var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
    s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}
    if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
</script>
<!-- {/literal} END JIVOSITE CODE -->
<script>
    //$('input[name="login"]').mask('38 000 000 00 00');
    //$('input[name="regNum"]').mask('38 000 000 00 00');
</script>
<script src="/webroot/js/all.js"></script>
</html>

