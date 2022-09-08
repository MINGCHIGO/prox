<?php
$TEMP_DIR='temp';
if(!file_exists ($TEMP_DIR)){
  mkdir("$TEMP_DIR", 0777, true);
}
error_reporting(0);
if(isset($_REQUEST['clear'])&&$_REQUEST['clear']=='clear'){
  clearTemp(true);
  header('Location: '.$_SERVER['PHP_SELF']);
  die();
}else{
  clearTemp(false);
}
if(isset($_REQUEST['url'])&&!empty($_REQUEST['url'])) {
  $url = $_REQUEST['url'];
  $encode=(isset($_REQUEST['encode'])&&$_REQUEST['encode']=='yes')?'yes':'no';
  $full=(isset($_REQUEST['full'])&&$_REQUEST['full']=='yes')?'yes':'no';
  $fixhref=(isset($_REQUEST['fixhref'])&&$_REQUEST['fixhref']=='yes')?'yes':'no';
  loadPage($url,$encode,$full,$fixhref);
} else {
  ?>

 <!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
        <title>Google</title>
        <style>
header {
    position: absolute;
    top:12px;
    right:30px;
}

header a:link, a:visited, a:active {
    color: #2a2a2a;
    font-family: arial, sans-serif;
    font-size:12px;
    text-decoration:none;
    margin-right:5px;
    margin-left:5px;
    position:relative;
    top:-10px;
}

header a:hover {
    text-decoration:underline;
}

#products {
    margin-right:8px;
    margin-left:8px;
}

#bell {
    margin-right:10px;
    margin-left:8px;
}

#share {
    margin-right:10px;
    margin-left:10px;
}

#lara {
    border-radius:50%;
}

div {
    display:block;
    margin: auto;
    text-align:center;
    padding-top: 14%;
}

div img {
    width: 270px;
    height: 95px;
    margin:5px;
}

.search {
    width: 60%;
    height: 28px;
    font: 16px arial, sans-serif;
    text-indent:5px;
    background-repeat:no-repeat;
    background-position:548px 4px;
    background-size:14px 19px;
    border-radius: 20px;
    background-color: #444444;
    color: white;
    }

.button {
    border: 1px solid #d3d3d3;
    background: #f3f3f3;
    color:#696969;
    margin-left:4px;
    margin-right:4px;
    margin-top: 25px;
    font-family: arial, sans-serif;
    font-size: 11px;
    font-weight: bold;
    padding: 7px;
    border-radius:40px;
}

.buttonsearch {
    height: 28px;
    border: 1px solid #d3d3d3;
    background: #f3f3f3;
    color:#696969;
    margin-left:4px;
    margin-right:4px;
    margin-top: 25px;
    font-family: arial, sans-serif;
    font-size: 11px;
    font-weight: bold;
    padding: 7px;
    border-radius:40px;
}

.button:hover {
    color: #2a2a2a;
    border: 1px solid #bdbdbd;
}

.search:hover {
    border:1px solid #aaaaaa;
    background-color: #aaaaaa
}

footer {
    position: absolute;
    bottom:0px;
    left:0px;
    right:0px;
    background:#555555;
    padding-top:22px;
}

footer a:link, a:visited, a:active {
    color: white;
    font-family: arial, sans-serif;
    font-size:12px;
    text-decoration:none;
    }
    
footer a:hover {
    text-decoration:underline;
}
    
.leftlinks {
    position: relative;
    bottom:12px;
    left:18px;
    padding-right:15px;
    padding-left:15px;
}

.rightlinks {
    position: relative;
    bottom:9px;
    float:right;
    right:18px;
    padding-right:15px;
    padding-left:15px;
}

body {
    background-color: #202124;
}
         
.check {
    visibility: hidden;       
}
    
