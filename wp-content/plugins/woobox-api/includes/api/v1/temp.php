<?php
if(!function_exists('iq_tab')){

    function iq_tab( $atts, $content = null){

        $data = wp_parse_args( $atts, array(
            'tabs_style'        => '1',
            'vertical_style' => '1',
            'tag'               => 'h2',
            'position'          => 'left',
            'extra_class'       => '',
            'css' 			    => '',
        ) );

        $css_class = '';

        $extra_class = $data['extra_class'] ?  $data["extra_class"]  : '';

        $tag = $data['tag'] ?  $data["tag"]  : '';

        $vertical_style = $data['vertical_style'] ?  $data["vertical_style"]  : '';

        $position = $data['position'] ?  $data["position"]  : '';

		$css = $data['css'] ?  $data["css"]  : '';

        if(!empty($css)){
			$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $atts['css'] );
            }

        $par = shortcode_atts($data, $atts);
        extract($par);

        /*---------Title---------*/
        preg_match_all('/tab_title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $tab_title = array();

        if (isset($matches[0])) { $tab_title = $matches[0]; }
        $tab_title_array = array();

        foreach($tab_title as $tab) {
            preg_match('/tab_title="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
            $tab_title_array[] = $tab_matches[1][0];
        }

        /*---------iq_image---------*/
        preg_match_all('/iq_image="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $iq_image = array();
        if (isset($matches[0])) { $iq_image = $matches[0]; }
        $iq_image_array = array();
        foreach($iq_image as $tab) {
            preg_match('/iq_image="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
            $iq_image_array[] = $tab_matches[1][0];
        }


        /*---------Fontawesome---------*/
        preg_match_all('/fontawesome="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $fontawesome = array();
        if (isset($matches[0])) { $fontawesome = $matches[0]; }
        $fontawesome_array = array();

        foreach($fontawesome as $tab) {
            preg_match('/fontawesome="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
            $fontawesome_array[] = $tab_matches[1][0];
            //print_r($fontawesome_array);
        }

        /*---------Openiconic---------*/
        preg_match_all('/openiconic="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $openiconic = array();
        if (isset($matches[0])) { $openiconic = $matches[0]; }
        $openiconic_array = array();

        foreach($openiconic as $tab) {
            preg_match('/openiconic="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
            $openiconic_array[] = $tab_matches[1][0];
            //print_r($fontawesome_array);
        }

         /*---------Conatnt---------*/
        preg_match_all('/tab_content="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE);
        $tab_content = array();

        if (isset($matches[0])) { $tab_content = $matches[0]; }
        $tab_content_array = array();

        foreach($tab_content as $tab) {
            preg_match('/tab_content="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
            $tab_content_array[] = $tab_matches[1][0];
        }

        $a1['t1'] = $tab_title_array;
        $a5['t2'] = $iq_image_array;
        $a6['t3'] = $fontawesome_array;
        $a7['t4'] = $openiconic_array;
        $a8['t5'] = $tab_content_array;

        $merge[] = array_merge($a1,$a5,$a6,$a7,$a8);

        foreach ($merge as $key=>$val) {
            $title1         = $val['t1'];
            $image1         = $val['t2'];
            $fontawesome    = $val['t3'];
            $openiconic     = $val['t4'];
            $content1       = $val['t5'];
        }

        $p = count($tab_title);
        $params['content'] = $content;

        $out = '';

        if($tabs_style == 1){

            if($vertical_style == 1){

            $out .= '
            <div class="row custom-tab verticaltab1 text-'.$position.' '.$extra_class.' '. esc_attr( $css_class ) .'">
                <div class="col-lg-4 col-md-12 ">
               
                         <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist"  aria-orientation="vertical">';
                        for($i=0;$i<$p;$i++){

                            $img ='';
                            $title = $title1[$i];

                            $img = isset($image1[$i]) ? $image1[$i] : '';

                            if($i == 0){ $ac = 'active show'; }else { $ac=''; }
                            if($i == 0){ $aria = 'true'; }else { $aria='false'; }
                            $out .='
                            <li class="nav-item">';

                                $out .= '
                                    <a class="nav-link '.$ac.'" data-toggle="pill" href="#tab-'.sanitize_title($title).'" role="tab" aria-selected="'.$aria.'">';
                                    if(!empty($img))
                                    {
                                        $url   = wp_get_attachment_image_url( $img );
                                        $out .= '<img src="'. $url .'" alt="tab-image" class="img-fluid" />';
                                    }
                                    $out .= '<'.$tag .' class="tab-title">'.$title .'</'.$tag .'>';
                                $out .= '</a> ';
                            $out .= '
                            </li>';

                        }
                    $out .= '
                    </ul>
                </div>';

        $out .='<div class="col-lg-8 col-md-12 show_content">';
                    $out .= do_shortcode( $content );
            $out .= '</div>';
        $out .='</div>';

            return $out;
        }
         if($vertical_style == 2){

            $out .= '
            <div class="row custom-tab verticaltab2 text-'.$position.' '.$extra_class.' '. esc_attr( $css_class ) .'">
                <div class="col-lg-4 col-md-12 ">
               
                         <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist"  aria-orientation="vertical">';
                        for($i=0;$i<$p;$i++){

                            $img ='';
                            $title = $title1[$i];

                            $img = isset($image1[$i]) ? $image1[$i] : '';

                            if($i == 0){ $ac = 'active show'; }else { $ac=''; }
                            if($i == 0){ $aria = 'true'; }else { $aria='false'; }
                            $out .='
                            <li class="nav-item">';

                                $out .= '
                                    <a class="nav-link '.$ac.'" data-toggle="pill" href="#tab-'.sanitize_title($title).'" role="tab" aria-selected="'.$aria.'">';
                                    if(!empty($img))
                                    {
                                        $url   = wp_get_attachment_image_url( $img );
                                        $out .= '<img src="'. $url .'" alt="tab-image" class="img-fluid" />';
                                    }
                                    $out .= '<'.$tag .' class="tab-title">'.$title .'</'.$tag .'>';
                                $out .= '</a> ';
                            $out .= '
                            </li>';

                        }
                    $out .= '
                    </ul>
                </div>';

        $out .='<div class="col-lg-8 col-md-12 show_content">';
                    $out .= do_shortcode( $content );
            $out .= '</div>';
        $out .='</div>';

            return $out;
        }

         if($vertical_style == 3){

            $out .= '
            <div class="row custom-tab verticaltab3 text-'.$position.' '.$extra_class.' '. esc_attr( $css_class ) .'">
                <div class="col-lg-6 col-md-12 ">
               
                         <ul class="nav nav-pills nav-pills-style-3 mb-5" id="pills-tab" role="tablist"  aria-orientation="vertical">';
                        for($i=0;$i<$p;$i++){

                            $img ='';
                            $title = $title1[$i];
                            $tab_cont = isset($content1[$i]) ? $content1[$i] : '';

                            $img = isset($image1[$i]) ? $image1[$i] : '';

                            if($i == 0){ $ac = 'active show'; }else { $ac=''; }
                            if($i == 0){ $aria = 'true'; }else { $aria='false'; }
                            $out .='
                            <li class="nav-item">';

                                $out .= '
                                    <a class="nav-link '.$ac.'" data-toggle="pill" href="#tab-'.sanitize_title($title).'" role="tab" aria-selected="'.$aria.'">';
                                    $out .='<div class="media">';
                                    if(!empty($img))
                                    {
                                        $url   = wp_get_attachment_image_url( $img );
                                        $out .= '<img src="'. $url .'" alt="tab-image" class="img-fluid" />';
                                    }
                                    $out .='<div class="media-body">';
                                                $out .='<'.$tag .' class="mb-2 active-title">'.$title .'</'.$tag .'>';
                                                $out .='<p class="mb-0">'.$tab_cont.'</p>';
                                            $out .='</div>';
                                        $out .='</div>';
                                $out .= '</a> ';
                             
                            $out .= '
                            </li>';

                        }
                    $out .= '
                    </ul>
                </div>';
                    $out .='<div class="col-lg-6 col-md-12  show_content">';
                                    $out .= do_shortcode( $content );
                            $out .= '</div>';
       
        $out .='</div>';

            return $out;
        }

        }

        if($tabs_style == 2){
        $out .= '<div class="custom-tab text-'.$position.' '.$extra_class.' '. esc_attr( $css_class ) .'">
                    <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">';
                        for($i=0;$i<$p;$i++){

                            $img ='';
                            $title = $title1[$i];

                            $img = isset($image1[$i]) ? $image1[$i] : '';

                            if($i == 0){ $ac = 'active show'; }else { $ac=''; }
                            if($i == 0){ $aria = 'true'; }else { $aria='false'; }
                            $out .='
                            <li class="nav-item">';

                                $out .= '
                                    <a class="nav-link '.$ac.'" data-toggle="pill" href="#tab-'.sanitize_title($title).'" role="tab" aria-selected="'.$aria.'">';
                                    if(!empty($img))
                                    {
                                        $url   = wp_get_attachment_image_url( $img );
                                        $out .= '<img src="'. $url .'" alt="tab-image" class="img-fluid" />';
                                    }
                                    $out .= '<'.$tag .' class="tab-title">'.$title .'</'.$tag .'>';
                                $out .= '</a> ';
                            $out .= '
                            </li>';

                        }
                    $out .= '
                    </ul>';
            $out .='<div class="show_content">';
                    $out .= do_shortcode( $content );
            $out .= '</div>';
            $out .= '</div>';

            return $out;
        }

    }

    add_shortcode( 'iq_tab' , 'iq_tab' );
}

if(!function_exists('iq_inner_item')){

    function iq_inner_item($atts, $content = null){

    $data = wp_parse_args( $atts, array(
        'tab_title'      => 'Tab',
        'tab_content'   => 'It is a long established fact that a reader will be distracted by the of readable.',
        'iq_image'       => '',
        'openiconic' => '',
        'fontawesome' => '',
        'typicons' => '',
        'entypo' => '',
        'linecons' => '',
        'ionicons' => '',
    ) );

    $params = shortcode_atts($data, $atts);
    extract($params);

    $params['tab_title']     = $params['tab_title'];


    $params['tab_content']     = $params['tab_content'];

    $params['content'] = $content;

    wp_enqueue_style( 'font-awesome' );
    wp_enqueue_style( 'vc_openiconic' );
    wp_enqueue_style( 'vc_typicons' );
    wp_enqueue_style( 'vc_entypo' );
    wp_enqueue_style( 'vc_linecons' );

    $icons ='';

        $out = '';

        $out .= '
        <div id="tab-'.sanitize_title($params['tab_title']).'" class="tab-pane fade">';
            $out .= do_shortcode($content);
        $out .= '
        </div>';

        return $out;
    }
    add_shortcode( 'iq_inner_item' , 'iq_inner_item' );
}
?>