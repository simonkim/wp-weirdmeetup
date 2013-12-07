<?php

/* ===== 

You are free to modify this file but you should really create a folder in your
themes directory called (exactly) multi-column-tag-map and then copy this file 
to that folder. Make your edits to the copy. If you make your edits to the 
file in the plugins folder, all your edits will be overwritten if you update.

===== */ 
	
	/* =====  version 12.0.4 ===== */ 

	/* ===== set up options ===== */ 
	extract(shortcode_atts(array(
        "columns" => "2",
        "more" => "View More",
		"hide" => "no",
		"num_show" => "5",
		"toggle" => "no",
		"show_empty" => "no",
		"name_divider" => "|",
		"tag_count" => "no",
		"exclude" => "",
		"descriptions" => "no",
		"width" => "",
		"equal" => "no",
		"manual" => "",
		"basic" => "no",
		"basic_heading" => "no",
		"show_categories" => "no",
		"child_of" => "",
		"from_category" => "",
		"show_pages" => "no",
		"page_excerpt" => "no",
		"taxonomy" => "",
		"group_numbers" => "no",
		"show_navigation" => "no",
	), $atts));

		
	if(!in_array($columns, array(1, 2, 3, 4, 5))){
		$columns = "2";
	}
   
	if($show_empty == "yes"){
		$show_empty = "0";
	}
	if($show_empty == "no"){
		$show_empty = "1";
	}
	
	$tug_width = '';
	if($width){
		$tug_width = "style=\"width: ". $width ."px;\"";
	}
	$equalize = '';
	if($equal == "yes" && $columns != "1"){ 
		$equalize = 'mcEqualize';
	}
	if($toggle != "no"){
		$toggable = "toggleYes";
	} else {
		$toggable = "toggleNo";
	}
	
	/* ===== show settings for debug purposes ===== */ 
	if(isset($_REQUEST[base64_decode('dHVnYWRtaW4=')])){ 
		$childof = preg_replace('/\s+/', '', explode(',',$child_of));
		$child_of_list = array();
		foreach($childof as $kids){
			if(!empty($kids)){
				array_push($child_of_list, $kids.' ('.get_cat_name($kids).')');
			}
		}
		foreach($child_of_list as $col1){
			$colist .= $col1.', ';
		}		
		$list = '<div>
			<style type="text/css">
				#tug-settings-mctagmap { border: 2px solid #ccc; background: #f8f8f8; font: 12px/16px monospace; padding: 10px; margin: 0; color: #333; }
				#tug-settings-mctagmap dt { padding: 0 0 8px 0; }
				#tug-settings-mctagmap dd { margin-bottom: 6px; }
			</style>
			<dl id="tug-settings-mctagmap">
				<dt>mctagmap settings</dt>
					<dd>columns => '.$columns.'</dd>
					<dd>more => '.$more.'</dd>
					<dd>hide => '.$hide.'</dd>
					<dd>num_show => '.$num_show.'</dd>
					<dd>toggle => '.$toggle.'</dd>
					<dd>show_empty => '.$show_empty.'</dd>
					<dd>name_divider => '.$name_divider.'</dd>
					<dd>tag_count => '.$tag_count.'</dd>
					<dd>exclude => '.$exclude.'</dd>
					<dd>descriptions => '.$descriptions.'</dd>
					<dd>width => '.$width.'</dd>
					<dd>equal => '.$equal.'</dd>
					<dd>manual => '.$manual.'</dd>
					<dd>basic => '.$basic.'</dd>
					<dd>basic_heading => '.$basic_heading.'</dd>
					<dd>show_categories => '.$show_categories.'</dd>
					<dd>child_of => '.substr($colist,0,-2).'</dd>
					<dd>from_category => '.$from_category.' ('.get_cat_name($from_category).')</dd>
					<dd>show_pages => '.$show_pages.'</dd>
					<dd>page_excerpt => '.$page_excerpt.'</dd>
					<dd>taxonomy => '.$taxonomy.'</dd>
					<dd>group numbers => '.$group_numbers.'</dd>
					<dd>show_navigation => '.$show_navigation.'</dd>
			</dl>
			</div>';
	} else {
		$list = '';
	}
	$manual = str_replace(' ', '', strtoupper($manual));
	$manualArray = explode(',', $manual);
	
    $list .= '<!-- begin list -->'."\n".'<div id="mcTagMap" class="'.$equalize.' '.$toggable.'">'."\n";
	
	/* ===== set up what to get (tags, categories, etc...) ===== */ 
	if($taxonomy){
		$tags = get_terms($taxonomy, 'order=ASC&hide_empty='.$show_empty.''); 
	} elseif($show_categories == "yes"){
		if($child_of != ""){
			$childof = array();
			$childof = preg_replace('/\s+/', '', explode(',',$child_of));
			$tags = array();
			foreach($childof as $kids){
				$args = array(
					'child_of' => $kids,
					'order' => 'ASC'
				);
				$childcats = get_categories($args);
				$tags = array_merge($tags, $childcats);
			}
		} else {
			$tags = get_categories('order=ASC&hide_empty='.$show_empty.'');
		}	
	} elseif($show_pages == "yes"){
		//$tags = get_pages('sort_order=DESC&sort_column=post_title&hide_empty='.$show_empty.'');
		$tags = get_pages(array('sort_order' => 'asc', 'sort_column' => 'post_title', 'post_status' => 'publish'));
	} elseif($from_category){
		$tags = array();
		$posts_array = get_posts('category='.$from_category.'&numberposts=-1');
		foreach($posts_array as $pa) {
			$tags = array_merge($tags, wp_get_post_tags($pa->ID));		
		}
		$tmp = array();
		foreach($tags as $k => $v){
    		$tmp[$k] = $v->term_id;
 		}
		$tmp = array_unique($tmp);
 		foreach($tags as $k => $v){
    		if (!array_key_exists($k, $tmp)){
        		unset($tags[$k]);
			}
		}
	} else {
		$tags = get_terms('post_tag', 'order=ASC&hide_empty='.$show_empty.''); 
	}
		
	/* === create a variable to pull the correct title from the given arrays === */	
	if($show_pages == "yes"){
		$arraypart = "post_title";
		if($page_excerpt == "yes"){
		}
	} else {
		$arraypart = "name";
	}
	
	/* === exclude tags === */	
	foreach($tags as $tag){
		$fl = mb_substr($tag->$arraypart ,0,1);
		$ll = mb_substr($tag->$arraypart ,1);
		$tag->$arraypart  = $fl.$ll;
		if (preg_match('/(?<=^|[^\p{L}])' . preg_quote($tag->$arraypart ,'/') . '(?=[^\p{L}]|$)/ui', $exclude)) {
			unset($tag->$arraypart );
		}
	}

	/* ===== show only one tag ===== */ 
	if(strpos($exclude,'*!') !== false){
		foreach($tags as $tag){
			$exclude = str_replace('*!', '', $exclude);
			if(strpos($tag->name, $exclude) == false) {
				unset($tag->$arraypart);
			}
		}
	}

	/* ===== start grouping the tags ===== */ 
	$groups = array();
	if( $tags && is_array( $tags ) ) {
		foreach( $tags as $tag ) {	
		/* ===== exclude tags ===== */ 
		if(isset($tag->$arraypart)){	
			// added 09.02.11
			if (strlen(strstr($tag->$arraypart, $name_divider))>0) {
 				$tag->$arraypart = preg_replace("/\s*([\\".$name_divider."])\s*/", "$1", $tag->$arraypart);
				$tagParts = explode($name_divider, $tag->$arraypart);
				$tag->$arraypart = $tagParts[1].', '.$tagParts[0];
			}
    		if(function_exists('mb_strtoupper')) {
        		$first_letter = mb_strtoupper(mb_substr($tag->$arraypart,0,1)); /* === Thanks to Birgir Erlendsson === */
    		} else {
        		$first_letter = strtoupper(substr($tag->$arraypart,0,1)); /* === Thanks to Birgir Erlendsson === */
    		}			
			
			$groups[$first_letter][] = $tag;
			ksort($groups);
		}
		}
		
		/* ===== group numbers ===== */ 
		if($group_numbers == 'yes'){
			$numericArray = array_filter(array_keys($groups), 'is_numeric');
			$ed = array_keys($groups);
			$d = array_diff_assoc($ed, $numericArray);
			$numGroups = $groups;
			foreach($d as $dd){
				$numGroups[$dd] = "";
			}
			ksort($numGroups);		
			function mctm_flatten($arr, $base = "", $divider_char = "/") {
    			$ret = array();
    			if(is_array($arr)) {
        			foreach($arr as $k => $v) {
            			if(is_array($v)) {
                			$tmp_array = mctm_flatten($v, $base.$k.$divider_char, $divider_char);
                			$ret = array_merge($ret, $tmp_array);
            			} else {
                			$ret[$base.$k] = $v;
            			}
        			}
   				}
    			return $ret;
			}
			$numbersArray = array_filter(array_values(mctm_flatten($numGroups)));
			$groups = array_diff_assoc($groups, $numGroups);
			if(!empty($numbersArray)){	
				$nums = array('#' => $numbersArray);
				$groups = array_merge($groups, $nums);
			} 
		}
	
		/* ===== create the navigation ===== */ 
		if($show_navigation == "yes"){
			$list .= '<div id="mcTagMapNav">'."\n";
			foreach( array_keys($groups) as $fl ) {
				$list .= '<a href="#mctm-'.$fl.'">'.$fl.'</a>'."\n";
			}
			$list .= '</div>'."\n";
		}
		
	/* ===== build columns ===== */ 	
	if( !empty ( $groups ) ) {	
			
		$count = 0;
		$howmany = count($groups);
		
		/* ===== two columns ===== */ 
		if ($columns == 2){
			$firstrow = ceil($howmany * 0.5);
	   	 $secondrow = ceil($howmany * 1);
	    	$firstrown1 = ceil(($howmany * 0.5)-1);
	    	$secondrown1 = ceil(($howmany * 1)-0);
		}
		
		/* ===== three columns ===== */ 
		if ($columns == 3){
	    	$firstrow = ceil($howmany * 0.33);
	    	$secondrow = ceil($howmany * 0.66);
	    	$firstrown1 = ceil(($howmany * 0.33)-1);
	    	$secondrown1 = ceil(($howmany * 0.66)-1);
		}
		
		/* ===== four columns ===== */ 
		if ($columns == 4){
	    	$firstrow = ceil($howmany * 0.25);
	    	$secondrow = ceil(($howmany * 0.5)+1);
	    	$firstrown1 = ceil(($howmany * 0.25)-1);
	    	$secondrown1 = ceil(($howmany * 0.5)-0);
			$thirdrow = ceil(($howmany * 0.75)-0);
	    	$thirdrow1 = ceil(($howmany * 0.75)-1);
		}
		
		/* ===== five columns ===== */ 
		if ($columns == 5){
	    	$firstrow = ceil($howmany * 0.2);
	    	$firstrown1 = ceil(($howmany * 0.2)-1);
	    	$secondrow = ceil(($howmany * 0.4));
			$secondrown1 = ceil(($howmany * 0.4)-1);
			$thirdrow = ceil(($howmany * 0.6)-0);
	    	$thirdrow1 = ceil(($howmany * 0.6)-1);
			$fourthrow = ceil(($howmany * 0.8)-0);
	    	$fourthrow1 = ceil(($howmany * 0.8)-1);
		}
		
		if(!$manual && $basic == "no"){
		/* ===== display columns ===== */ 
		foreach( $groups as $letter => $tags ) { 
			if ($columns == 2){
				if ($count == 0 || $count == $firstrow || $count ==  $secondrow) { 
			    	if ($count == $firstrow){
						$list .= "\n<div class='holdleft noMargin' ". $tug_width .">\n";
						$list .="\n";
					} else {
						$list .= "\n<div class='holdleft' ". $tug_width .">\n";
						$list .="\n";
					}
				}
			}
			if ($columns == 3){
				if ($count == 0 || $count == $firstrow || $count ==  $secondrow) { 
			   		if ($count == $secondrow){
						$list .= "\n<div class='holdleft noMargin' ". $tug_width .">\n";
						$list .="\n";
					} else {
						$list .= "\n<div class='holdleft' ". $tug_width .">\n";
						$list .="\n";
					}
				}
			}
			if ($columns == 4){				
				if ($count == 0 || $count == $firstrow || $count ==  $secondrow || $count == $thirdrow) { 
			    	if ($count == $thirdrow){
						$list .= "\n<div class='holdleft noMargin' ". $tug_width .">\n";
						$list .="\n";
					} else {
						$list .= "\n<div class='holdleft' ". $tug_width .">\n";
						$list .="\n";
					}
				}
			}
			if ($columns == 5){
				if ($count == 0 || $count == $firstrow || $count ==  $secondrow || $count == $thirdrow || $count == $fourthrow ) { 
			    	if ($count == $fourthrow){
						$list .= "\n<div class='holdleft noMargin' ". $tug_width .">\n";
						$list .="\n";
					} else {
						$list .= "\n<div class='holdleft' ". $tug_width .">\n";
						$list .="\n";
					}
				}
			}
			
			/* =====  start bulding the individual lists for each letter ===== */ 
			$list .= '<div class="tagindex">';
			$list .="\n";
			$list .='<h4 id="mctm-'.$letter.'">' . apply_filters( 'the_title', $letter, null ) . '</h4>';
			$list .="\n";
			$list .= '<ul class="links">';
			$list .="\n";			
			$i = 0;
	
			/* ===== this helps sort non-english characters ===== */ 
			if($show_pages == "yes"){
				uasort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->post_title, $b->post_title);') ); // addded 09.02.11
			} else {
				uasort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->name, $b->name);') ); // addded 09.02.11
			}

			foreach( $tags as $tag ) {
				/* =====  exclude tags ===== */ 
				if(isset($tag->$arraypart)){
					/* =====  tag count ===== */ 
					$mctagmap_count = '';
					if($tag_count == "yes" && $show_pages != "yes"){
						$mctagmap_count = ' <span class="mctagmap_count">('.$tag->count.')</span>';
					}

					if($taxonomy){
						$url = get_term_link($tag->slug, $taxonomy);
					} elseif($show_categories == "yes"){
						$url = get_category_link($tag->term_id); 
					} elseif($show_pages == "yes"){
						$url = get_page_link($tag->ID); 
						$pex = mctm_get_the_excerpt_here($tag->ID);
					} elseif($from_category){
						$url =esc_attr( get_tag_link( $tag->term_id ) ).'?mctmCatId='.$from_category.'&mctmTag='.$tag->slug; 
					} else {
						$url =esc_attr( get_tag_link( $tag->term_id ) ); 
					}
		
					$name = apply_filters( 'the_title', $tag->$arraypart, null );
					
					/* =====  show descriptions / excerpts ===== */ 
					$mctagmap_description = '';
					if($descriptions == "yes"){
						$mctagmap_description = '<span class="tagDescription">' . $tag->description . '</span>';
					}
					if($show_pages == "yes" && $page_excerpt == "yes"){
						$mctagmap_description = '<span class="tagDescription">' . $pex. '</span>';
					}

					$i++;
					$counti = $i;
					/* =====  if hide = yes ===== */ 
					if ($hide == "yes"){
						$num2show = $num_show;
						$num2show1 = ($num_show +1);
						if ($i != 0 and $i <= $num2show) {
							$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a>'. $mctagmap_count . $mctagmap_description . '</li>';
							$list .="\n";
						}
						if ($i > $num2show && $i == $num2show1 && $toggle == "no") {
							$list .=  "<li class=\"morelink\">"."<a href=\"#x\" class=\"more\">".$more."</a>"."</li>"."\n";
						}
						if ($i >= $num2show1){
               				$list .= '<li class="hideli"><a title="' . $name . '" href="' . $url . '">' . $name . '</a>' . $mctagmap_count . $mctagmap_description. '</li>';
			   				$list .="\n";
						}
						} else {
							$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a>' . $mctagmap_count . $mctagmap_description . '</li>';
							$list .="\n";
						}	
					}	
				}
				/* =====  toggle link ===== */ 
			if ($hide == "yes" && $toggle != "no" && $i == $counti && $i > $num2show) {
				$list .=  "<li class=\"morelink\">"."<a href=\"#x\" class=\"more\">".$more."</a>"."<a href=\"#x\" class=\"less\">".$toggle."</a>"."</li>"."\n";
			}	 
		 
			$list .= '</ul>';
			$list .="\n";
			
			/* fixing the extra div if there are less tags than columns */			
			if(count($groups) - $columns > 0 && $columns != 1){
				$list .= '</div>';
			}
			
			/* =====  close the columns ===== */ 
			$list .="\n\n";
			if ($columns == 3 || $columns == 2){
				if ( $count == $firstrown1 || $count == $secondrown1) { 
					$list .= "</div>"; 
				}	
			}
			if ($columns == 4){
				if ( $count == $firstrown1 || $count == $secondrown1 || $count == $thirdrow1) { 
					$list .= "</div>"; 
				}	
			}
			if ($columns == 5){		
				if ( $count == $firstrown1 || $count == $secondrown1 || $count == $thirdrow1 || $count == $fourthrow1) { 
					$list .= "</div>"; 
				}	
			}
			$count++;
		} 
		$list .="</div>";
		}
	}	