input[type="search"]::-webkit-search-decoration,
input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-results-button,
input[type="search"]::-webkit-search-results-decoration { display: none; }
input[type=text]::-ms-clear {  display: none; width : 0; height: 0; }
input[type=text]::-ms-reveal {  display: none; width : 0; height: 0; }

        </style>
    </head>
    <body>
        <header>
            <a href="https://mail.google.com">Gmail</a>
            <a href="https://www.google.com/imghp?hl=en&tab=wi&ei=Ox1NVMXuJYO3yATCjoFA&ved=0CAQQqi4oAg">Images</a>
        </header>
        <div>
            <img src="https://www.google.com/images/branding/googlelogo/1x/googlelogo_light_color_272x92dp.png"/>
             <form method="get" action="index.php">
    <input type="text" name="url" class="search"/><br/>
   <input type="checkbox" class="check"  name="full" value="yes" <?=is_dir($TEMP_DIR)?'':'disabled="disabled"'?>/>
   <input type="checkbox" class="check"   name="encode" value="yes" checked="checked"/>
<input type="checkbox" class="check" name="fixhref" value="yes" checked="checked"/><br/>
    <input class="buttonsearch" type="submit" value="Google Search"/>
    <button tabIndex="-1" class="button" onclick="if(confirm('Sure?')==true){window.location.href='?clear=clear';} return false;">I'm Feeling Lucky</button>
  </form>
        </div>
        <footer>
            <a class="leftlinks" href="https://www.google.com/intl/en/ads/?fg=1">Advertising</a>
            <a class="leftlinks" href="https://www.google.com/services/?fg=1">Business</a>
            <a class="leftlinks" href="https://www.google.com/intl/en/about/">About</a>
            <a class="rightlinks" href="https://www.google.com/preferences?hl=en">Settings</a>
            <a class="rightlinks" href="https://www.google.com/intl/en/policies/?fg=1">Privacy & Terms</a>
        </footer>
    </body>
</html>

  <?php
}

function loadPage($targetUrl,$encode,$full,$fixhref){
  global $TEMP_DIR;
  $localHttpProtocol=(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off')?'https://':'http://';
  //  Prepend http protocol if not exists
  if(!preg_match("/^(?:https?:\\/\\/).+/i",$targetUrl)){
    $targetUrl='http://'.$targetUrl;
  }
  //  Remove duplicated '/'
  $targetUrl=preg_replace("/([^:\\/]+?)(\\/\\/+)/i","$1/",$targetUrl);
  //  Figure out local and target urls
  preg_match("/^(?:https?:\\/\\/)(?:.+\\/|.+)/i",$targetUrl,$basicTargetUrlMatch);
  $basicTargetUrl=$basicTargetUrlMatch[0];
  preg_match("/^(?:https?:\\/\\/)((?:(?!\\/).)+)[\\/]?/i",$basicTargetUrl,$veryBasicTargetUrlMatch);
  $veryBasicTargetLocalUrl=$veryBasicTargetUrlMatch[1];
  preg_match("/^(?:https?:\\/\\/)(?:.+\\/|.+)/i",$localHttpProtocol.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],$basicLocalUrlMatch);
  $basicLocalUrl=$basicLocalUrlMatch[0];

  //  Get original html view
  $cookieFile=$TEMP_DIR.'/'.'CURLCOOKIE_'.urlencode($veryBasicTargetLocalUrl).".txt";
  //$UAIE = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
  $UAChrome='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36';
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL, $targetUrl);
  curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_USERAGENT,$UAChrome);
  curl_setopt($ch,CURLOPT_COOKIEFILE,$cookieFile);
  curl_setopt($ch,CURLOPT_COOKIEJAR,$cookieFile);
  //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $html = curl_exec ($ch);
  curl_close($ch);

  if($full=='yes'){
    //  Fix full resources
    $resPattern="/<.+?(?:src=|href=|url)['\"\\(]?((?:(?![>'\"]).)*?\\.(?:jpg|jpeg|png|gif|bmp|ico|js|css))['\"\\)]?.*?(?:\\/>|>)/i";
    preg_match_all($resPattern,$html,$matchReses);
    for($i=0;$i<count($matchReses[0]);$i++){
      if(strlen($matchReses[1][$i])<=0){
        continue;
      }
      $newResPath=downloadToTemp($matchReses[1][$i],$basicTargetUrl,$TEMP_DIR,$basicLocalUrl);
      $html=str_replace($matchReses[0][$i],str_replace($matchReses[1][$i],$newResPath,$matchReses[0][$i]),$html);
    }
  }

  if($fixhref=='yes'){
    //  Fix href for web links
    $hrefPattern="/<.+?(?:src=|href=|action=)['\"]?((?!(?:(?:https?:)?\\/\\/)|javascript:)(?:(?![>'\"]).)*)['\"]?.*?(?:\\/>|>)/i";
    preg_match_all($hrefPattern,$html,$hrefMatches);
    for($i=0;$i<count($hrefMatches[0]);$i++){
      if(strlen($hrefMatches[1][$i])<=0){
        continue;
      }
      $html=str_replace($hrefMatches[0][$i],str_replace($hrefMatches[1][$i],$basicTargetUrl.'/'.$hrefMatches[1][$i],$hrefMatches[0][$i]),$html);
    }
  }

  //  Add onclick method for href to avoid jumping out
  $html=preg_replace('/href=/','onclick="window.location.href=\''.$localHttpProtocol.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=\'+escape(this.href)+\'&encode='.$encode.'\'+\'&fixhref='.$fixhref.'\'+\'&full='.$full.'\';return false;" href=',$html);

  //  Output html view
  header('Content-Security-Policy: '.'upgrade-insecure-requests');
  echo '<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">';
  echo '<div onclick="window.open(\''.$targetUrl.'\');" style="width:50%;height:10px;background:red;position:fixed;top:0;z-index:1000000;left:0"></div>';
  echo '<div onclick="window.location.href=\''.$localHttpProtocol.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'\';" style="width:50%;height:10px;background:blue;position:fixed;top:0;z-index:1000000;left:50%"></div>';
  echo $encode=='yes'?changeEncoding($html):$html;
}

