<?php
	defined('C5_EXECUTE') or die("Access Denied.");
	$aBlocks = $controller->generateNav();
	$c = Page::getCurrentPage();
	echo("<ul class=\"nav-header\">");

	$nh = Loader::helper('navigation');

	$maxLevels = 3;
        $thisLevel = 0;
        $menul2html = "";
        $menul3html = "";
        $bindjs = "";
	$isFirst = true;
        $endItems = '';
	
	foreach($aBlocks as $ni) {
		$_c = $ni->getCollectionObject();

		if (!$_c->getCollectionAttributeValue('exclude_nav') && $thisLevel <= $maxLevels) {
			$prevLevel = $thisLevel;
			$thisLevel = $ni->getLevel()+1;
                        $id = $_c->getCollectionID();
                        $attrs = $inserts = null;
                        $classes = " menu-element";
                        $isURL = (substr($ni->getURL(), 0, 4)=='http');
			
			if($_c->getCollectionAttributeValue('dont_ajaxify_nav_links')) $classes .= " no-ajaxy";

                        switch ($thisLevel) {
                          case 1:
                               //$startL1 = ($prevLevel==0);
                               $startL2 = FALSE;
                               $startL3 = FALSE;
                               $endL2 = ($prevLevel>1) && ($countL2 > 0);
                               $endL3 = ($prevLevel>2);
                               $lastL1 = $id;
                               $parentID = null;
                               break;
                          case 2:
				$countL2 ++;
                               $startL2 = ($prevLevel<2);
                               $startL3 = FALSE;
                               $endL2 = FALSE;
                               $endL3 = ($prevLevel>2) && ($countL3 > 0);
                               $lastL2 = $id;
                               $parentID = $lastL1;
                               break;
                          case 3:
				$countL3 ++;
                               $startL2 = ($prevLevel<2);
                               $startL3 = ($prevLevel<3);
                               $endL2 = FALSE;
                               $endL3 = FALSE;
                               $lastL3 = $id;
                               $parentID = $lastL2;
                               break;
                          default:
                        }

			$target = $ni->getTarget();
			if ($target != '') {
				$target = 'target="' . $target . '"';
			}
			
			$name = htmlentities($ni->getName(), ENT_QUOTES);
			$title = htmlentities(($name=="Home" ? $c->getCollectionAttributeValue('meta_title') : $name), ENT_QUOTES);

			if ($ni->isActive($c) || strpos($c->getCollectionPath(), $_c->getCollectionPath()) === 0) {
				$navSelected='nav-selected';
			} else {
				$navSelected = '';
			}
			
			$pageLink = false;
			
			if ($_c->getCollectionAttributeValue('replace_link_with_first_in_nav')) {
				$subPage = $_c->getFirstChild();
				if ($subPage instanceof Page) {
					$pageLink = $nh->getLinkToCollection($subPage);
				}
			}
                        
			if (!$pageLink) {
                                if($_c->getCollectionAttributeValue('dont_link_menu_item')){
                                    $pageLink = "javascript:void(0)";
                                    //$title = ($name=="Home" ? $c->getCollectionAttributeValue('meta_title') : $name);
				    $classes .= ' nolink';
				    $title = '';
                                }
                                else
                                    $pageLink = $ni->getURL();
			}                        
			
			if (!$pageLink) {
				$pageLink = $ni->getURL();
			}

			if ($isFirst) $isFirstClass = 'first';
			else $isFirstClass = '';

      			if ($c->getCollectionID() == $_c->getCollectionID())
                                $classes .= ' nav-selected';
                        if ($isURL)
                                $classes .= ' external';
                        
                        $subPage = $_c->getFirstChild();
                        $hasChildren = ($subPage instanceof Page);
                        
                        if ($hasChildren && $thisLevel < $maxLevels) {
                                $inserts .= '<div class="arrow"></div>';
                                $bindjs .= "$('li#navli$id').mouseenter(function(event) {showMenu(event, this, $id, ".($thisLevel>1 ? 1 : 0).");});\n";
                                $bindjs .= "$('li#navli$id').mouseleave(function(event) {hideMenu(event, $id, 0);});\n";
                        }
                        
      			$itemtext = '<li id="navli'.$id.'" class="navli pli'.$prevLevel.' navli'.$thisLevel.$classes.' '.$isFirstClass.'"'. $attrs.'>';
      			$itemtext .= '<a class="'.$classes.'" href="' . $pageLink . '"  ' . $target .' title="' . $title . '">' . $name  . '</a>';
                        $itemtext .= ($name=="Home" ? '' : $inserts);
      			$itemtext .= '</li>';

                        if ($startL2) {
                            $menul2html .= '<div class="nav_L2 jsnav" id="menu'.$lastL1.'" style="margin: 5px;"><ul class="menu-element">';
                            $bindjs .= "$('#menu$lastL1').mouseleave(function(event) {hideMenu(event, $lastL1, 1500);});\n";
                        }
                        if ($startL3) {
                            $menul3html .= '<div class="nav_L3 jsnav" id="menu'.$lastL2.'" style="margin: 5px;"><ul class="menu-element">';
                            $bindjs .= "$('#menu$lastL2').mouseleave(function(event) {hideMenu(event, $lastL2, 1500);});\n";
                        }
                        switch ($thisLevel) {
                            case 1:
                                if($_c->getCollectionAttributeValue('move_item_to_end_of_nav'))
                                    $endItems .= $itemtext;
                                else
                                    echo $itemtext;
                                break;
                            case 2:
                                $menul2html .= $itemtext;
                                break;
                            case 3:
                                $menul3html .= $itemtext;
                                break;
                        }
                        if ($endL3) {
				$menul3html .= '</ul></div>';
				$countL3 = 0;
			}
      			if ($endL2) {
				$menul2html .= '</ul></div>';
				$countL2 = 0;
			}


			$isFirst = false;
		}
	}
	if($thisLevel>2) $menul3html .= '</ul></div>';
	if($thisLevel>1) $menul2html .= '</ul></div>';

        echo $endItems;
	echo('</ul>');
	echo('<div class="ccm-spacer">&nbsp;</div>');
	//echo '<div id="menus">'.$menul2html.$menul3html.'</div>';
