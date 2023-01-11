<?php ?>
<!DOCTYPE HTML>
<html lang="<?php language_attributes(); ?>">

<head>
    <!--=============== basic  ===============-->
    <meta charset="<?php bloginfo('charset'); ?>">
    <title>Vbook - Creative vCard Resume Portflio Template</title>
    <meta name="robots" content="index, follow"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <!--=============== css  ===============-->
    <link type="text/css" rel="stylesheet" href="<?php echo URI_ASSETS ?>css/plugins.css">
    <link type="text/css" rel="stylesheet" href="<?php echo URI_ASSETS ?>css/style.css">
    <link type="text/css" rel="stylesheet" href="<?php echo URI_ASSETS ?>css/color.css">
    <!--=============== favicons ===============-->
    <link rel="shortcut icon" href="<?php echo URI_ASSETS ?>images/favicon.ico">
    <?php wp_head(); ?>
</head>

<body<?php body_class(); ?>>
<?php
if (function_exists('wp_body_open')) {
    wp_body_open();
} ?>
<!--Loader -->
<div class="body-preload">
    <div class="pl-spinner2"><span></span></div>
</div>
<!-- loader end  -->
<!-- main start  -->
<div id="main">
    <!--main-container -->
    <div class="main-container">
        <!--header -->
        <header class="main-header">
            <div class="header-titile">
                <h1>سارا صفرزاده</h1>
                <h4>طراح وب سایت</h4>
            </div>
            <a data-src="<?php echo URI_ASSETS ?>images/main.jpg" class="image-popup header-popup color-bg"><i
                        class="fal fa-plus"></i></a>
            <div class="header-titile-img">
                <div class="bg" data-bg="<?php echo URI_ASSETS ?>images/main.jpg"></div>
            </div>
            <div class="main-menu-wrap">
                <!-- nav -->
                <nav class="nav-inner fl-wrap" id="menu">
                    <ul>
                        <li><a href="index.html" class="ajax"><i class="fal fa-home"></i> خانه</a></li>
                        <li><a href="resume.html" class="ajax"><i class="fal fa-address-card"></i> رزومه</a></li>
                        <li><a href="portfolio.html" class="ajax"><i class="fal fa-images"></i> نمونه کار</a></li>
                        <li><a href="contacts.html" class="ajax"><i class="fal fa-envelope"></i> تماس</a></li>
                        <li><a href="blog.html" class="ajax"><i class="fal fa-book"></i> وبلاگ</a></li>
                        <li>
                            <a href="#"><i class="fal fa-layer-group"></i> صفحات</a>
                            <!--level 2 -->
                            <ul>
                                <li><a href="portfolio-single.html" class="ajax">جزئیات نمونه کار - 1</a></li>
                                <li><a href="portfolio-single2.html" class="ajax">جزئیات نمونه کار - 2</a></li>
                                <li><a href="blog-single.html" class="ajax">جزئیات وبلاگ</a></li>
                                <li><a href="404.html" class="ajax">404</a></li>
                            </ul>
                            <!--level 2 end -->
                        </li>
                    </ul>
                </nav>
                <!-- nav end-->
            </div>
            <a href="#" download="" class="header_btn gradient-bg"><i class="fas fa-download"></i> دانلود رزومه</a>
        </header>
        <!--header end-->