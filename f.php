<?php

//set_time_limit(600); //10 min timeout

 $p2="";
 //fixed id, need to change to 1st spot in $p2a, put folder id in url immediately following .php/, 
 $fid="0Bxb5iFgmM3V6VkUtdVU1eWV4ZWs";  //might need to make adjustments everywhere $p2a and $p2i are used?

 $scn=0; //stat update count;

 if(isset($_SERVER["PATH_INFO"])) $ptha=substr($_SERVER["PATH_INFO"],1); else $ptha="";
  //api_req($fpi."?fields=name,description,webContentLink,mimeType",!$jsn);

  if(isset($_SERVER["HTTP_REFERER"])){
    $Rfrr=$_SERVER["HTTP_REFERER"];
    $sLp=strpos($Rfrr,"/",12);
    if($sLp){
      $or_=substr($Rfrr,0,$sLp);
       header("Access-Control-Allow-Origin: ".$or_);
    }
  }


  $hdtxp="Content-Type:text/plain;charset=utf-8";

$p2=urldecode( $sp=$ptha );


  $gdFmT="application/vnd.google-apps.folder"; //googledrive folder mimeType

 //if(strlen($fid)>15){
   //http_response_code(200)
   header("X-dbg-stat-".$scn.": initial fid ".$fid);$scn++;

  $p2a=explode("/",$p2); $p2i=0;
  if($p2C=count($p2a)){
    if($p2a[$p2C-1]=="") array_pop($p2a);
  }



// is  include("func.php");


  //if($_SERVER["PHP_SELF"]=="/func.php") exit("nope "); //file_get_contents("http://v4.ipv6-test.com/json/widgetdata.php?callback=dos")


  //$KEY=json_decode(file_get_contents("gdkey.json"))["KEY"];
  $KEY=explode('"',file_get_contents("gdkey.json"))[3];
  /* ^
     |___ That is MY google-developer-googledrive-API-key, please don't use it for yourself, if you also like to code and you want an API key, you can get one, from Google Inc, 

      FOR     F R E E E E    !!

    for yourself. See: --,
                        \/
      https://developers.google.com/drive/api/v3/enable-drive-api

  */

  $api_p="https://www.googleapis.com/drive/v3/";
  $fldr_ls="%27%20in%20parents&fields=kind,nextPageToken,files(kind,name,mimeType,createdTime,modifiedTime,id,size,shared,owners(displayName,kind),shortcutDetails),incompleteSearch";

 if(!isset($scn)) $scn=0;

