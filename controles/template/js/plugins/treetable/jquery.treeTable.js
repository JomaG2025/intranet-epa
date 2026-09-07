Array.prototype.unique=function(a){
    return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

Array.prototype.sortNum = function() {
        return this.sort( function (a,b) { return a-b; } );
};

(function(jq){
  jq.fn.jqTreeTable=function(map, options){
    var opts = jq.extend({openImg:"",shutImg:"",leafImg:"",lastOpenImg:"",lastShutImg:"",lastLeafImg:"",vertLineImg:"",blankImg:"",collapse:false,column:0,striped:false,highlight:false,state:true},options),
    mapa=[],mapb=[],tid=this.attr("id"),collarr=[],
    stripe=function(){
      if(opts.striped){
        $("#"+tid+" tr:visible").filter(":even").addClass("even").end().filter(":odd").removeClass("even");
      }
    },
    buildText = function(parno, preStr){//Recursively build up the text for the images that make it work
      var mp=mapa[parno], ro=0, pre="", pref, img;
      for (var y=0,yl=mp.length;y<yl;y++){
        ro = mp[y];
        if (mapa[ro]){//It's a parent as well. Build it's string and move on to it's children
          pre=(y==yl-1)? opts.blankImg: opts.vertLineImg;
          img=(y==yl-1)? opts.lastOpenImg: opts.openImg;
          mapb[ro-1] = preStr + '<img src="'+img+'" class="parimg" id="'+tid+ro+'">';
          pref = preStr + '<img src="'+pre+'" class="preimg">';
          arguments.callee(ro, pref);
        }else{//it's a child
          img = (y==yl-1)? opts.lastLeafImg: opts.leafImg;//It's the last child, It's child will have a blank field behind it
          mapb[ro-1] = preStr + '<img src="'+img+'" class="ttimage" id="'+tid+ro+'">';
        }
      }
    },
    expandKids = function(num, last){//Expands immediate children, and their uncollapsed children
      jq("#"+tid+num).attr("src", (last)? opts.lastOpenImg: opts.openImg);//
      for (var x=0, xl=mapa[num].length;x<xl;x++){
        var mnx = mapa[num][x];
        jq("#"+tid+mnx).parents("tr").removeClass("collapsed");
        if (mapa[mnx] && opts.state && jq.inArray(mnx, collarr)<0){////If it is a parent and its number is not in the collapsed array
          arguments.callee(mnx,(x==xl-1));//Expand it. More intuitive way of displaying the tree
        }
      }
    },
    collapseKids = function(num, last){//Recursively collapses all children and their children and change icon
      jq("#"+tid+num).attr("src", (last)? opts.lastShutImg: opts.shutImg);
      for (var x=0, xl=mapa[num].length;x<xl;x++){
        var mnx = mapa[num][x];
        jq("#"+tid+mnx).parents("tr").addClass("collapsed");
        if (mapa[mnx]){//If it is a parent
          arguments.callee(mnx,(x==xl-1));
        }
      }
    },
    creset = function(num, exp){//Resets the collapse array
                  
      for( var i=0;  i<=collarr.length; i++){
        if(num == collarr[i]){
            collarr.splice(i,1);
        }
      }
      
      if(exp == false){
              
        collarr.push(num);
          
      }
      
      collarr = collarr.sortNum();
            
      cset(tid,collarr);
    },
    cget = function(n){
      var v='',c=' '+document.cookie+';',s=c.indexOf(' '+n+'=');
      if (s>=0) {
        s+=n.length+2;
        v=(c.substring(s,c.indexOf(';',s))).split(".");
      }
                                       
      return v||0;
    },
    cset = function (n,v) {      
      //alert(v.join("."));  
      document.cookie = n+"="+v.join(".")+";";
    };
    for (var x=0,xl=map.length; x<xl;x++){//From map of parents, get map of kids
      num = map[x];
      if (!mapa[num]){
        mapa[num]=[];
      }
      mapa[num].push(x+1);
    }
    buildText(0,"");
    jq("tr", this).each(function(i){//Inject the images into the column to make it work
      jq(this).children("td").eq(opts.column).prepend(mapb[i]);
    });
    
    if(cget(tid)=='0'){
        
            var map_tmp = map.unique();
      
            map_tmp = map_tmp.sortNum();
              
            map_tmp.splice(0,1);
                                  
            collarr = map_tmp||opts.collapse||collarr;
    
    }else if(cget(tid)==''){
            
            collarr = opts.collapse||collarr;    
        
    }else{
            collarr = cget(tid)||opts.collapse||collarr;
    }
    
    if (collarr.length){
      for (var y=0,yl=collarr.length;y<yl;y++){
        collapseKids(collarr[y],($("#"+collarr[y]+ " .parimg").attr("src")==opts.lastOpenImg));
      }
    }
    stripe();
    jq(".parimg", this).each(function(i){
      var jqt = jq(this),last;
      jqt.click(function(){
        var num = parseInt(jqt.attr("id").substr(tid.length));//Number of the row
        if (jqt.parents("tr").next().is(".collapsed")){//If the table row directly below is collapsed
          expandKids(num, (jqt.attr("src")==opts.lastShutImg));//Then expand all children not in collarr
          if(opts.state){creset(num,true);}//If state is set, store in cookie
        }else{//Collapse all and set image to opts.shutImg or opts.lastShutImg on parents
          collapseKids(num, (jqt.attr("src")==opts.lastOpenImg));
          if(opts.state){creset(num,false);}//If state is set, store in cookie
        }
        stripe();//Restripe the rows
      });
    });
    
    jq(".capitulo", this).each(function(i){
      var jqt = jq(this).prev();
      var jqt1 = jq(this),last;
      if(jqt.attr('class') != 'ttimage'){
      jqt1.click(function(){
        var num = parseInt(jqt.attr("id").substr(tid.length));//Number of the row
        if (jqt.parents("tr").next().is(".collapsed")){//If the table row directly below is collapsed
          expandKids(num, (jqt.attr("src")==opts.lastShutImg));//Then expand all children not in collarr
          if(opts.state){creset(num,true);}//If state is set, store in cookie
        }else{//Collapse all and set image to opts.shutImg or opts.lastShutImg on parents
          collapseKids(num, (jqt.attr("src")==opts.lastOpenImg));
          if(opts.state){creset(num,false);}//If state is set, store in cookie
        }
        stripe();//Restripe the rows
        
        return false;
        
      });
      }else{
        jqt1.click(function(){
            return false;   
        });    
      }
      
    });
       
    $(".btn-expandir").click(function(){
        document.cookie = tid+"=;";
        location.reload();
    });
    
    $(".btn-contraer").click(function(){
        document.cookie = tid+"=0;";
        location.reload();
    });
      
    if (window.location.hash.substr(1) == 'expandir'){
        document.cookie = tid+"=;";
        window.location.href = window.location.href.substr(0, window.location.href.indexOf('#'));
    }
      
    if (window.location.hash.substr(1) == 'contraer'){
        document.cookie = tid+"=0;";
        window.location.href = window.location.href.substr(0, window.location.href.indexOf('#'));
    }
    
    if (opts.highlight){//This is where it highlights the rows
      jq("tr", this).hover(
        function(){jq(this).addClass("over");},
        function(){jq(this).removeClass("over");}
      );
    };
  };
  return this;
})(jQuery);