/* ==================== manual  settings ==================== */

	if($manual && $basic == "no"){
		$endManual = '';
		$marginEh = '';
		$list .= "\n<div class='holdleft' ". $tug_width .">\n";
		$manualCount = 1;
		foreach( $groups as $letter => $tags ) {	
			foreach(array(strtoupper(apply_filters('the_title', $letter, null))) as $qw) { 
				if(in_array($qw, $manualArray)){
					if($manualCount == count($manualArray)){
						$marginEh = "noMargin";
						$endManual = '</div>';
					}
					$list .= "</div>\n<div class='holdleft ". $marginEh ."' ". $tug_width . ">\n";
					$manualCount++;
				}			
			}
		$list .= '<div class="tagindex">';
		$list .="\n";
		$list .='<h4 id="mctm-'.$letter.'">' . apply_filters( 'the_title', $letter, null ) . '</h4>';
		$list .="\n";
		$list .= '<ul class="links">';
		$list .="\n";			
		$i = 0;
		
		/* ===== this helps sort non-english characters ===== */ 
		if($show_pages == "yes"){
			uasort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->post_title, $b->post_title);') ); // addded 09.02.11
		} else {
			uasort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->name, $b->name);') ); // addded 09.02.11
		}

		foreach( $tags as $tag ) {
			/* =====  exclude tags ===== */ 
			if(isset($tag->$arraypart)){
			$mctagmap_count = '';
			if($tag_count == "yes" && $show_pages != "yes"){
				$mctagmap_count = ' <span class="mctagmap_count">('.$tag->count.')</span>';
			}
		
			if($taxonomy){
				$url = get_term_link($tag->slug, $taxonomy);
			} elseif($show_categories == "yes"){
				$url = get_category_link($tag->term_id); 
			} elseif($show_pages == "yes"){
				$url = get_page_link($tag->ID); 
				$pex = mctm_get_the_excerpt_here($tag->ID);
			} else {
				$url =esc_attr( get_tag_link( $tag->term_id ) ); 
			}
		
			$name = apply_filters( 'the_title', $tag->$arraypart, null );
			
			/* =====  show descriptions / excerpts ===== */ 
			$mctagmap_description = '';
			if($descriptions == "yes"){
				$mctagmap_description = '<span class="tagDescription">' . $tag->description . '</span>';
			}
			if($show_pages == "yes" && $page_excerpt == "yes"){
				$mctagmap_description = '<span class="tagDescription">' . $pex. '</span>';
			}
			$i++;
			$counti = $i;
			if ($hide == "yes"){
				$num2show = $num_show;
				$num2show1 = ($num_show +1);
				if ($i != 0 and $i <= $num2show) {
					$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a>'. $mctagmap_count . $mctagmap_description . '</li>';
					$list .="\n";
				}
				if ($i > $num2show && $i == $num2show1 && $toggle == "no") {
					$list .=  "<li class=\"morelink\">"."<a href=\"#x\" class=\"more\">".$more."</a>"."</li>"."\n";
				}
				if ($i >= $num2show1){
               		$list .= '<li class="hideli"><a title="' . $name . '" href="' . $url . '">' . $name . '</a>' . $mctagmap_count . $mctagmap_description . '</li>';
			   		$list .="\n";
				}
			} else {
				$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a>' . $mctagmap_count . $mctagmap_description . '</li>';
				$list .="\n";
			}	
		}	
	}
		if ($hide == "yes" && $toggle != "no" && $i == $counti && $i > $num2show) {
			$list .=  "<li class=\"morelink\">"."<a href=\"#x\" class=\"more\">".$more."</a>"."<a href=\"#x\" class=\"less\">".$toggle."</a>"."</li>"."\n";
		}	 
			$list .= '</ul>';
			$list .="\n";
			$list .= '</div>';
		}
		$list .= $endManual;
	}