if(isset($_GET["dbgr"])) $dbgRt=$_GET["dbgr"]; else $dbgRt="";


  function api_req($DO,$jsn){
   global $scn,$api_p,$KEY,$dbgRt;
   header("X-dbg-stat-".$scn.": api_req: ".$DO);
   $url=$api_p.$DO; $qi=strpos($url,"?");
   if($qi>0){
    $url.="&";
   }else $url.="?";
   $url.="key=".$KEY;
   $R=file_get_contents($url);
   if($dbgRt==("apic".$scn)) exit($R);
   $scn++;
   if($jsn){ $R=json_decode($R,true); }
   return $R;
  }

  
  if(file_exists("stor_01.json")){
   $sfj=file_get_contents("stor_01.json");
   $sfjS=strlen($sfj);
   if($sfjS>7){
     $TREE_=json_decode($sfj,!0);
     header("X-stor-size: ".strlen($sfjS));
   }
  }
  if(!isset($TREE_)){
   $TREE_=["file_lists"=>[]];
  }


   $cut=time();
   $Lim=259200; //keep folder contents cache for three days
   $on_L=$TREE_["file_lists"];
   foreach($on_L as $fi=>$fd){
     if(isset($TREE_[$fi])&&isset($TREE_[$fi]["LRT"])&&$cut-$TREE_[$fi]["LRT"]>$Lim) unset($on_L[$fi]);
   }


  function done(){
    global $TREE_;
    $jsnc=json_encode($TREE_);
    if(strlen($jsnc)>20)
    file_put_contents("stor_01.json",$jsnc);
  }

  
  function file_rdr($dat){ //file_reader
    global $scn,$KEY,$api_p,$p2i,$p2C,$p2a,$html_hed,$file_nof,$file2nof;

    header("X-dbg-stat-".$scn.": file_rdr: ".($id=$dat["id"]));$scn++;

    $mt=$dat["mimeType"];
    if($mt=="application/vnd.google-apps.shortcut"&&isset($dat["shortcutDetails"])){
      $mt=($sd_=$dat["shortcutDetails"])["targetMimeType"];
      $id=$sd_["targetId"];
      //note: if $mt == $gdFmT then folder handel
    }

    $fpi="files/".$id;
  
    $ept=$p2C-$p2i;
    if($ept){
      if($ept==1&&substr($dsn=strtolower($p2a[$p2i]),0,5)=="dscr."){
        $ext=substr($dsn,5);
        $jsn=$ext=="json";
        $dd=api_req($fpi."?fields=name,description",!$jsn);
        if($jsn){$dsc=$dd; $typ="application/json";}
        else{
          if(isset($dd["description"]))$dsc=$dd["description"];else $dsc="";
          if($ext=="html"||$ext=="htm"){
            $dsc=$html_hed.$dd["name"]." file description</title></head><body>".nl2br(htmlentities($dsc))."</body></html>";
            $typ="text/html";
          }else $typ="text/plain";
        }
        header("Content-Type: ".$typ);
        echo $dsc;
      }else{
        exit($file_nof.implode("/",array_slice($p2a,0,$p2i)).$file2nof);
      }
    }else{

      $mb=substr($mt,0,strpos($mt,"/"));
      $ftp=$fpi."?alt=media"; //fetch path
      $dop=true;
  
      header("Content-Type: ".$mt.";charset=utf-8");

      if($mb=="image"){
       $dop=false;
       header("Location: https://lh3.googleusercontent.com/u/0/d/".$id);
       //alt for does 302 to long random url for image: https://lh3.google.com/u/0/d/
     //append "=w32-h32-p-k-nu-iv1" where 32 can be replaced with any width and height to get thouse diminsions, can omit -p-k and after I don't know what that does yet
      } //else if($mb=="video"||$mb=="audio"){
     //do media server stuff?
    //}
   
      if($dop){  $cont=file_get_contents($api_p.$ftp."&key=".$KEY); header("Content-Length: ".strlen($cont)); echo $cont; }
    }
    done(); exit();
   //could use 302? instead of file get contents?
   //then could still use referer restrictions
  } // */




  function fldr_rdr($fL,$fdn){ //folder_render; fL=driveFileList (array),fdn==folder displayName
 
   global $fid,$p2,$p2a,$p2i,$fidp,$gdFmT,$html_hed;
   $outv="";
   $psa=array_slice($p2a,1,$p2i);$pf=fid."/".implode("/",$psa);$pa='<a href="/./gd/f.php/'.$pf;
   if($pa[strlen($pa)-1]!="/")$pa.="/";

   $outv.=$html_hed.$fdn.'</title></head><body>
   <div id="wrapper">
   <span id="title_" >'.$fdn.'</span><br>
   <table>
   <tbody id="fldrLst">
   <tr id="fLhdr">
    <th>Name</th>
    <th>Type</th>
    <th>CreatedTime</th>
    <th>ModifeiedTime</th>
    <th>size</th>
    <th>owners</th>
   </tr>';

   $up="";
   if(count($psa)){
    $up=$fid."/". implode("/",array_slice($psa,0,-1));
   }elseif($fidp&&count($fidp)){
    $up=$fidp[0]; //should add navigation to multiple parents?
   }
   $td='</td><td>';
   if($up){ $up='/./gd/f.php/'.$up;
    $outv.="\r\n".'<tr><td><a href="'.$up.'">../ (up to parent directory</a>'.$td.'folder'.$td.'_'.$td.'_'.$td.'_'.$td.'_</td></tr>';
   }

   $i=0; $L_=count($fL); $rs="\r\n"; //return string / result
   while($i<$L_){
    $cf=$fL[$i];
    $tv2='';
    if(count($ow=$cf["owners"]))$tv2.=implode(", ",array_column($ow,"displayName")); 
    $rs.="\r\n".'<tr><td>'.$pa.($tv3=$cf["name"]).'" id="'.$cf["id"].'" >'.$tv3.'</a>'.$td;
    if($cf["mimeType"]==$gdFmT)$rs.="folder";else $rs.=$cf["mimeType"];
    if(isset($cf["size"]))$sZ=$cf["size"];else $sZ="";
    $rs.=$td.$cf["createdTime"].$td.$cf["modifiedTime"].$td.$sZ.$td.$tv2."</td></tr>";
    $i++;
   }
   done();
   $outv.=($rs."\r\n".'</tbody></table></div><script src="/./gd/file_int.js" type="text/javascript"></script></body></html>');
   header("Content-Length: ".strlen($outv));
   echo($outv); exit();
  } //  */




 function fldr_hdl($dat){ //folder handle
  $id=$dat["id"]; //must have id

  global $TREE_,$scn,$p2,$p2a,$p2i,$fidn,$gdFmT,$fldr_ls,
  $file_nof,$file2nof;
  
  header("X-dbg-stat-".$scn.": fldr_hdl: ".$id);$scn++;

  $refresh=false;


  if(!isset($dat["files"])){
   $tv="id$".$id;
   if(isset($TREE_["file_lists"][$tv])){
    $dat["kind"]="drive#fileList";
    $dat["files"]=$TREE_["file_lists"][$tv];
   }else{
    $dat=api_req("files?q=%27".$id.$fldr_ls, true);
    $dat["id"]=$id;
    $TREE_[$tv]["LRT"]=time();
    $refresh=true;
   }
  }

  
  if($dat["kind"]=="drive#fileList"){
    $cfL=$dat["files"]; //current file list

    if(!is_array($cfL)){ var_dump($dat); exit();}

    $mpgs=0;
    while(isset($dat["nextPageToken"])){
     $np_=api_req("files?q='".$id.$fldr_ls."&pageToken=".$dat["nextPageToken"],!0);
     $dat=$np_;
     if(!is_array($cfL)){ var_dump($dat); exit();}
     $cfL=array_merge($cfL,$np_["files"]);
     header("X-dbg-stat-".$scn.": nextPage");$scn++;
     $mpgs++;
    }
    $dat["files"]=$cfL;

    if($refresh){
      $TREE_["file_lists"][$tv]=$cfL;
    }

    $F_L=count($cfL); $i=0; $fn=false;$r=false; $wuz=false;

    $is404=false;
    //$names_=array_column($cfL,"name");
    //$i=array_search($fn,$names_);
    if(isset($p2a[$p2i])){ $fn=$p2a[$p2i]; $p2i++; $is404=true;
     while($i<$F_L){ if($fn==$cfL[$i]["name"]){$r=$cfL[$i];$is404=false;break;} $i++;}
     if($is404) exit($file_nof.implode("/",array_slice($p2a,0,$p2i-1)).$file2nof);
      $wuz=true;
    }else{
      while($i<$F_L){ $fn=strtolower($cfL[$i]["name"]); if($fn=="index.html"||$fn=="index.htm"||$fn=="index.php"){$r=$cfL[$i];break;} $i++;}
    }

    if($r){
      $r["parents"]=[$id];
      $TREE_["id$".$r["id"]]=$r;
      if($r["mimeType"]==$gdFmT){
       //', scanning folder "'.$r["name"].'" ';
       fldr_hdl($r);
      }else{ file_rdr($r); }
    }elseif(!$wuz){
      fldr_rdr($cfL,"Index Of ".$fidn."/".$p2);
    }elseif(!isset($dat["re_scan"])){
      unset($dat["files"]);
      unset($TREE_["file_lists"][$tv]);
      $dat["re_scan"]=true;
      header("X-dbg-stat-".$scn.": fldr rescan");$scn++;
      fldr_hdl($dat);
    }else{
      exit($file_nof.implode("/",array_slice($p2a,0,$p2i-1)).$file2nof);
    }
  }
 } // */