?>

<script type="text/javascript">

	twsMenuHTML = '<div id="menus"><?php echo $menul2html.$menul3html ?></div>';
    
    function showMenu(e, me, id, p) {
        moi = $(me);
        menu = $("#menu"+id);
        mymenu = moi.closest('.jsnav');
        allmenus = $(".jsnav");
        if (moi.hasClass("navli"))
            allmenus.not(mymenu).not(menu).stop().hide();
        allmenus.clearQueue();
	
	tm = 5; menu.css("margin-top", tm+'px');
	lm = 5; menu.css("margin-left", lm+'px');

	
        pos = moi.offset();
        topoffset = (p==0 ? moi.outerHeight()-1 : 0);
        leftoffset = (p==1 ? moi.outerWidth()-1 : 0);

        menu.css({"left":(pos.left+leftoffset-lm)+"px", "top":(pos.top+topoffset-tm)+"px"});   

        menu.css('height', '').slideDown(400);
    }
    
    function hideMenu(e, id, delay) {
        var to = $(e.toElement ? e.toElement : e.relatedTarget);
        //console.log(to.get(0).nodeName + " / " + to.get(0).id + " / " + to.get(0).class);
        if (!to.is("#menu"+id+", .navli, .menu-element")) {
            var menus = $("div.jsnav");
            menus.delay(delay).slideUp(300);
        }
    }

    $(function() {
        $(twsMenuHTML).appendTo("body");
	<?php echo $bindjs; ?>
        
    })
    
</script>   