<?php

    #
    #host info
    #
    
    $data = get('http://www.iplocationfinder.com/' . clean_url($url));
    preg_match('~ISP.*<~',$data,$isp);
    preg_match('~Region.*<~',$data,$region);
    preg_match('~Country.*<~',$data,$country);
    preg_match('~IP:.*<~',$data,$ip);


    $country = explode(':',strip_tags($country[0]));
    $country = trim(str_replace('إخفاء عنوان IP الخاص بك والموقع هنا','',$country[1]));
    if($country == '') $country = 'غير مزود';

    $region = explode(':',strip_tags($region[0]));
    $region = trim($region[1]);
    if($region == '') $region = 'غير مزود';

    $isp = explode(':',strip_tags($isp[0]));
    $isp = trim($isp[1]);
    if($isp == '') $isp = 'غير مزود';

    $ip  = $ip[0];
    $ip  = trim(str_replace(array('IP:','<','/label>','/th>td>','/td>'),'',$ip));
    if($ip == '') $ip = 'غير مزودided';
 

 

    #
    # end of host info
    #

    #
    # Domain info
    #

    if(function_exists('fsockopen')){
        $pagerank = getpr($url);
        if($pagerank == ''){
            $pagerank = 0;
        }
            
    }
    else{
        $pagerank = 'غير مزود';
    }


    $alexa_rank = get_alexa_popularity($url);
    if($alexa_rank == NULL){
        $alexa_rank = 'غير مزود';
    }



    #
    # End of domain info
    #

    

    //http response

    
    $msg_httpcode = '';
    if($httpcode == 200 || $httpcode == 500 || $httpcode == 301 || $httpcode == 302 || $httpcode == 403){
       $msg_httpcode =  ' <span class="label label-important" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> جيد </span>';
    }
    else{
        $msg_httpcode =  ' <span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;">سيء</span>';
    }  
    

    //end of http response

    //robots.txt check

    

    $robotsurl = $url;
    $robotstats = '';
    if($robotsurl{strlen($robotsurl) - 1} != '/') $robotsurl .= '/';
    $robotsurl .= 'robots.txt';
    $headers = explode("\r\n",getHeaders($robotsurl));
    
    $msg_robots = '';
    if(!empty($headers[0])){
        $httpcode = httpcodextractor($headers[0]);
        if($httpcode == 200 || $httpcode == 500 || $httpcode == 301 || $httpcode == 302 || $httpcode == 403){
            $msg_robots = '<span class="label label-important" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> موجود </span>';
            $sitemapcheck = strpos('sitemap.xml',get($robotsurl));
        }
        else{
            $msg_robots = ' <span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> غير موجود</span>';
        }
    }
    else{
        $msg_robots = ' <span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> غير موجود</span>';
    }
    if(!isset($sitemapcheck)){
        $sitemapcheck = 'abc';
    }

    

    //end of robots check

    //sitemap check (to be tested more)




   
   $msg_sitemapcheck = '';
    $robotsurl = $url;
    $robotstats = '';
    if($robotsurl{strlen($robotsurl) - 1} != '/') $robotsurl .= '/';
    $robotsurl .= 'sitemap.xml';
    $headers = explode("\r\n",getHeaders($robotsurl));

    if(!empty($headers[0])){
        $httpcode = httpcodextractor($headers[0]);
        if($httpcode == 200 || $httpcode == 500 || $httpcode == 301 || $httpcode == 302 || $httpcode == 403){
             $msg_sitemapcheck = '<span class="label label-important" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> موجود </span>';
        }
        elseif(is_int($sitemapcheck)){
             $msg_sitemapcheck = '<span class="label label-important" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> موجود </span>';
        }
        else{
             $msg_sitemapcheck = ' <span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> غير موجود</span>';
        }
    }
    elseif(is_int($sitemapcheck)){
             $msg_sitemapcheck = '<span class="label label-important" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> موجود </span>';
    }
    else{
        $msg_sitemapcheck = ' <span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 2px 4px 3px; font-size: 13px;"> غير موجود</span>';
    }

    

    //end of sitemap check

    #
    # Head elements check
    #

    //grabing meta data

    $url_data = get($url);
    $doc = new DOMDocument();
    @$doc->loadHTML($url_data);
    $nodes = $doc->getElementsByTagName('title');

    //get and display what you need:
    $title = $nodes->item(0)->nodeValue;
    $metas = $doc->getElementsByTagName('meta');
    for ($i = 0; $i < $metas->length; $i++){
        $meta = $metas->item($i);
        if($meta->getAttribute('name') == 'الوصف')
            $description = $meta->getAttribute('المحتوى');
    if($meta->getAttribute('name') == 'كلمات مفتاحية')
            $keywords = $meta->getAttribute('المحتوى');
    }
    if(isset($keywords) && !empty($keywords)){
       $original_keywords = $keywords;
    }
    if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
       $keywords = $_GET['keywords'];
    }

    if(!isset($title)){
        $title = 'غير موجود';
        
    }
    if(!isset($description)){
        $description = 'غير موجود';
    }
    if(!isset($keywords)){
        $keywords = 'غير موجود';
    }