function downloadToTemp($fileUrl,$basicTargetUrl,$tempDir,$basicLocalUrl){
  $needPrepend=false;
  $localHttpProtocol=(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off')?'https://':'http://';
  if(preg_match("/^(?:\\/\\/).+/i",$fileUrl)){
    $fileUrl='http:'.$fileUrl;
  }else if(!preg_match("/^(?:https?:\\/\\/).+/i",$fileUrl)){
    $needPrepend=true;
    //$fileUrl=$basicTargetUrl.'/'.$fileUrl;
  }
  $splitedUrl=explode(".",$fileUrl);
  $ext=$splitedUrl[count($splitedUrl)-1];
  do{
    $tempFilename = rand(0,100000).'.'.$ext;
  }while(file_exists($tempFilename));
  if($needPrepend){
    do{
      $attemptFileUrl=$basicTargetUrl.'/'.$fileUrl;
      $downloadedFile=file_get_contents(html_entity_decode($attemptFileUrl));
      $basicTargetUrl=substr($basicTargetUrl,0,strrpos($basicTargetUrl,"/"));
    }while(empty($downloadedFile)&&!preg_match("/^(?:https?:\\/\\/).+?/i",$basicTargetUrl));
  }else{
    $downloadedFile=file_get_contents(html_entity_decode($fileUrl));
  }
  if(!empty($downloadedFile)){
    $newFileUrl=$basicLocalUrl.'/'.$tempDir.'/'.$tempFilename;
    file_put_contents($tempDir.'/'.$tempFilename,$downloadedFile);
    return $newFileUrl;
  }else{
    return $fileUrl;
  }
}

function clearTemp($clearCookies){
  global $TEMP_DIR;
  $dirTemp=opendir($TEMP_DIR);
  while ($file=readdir($dirTemp)) {
    if($file!="." && $file!="..")
    {
      if(strpos($file,"CURLCOOKIE_")!==false && !$clearCookies)
      continue;
      try{
        $fullpath=$TEMP_DIR."/".$file;
        unlink($fullpath);
      }catch(Exception $ee){}
      }
    }
  }

  function changeEncoding($text){
    $encodeType=mb_detect_encoding($text,array('UTF-8','ASCII','GBK'));
    if ($encodeType=='UTF-8') {
      return $text;  //No need to change
    } else {
      //return iconv($encodeType,"UTF-8//ignore",$text);
      return mb_convert_encoding($text,"UTF-8",$encodeType);  //Change to UTF-8
    }
  }

  EOF
  ?>
  