//end of func.php


  
  //silly pjsm svr section

  if($ptha=="/pjsm.jsdos"){

    //content-type: application/x-www-form-urlencoded  //from js-dos.com official?
    // google drive ids in order of the 16 file split chunk sections of pjsm.jsdos.gz 

$pjsdos=["13TRfEiJwO799a4u5Am2dKB_wfzYlemBE","1usQbHBbt3-QL_bGOJ71iiZ99tmXuKzpV","1YTu6mSvMZs-_toEgiuM07Afgh3kuVFyZ","1xGwt3Jevs9YP0mDnMk75VJRzIg67QMDu","1zN1qTHSh4qXSvsyHGX28Xpj_pFDBaTf5","1ZgHne3g73twWtOHSUVEEvyu8rSQenPIt","12tu3JanW6sWUrNHYpcEApeq74rtxULfk","1MWQL1NM5ZYrRLDz799ZLRAvoyhkkGBf7","1nXGTVtoyDBiMz0pdT3fR40r74sVOdunZ","1PYgOZDGc7oFbl1qtutXgraFimMKoVB2P","1DGwO5R40SzbZyExDFAXEeVaMumv0eXoN","1N77xUhjVHVVYGrolNaQQyD98sZRYMQ6b","1gWHtSOBL_L-1dQ11fAJj8wkl3tlLKdJ6","1k1835P_qoHZG7_niMNeokVhjjfTM0Idc","10O9m6nRkFBY5FTMRF4jbuAAjlHG5yC6i","1KTZSQEtN8d8C4XAg0ubbl__VjimvECdJ"];
$ch_sz_1=16777216; //size of chunks 0-14
$cj_sz_15=4206096; //size of last chunk (15) (16th one) (shorter)
$ch_all_sz=255864336; //total combined file size
// shift >> bits 24 down to get sequence number. sequence number is array number, cause 0 is 0, and 16777216>>24 == 1 is true

    header("access-control-allow-methods: GET");
    header("Access-Control-Allow-Headers: Range");
    header("Content-Type: application/x-octet-stream;charset=utf-8");
    header("Content-Encoding: gzip");
    header("Accept-Ranges: bytes");
    

    // M A G I C.  (mr bean)
    // req.headers.range;
    //parseInt("1111000000000000000000000000" ,2)
    $magic_and=251658240;
    $range1=false; $start=0; $end=$ch_sz_1;
    //http range request header handler
    if(isset($_SERVER["HTTP_RANGE"])){
      $range1=$_SERVER["HTTP_RANGE"];
      $rbi_1=substr($range1,strpos($range1,"=")+1);
      $dpo=strpos($rbi_1,"-");
      $start=(substr($rbi_1,0,$dpo)*1)&$magic_and;
      $end=(substr($rbi_1,$dpo+1)*1);

      http_response_code(206); //206 partial content
    }else{
      //silly fixy code
      header("Content-Length: ".$ch_all_sz);
      $ra_=0;
      while($ra_!=16){
        echo api_req("files/".$pjsdos[$ra_]."?alt=media",false);
        $ra_++;
      }
      exit();
    }

    if($start==0)$seq_n=0; //chunk seqence number
    else $seq_n=$start>>24;

    $LIM_A=false;

    $cL_1=$end-$start;
    if($cL_1>$ch_sz_1){ $cL_1=$ch_sz_1; $end=$start+$ch_sz_1; }
    if(($seq_n!=15)&&$cL_1<$ch_sz_1) $LIM_A=true;

    header("X-dat-huuuu: seq ".$seq_n."  rng ".$start."-".$end);

    header("Content-Range: bytes ".$start."-".$end."/".$ch_all_sz);
    header("Content-Length: ".$cL_1);
    

    if($LIM_A)
      echo substr(api_req("files/".$pjsdos[$seq_n]."?alt=media",false),0,$cL_1);
    else
      echo api_req("files/".$pjsdos[$seq_n]."?alt=media",false);
    exit();
  }

    //    END OF silly pjsm svr section


    $tv="id$".$fid;
    

    if(isset($TREE_[$tv])){
      $_dat=$TREE_[$tv];
      $rctgt=false;
    }else{
      $_dat=api_req("files/".$fid."?fields=kind,id,name,mimeType,size,parents,owners(displayName,kind),shortcutDetails",true);
      $rctgt=true;
    }

    //if(isset($_GET["gdfre"])) echo json_encode($_dat);

    if(isset($_dat["id"])){
      if($rctgt)$TREE_[$tv]=$_dat;
    }else{
      header($hdtxp);
      exit("Error the specified fid (googledrive folder/file id) was not found. This could be because it's not publicly shared or because it doesn't exist");
    }

    $fidn=$_dat["name"];
    $fidp=$_dat["parents"];




    $html_hed='<!Doctype html><html><head><meta content="text/html;charset=utf-8" http-equiv="Content-Type" />
  <link rel="shortcut icon" href="/./favicon.ico" type="image/x-icon"/>
  <link href="/./gd/main.css" rel="stylesheet" type="text/css" />
  <title>';


    $file_nof=$html_hed.'File not found</title></head><body><div id="wrapper"> The file "'.$fidn.'/'.$p2.'" was not found.<br><br> Note:<br>"'.$fidn.'" = "'.$fid.'"<br><br> Here is how much was found:<br>'.$fidn.'/';
    $file2nof='<br><br> <a href="/./">main page</a></div></body></html>';


    //", initialized ";
  if($_dat["mimeType"]==$gdFmT){
    //', scanning folder "'.$fidn.'" ';
    fldr_hdl(["id"=>$fid]);
  }else file_rdr($_dat);

 //use "files/".fid."?alt=media" for file data access
 
 /*}else{
  echo file_get_contents("index.php");
 }*/


?>