// end of grabbing metadata

//title check
$msg_titlelenght = '';
$msg_kwintitle   = '';
$kwintitlests = '';
if($title != 'غير موجود'){
    $lenght = strlen($title);
    if($lenght <= 20){
         $msg_titlelenght = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$lenght.' (سيء - غير كافي)</span>';
    }
    elseif($lenght >= 21 && $lenght <= 39){
         $msg_titlelenght = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$lenght.' (جيد)</span>';
    }
    elseif($lenght >= 40 && $lenght <= 65){
        $msg_titlelenght = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$lenght.' (ممتاز)</span>';
    }
    elseif($lenght >= 66){
        $msg_titlelenght = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$lenght.' (سيء - زيادة عن اللزوم)</span>';
    }

    $titlestopwords = stopwordscheck($title);
    $msg_titlestopwords = '';
    if($titlestopwords != null){
        $msg_titlestopwords = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">' . implode(',',$titlestopwords) . '</span>';
    }
    else{
        $msg_titlestopwords = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">غير موجود</span>';
    }

    if($keywords != 'غير موجود'){
        $kwintitle = explode(',',$keywords);
        foreach($kwintitle as $kw){
            if(!empty($kw)){
                if(is_int(strpos(trim(strtolower($title)),trim(strtolower($kw))))){
                    $kwintitlests .= '1';
                }
                else{
                    $kwintitlests .= '0';
                }
            }
        }
        if(is_int(strpos($kwintitlests,'1'))){
            $msg_kwintitle = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">موجود</span>';
        }else
        {
            $msg_kwintitle= '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">غير موجود</span>';
       
        }
        
        $msg_kw2titlerel = '';

        if($title != 'غير موجود'){
        
        
          
         $count = 0;
         
         $titletemp = strtolower($title);
         
        foreach(explode(',',$keywords) as $kw){
            if(is_int(strpos($titletemp,  strtolower(trim($kw))))){
                $count = $count + substr_count($titletemp,strtolower(trim($kw)));
            }
        }
        
      
        $common = $count;

        if($common == 0){
            $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 0% (سيء)</span>';       
        }
        elseif($common == 1){
               $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 40% (سيء)</span>';
        }
        elseif($common == 2){
                $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 60% (جيد)</span>';
        }
        elseif($common == 3){
                $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 80% (ممتاز)</span>';
        }
        elseif($common == 4){
               $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 90% (ممتاز جدا)</span>';
        }
        elseif($common == 5 || $common > 5){
                $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 100% (رائع)</span>';
        }
       
        }
        else{
            $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
        }

     }
     else{
         $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
          $msg_kwintitle = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
     }



}
else{
    $msg_titlelenght = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
     $msg_titlestopwords = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
     $msg_kwintitle = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
    $msg_kw2titlerel = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
}




