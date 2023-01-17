<?php
require_once('php/config.php');
?>


<!doctype html>
<html lang="ar" dir="rtl" ng-app="myApp">
<head>
<?php require_once('php/header.php'); ?>

<script>
$(document).ready(function() {

  $('.tabs button').click(function(){
    switch_tabs($(this));
  });
 
  switch_tabs($('.defaulttab'));
 
});
function switch_tabs(obj)
{
  $('.tab-content').hide();
  $('.tabs button').removeClass("selected");
  var id = obj.attr("rel");
 
  $('#'+id).show();
  obj.addClass("selected");
}
</script>

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

  




 

  <?php

  $url  = trim(urldecode($_GET['site'])); 
  $url0 = $url;
  print 'تحميل تقرير SEO لـ: '. trim(htmlentities($url0,ENT_QUOTES));
  $site_headers = explode("\r\n",getHeaders($url));
  if(!empty($site_headers[0])){
  $httpcode = httpcodextractor($site_headers[0]);

  if($httpcode == 200 || $httpcode == 500 || $httpcode == 301 || $httpcode == 302 || $httpcode == 403){
    

     add_site($url,'php/' . $site_dbfile);
    print ' <font color="green">[Host up] </font><hr/>';
   

    #here the magic starts
    require_once('php/core.php'); 
    #and here it ends
   

    #and now let`s print the results

    
      //
      //host info printing
      //

      print '
      <div id="info_wrapper">
      <div class="info">
      <h1>معلومات الاستضافة</h1>
      العنوان: ' . $ip . ' | <a href="http://www.spamhaus.org/query/domain/'.clean_url($url0).'" target="_blank">حالة القائمة السوداء</a> <br />
      الدولة: ' . $country . '<br />
      المنطقة: ' . $region . '<br />
      المزود: ' . $isp . '<br />
      </div>
      ';

      //
      //domain info printing
      //

      print '
      <div class="info">
      <h1>معلومات الدومين</h1>
      Pagerank: ' . $pagerank . '<br/>
      Alexa rank: '. $alexa_rank .'<br/>
      WhoIs: <a href="http://whois.net/whois/'.clean_url(htmlentities($url0,ENT_QUOTES)).'" target="_blank">عرض معلومات النطاق</a>
      <br/>
      </div>
      </div>
      <center>
      <h2>تقرير Seo</h2>
      <div id="buttons">
      <ul class="tabs">
       <button href="#" class="defaulttab" rel="tabs1">حالة الزحف</button>
        <button href="#" rel="tabs2">فحص عناصر الرأس</button>
        <button href="#" rel="tabs3">فحص المحتوى</button>
        <button href="#" rel="tabs4">فحص بنية الرابط</button>
        <button href="#" rel="tabs5">تحقق الروابط</button>
      </div>
      </center>
      <hr/>
      <div id="results">
      ';

      //
      //crawling report printing
      //

      print '<div class="tab-content" id="tabs1"><h1>1.حالة الزحف</h1>
      <br />
      <span class="aqua">A)</span><span class="ginfo2"> استجابة HTTP</span>' . $msg_httpcode .
      '<br />
      <br />
      <div class="alert alert-block" style="padding: 8px 35px 8px 14px; background-color: rgb(252, 248, 227); border: 1px solid rgb(251, 238, 213); color: rgb(252, 248, 227);color:black;">
      ';

      foreach($site_headers as $header){
          if(trim($header) != ''){
             print $header . '<br />';
          }
      }

      print '</div>
      <div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);">
        <strong>معلومات ونصائح:</strong> 
        يتم استخدام رؤوس Http بواسطة الروبوتات وبرامج الزحف لفهم بنية موقعك. تأكد من أن موقعك يستخدم الرؤوس الصحيحة. مزيد من المعلومات حول رموز HTTP والعناوين في <a href="www.seomoz.org/learn-seo/http-status-codes">www.seomoz.org/learn-seo/http-status-codes</a> 
      </div>
      <br />
      <span class="aqua">B)</span><span class="ginfo2"> فحص ملف robot.txt</span>' .  $msg_robots . '
      <br />
      <br />
      <div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);">
        <strong>/معلومات ونصائح:</strong>
        يوفر ملف robots.txt معلومات حول الأدلة التي يُسمح للزحف إليها عن طريق العناكب وبرامج الزحف وبرامج الروبوت. يمكنك العثور على مزيد من المعلومات حول <a href="http://www.robotstxt.org/robotstxt.html">http://www.robotstxt.org/robotstxt.html</a>
      </div>
      <span class="aqua">C)</span><span class="ginfo2"> فحص خريطة الموقع </span>' .  $msg_sitemapcheck . '
      <br />
      <br />
      <div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);">
        <strong>معلومات ونصائح:</strong> 
        يتم استخدام خرائط المواقع بواسطة محركات البحث "لرسم" خريطة لموقعك. قد يكون هذا الفحص إيجابيًا كاذبًا. مزيد من المعلومات حول ملفات sitemap على<a href="http://support.google.com/webmasters/bin/answer.py?hl=en&answer=183669">http://support.google.com/webmasters/bin/answer.py?hl=en&answer=183669</a>
      </div>
      </div>';

      //
      //head elements report printing
      //

      print '<div class="tab-content" id="tabs2">
      <h1>2. فحص عناصر الرأس</h1>
      <br />';

      //title

      print '<span class="aqua">A)</span><span class="ginfo2"> Title tag فحص</span>
      <br />
      <br />';
      print '<span class="ginfo">Title: </span>' . $msg_title . '<br />';
      print '<span class="ginfo">Lenght: </span> ' . $msg_titlelenght . '<br />';
      print '<span class="ginfo">Stop words:</span> ' . $msg_titlestopwords . '<br />';
      print '<span class="ginfo">Keyword(s) in title:</span> ' . $msg_kwintitle . '<br />';
      print '<span class="ginfo">Keyword relevance:</span> ' . $msg_kw2titlerel . '<br /><br />';
      print '<div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);">
        <strong>Info & tips:</strong> 
        د علامة العنوان مصدرًا مهمًا للغاية لتحسين محركات البحث. لا توجد وصفة معينة لتحسين العنوان بنسبة 100٪ ، ولكن يوصى باستخدام عنوان بطول يتراوح بين 40 و 65 حرفًا ، ولا توجد  <a href="http://www.link-assistant.com/seo-stop-words.html">كلمات توقف</a>في العنوان ، وعلاقة جيدة بين العنوان والكلمات الرئيسية التي تروج لها و المحتوى الخاص بك. مزيد من المعلومات: <a href="http://www.seomoz.org/learn-seo/title-tag">http://www.seomoz.org/learn-seo/title-tag</a>
      </div><br />';

      //keywords

      print '<span class="aqua">B)</span><span class="ginfo2"> فحص الكلمات المفتاحية</span><br/>';
      print '<span class="ginfo">الكلمات المفاتحية: </span>' . $msg_metakeywords . '<br />';
      print '<span class="ginfo">عدد الكلمات:</span> ' . $msg_keywordcountstats . '<br />';
      print '<span class="ginfo">علاقة الكلمات بالموقع:</span> '. $msg_metakeywordsrel . '<br /><br />';
      print '<div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);">
        <strong>معلومات ونصائح:</strong> 
        على الرغم من أن محركات البحث الرئيسية مثل google أو bing تتجاهل الكلمات الرئيسية الوصفية لعدة سنوات حتى الآن ، إلا أن بعض خبراء تحسين محركات البحث يزعمون أنهم ما زالوا يلعبون دورًا في التحسين على الصفحة. من المستحسن أن يكون لديك عدد من الكلمات الرئيسية بين 5 و 9 وتقرير صلة لائق. يمكنك قراءة المزيد عن الكلمات الرئيسية المفاتحية هنا:<a href="http://www.w3schools.com/tags/tag_meta.asp">http://www.w3schools.com/tags/tag_meta.asp</a>
      </div>';

      //description

      print '<span class="aqua">C)</span><span class="ginfo2"> فحص الوصف التعريفي</span><br/>';
      print '<span class="ginfo">الوصف:</span> ' . $msg_metadesctext . '<br/>';
      print '<span class="ginfo">طول الوصف:</span> ' . $msg_descleng . '<br/>';
      print '<span class="ginfo">الكلمات المفتاحية بالوصف:</span> ' . $msg_metakwindesc . '<br/>';
      print '<span class="ginfo">الصلة بالكلمات المفاتحية:</span> ' . $msg_metadescrel . '<br/>';
      print '<div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);"><br />
        <strong>معلومات ونصائح:</strong> 
        تعد علامة الوصف التعريفي طريقة مفيدة لوصف المحتوى الذي تستضيفه صفحتك. تجنب وجود نفس الأوصاف في جميع صفحاتك. يبلغ طول الوصف التعريفي المناسب 150/160 حرفًا كحد أقصى ، وهو وثيق الصلة بالكلمات الرئيسية التي تريد الترويج لموقعك بها ، وبالطبع الكلمات الرئيسية في الوصف. يمكنك العثور على مزيد من المعلومات على:
        <a href="http://www.seomoz.org/learn-seo/meta-description">http://www.seomoz.org/learn-seo/meta-description</a>
      </div></div>';

      //
      //content report printing
      //

      print '<div class="tab-content" id="tabs3">
      <h1>3. فحص المحتوى</h1>
      <br />';
      print '<span class="ginfo">عدد أحرف المحتوى: </span>' . $contentlenght . '<br />';
      print '<span class="ginfo">عدد كلمات المحتوى: </span>' . $contentwords . '<br />';
      print '<span class="ginfo">الكلمات المفتاحية في المحتوى: </span>' . $kwincontent . '<br />';
      print '<span class="ginfo">صلة المحتوى </span>' . $contentrelevance . '<br />';
      print '<span class="ginfo">كلمات مفتاحية مائلة: </span>' . $msg_contentitalics . '<br />';
      print '<span class="ginfo">كلمات مفتاحية مغمقة: </span>' . $msg_contentbold . '<br />';
      print '<span class="ginfo">فحص العناوين: </span>' . $msg_contentheadings . '<br />';
      print '<span class="ginfo">تحقق إطارات Iframes: </span>' . $msg_contentframes . '<br /><br />'; 
      print '<div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);">
        <strong>معلومات ونصائح:</strong> 
        محتوى الموقع هو أهم شيء في SEO ، فكلما كان المحتوى الخاص بك فريدًا ، زادت فرصة فهرسته بواسطة محركات البحث. هناك بعض الأشياء التي يجب أن تضعها في اعتبارك. يجب أن يكون المحتوى الخاص بك أكثر من 300 حرفًا ، أو 50 كلمة ، ويجب أن يحتوي على الكلمات الرئيسية التي تروج لها في جمل جيدة التنسيق ، ويجب أن تكون كلماتك الرئيسية في علامات (مائلة) و (غامقة) (ولكن ليس كثيرًا) يجب أن تحتوي أيضًا على علامات. تجنب استخدام إطارات iframe ، لأن المحتوى غير مفهرس. المزيد من النصائح :<a href="http://www.seomoz.org/learn-seo/on-page-factors">http://www.seomoz.org/learn-seo/on-page-factors</a>
      </div>
      </div>';

      //
      //url structure report printing
      //

      print '<div class="tab-content" id="tabs4">
      <h1>4. فحص بنية الرابط</h1>';
      print '<span class="ginfo">النطاق: </span>' . $msg_urldomain . '<br />';
      print '<span class="ginfo">الكلمات المفاتيحة في النطاق: </span>'. $msg_kwinurl . '<br />';
      print '<span class="ginfo">طول النطاق: </span>' .$msg_urldomainlenght . '<br />';
      print '<span class="ginfo">نطاق فرعي: </span>' . $msg_urlsubdomain . '<br /><br />';
      print '<div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);">
        <strong>معلومات ونصائح:</strong> 
       النطاق هو عامل مهم آخر في تحسين محركات البحث ، يجب عليك اختيار أسماء النطاقات التي يسهل تذكرها ، بدون أحرف خاصة ولا يزيد طولها عن 76 حرفًا. تعتبر محركات البحث أيضًا أن البيانات المستضافة على النطاقات الفرعية أقل أهمية ، لذا حاول استضافة مشاريعك على المجالات ، وليس المجالات الفرعية. TLD مهم أيضًا ، حاول شراء TLDs المناسبة ، وفقًا لمكانة موقعك. المزيد حول اختيار اسم المجال:<a href="http://www.seomoz.org/learn-seo/domain">http://www.seomoz.org/learn-seo/domain</a>
      </div>
      </div>';

      //
      //links report printing
      //

      print '<div class="tab-content" id="tabs5">
      <h1>5. تحقق الروابط</h1>';
      print '<span class="ginfo">روابط داخلية: </span>' .  $msg_internalinks . '<br />';
      print '<span class="ginfo">روابط خارجية: </span>'.  $msg_externalinks . '<br /><br />';
      print '<div class="alert alert-info" style="padding: 8px 35px 8px 14px; background-color: rgb(217, 237, 247); border: 1px solid rgb(188, 232, 241); color: rgb(58, 135, 173);">
        <strong>معلومات ونصائح:</strong> 
        من خلال الروابط ، يتم الزحف إلى موقعك ، لذا تذكر أن تتحقق طوال الوقت من الروابط المعطلة. من المستحسن أيضًا ألا يكون لديك أكثر من 100 رابط داخلي (روابط تشير إلى موقعك). استخدم دائمًا عنوانًا مناسبًا ومرساة للروابط الخاصة بك. المزيد عن تحسين الروابط هنا:<a href="http://www.seomoz.org/learn-seo/internal-link">http://www.seomoz.org/learn-seo/internal-link</a>
      </div>
      </div>
      </div>';

      //add_site($url);
   }
  else{
      print '<font color="red"> [Host down]</font>';
  }
  }
  else{
      print '<font color="red"> [Host down]</font>';
  }

  ?>
  </div>



<?php require_once('php/footer.php'); ?> 

</body>
</html>
