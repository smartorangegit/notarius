<!DOCTYPE html>
<html>
<head>
    <title><?=Config::get('site_name')?> <?=__('lng.user')?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/upload.css">
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<? include(ROOT.DS.'webroot'.DS.'sprite.html');?>
<body>
<?php //if ( Session::get('login') ) {?>
<section class="head gray-head">
    <div class="header-wrap">
    <div class="container header-container">
        <header class="header cabinet-header">
            <svg class="hamburger-open"><use xlink:href="#menu"></use></svg>
            <a href="/" class="header__link"><img src="/img/zavireno.png" alt="image" class="header__image"></a>
            <div class="hamburger-content">
                <ul class="menu header__menu">
                    <li class="menu__item">
                        <a href="/pages/view/about-us/" class="menu__link">о нас</a>
                    </li>
                    <li class="menu__item menu__item-drop">
                        <a href="#tutorial" class="menu__link menu__link-drop">как это работает</a>
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
                        <a href="/pages/view/advantages/" class="menu__link">преимущества</a>
                    </li>
                    <li class="menu__item">
                        <a href="/services/" class="menu__link">услуги</a>
                    </li>
                    <li class="menu__item">
                        <a href="/notaries/" class="menu__link">нотариусы</a>
                    </li>
                    <li class="menu__item">
                        <a href="/pages/view/FAQ/" class="menu__link">вопросы</a>
                    </li>
                    <li class="menu__item">
                        <a href="/pages/view/contacts/" class="menu__link">контакты</a>
                    </li>
                </ul>
                <button class="header__button header__button-question">Подобрать нотариуса</button>
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
                <svg class="hamburger-cansel"><use xlink:href="#cancel"></use></svg>
            </div>
            <div class="head-client">
                <p class="client-name">
                    <? $name=Session::get('name');
                    if(!empty($name)){?>
                        <a class="client-name__link-name" href="/<?=Session::get('role')?>/"><?=Session::get('name')?></a>
                    <?}else{echo 'Вход';}?>
                </p>
                <ul class="client-menu head__client-menu">
                    <li class="client-menu__item">
                        <a href="/user/" class="client-menu__link">Личные данные</a>
                    </li>
                    <li class="client-menu__item">
                        <a href="/user/#document" class="client-menu__link">Мои документы</a>
                    </li>
                    <li class="client-menu__item">
                        <a href="/user/dashboard/" class="client-menu__link">Мои заказы</a>
                    </li>
                    <li class="client-menu__item">
                        <a href="/user/#my-notary" class="client-menu__link">Мои нотариусы</a>
                    </li>
                    <li class="client-menu__item">
                        <a href="/user/reviews/" class="client-menu__link">Мои отзывы</a>
                    </li>
                    <li class="client-menu__item ">
                        <a href="/user/users/logout" class="client-menu__link client-menu__exit">Выход</a>
                    </li>
                </ul>
            </div>
    </div>
    </header>
    </div>
    </div>
</section>
<?//}?>
<div class="wrapper">

    <div class="starter-template">
        <?php if( Session::hasFlash() ){ ?>
            <div class="alert alert-info" role="alert" style="top: 15%;">
                <?php Session::flash(); ?>
                <script>
                    function func() {
                        $(".alert.alert-info").css( "display","none" );
                        location.replace('<?echo $_SERVER['REQUEST_URI']?>');
                    }
                    setTimeout(func, 3000);
                </script>
            </div>
            <?php
        }
        ?>
            <?=$data['content'];
            try {
                $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
                $dbh -> exec("SET CHARACTER SET utf8");
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sth = $dbh->query("select common_user.userID, common_user.fName, common_user.sName, common_user.mName, common_user.email, users.role,
                                              users.login, COUNT(deal.id) as deals
                                              from common_user
                                              LEFT JOIN `deal` ON common_user.userID=deal.userID
                                              LEFT JOIN `users` ON common_user.mainPhone=users.login
                                              WHERE users.login={$_SESSION['login']}");
                $msg_info = $sth->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $status = 'Fail: ' . $e->getMessage();
            }

            $data = array(
                'msg_info' => $msg_info,
                'status' => $status
            );
            $jivoJS = json_encode($data['msg_info'][0]);
            ?>
    </div>

</div>
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
                <?/*
<!--                <li class="footer-list__item footer-list__heading footer-phone-list__heading">-->
<!--                    Звоните нам-->
<!--                </li>-->
<!--                <li class="footer-list__item">-->
<!--                    С  Пн.-Вс. с 9:00 до 20:00-->
<!--                </li>-->
<!--                <li class="footer-list__item">-->
<!--                    <a href="tel:+3 8 (044) 990 60 08" class="footer-list__link footer-phone-list__link">+3 8 (044) 990 60 08</a>-->
<!--                    <a href="tel:+3 8 (097) 555 99 99" class="footer-list__link">+3 8 (097) 555 99 99</a>-->
<!--                </li>-->
<!--                <li class="footer-list__item">-->
<!--                    <a href="tel:+3 8 (093) 555 99 99" class="footer-list__link footer-phone-list__link">+3 8 (093) 555 99 99</a>-->
<!--                    <a href="tel:+38 (095) 555 99 99" class="footer-list__link">+3 8 (095) 555 99 99</a>-->
<!--                </li>-->*/?>
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
<script src="/js/user.js"></script>
<script src="/js/all.js"></script>
</body>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
    var jivo = JSON.parse('<?php echo $jivoJS; ?>');
    (function(){ var widget_id = 'NcUNCQRxc3';var d=document;var w=window;function l(){
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
        s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}
        if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
        function jivo_onOpen(){
         jivo_api.setContactInfo(
            {
                client_name : jivo.fName+' '+jivo.mName,
                email : jivo.email,
                phone : '+'+jivo.login,
                description: "Роль - "+jivo.role+" | Количество заказов :"+jivo.deals
            });
        }
</script>
<!-- {/literal} END JIVOSITE CODE -->
</html>