// end of title check





// meta keywords check


if(isset($original_keywords)){
    
    $msg_metakeywords = '<span class="label label-success" style="background-color: rgb(153, 153, 153); color: rgb(255, 255, 255);  padding: 1px 3px 1px; font-size: 11px;">'. $original_keywords . '</span>';
    
    $count = count(explode(',',$original_keywords));
    $msg_keywordcountstats = '';
    if($count <= 0){
        $msg_keywordcountstats = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'. $count .' (سيء, غير كافي)</span>';
    }
    elseif($count > 0 && $count <= 3){
        $msg_keywordcountstats = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'. $count .' (سيء, غير كافي)</span>';    
    }
    elseif($count >3 && $count <= 5){
        $msg_keywordcountstats = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'. $count .' (جيد)</span>';    
    }
    elseif($count >5 && $count <= 9){
        $msg_keywordcountstats = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'. $count .' (ممتاز)</span>';    
    }
    elseif($count > 9){
        $msg_keywordcountstats = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'. $count .' (ممتاز)</span>';      }
    else{
        $msg_keywordcountstats = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';        
    }
}
else{
    $msg_keywordcountstats = '<span class="label label-success" style="background-color: rgb(153, 153, 153); color: rgb(255, 255, 255);  padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
    $msg_metakeywords      = '<span class="label label-success" style="background-color: rgb(153, 153, 153); color: rgb(255, 255, 255);  padding: 1px 3px 1px; font-size: 11px;">غير موجود</span>';
}

// meta keywords check


//meta description chech



if($description != 'غير موجود'){
    $msg_metakwindesc = '';
    $msg_metadescrel  = '';
    $msg_metadesctext = '';
    $msg_descleng = strlen($description);
    $tmp = $msg_descleng;
    if($tmp < 50){
        $msg_descleng = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$tmp.' (Bad,not enough)</span>';
         $msg_metadesctext = $description;
    }
    elseif($tmp >= 50 && $tmp <= 150){
      $msg_descleng = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$tmp.' (Perfect)</span>'; 
      $msg_metadesctext = $description;
    }

    elseif($tmp > 150){
        $msg_descleng = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$tmp.' (To much)</span>'; 
        $msg_metadesctext = substr($description,0,150) . ' [...]';
    }


         $count = 0;
         
         $desctemp = strtolower($description);
         
        foreach(explode(',',$keywords) as $kw){
            if(is_int(strpos($desctemp,  strtolower(trim($kw))))){
                $count = $count + substr_count($desctemp,strtolower(trim($kw)));
            }
        }
        
      
       $common = $count;

        
     if($common == 0){
            $msg_metadescrel = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 0% (سيء)</span>';       
        }
        elseif($common == 1){
                $msg_metadescrel = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 40% (سيء)</span>';
        }
        elseif($common == 2){
                $msg_metadescrel = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 60% (جيد)</span>';
        }
        elseif($common == 3){
                $msg_metadescrel = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 80% (ممتاز)</span>';
        }
        elseif($common == 4){
                $msg_metadescrel = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 90% (ممتاز)</span>';
        }
        elseif($common == 5 || $common > 5){
                $msg_metadescrel = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 100% (ممتاز جدا)</span>';
        }
           
        $tmpstr = '';
        $tmp = 0;
        $tmpdesc = strtolower($description);
        foreach(explode(',',$keywords) as $kw){
            if(is_int(strpos($tmpdesc,trim(strtolower($kw))))){
                $tmp .= '1';
            }
        else{
                $tmp .= '0';
           }
        }
                
        if(is_int(strpos($tmp,'1'))){
           $msg_metakwindesc = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> موجود</span>';
        }
        else{
           $msg_metakwindesc = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">غير موجود</span>';
        }
        
}
else{
      $msg_metakwindesc = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
     $msg_metadesctext = $description;
     $msg_descleng = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';     
}



    
    
    
    
    
    
//meta description chech    
    
    
    
    
    
    


    #
    # content check
    #




    $pageData = get($url);

    $str = $pageData;

    $str = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $str);
    $str = str_replace(array("\r","\rn","\n"),'',$str);
    $str = strip_tags($str);
    $str = preg_replace("/[^A-Za-z0-9 .,\"]/", '', $str);
    $strtemp = array();
    foreach(explode(' ',$str) as $word){
        if(strlen($word) != 0){

            $strtemp[] = $word;

        }
    }

    $pageData = implode(' ',$strtemp);

    if(isset($pageData) && !empty($pageData)){
        $contentlenght = strlen($pageData);
        $contentwords  = str_word_count($pageData);
        $kwincontent   = '';
        if($contentlenght < 150){
            $contentlenght = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'. $contentlenght .' (سيء, غير كافي)</span>';

        }
         elseif($contentlenght >= 151 && $contentlenght <= 299){
            $contentlenght = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'. $contentlenght .' (جيد)</span>';

        }
        elseif($contentlenght > 300){
             $contentlenght = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'. $contentlenght .' (ممتاز)</span>'; 
        }

        if($contentwords < 25){
            $contentwords = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> '. $contentwords .' (سيء, غير كافي)</span>';
        }
        elseif($contentwords >= 26 && $contentwords <= 49){
            $contentwords = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> '. $contentwords .' (جيد)</span>';
        }
        elseif($contentwords > 50){
         $contentwords = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> '. $contentwords .' (ممتاز)</span>';  
        }

         if($keywords != 'غير موجود'){
            $kwintitle = explode(',',$keywords);
            foreach($kwintitle as $kw){
            if(!empty($kw)){
                if(is_int(strpos(trim(strtolower($pageData)),trim(strtolower($kw))))){
                    $kwincontent .= '1';
                }
                else{
                    $kwincontent .= '0';
                }
            }
        }
        if(is_int(strpos($kwincontent,'1'))){
            $kwincontent = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> موجود</span>';
        }else{
             $kwincontent = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
       
        }
        $count = 0;
        
        
        
        foreach($kwintitle as $kw){
            if(is_int(strpos($pageData,  strtolower(trim($kw))))){
                $count = $count + substr_count($pageData,strtolower(trim($kw)));
            }
        }
        
        
       $common = $count;
        if($common == 0){
            $contentrelevance = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 0% (سيء)</span>';       
        }
        elseif($common >= 1 && $common <= 5){
           $contentrelevance = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 20% (سيء)</span>';  
        }
       
        elseif($common > 5 && $common <= 11){
                $contentrelevance = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 40% (سيء)</span>';
        }
        elseif($common > 11 && $common <= 16){
                $contentrelevance = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 60% (جيد)</span>';
        }
        elseif($common > 16 && $common <= 21){
                $contentrelevance = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 80% (ممتاز)</span>';
        }
        elseif($common > 21 && $common <= 26){
                $contentrelevance = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 90% (ممتاز)</span>';
        }
        elseif($common > 26){
                $contentrelevance = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> 100% (ممتاز جدا)</span>';
        }
         }
         else{
            $contentrelevance = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> تعذر الحساب</span>';
             $kwincontent = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> تعذر الحساب </span>';
       
         }
    }  
    else{
        $contentlenght = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> تعذر الحساب.</span>'; 
        $kwincontent = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
        $contentwords = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> تعذر الحساب.</span>';
        $contentrelevance = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> تعذر الحساب.</span>';
    }

    
    
    // checking for italic elements 
    
    
