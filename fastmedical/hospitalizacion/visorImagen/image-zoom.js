
namespacing={
    init:function(namespace){
        var spaces=[];
        namespace.split('.').each(function(space){
            var curSpace=window,i;
            spaces.push(space);
            for(i=0;i<spaces.length;i++){
                if(typeof curSpace[spaces[i]]==='undefined'){
                    curSpace[spaces[i]]={};
                
            }
            curSpace=curSpace[spaces[i]];
            }
        });
}
};

document.observe('dom:loaded',function(){
    zoomFile.zoomControlInit();
});
var trackZoom;
var zoomFile={
    ZOOMIN:'zoomin',
    ZOOMOUT:'zoomout',
    IMAGE_ZOOM_PAGE:'/image-zoom/',
    IMAGE_EXT:'.jpg',
    resized:false,
    stepSkipping:0,
    move:'',
    fileID:0,
    zoomFactor:0,
    maxSize:0,
    panoramicPercentage:0,
    viewport:{
        width:0,
        height:0
    },
    size:0,
    currentPos:{
        X:0,
        Y:0
    },
    clickPos:{
        X:0,
        Y:0
    },
    thumbImg:{
        width:0,
        height:0
    },
    currentImg:{
        width:0,
        height:0
    },
    offset:{
        width:0,
        height:0
    },
    zoomControl:null,
    zoomDraggable:null,
    zoomDraggableObserver:null,
    navigatorDraggable:null,
    constraintArea:{
        ZoomDraggableDiv:{
            minX:0,
            minY:0,
            maxX:0,
            maxY:0
        },
        NavCtrlBox:{
            minX:0,
            minY:0,
            maxX:-1,
            maxY:-1
        }
    },
navigatorData:{
    img:{
        width:0,
        height:0
    },
    box:{
        width:10,
        height:10,
        border:2
    },
    ratio:{
        width:0,
        height:0
    }
},
isDragging:false,
loadingQueue:[],
initializeZoomTracking:function(){
    if(trackZoom==undefined){
        trackZoom=true;
    }
},
trackInteraction:function(){
    if(trackZoom){
        document.fire('omniture:imageZoom');
        trackZoom=false;
    }
},
zoomControlInit:function(){
    if(!$('ZoomImageDiv')){
        return;
    }
    this.move=this.ZOOMIN;
    this.fileID=$F('ZoomFileID');
    this.zoomFactor=parseFloat($F('ZoomFactor'));
    this.maxSize=parseInt($F('ZoomMaxSize'));
    this.panoramicPercentage=parseFloat($F('PanoramicPercent'));
    var viewportSize=parseInt($F('ViewPortSize'));
    this.viewport.width=viewportSize;
    this.viewport.height=viewportSize;
    this.offset.width=Math.round(this.viewport.width/2);
    this.offset.height=Math.round(this.viewport.height/2);
    this.currentPos.X=this.offset.width;
    this.currentPos.Y=this.offset.height;
    var zoomImg=$('ZoomImage');
    this.thumbImg.width=zoomImg.width;
    this.thumbImg.height=zoomImg.height;
    this.currentImg.width=this.thumbImg.width;
    this.currentImg.height=this.thumbImg.height;
    ['zoom-hover-div','ZoomDraggableDiv','zoom-navigator','nav-image-clear','NavCtrlBox','nav-ctrl-handle','nav-image'].each(function(eleID){
        this.disableHighlight($(eleID));
    }.bind(this));
    var navImg=$("nav-image-clear");
    this.navigatorData.img.width=parseInt(navImg.getStyle("width"));
    this.navigatorData.img.height=parseInt(navImg.getStyle("height"));
    zoomImg.observe('mouseover',function(){
        this.showZoomHover();
    }.bind(this));
    zoomImg.observe('mouseout',function(){
        this.hideZoomHover();
    }.bind(this));
    zoomImg.observe('click',function(e){
        this.initializeZoomTracking();
        this.doClick(e)
        }.bind(this));
    var hoverDiv=$("zoom-hover-div");
    hoverDiv.observe("mouseover",function(){
        this.showZoomHover();
    }.bind(this));
    hoverDiv.observe("click",function(e){
        $("zoom-hover-div").stopObserving("mouseover");
        this.initializeZoomTracking();
        this.hideZoomHover();
        this.doClick(e);
    }.bind(this));
    var draggableDiv=$('ZoomDraggableDiv');
    draggableDiv.oncontextmenu=function(e){
        this.mSetMove(this.ZOOMOUT);
        this.doClick(e);
        return false;
    }.bind(this);
    draggableDiv.observe('click',function(e){
        this.trackInteraction();
        if(this.isDragging){
            Event.stop(e);
        }else{
            if(this.zoomControl.value+1<=this.maxSize){
                this.mSetMove(this.ZOOMIN);
                this.doClick(e);
            }
        }
    }.bind(this));
this.zoomDraggable=new Draggable('ZoomDraggableDiv',{
    constraintarea:true
});
$("nav-image-clear").observe("click",this.navigationMoveTo.bind(this));
var zoomIn=$('zoom-in-img');
zoomIn.observe('click',function(){
    this.zoomControl.setValue(parseInt(this.zoomControl.value)+1)
    }.bind(this));
zoomIn.observe('mouseover',function(){
    $('zoom-in-img').src=istock.cookielessUrl+'/static/images/zoom/in-hover.png';
});
zoomIn.observe('mouseout',function(){
    $('zoom-in-img').src=istock.cookielessUrl+'/static/images/zoom/in.png';
});
var zoomOut=$('zoom-out-img');
zoomOut.observe('click',function(){
    this.zoomControl.setValue(parseInt(this.zoomControl.value)-1)
    }.bind(this));
zoomOut.observe('mouseover',function(){
    $('zoom-out-img').src=istock.cookielessUrl+'/static/images/zoom/out-hover.png';
});
zoomOut.observe('mouseout',function(){
    $('zoom-out-img').src=istock.cookielessUrl+'/static/images/zoom/out.png';
});
var handle=$("zoom-handle-img");
handle.observe("mouseover",function(){
    $("zoom-handle-img").src=istock.cookielessUrl+"/static/images/zoom/handle-hover.png";
});
handle.observe("mouseout",function(){
    $("zoom-handle-img").src=istock.cookielessUrl+"/static/images/zoom/handle.png";
});
var zoomSliderSteps=[];
for(var i=0;i<=this.maxSize;i++){
    zoomSliderSteps[i]=i;
}
this.zoomControl=new Control.Slider("zoom-handle-div","zoom-track-div",{
    range:$R(this.maxSize,0),
    axis:'vertical',
    values:zoomSliderSteps,
    sliderValue:0,
    onChange:function(value){
        if(value==this.size){}else{
            if(value<this.size){
                this.move=this.ZOOMOUT;
            }else{
                this.move=this.ZOOMIN;
                this.trackInteraction();
            }
            this.clickPos.X=parseFloat($('ZoomDroppableDiv').style.width)/2;
            this.clickPos.Y=parseFloat($('ZoomDroppableDiv').style.height)/2;
            this.submitMove(value);
        }
    }.bind(this)
    });
this.navigatorDraggable=new Draggable("NavCtrlBox",{
    handle:"nav-ctrl-handle",
    constraintarea:true
});
this.zoomDraggableObserver={
    onDrag:function(eventName,draggable,event){
        if($('h'+this.size)){
            $('h'+this.size).show();
        }
        if(draggable.element.id==this.zoomDraggable.element.id){
            this._viewPortDraggable();
        }
        else if(draggable.element.id==this.navigatorDraggable.element.id){
            this._navBoxDraggable();
        }
    }.bind(this),
onEnd:function(eventName,draggable,event){
    this.trackInteraction();
    if($('h'+this.size)){
        $('h'+this.size).hide();
    }
    if(draggable.element.id==this.zoomDraggable.element.id){
        this.isDragging=true;
        var returnVal=this._viewPortDraggable();
        this.currentPos.X=(returnVal[0]+this.offset.width);
        this.currentPos.Y=(returnVal[1]+this.offset.height);
        window.setTimeout(function(){
            this.isDragging=false;
        }.bind(this),500);
    }
    else if(draggable.element.id==this.navigatorDraggable.element.id){
        var returnVal=this._navBoxDraggable();
        this.currentPos.X=(returnVal[0]+this.offset.width);
        this.currentPos.Y=(returnVal[1]+this.offset.height);
    }
}.bind(this)
}
Draggables.addObserver(this.zoomDraggableObserver);
$('ActualImageDiv').removeClassName('h');
},
_viewPortDraggable:function(){
    var zoomDrag=$('ZoomDraggableDiv');
    var left=-(parseInt(zoomDrag.getStyle('left')));
    var top=-(parseInt(zoomDrag.getStyle('top')));
    var navLeft=Math.round(left*this.navigatorData.ratio.width);
    var navTop=Math.round(top*this.navigatorData.ratio.height);
    $('NavCtrlBox').setStyle({
        left:(navLeft+1)+'px',
        top:(navTop+1)+'px'
        });
    $('nav-image').setStyle({
        left:-(navLeft-this.navigatorData.box.border)-4+'px',
        top:-(navTop-this.navigatorData.box.border)-3+'px'
        });
    return[left,top];
},
_navBoxDraggable:function(){
    var navBox=$('NavCtrlBox');
    var left=parseInt(navBox.getStyle('left'));
    var top=parseInt(navBox.getStyle('top'));
    $('nav-image').setStyle({
        left:-(left-this.navigatorData.box.border)-3+'px',
        top:-(top-this.navigatorData.box.border)-4+'px'
        });
    var zoomDrag=$('ZoomDraggableDiv');
    var zoomDragLeft=Math.round(left/this.navigatorData.ratio.width);
    var zoomDragTop=Math.round(top/this.navigatorData.ratio.height);
    zoomDrag.setStyle({
        left:-(Math.min(zoomDragLeft,this.constraintArea.ZoomDraggableDiv.maxX))+'px',
        top:-(Math.min(zoomDragTop,this.constraintArea.ZoomDraggableDiv.maxY))+'px'
        });
    return[zoomDragLeft,zoomDragTop];
},
navigationMoveTo:function(e){
    $("nav-image-clear").stopObserving("click");
    this.trackInteraction();
    if(this.size!=0){
        var navPos=$("zoom-navigator").cumulativeOffset();
        var newClickX=Event.pointerX(e);
        var newClickY=Event.pointerY(e);
        var navBox=$('NavCtrlBox');
        var navBoxX=parseInt(navBox.getStyle('left'));
        var navBoxY=parseInt(navBox.getStyle('top'));
        var x=newClickX-navPos.left-navBoxX-this.navigatorData.box.width/2;
        var y=newClickY-navPos.top-navBoxY-this.navigatorData.box.height/2;
        if(navBoxX+x>=this.constraintArea.NavCtrlBox.minX){
            x=this.constraintArea.NavCtrlBox.minX-navBoxX;
        }
        if(navBoxY+y>=this.constraintArea.NavCtrlBox.minY){
            y=this.constraintArea.NavCtrlBox.minY-navBoxY;
        }
        if(navBoxX+x<=0){
            x=-(navBoxX+1);
        }
        if(navBoxY+y<=0){
            y=-(navBoxY+1);
        }
        new Effect.MoveBy(navBox,y,x,{
            duration:1.0
        });
        new Effect.Move($('nav-image'),{
            x:(-(navBoxX+x)-this.navigatorData.box.border),
            y:(-(navBoxY+y)-this.navigatorData.box.border),
            mode:'absolute',
            duration:1.0
        });
        var zoomDiv=$('ZoomDraggableDiv');
        var zoomX=parseInt(zoomDiv.getStyle('left'));
        var zoomY=parseInt(zoomDiv.getStyle('top'));
        var moveX=-x/this.navigatorData.ratio.width;
        var moveY=-y/this.navigatorData.ratio.height;
        if(-(zoomX+moveX)>=this.constraintArea.ZoomDraggableDiv.maxX){
            moveX=-(this.constraintArea.ZoomDraggableDiv.maxX+zoomX);
        }
        if(-(zoomY+moveY)>=this.constraintArea.ZoomDraggableDiv.maxY){
            moveY=-(this.constraintArea.ZoomDraggableDiv.maxY+zoomY);
        }
        if(-(zoomX+moveX)<=0){
            moveX=-(zoomX);
        }
        if(-(zoomY+moveY)<=0){
            moveY=-(zoomY);
        }
        new Effect.MoveBy(zoomDiv,moveY,moveX,{
            duration:1.0,
            afterFinish:function(){
                $("nav-image-clear").observe("click",this.navigationMoveTo.bind(this));
            }.bind(this)
            });
        var left=-(zoomX+moveX);
        var top=-(zoomY+moveY);
        this.currentPos.X=left+this.offset.width;
        this.currentPos.Y=top+this.offset.height;
    }
},
doClick:function(e){
    if(this.isDragging||this.move==''){
        Event.stop(e);
    }else{
        this.getXYOffset(e);
        var newSize;
        if(this.move==this.ZOOMIN){
            newSize=this.size+1;
        }
        else if(this.move==this.ZOOMOUT){
            newSize=this.size-1;
        }
        this.submitMove(newSize);
    }
},
getXYOffset:function(e){
    if(this.size==0){
        var div=$('ZoomImage');
    }else{
        var div=$('ZoomDroppableDiv');
    }
    var imgY=div.offsetTop;
    var imgX=div.offsetLeft;
    parentObj=div.offsetParent;
    do{
        imgY+=parentObj.offsetTop;
        imgX+=parentObj.offsetLeft;
        parentObj=parentObj.offsetParent;
    }while(parentObj);
    var pageX=pageY=0;
    if(typeof e!='undefined'&&e.pageX!=undefined){
        pageX=e.pageX;
        pageY=e.pageY;
    }else if(typeof window.event!='undefined'&&event.clientX!=undefined){
        pageX=event.clientX;
        pageY=event.clientY;
    }
    this.clickPos.X=(pageX-imgX);
    this.clickPos.Y=(pageY-imgY);
},
submitMove:function(value){
    $("zoom-hover-div").addClassName('h');
    this.size=value;
    if($('sayes3'+this.size)){
        this.requestImg=false;
    }
    else{
        this.requestImg=true;
    }
    this.zoomInOut();
},
zoomInOut:function(){
    $("zoom-loading-img").addClassName('h');
    this.mSetCurrentValues();
    this.mSetMove(this.move);
    this.zoomControl.setValue(this.size);
    var draggable=$('ZoomDraggableDiv');
    var droppable=$('ZoomDroppableDiv');
    if(this.size==0){
        $('zoom-hover-div').observe('mouseover',function(){
            this.showZoomHover();
        }.bind(this));
        $('ZoomImageDiv').setStyle({
            height:''
        });
        $('ActualImageDiv').removeClassName('h');
        $('zoom-control-div').addClassName('h');
        droppable.hide();
    }else{
        this.constraintArea.ZoomDraggableDiv.maxX=this.currentImg.width-this.viewport.width;
        this.constraintArea.ZoomDraggableDiv.maxY=this.currentImg.height-this.viewport.height;
        this.mBuildImage(this.mGetImageURL(),this.currentImg.width,this.currentImg.height);
        $('ActualImageDiv').addClassName('h');
        $('zoom-control-div').removeClassName('h');
        var divLeft=-(this.currentPos.X-this.offset.width);
        var divTop=-(this.currentPos.Y-this.offset.height);
        draggable.setStyle({
            width:this.currentImg.width+'px',
            left:divLeft+'px',
            top:divTop+'px'
            });
        droppable.setStyle({
            display:'block',
            width:(Math.min(this.currentImg.width,this.viewport.width))+'px',
            height:(Math.min(this.currentImg.height,this.viewport.height))+'px'
            });
        $('ZoomImageDiv').setStyle({
            height:this.viewport.height+'px'
            });
        this.setNavigatorData(this.currentImg.width,this.currentImg.height);
        this.updateNavigatorLocation();
    }
},
mSetCurrentValues:function(){
    if(this.size==0){
        this.currentPos.X=this.offset.width;
        this.currentPos.Y=this.offset.height;
        this.currentImg.width=this.thumbImg.width;
        this.currentImg.height=this.thumbImg.height;
    }
    else{
        var targetWidth=this.mGetZoomedValue(this.thumbImg.width);
        var targetHeight=this.mGetZoomedValue(this.thumbImg.height);
        if(!this.resized){
            var newDimensions=this.mResizeForPanoramic(targetWidth,targetHeight);
            targetWidth=newDimensions[0];
            targetHeight=newDimensions[1];
        }
        this.clickPos.X=this.currentPos.X-this.offset.width+this.clickPos.X;
        this.clickPos.Y=this.currentPos.Y-this.offset.height+this.clickPos.Y;
        var percentageX=this.clickPos.X/this.currentImg.width;
        var percentageY=this.clickPos.Y/this.currentImg.height;
        var targetPosX=Math.round(targetWidth*percentageX);
        var targetPosY=Math.round(targetHeight*percentageY);
        targetPosX=Math.max(targetPosX,this.offset.width);
        targetPosY=Math.max(targetPosY,this.offset.height);
        this.currentPos.X=Math.min(targetPosX,targetWidth-this.offset.width);
        this.currentPos.Y=Math.min(targetPosY,targetHeight-this.offset.height);
        this.currentImg.width=targetWidth;
        this.currentImg.height=targetHeight;
    }
},
mResizeForPanoramic:function(width,height){
    if(width<this.viewport.width){
        this.resized=true;
        this.viewport.width=this.viewport.width*this.panoramicPercentage;
        this.offset.width=this.offset.width*this.panoramicPercentage;
        var zoomLoadingImg=$('zoom-loading-img');
        zoomLoadingImg.setStyle({
            left:'70px'
        });
    }
    if(height<this.viewport.height){
        this.resized=true;
        this.viewport.height=this.viewport.height*this.panoramicPercentage;
        this.offset.height=this.offset.height*this.panoramicPercentage;
        var zoomLoadingImg=$('zoom-loading-img');
        zoomLoadingImg.setStyle({
            top:'124px'
        });
    }
    while(this.resized&&(width<this.viewport.width||height<this.viewport.height)){
        this.stepSkipping++;
        width=this.mGetZoomedValue(this.thumbImg.width);
        height=this.mGetZoomedValue(this.thumbImg.height);
    }
    return[width,height];
},
mGetZoomedValue:function(value){
    return Math.round(value*(1+(this.zoomFactor*this.mGetSize())));
},
mGetImageURL:function(){
    return $('nameImagen').value;
},
mGetSize:function(){
    return this.size+this.stepSkipping;
},
mBuildImage:function(image,targetWidth,targetHeight){
    this.hideChildren($('ZoomDraggableDiv'));
    if($('h'+this.size)){
        $('h'+this.size).hide();
    }
    var hEle;
    if(!$('h'+(this.size-1))){
        var hEle=$('ActualImageDiv').cloneNode(true);
        hEle.id='h'+(this.size-1);
        hEle.align='left';
        hEle.setStyle({
            position:'relative',
            left:'0px',
            top:'0px'
        });
        hEle.className='pointer';
        $('ZoomDraggableDiv').insertBefore(hEle,$('s'+(this.size)));
        hEle.childElements().each(function(img){
            img.setStyle({
                width:targetWidth+'px',
                height:targetHeight+'px'
                });
            img.id='h'+(this.size-1)+'Img';
        });
    }else{
        var hEle=$('h'+(this.size-1));
    }
    hEle.show();
    var parent;
    if($('sayes4'+this.size)){
        parent=$('sayes5'+this.size);
    }else{
        parent=document.createElement('div');
        Element.extend(parent);
        parent.id=('sayes1'+this.size);
        parent.setStyle({
            position:'absolute',
            left:'0px',
            top:'0px',
            display:'none'
        });
    }
    $('ZoomDraggableDiv').appendChild(parent);
    if(this.requestImg){
        if(!$('sayes2'+this.size+'img')){
            var imgDiv=document.createElement('div');
            imgDiv.id='s'+this.size+'img';
            parent.appendChild(imgDiv);
            var clearDiv=document.createElement('div');
            clearDiv.className='clear';
            parent.appendChild(clearDiv);
            var img;
            img=document.createElement('img');
            Element.extend(img);
            img.id='sayes6'+this.size+'img';
            img.width=this.viewport.width;
            img.height=this.viewport.height;
                        img.className=$('class').value;
            img.setStyle({
                width:targetWidth+'px',
                height:targetHeight+'px',
                display:'block',
                'float':'left'
            });
            imgDiv.appendChild(img);
            this.showSpinner();
            img.observe('load',function(){
                zoomFile.observeImgLoad(this.id);
            });
            img.src=image;
        }
    }else{
    hEle.hide();
}
parent.show();
},
observeImgLoad:function(eleID){
    this.hideSpinner();
    if($('h'+this.size)){
        $('h'+this.size).hide();
    }
},
showZoomHover:function(){
    if(this.size==0){
        $("zoom-hover-div").removeClassName('h');
    }
},
hideZoomHover:function(){
    $("zoom-hover-div").addClassName('h');
},
showSpinner:function(){
    $("zoom-loading-img").removeClassName('h');
},
hideSpinner:function(){
    $("zoom-loading-img").addClassName('h');
},
hideChildren:function(element){
    if($(element).hasChildNodes()){
        var divs=$(element).childNodes;
        for(var i=0;i<divs.length;i++){
            divs[i].style.display='none';
        }
        }
    },
mSetMove:function(mMove){
    if(this.size==0){
        this.move=this.ZOOMIN;
    }
    else if(this.size==this.maxSize){
        this.move=this.ZOOMOUT;
    }
    else{
        this.move=mMove;
    }
},
setNavigatorData:function(targetWidth,targetHeight){
    this.navigatorData.ratio.width=(this.navigatorData.img.width/targetWidth);
    this.navigatorData.ratio.height=(this.navigatorData.img.height/targetHeight);
    this.navigatorData.box.width=Math.round((this.navigatorData.ratio.width*this.viewport.width));
    this.navigatorData.box.height=Math.round((this.navigatorData.ratio.height*this.viewport.height));
},
updateNavigatorLocation:function(){
    var boxWidth=(this.navigatorData.box.width-(this.navigatorData.box.border*2));
    var boxHeight=(this.navigatorData.box.height-(this.navigatorData.box.border*2));
    var navLeft=Math.round((this.currentPos.X*this.navigatorData.ratio.width)-(this.navigatorData.box.width/2));
    var navTop=Math.round((this.currentPos.Y*this.navigatorData.ratio.height)-(this.navigatorData.box.height/2));
    $('NavCtrlBox').setStyle({
        width:boxWidth+'px',
        height:boxHeight+'px',
        left:(navLeft+1)+'px',
        top:(navTop+1)+'px'
        });
    this.constraintArea.NavCtrlBox.minX=this.navigatorData.img.width-this.navigatorData.box.width+1;
    this.constraintArea.NavCtrlBox.minY=this.navigatorData.img.height-this.navigatorData.box.height+1;
    $('nav-image').setStyle({
        left:-(navLeft-this.navigatorData.box.border)-3+'px',
        top:-(navTop-this.navigatorData.box.border)-3+'px'
        });
},
disableHighlight:function(ele){
    if(typeof ele.onselectstart!='undefined'){
        ele.observe('selectstart',function(){
            return false;
        });
    }else if(typeof ele.style.MozUserSelect!='undefined'){
        ele.style.MozUserSelect='none';
    }else{
        ele.observe('mousedown',function(){
            return false;
        });
    }
}
};