/* =============== basic settings  ================= */ 

	if($basic == "yes"){
		$sum = 0 - count(explode(',', $exclude));
		foreach($tags as $tag){
			$sum += count($tag);
		}
		$basicCount = 1;
		$list .= "\n<div class='holdleft' ". $tug_width .">\n";				
    	$list .= '<div class="tagindex">';
		$list .="\n";	
		foreach( $groups as $letter => $tags ) {
			if($basic_heading == 'yes'){
				$list .='<h4 id="mctm-'.$letter.'">' . apply_filters( 'the_title', $letter, null ) . '</h4>'."\n";
			}
			$list .= '<ul class="links">';
			$list .="\n";		
	
			/* ===== this helps sort non-english characters ===== */ 
			if($show_pages == "yes"){
				uasort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->post_title, $b->post_title);') ); // addded 09.02.11
			} else {
				uasort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->name, $b->name);') ); // addded 09.02.11
			}	
			foreach($tags as $tag){
				if(isset($tag->$arraypart)){
					$mctagmap_count = '';
					if($tag_count == "yes" && $show_pages != "yes"){
						$mctagmap_count = ' <span class="mctagmap_count">('.$tag->count.')</span>';
					}
				
					if($taxonomy){
						$url = get_term_link($tag->slug, $taxonomy);
					} elseif($show_categories == "yes"){
						$url = get_category_link($tag->term_id); 
					} elseif($show_pages == "yes"){
						$url = get_page_link($tag->ID); 
						$pex = mctm_get_the_excerpt_here($tag->ID);
					} else {
						$url =esc_attr( get_tag_link( $tag->term_id ) ); 
					}
		
					$name = apply_filters( 'the_title', $tag->$arraypart, null );
					$mctagmap_description = '';
					if($descriptions == "yes"){
						$mctagmap_description = '<span class="tagDescription">' . $tag->description . '</span>';
					}
					if($show_pages == "yes" && $page_excerpt == "yes"){
						$mctagmap_description = '<span class="tagDescription">' . $pex. '</span>';
					}
					$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a>' . $mctagmap_count . $mctagmap_description . '</li>'."\n";
					if($basicCount == ceil($sum/$columns) + 1 ){
						$list .= '</ul>';
						$list .="\n";
						$list .= '</div>';
						$list .= '</div>';
						$list .= "\n<div class='holdleft' ". $tug_width .">\n";				
    					$list .= '<div class="tagindex">';
						$list .="\n";
						$list .= '<ul class="links">';
						$list .="\n";
						$basicCount = 0;
					}	
					$basicCount++;
				}	
			}
		} 
		$list .= '</ul>';
		$list .="\n";
		$list .= '</div>';		
		$list .= '</div>';
	}

/* ===== wrap it all up ===== */ 
	$list .= "<div style='clear: both;'></div></div><!-- end list -->";
	}
	else $list .= '<p>Sorry, but no tags were found</p>';
?>