var d=document,cE="createElement",aC="appendChild",gI="getElementById",gTN="getElementsByTagName",

title_=d[gI]("title_"),
tbdy=d[gI]("fldrLst");


//sort by attributes:

var th=tbdy.children[0].children, //th elements

sort_files=function(ai,cf){
 var c1=tbdy.children,a1=[],i=c1.length,j,tv,tv2,lim=7000000,L;
 if(i<4)return 0; //too few
 if(typeof cf=="function"){
  while((--i)>1) a1.push([cf(c1[i].children[ai].innerText),c1[i]]);
 }else{
  while((--i)>1) a1.push([c1[i].children[ai].innerText,c1[i]]);
 }
 
 L=a1.length-2; i=L;

 while(i&&lim){ tv=a1[i]; j=i+1; tv2=a1[j]; if(tv[0]<tv2[0]){  a1[j]=tv; a1[i]=tv2; i=L; }else i--; lim--; }

 i=a1.length; while(i--)tbdy.removeChild(a1[i][1]);
 i=a1.length; while(i--)tbdy[aC](a1[i][1]);
},

i=th.length,tv;
while(i--){tv=th[i].innerHTML;th[i].innerHTML="<button>"+tv+"</button>";}

th[0].children[0].onclick=function(){ sort_files(0); };
th[1].children[0].onclick=function(){ sort_files(1,function(s){ if(s=="folder")s="\0"; return s;}); };

th[4].children[0].onclick=function(){ sort_files(4,function(s){return s*1;}); };




var clicktrap=function(e){
 var tar=e.target||e.srcElement,L=12;
 while(tar&&tar.nodeName!="A"&&L--)tar=tar.parentNode;
 if(tar){
  var tpn=tar.parentNode;
  if(tar.nodeName=="A"&&tpn.nodeName=="TD"&&tpn.parentNode.children[1].innerText.indexOf("image/")+1){
   //img browser
   e.preventDefault();
   var guc3="https://lh3.googleusercontent.com/u/0/d/";
   if(!self.imgb){
     self.imgb=d[cE]("div");d.body[aC](imgb);
     imgb.i=d[cE]("img");imgb.id="imgb";imgb[aC](imgb.i);
     imgb.i.className="centr nice";
     (imgb.cls=d[cE]("button")).innerText="close image viewer";
     imgb.cls.onclick=function(){d.title=imgb.ot; tbdy.parentNode.className="";imgb.className="hid";};
     imgb.cls.className="closeb";
     imgb[aC](imgb.cls);
     var ar=[].indexOf,tbcd=tbdy.children,cL=tbcd.length,
     imbf=function(e){
       var io=ar.call(tbcd,imgb.ci),nx,nxc;
       if(this.className=="rhb_"){io++; if(io>=cL)io-=cL;}
       else{io--; if(io<2)io=cL-1;}
       nx=tbcd[io];
       imgb.ci=nx; imgb.i.src=guc3+(nxc=nx.children[0].children[0]).id;
       imgb.i.title=(d.title=nxc.innerText);
     };

     (imgb.lhb=d[cE]("button")).innerHTML="/<br>\\";
     imgb.lhb.className="lhb_";
     imgb.lhb.onclick=imbf;
     imgb[aC](imgb.lhb);

     (imgb.rhb=d[cE]("button")).innerHTML="\\<br>/";
     imgb.rhb.className="rhb_";
     imgb.rhb.onclick=imbf;
     imgb[aC](imgb.rhb);
     
   }
   tbdy.parentNode.className="hid"; 
   imgb.i.src=guc3+tar.id;
   imgb.i.title=(d.title=tar.innerText);
   imgb.ot=d.title;
   imgb.ci=tar.parentNode.parentNode;
   imgb.className="";
  }
 }
};

self.addEventListener("click",clicktrap);