$tmp = '';
$msg_contentitalics = '';
preg_match_all('#<i>(.+?)</i>#', $url_data, $italics);
if(!empty($italics[0])){
    $italics = trim(strtolower(implode('',$italics[0])));
    if($keywords != 'غير موجود'){
    foreach(explode(',',$keywords) as $kw){
        if(is_int(strpos($italics,trim(strtolower($kw))))){
          $tmp .= '1';  
        }
        else{
            $tmp .= '0';
        }
                
    }
    if(is_int(strpos($tmp,'1'))){
        $msg_contentitalics = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> موجود</span>';
    }
    else{
        $msg_contentitalics = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
    }
}
else{
      $msg_contentitalics = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> تعذر الحساب</span>';
}

    }
    else{
         $msg_contentitalics = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
    }   
    // end of checking for italic elements 
    
    //bold checkings
    
$tmp = '';
$msg_contentbold = '';
preg_match_all('#<b>(.+?)</b>#', $url_data, $bolds);
preg_match_all('#<strong>(.+?)</strong>#', $url_data, $bolds2);

$bolds = array_merge($bolds[0],$bolds2[0]);

if(!empty($bolds)){
    $bolds = trim(strtolower(implode('',$bolds)));
    if($keywords != 'غير موجود'){
    foreach(explode(',',$keywords) as $kw){
        if(is_int(strpos($bolds,trim(strtolower($kw))))){
          $tmp .= '1';  
        }
        else{
            $tmp .= '0';
        }
                
    }

    if(is_int(strpos($tmp,'1'))){
        $msg_contentbold = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> موجود</span>';
    }
    else{
        $msg_contentbold  = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
}}
else{
     $msg_contentbold = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> تعذر الحساب</span>';
}

    }
    else{
        $msg_contentbold = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
    }  
    
    // end of bold chekcings
    
    
    
    //headings check
    
    preg_match_all('/<h\d>([^<]*)<\/h\d>/iU', $url_data, $headings);
    $msg_contentheadings = '';
    $_1 = 0;
    $_2 = 0;
    $_3 = 0;
    $_4 = 0;
    $_5 = 0;
    $_6 = 0;
    if(!empty($headings[0])){
        foreach($headings[0] as $head){
            if(is_int(strpos(trim(strtolower($head)),'<h1>'))){
                $_1++;
            }
            if(is_int(strpos(trim(strtolower($head)),'<h2>'))){
                $_2++;
            }
            if(is_int(strpos(trim(strtolower($head)),'<h3>'))){
                $_3++;
            }
            if(is_int(strpos(trim(strtolower($head)),'<h4>'))){
                $_4++;
            }
            if(is_int(strpos(trim(strtolower($head)),'<h5>'))){
                $_5++;
            }
            if(is_int(strpos(trim(strtolower($head)),'<h6>'))){
                $_6++;
            }
        }
        $msg_contentheadings = '<span class="label label-success" style="background-color: rgb(153, 153, 153); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"><strong>h1:</strong>' . $_1 . '<strong>h2:</strong>' . $_2 .'<strong>h3:</strong>' . $_3 . '<strong>h4:</strong>' . $_4. '<strong>h5:</strong>' . $_5. '<strong>h6:</strong>' . $_6 . '</span>';
    }
    else{
        $msg_contentheadings = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
    }
    
    
    //end of headings check
    
    
    // iframe checking
    $msg_contentframes = '';
    preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $url_data, $matches);
    if(empty($matches[0])){
        $msg_contentframes = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">غير موجود</span>';
    }
    else{
    $msg_contentframes = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">موجود</span>';    
    }
    
    // end of iframe checkings
    
    
    
    
    #
    # end of content check
    #
    



    #
    # url structure check
    #




    $msg_urldomain = strtolower(clean_url($url));
    $msg_urlsubdomain = '';

    if(count(explode('.',$msg_urldomain)) > 2){
        $msg_urlsubdomain = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> موجود</span>';
    }
    else{
         $msg_urlsubdomain = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
    }

    $tmp = '';
    $msg_kwinurl = '';
    if($keywords != 'غير موجود'){
        foreach(explode(',',$keywords) as $kw){
            if(is_int(strpos($msg_urldomain,trim(strtolower($kw))))){
                $tmp .= '1';
            }
            else{
                $tmp .= '0';
            }
        }
        if(is_int(strpos($tmp,'1'))){
        $msg_kwinurl = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> موجود</span>';
    }
    else{
        $msg_kwinurl = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';
    }

    }
    else{
         $msg_kwinurl = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;"> غير موجود</span>';

    }
    
    $msg_urldomainlenght = '';
    $tmp = strlen($msg_urldomain);
    if($tmp > 76){
        $msg_urldomainlenght = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">' . $tmp . ' (سيء, زيادة عن اللزوم)</span>';
    }
    else{
        $msg_urldomainlenght = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">' . $tmp . ' (جيد)</span>';
       }


       

    #
    # end of url structure check
    #


    #
    # links check
    #


    $doc = new DOMDocument();
    $doc->loadHTML($url_data);
    $xpath = new DOMXPath($doc);
    $urls = array();
    $nodeList = $xpath->query('//a/@href');
    for ($i = 0; $i < $nodeList->length; $i++) {
        $urls[] = $nodeList->item($i)->value . "<br/>\n";
    }
    $tmp = 0;
    foreach($urls as $url){
        if(is_int(strpos($url,'http')) || is_int(strpos($url,'www'))){
            if(!is_int(strpos($url,$msg_urldomain))){
                $tmp++;
            }
        }
    }
    $msg_externalinks = $tmp;
    $msg_internalinks = count($urls) - $tmp ;

    if($msg_internalinks > 100){
        $msg_internalinks = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$msg_internalinks.' (زيادة عن اللزوم)</span>';
    }
    else{
      $msg_internalinks = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">'.$msg_internalinks.' </span>';
    }

    $msg_externalinks = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">' . $tmp . '</span>';



    #
    # end of links check
    # 


    //other calculations
    
    
    if(isset($original_keywords)){
    $msg_metakeywordsrel = (extractint(strip_tags($msg_kw2titlerel)) + extractint(strip_tags($contentrelevance)) ) / 2;
    $tmp = $msg_metakeywordsrel + 25;
    if(is_int($tmp)){
       if($tmp < 40){
        $msg_metakeywordsrel = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">' . $msg_metakeywordsrel . '%</span>';
       }
       elseif($tmp >= 40 && $tmp <= 60){
         $msg_metakeywordsrel = '<span class="label label-success" style="background-color: rgb(248, 148, 6); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">' . $msg_metakeywordsrel . '%</span>';
       }
        elseif($tmp > 60){
         $msg_metakeywordsrel = '<span class="label label-success" style="background-color: rgb(70, 136, 71); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">' . $msg_metakeywordsrel . '%</span>';
       }

    }
    else{
       $msg_metakeywordsrel = '<span class="label label-success" style="background-color: rgb(153, 153, 153); color: rgb(255, 255, 255);  padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
    }
    }else{
        $msg_metakeywordsrel = '<span class="label label-success" style="background-color: rgb(153, 153, 153); color: rgb(255, 255, 255);  padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
    }
    
    
    
    //some double checkings
    
    if(!isset($msg_metakeywordsrel) || empty($msg_metakeywordsrel)){
        $msg_metakeywordsrel = '<span class="label label-success" style="background-color: rgb(153, 153, 153); color: rgb(255, 255, 255);  padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
    }
    if(!isset($msg_metadescrel) || empty($msg_metadescrel)){
        $msg_metadescrel = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">تعذر الحساب</span>';
    }
    



    $msg_title = '';
    if($title == 'غير موجود'){
       $msg_title = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">غير موجود</span>'; 
    }
    else{
        $msg_title = htmlentities($title,ENT_QUOTES);
    }

   

    if( $msg_metadesctext == 'غير موجود'){
        $msg_metadesctext = '<span class="label label-success" style="background-color: rgb(185, 74, 72); color: rgb(255, 255, 255); padding: 1px 3px 1px; font-size: 11px;">غير موجود</span>'; 
    }
    else{
         $msg_metadesctext = htmlentities($description,ENT_QUOTES);
    }

    //other calculations