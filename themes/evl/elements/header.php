<!DOCTYPE html>
<html>
    <head>
        
        <?PHP  
            $html = Loader::helper('html');
            $view = View::getInstance();
            $view->addHeaderItem( $html->javascript('jquery.ui.js') );
            $view->addHeaderItem( $html->css('jquery.ui.css') );
            Loader::element('header_required'); ?>
        <link rel="stylesheet" type="text/css" href="<?php print $this->getStyleSheet('main.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php print $this->getStyleSheet('typography.css'); ?>" />
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
        
        <!-- Internet Explorer HTML5 enabling script: -->
        <!--[if IE]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

            <style type="text/css">

                .clear {
                    zoom: 1;
                    display: block;
                }
            </style>
        <![endif]-->
      
    </head>
    <body>
                <section id="page">
            <header>
                <div id="evl-logo" class="header-image">
                                        <?PHP  
			$a = new GlobalArea('Header_Main_Logo');
			$a->setBlockLimit(1);
			$a->display($c);
			?>
                </div>
                <div id="evl-title" class="header-image">
                                        <?PHP  
			$a = new GlobalArea('Header_Title');
			$a->setBlockLimit(1);
			$a->display($c);
			?>
                </div>
                <div id="gk-logo" class="header-image">
                                        <?PHP  
			$a = new GlobalArea('Header_Second_Logo');
			$a->setBlockLimit(1);
			$a->display($c);
			?>
                </div>
                <div id="navarea" class="drop_shadow">
                    <?PHP  
			$a = new GlobalArea('Header');
			$a->setBlockLimit(1);
			$a->display($c);
			?>
                </div>
            </header>