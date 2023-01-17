<?php
require_once('php/config.php');
?>



<!doctype html>
<html lang="ar" dir="rtl" ng-app="myApp">
<head>
<?php require_once('php/header.php'); ?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/ar_AR/sdk.js#xfbml=1&version=v9.0&appId=1533356736719599&autoLogAppEvents=1" nonce="RVWyXAEZ"></script>

</head>
<body>

  
  <div id="top">
    <div class="container">
      <div class="row">
        <div class="span12">
         
           <h1><a href="index.php" id="homelink"><?php print $site_name ?></a></h1>
          <p><?php print $site_slogan ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="container" ng-controller="projectorCtrl">

    <h1>اهلا بك في <?php print $site_name; ?></h1>
    <p>مع موقعنا ، يمكنك إنشاء تقارير SEO احترافية لك و / أو لعملائك. نحن نقدم لك إحصاءات حول الزحف وعناصر الرأس والمحتوى والروابط والمجال وبنية عنوان url وغير ذلك الكثير.
     يمكنك أيضًا التحقق من موقعك مقابل كلمات رئيسية معينة ، لمعرفة مدى ملاءمة موقعك. باستخدام هذه الخدمة ، يمكنك كسب الكثير من المستخدمين الجدد وتحسين جودة الموقع أكثر من ذلك بكثير.
      ضع رابط موقعك في المربع ادناه ، ومع بعض الكلمات المفاتحية (إذا كنت تريد) مفصولة بفاصلة.</p>
      <br />
      <div class="row">
<center>
  <div class="span12">
    <form method="get" action="analyser.php" class="form-inline" >
      <input name="site" class="span5" type="text"  placeholder="ضع رابط موقعك هنا" >
      <input name="keywords" class="span5" type="text"  placeholder="كلمات مفتاحية: انكور,تطوير,اشهار,تصميم">
      <button type="submit" class="btn btn-primary" style="margin-top:-5px;"> <i class="icon-search icon-white"></i></button>
    </form>
  </div>
</center>
</div>
   <h1>تم تحليل المواقع:</h1>
   <br />


   <?php
   $file = array_reverse(file('php/' . $site_dbfile));
   for($j=0;$j<3;$j++){
    print '<div class="latest">
    ';
     for($i=0;$i<5;$i++){
		$s_url = clean_url($file[$i]);
		if(strlen($s_url) > 16){
			$s_url = substr($s_url,0,16) . '...';
		}
        print '<span class="aqua"><a href="analyser.php?site='.clean_url($file[$i]).'" >'. $s_url .'</a></span><br />';
     }
     print '
     </div>';
     unset($file[0],$file[1],$file[2],$file[3],$file[4]);
     $file = array_values($file);
   }
   ?>

  
   <br />
   <br />
   <br />
   <br />
   <br />
   <br />
   
<hr />
<center>
   <div class="fb-page" data-href="https://www.facebook.com/inkorcompany" data-tabs="timeline" data-width="420px" data-height="220px" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/inkorcompany" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/inkorcompany">‎شركة انكور التطويرية‎</a></blockquote></div>
</center>
  </div>



<?php require_once('php/footer.php'); ?> 






</body>
</html>