Draggable.prototype.draw=function(point){
    var pos=Position.cumulativeOffset(this.element);
    if(this.options.ghosting){
        var r=Position.realOffset(this.element);
        pos[0]+=r[0]-Position.deltaX;
        pos[1]+=r[1]-Position.deltaY;
    }
    var d=this.currentDelta();
    pos[0]-=d[0];
    pos[1]-=d[1];
    if(this.options.scroll&&(this.options.scroll!=window&&this._isScrollChild)){
        pos[0]-=this.options.scroll.scrollLeft-this.originalScrollLeft;
        pos[1]-=this.options.scroll.scrollTop-this.originalScrollTop;
    }
    var p=[0,1].map(function(i){
        return(point[i]-pos[i]-this.offset[i])
        }.bind(this));
    if(this.options.snap){
        if(Object.isFunction(this.options.snap)){
            p=this.options.snap(p[0],p[1],this);
        }else{
            if(Object.isArray(this.options.snap)){
                p=p.map(function(v,i){
                    return(v/this.options.snap[i]).round()*this.options.snap[i]
                    }.bind(this))
                }else{
                p=p.map(function(v){
                    return(v/this.options.snap).round()*this.options.snap
                    }.bind(this))
                }
            }
    }
var style=this.element.style;
if((!this.options.constraint)||(this.options.constraint=='horizontal')){
    style.left=p[0]+'px';
}
if((!this.options.constraint)||(this.options.constraint=='vertical')){
    style.top=p[1]+'px';
}
if(this.options.constraintarea){
    var eleID=this.element.id;
    if(p[0]>zoomFile.constraintArea[eleID].minX){
        style.left=zoomFile.constraintArea[eleID].minX+'px';
    }
    if(p[1]>zoomFile.constraintArea[eleID].minY){
        style.top=zoomFile.constraintArea[eleID].minY+'px';
    }
    if(p[0]<zoomFile.constraintArea[eleID].maxX*-1){
        style.left=(zoomFile.constraintArea[eleID].maxX*-1)+'px';
    }
    if(p[1]<zoomFile.constraintArea[eleID].maxY*-1){
        style.top=(zoomFile.constraintArea[eleID].maxY*-1)+'px';
    }
}
if(style.visibility=='hidden')style.visibility='';
}