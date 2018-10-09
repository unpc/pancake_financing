"use strict";(function(a){"use strict";a.fn.circleChart=function(g){var h={color:"#3459eb",backgroundColor:"#e6e6e6",background:!0,speed:2e3,widthRatio:0.2,value:66,unit:"percent",counterclockwise:!1,size:110,startAngle:0,animate:!0,backgroundFix:!0,lineCap:"round",animation:"easeInOutCubic",text:!1,redraw:!1,cAngle:0,textCenter:!0,textSize:!1,textWeight:"normal",textFamily:"sans-serif",relativeTextSize:1/7,autoCss:!0,onDraw:!1},i={};i.linearTween=function(s,u,v,w){return v*s/w+u},i.easeInQuad=function(s,u,v,w){return s/=w,v*s*s+u},i.easeOutQuad=function(s,u,v,w){return s/=w,-v*s*(s-2)+u},i.easeInOutQuad=function(s,u,v,w){return(s/=w/2,1>s)?v/2*s*s+u:(s--,-v/2*(s*(s-2)-1)+u)},i.easeInCubic=function(s,u,v,w){return s/=w,v*s*s*s+u},i.easeOutCubic=function(s,u,v,w){return s/=w,s--,v*(s*s*s+1)+u},i.easeInOutCubic=function(s,u,v,w){return(s/=w/2,1>s)?v/2*s*s*s+u:(s-=2,v/2*(s*s*s+2)+u)},i.easeInQuart=function(s,u,v,w){return s/=w,v*s*s*s*s+u},i.easeOutQuart=function(s,u,v,w){return s/=w,s--,-v*(s*s*s*s-1)+u},i.easeInOutQuart=function(s,u,v,w){return(s/=w/2,1>s)?v/2*s*s*s*s+u:(s-=2,-v/2*(s*s*s*s-2)+u)},i.easeInQuint=function(s,u,v,w){return s/=w,v*s*s*s*s*s+u},i.easeOutQuint=function(s,u,v,w){return s/=w,s--,v*(s*s*s*s*s+1)+u},i.easeInOutQuint=function(s,u,v,w){return(s/=w/2,1>s)?v/2*s*s*s*s*s+u:(s-=2,v/2*(s*s*s*s*s+2)+u)},i.easeInSine=function(s,u,v,w){return-v*Math.cos(s/w*(Math.PI/2))+v+u},i.easeOutSine=function(s,u,v,w){return v*Math.sin(s/w*(Math.PI/2))+u},i.easeInOutSine=function(s,u,v,w){return-v/2*(Math.cos(Math.PI*s/w)-1)+u},i.easeInExpo=function(s,u,v,w){return v*Math.pow(2,10*(s/w-1))+u},i.easeOutExpo=function(s,u,v,w){return v*(-Math.pow(2,-10*s/w)+1)+u},i.easeInOutExpo=function(s,u,v,w){return(s/=w/2,1>s)?v/2*Math.pow(2,10*(s-1))+u:(s--,v/2*(-Math.pow(2,-10*s)+2)+u)},i.easeInCirc=function(s,u,v,w){return s/=w,-v*(Math.sqrt(1-s*s)-1)+u},i.easeOutCubic=function(s,u,v,w){return s/=w,s--,v*(s*s*s+1)+u},i.easeInOutCubic=function(s,u,v,w){return(s/=w/2,1>s)?v/2*s*s*s+u:(s-=2,v/2*(s*s*s+2)+u)},i.easeOutCirc=function(s,u,v,w){return s/=w,s--,v*Math.sqrt(1-s*s)+u},i.easeInOutCirc=function(s,u,v,w){return(s/=w/2,1>s)?-v/2*(Math.sqrt(1-s*s)-1)+u:(s-=2,v/2*(Math.sqrt(1-s*s)+1)+u)};var j=function(s,u,v,w,x,y,z,A){var B=Object.create(j.prototype);return B.pos=s,B.bAngle=u,B.eAngle=v,B.cAngle=w,B.radius=x,B.lineWidth=y,B.sAngle=z,B.settings=A,B};j.prototype={onDraw:function onDraw(s){if(!1!==this.settings.onDraw){var u=Object.assign({},this),v={percent:q,rad:function rad(w){return w},"default":n};u.value=(v[this.settings.unit]||v["default"])(u.cAngle),u.text=function(w){return l(s,w)},u.settings.onDraw(s,u)}},drawBackground:function drawBackground(s){s.beginPath(),s.arc(this.pos,this.pos,this.settings.backgroundFix?0.9999*this.radius:this.radius,0,2*Math.PI),s.lineWidth=this.settings.backgroundFix?0.95*this.lineWidth:this.lineWidth,s.strokeStyle=this.settings.backgroundColor,s.stroke()},draw:function draw(s){if(s.beginPath(),this.settings.counterclockwise){var u=2*Math.PI;s.arc(this.pos,this.pos,this.radius,u-this.bAngle,u-(this.bAngle+this.cAngle),this.settings.counterclockwise)}else s.arc(this.pos,this.pos,this.radius,this.bAngle,this.bAngle+this.cAngle,this.settings.counterclockwise);s.lineWidth=this.lineWidth,s.lineCap=this.settings.lineCap,s.strokeStyle=this.settings.color,s.stroke()},animate:function animate(s,u,v,w,x){var z=this,y=new Date().getTime()-v;1>y&&(y=1),v-w<1.05*this.settings.speed&&(!x&&1e3*this.cAngle<=Math.floor(1e3*this.eAngle)||x&&1e3*this.cAngle>=Math.floor(1e3*this.eAngle))?(this.cAngle=i[this.settings.animation]((v-w)/y,this.sAngle,this.eAngle-this.sAngle,this.settings.speed/y),u.clearRect(0,0,this.settings.size,this.settings.size),this.settings.background&&this.drawBackground(u),this.draw(u),this.onDraw(s),v=new Date().getTime(),r(function(){return z.animate(s,u,v,w,x)})):(this.cAngle=this.eAngle,u.clearRect(0,0,this.settings.size,this.settings.size),this.settings.background&&this.drawBackground(u),this.draw(u),this.setCurrentAnglesData(s))},setCurrentAnglesData:function setCurrentAnglesData(s){var u={percent:q,rad:function rad(w){return w},"default":n},v=u[this.settings.unit]||u["default"];s.data("current-c-angle",v(this.cAngle)),s.data("current-start-angle",v(this.bAngle))}};var l=function(s,u){s.data("text",u),a(".circleChart_text",s).html(u)},m=function(s){var u=s.getContext("2d"),v=window.devicePixelRatio||1,w=u.webkitBackingStorePixelRatio||u.mozBackingStorePixelRatio||u.msBackingStorePixelRatio||u.oBackingStorePixelRatio||u.backingStorePixelRatio||1,x=v/w,y=s.width,z=s.height;s.width=y*x,s.height=z*x,s.style.width=y+"px",s.style.height=z+"px",u.scale(x,x)},n=function(s){return 180*(s/Math.PI)},o=function(s){return s/180*Math.PI},p=function(s){return o(360*(s/100))},q=function(s){return 100*(n(s)/360)},r=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(u){window.setTimeout(u,1e3/60)}}();return this.each(function(s,u){var v=a(u),w={},x=v.data();for(var y in x)x.hasOwnProperty(y)&&0===y.indexOf("_cache_")&&h.hasOwnProperty(y.substring(7))&&(w[y.substring(7)]=x[y]);var z=Object.assign({},h,w,x,g);for(var A in z)z.hasOwnProperty(A)&&0!==A.indexOf("_cache_")&&v.data("_cache_"+A,z[A]);a("canvas.circleChart_canvas",v).length||(v.append(function(){return a("<canvas/>",{"class":"circleChart_canvas"}).prop({width:z.size,height:z.size}).css(z.autoCss?{"margin-left":"auto","margin-right":"auto",display:"block"}:{})}),m(a("canvas",v).get(0))),a("p.circleChart_text",v).length||!1===z.text||(v.append("<p class='circleChart_text'>"+z.text+"</p>"),z.autoCss&&(z.textCenter?a("p.circleChart_text",v).css({position:"absolute","line-height":z.size+"px",top:0,width:"100%",margin:0,padding:0,"text-align":"center","font-size":!1===z.textSize?z.size*z.relativeTextSize:z.textSize,"font-weight":z.textWeight,"font-family":z.textFamily}):a("p.circleChart_text",v).css({"padding-top":"5px","text-align":"center","font-weight":z.textWeight,"font-family":z.textFamily,"font-size":!1===z.textSize?z.size*z.relativeTextSize:z.textSize}))),z.autoCss&&v.css("position","relative"),z.redraw||(z.cAngle=z.currentCAngle?z.currentCAngle:z.cAngle,z.startAngle=z.currentStartAngle?z.currentStartAngle:z.startAngle);var B=a("canvas",v).get(0),C=B.getContext("2d"),D={percent:p,rad:function rad(M){return M},"default":o},E=D[z.unit]||D["default"],F=E(z.startAngle),G=E(z.value),H=E(z.cAngle),I=z.size/2,J=I*(1-z.widthRatio/2),K=J*z.widthRatio,L=j(I,F,G,H,J,K,H,z);v.data("size",z.size),z.animate?0===z.value?r(function(){C.clearRect(0,0,z.size,z.size),L.settings.background&&L.drawBackground(C),L.onDraw(v)}):L.animate(v,C,new Date().getTime(),new Date().getTime(),H>G):(L.cAngle=L.eAngle,r(function(){C.clearRect(0,0,z.size,z.size),z.background&&L.drawBackground(C),0===z.value?L.settings.background&&L.drawBackground(C):(L.draw(C),L.setCurrentAnglesData(v)),L.onDraw(v)}))})}})(jQuery);