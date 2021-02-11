/* jce - 2.9.1 | 2020-10-29 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2020 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){function uid(){var i,guid=(new Date).getTime().toString(32);for(i=0;i<5;i++)guid+=Math.floor(65535*Math.random()).toString(32);return"wf_"+guid+(counter++).toString(32)}function isMedia(node){return node&&/mce-item-(audio|video|iframe)/.test(node.className)}function ucfirst(s){return s.substring(0,1).toUpperCase()+s.substring(1)}function attribsToObject(elm,filter){var obj={};return each(elm.attributes,function(attrib){var name=attrib.nodeName;return!(!filter||!new RegExp(filter).test(name))||void(obj[name]=elm.getAttribute(name))}),obj}function insertMedia(ed,data,provider){var html=getMediaHtml(ed,data,provider);ed.execCommand("insertMediaHtml",!1,html),ed.undoManager.add(),ed.nodeChanged()}function getMediaHtml(ed,value,provider){"string"==typeof value&&(value={src:value});var html;if(html=/\.(mp4|m4v|ogg|webm|ogv)$/.test(value.src)?ed.dom.createHTML("video",value,""):/\.(mp3|m4a|oga)$/.test(value.src)?ed.dom.createHTML("audio",value,""):value.html?sanitize(ed,value.html):ed.dom.createHTML("iframe",value,""),/instagram|facebook/.test(provider)){var args={};each(value,function(val,key){if("html"===key)return!0;if("class"===key&&(val+=" mce-item-media-"+provider),"instagram"===provider&&("src"===key&&val.indexOf("/embed/captioned")===-1&&(val+="/embed/captioned"),"height"===key&&!val&&value.width)){var ratio=4/3;val=Math.floor(ratio*value.width)}if("facebook"===provider&&"src"===key&&val.indexOf("/plugins/")===-1){var url="https://www.facebook.com/plugins/";val.indexOf("/videos/")!==-1&&(url+="video.php?href="),val.indexOf("/posts/")!==-1&&(url+="post.php?href="),val=url+encodeURIComponent(val)+"&width="+value.width+"&height="+value.height}args[key]=val}),html=ed.dom.createHTML("iframe",args,"")}return html}function isSupportedMedia(ed,url){if(!url||"string"!=typeof url)return!1;var params=ed.getParam("mediamanager",{});if(/\.(mp4|m4v|ogg|ogv|webm)$/i.test(url))return"video";if(/\.(mp3|m4a|oga)$/i.test(url))return"video";if(/youtu(\.)?be(.+)?\/(.+)/.test(url))return"youtube";if(/vimeo(.+)?\/(.+)/.test(url))return"vimeo";if(/dai\.?ly(motion)?(\.com)?/.test(url))return"dailymotion";if(/scribd\.com\/(.+)/.test(url))return"scribd";if(/slideshare\.net\/(.+)\/(.+)/.test(url))return"slideshare";if(/soundcloud\.com\/(.+)/.test(url))return"soundcloud";if(/spotify\.com\/(.+)/.test(url))return"spotify";if(/ted\.com\/talks\/(.+)/.test(url))return"ted";if(/twitch\.tv\/(.+)/.test(url))return"twitch";if(/www\.facebook\.com\/(.+)?(posts|videos)\/(.+)/.test(url))return"facebook";if(/instagr\.?am(.+)?\/(.+)/.test(url))return"instagram";if(params.custom_embed){var match;return each(params.custom_embed,function(values,name){var rx=values.expression||name;if(new RegExp(rx).test(url))return match=name,!0}),match||!1}return!1}function getMediaProps(ed,data,provider){var value=data.url||"",params=ed.getParam("mediamanager",{}),defaultValues={youtube:{src:value,width:560,height:315,frameborder:0,allowfullscreen:"allowfullscreen",allow:"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"},vimeo:{src:value,width:560,height:315,frameborder:0,allowfullscreen:"allowfullscreen",allow:"autoplay; fullscreen"},dailymotion:{src:value,width:640,height:360,frameborder:0,allowfullscreen:"allowfullscreen",allow:"autoplay; fullscreen"},video:{src:value,width:560,height:315,controls:!0,type:"video/mpeg"},slideshare:{src:"",width:427,height:356,frameborder:0,allowfullscreen:"allowfullscreen",allow:"fullscreen"},soundcloud:{src:"",width:"100%",height:400,frameborder:0},spotify:{src:value,width:300,height:380,frameborder:0,allowtransparency:!0,allow:"encrypted-media"},ted:{src:"",width:560,height:316,frameborder:0,allowfullscreen:"allowfullscreen"},twitch:{src:"",width:500,height:281,frameborder:0,allowfullscreen:"allowfullscreen"},instagram:{src:value,width:658,height:877,frameborder:0,allowfullscreen:"allowfullscreen"},facebook:{src:value,frameborder:0,width:500,height:280,allowtransparency:"allowtransparency",allowfullscreen:"allowfullscreen",scrolling:"no",allow:"encrypted-media;fullscreen"}};params.custom_embed&&params.custom_embed[provider]&&(defaultValues[provider]=params.custom_embed[provider],delete defaultValues[provider].expression,extend(defaultValues[provider],{src:value,width:560,height:315,frameborder:0})),value=value.replace(/[^a-z0-9-_:&;=\?\[\]\/\.]/gi,""),defaultValues[provider]||(defaultValues[provider]={}),defaultValues[provider].url=value;var attribs=data.attributes||{},args={},attribsMap=["title","id","class","style","width","height"];if(each(attribs,function(val,key){key===provider?args=extend(args,attribs[provider]):tinymce.inArray(attribsMap,key)!==-1&&(args[key]=val)}),defaultValues[provider]=extend(defaultValues[provider],args),"youtube"===provider){var src=value.replace(/youtu(\.)?be([^\/]+)?\/(.+)/,function(a,b,c,d){return d=d.replace(/(watch\?v=|v\/|embed\/)/,""),b&&!c&&(c=".com"),id=d.replace(/([^\?&#]+)/,function($0,$1){return $1}),"youtube"+c+"/embed/"+d});defaultValues[provider].src=src}if("vimeo"===provider){var id="",s=/vimeo\.com\/(\w+\/)?(\w+\/)?([0-9]+)/.exec(value);s&&tinymce.is(s,"array")&&(id=s.pop()),defaultValues[provider].src="https://player.vimeo.com/video/"+id}if("dailymotion"===provider){var id="",s=/dai\.?ly(motion)?(.+)?\/(swf|video)?\/?([a-z0-9]+)_?/.exec(value);s&&tinymce.is(s,"array")&&(id=s.pop()),defaultValues[provider].src="https://dailymotion.com/embed/video/"+id}if("instagram"===provider&&(value=value.replace(/\/\?.+$/gi,""),value=value.replace(/\/$/,""),defaultValues[provider].src=value+"/embed/captioned"),"spotify"===provider&&(defaultValues[provider].src=value.replace(/open\.spotify\.com\/track\//,"open.spotify.com/embed/track/")),"ted"===provider&&(defaultValues[provider].src=value.replace(/www\.ted.com\/talks\//,"embed.ted.com/talks/")),"facebook"===provider){var url="https://www.facebook.com/plugins/";value.indexOf("/videos/")!==-1&&(url+="video.php?href="),value.indexOf("/posts/")!==-1&&(url+="post.php?href="),defaultValues[provider].src=url+encodeURIComponent(value)+"&width="+defaultValues[provider].width+"&height="+defaultValues[provider].height}return defaultValues}function getEmbedData(ed,data,provider){var defaultProviderData=data[provider]||"";return new Promise(function(resolve,reject){if(!defaultProviderData)return reject();if("video"===provider){var video=document.createElement("video");return video.onloadedmetadata=function(){data.video.width||data.video.height||tinymce.extend(data.video,{width:video.videoWidth,height:video.videoHeight}),video=null,tinymce.is(data.video.controls)||(data.video.controls="controls"),resolve(data.video)},video.onerror=function(){video=null,resolve(data.video)},void(video.src=ed.documentBaseURI.toAbsolute(data.video.src))}if("audio"===provider)return tinymce.is(data.audio.controls)||(data.audio.controls="controls"),void resolve(data.audio);var value=data[provider].url||data[provider].src,type="";"facebook"===provider&&/\/(posts|videos)\//.test(value)&&(type=/\/(posts|videos)\//.exec(value)[1]);var args={id:uid(),method:"getEmbedData",params:[provider,encodeURIComponent(value),type]},query="&"+ed.settings.token+"=1";tinymce.util.XHR.send({url:ed.getParam("site_url")+"index.php?option=com_jce&task=plugin.rpc&plugin=mediamanager&"+ed.settings.token+"=1&context="+ed.settings.context,data:"json="+JSON.stringify(args)+query,content_type:"application/x-www-form-urlencoded",success:function(response){var data="";try{data=JSON.parse(response)}catch(e){return resolve(defaultProviderData)}if(!data.result)return resolve(defaultProviderData);if(data.result.error)return reject(data.result.error);var result=tinymce.is(data.result,"object")?data.result:{};if("string"==typeof data.result)try{result=JSON.parse(data.result)}catch(e){return resolve(defaultProviderData)}!result.src&&result.url&&(result.src=result.url),data=tinymce.extend(defaultProviderData,result),resolve(data)},error:function(err,xhr){return resolve(defaultProviderData)}})})}function getDataAndInsert(ed,data){return new Promise(function(resolve,reject){if(data.url){var provider=isSupportedMedia(ed,data.url);if(!provider)return ed.windowManager.alert(ed.getLang("mediamanager.url_unsupported","This URL is not currently supported"));ed.setProgressState(!0);var props=getMediaProps(ed,data,provider);getEmbedData(ed,props,provider).then(function(args){ed.setProgressState(!1),insertMedia(ed,args),resolve()},function(msg){ed.setProgressState(!1),msg&&ed.windowManager.alert(msg),reject()})}})}var each=tinymce.each,extend=tinymce.extend,DOM=tinymce.DOM,Event=tinymce.dom.Event,counter=0,sanitize=function(editor,html){var blocked,writer=new tinymce.html.Writer;return new tinymce.html.SaxParser({validate:!1,allow_conditional_comments:!1,special:"script,noscript",comment:function(text){writer.comment(text)},cdata:function(text){writer.cdata(text)},text:function(text,raw){writer.text(text,raw)},start:function(name,attrs,empty){if(blocked=!0,"script"!==name&&"noscript"!==name&&"svg"!==name){for(var i=attrs.length-1;i>=0;i--){var attrName=attrs[i].name;0===attrName.indexOf("on")&&(delete attrs.map[attrName],attrs.splice(i,1)),"style"===attrName&&(attrs[i].value=editor.dom.serializeStyle(editor.dom.parseStyle(attrs[i].value),name))}writer.start(name,attrs,empty),blocked=!1}},end:function(name){blocked||writer.end(name)}},new tinymce.html.Schema({})).parse(html),writer.getContent()};tinymce.create("tinymce.plugins.MediaManagerPlugin",{init:function(ed,url){function isMediaElm(n){return"mce-item-shim"===n.className&&(n=n.parentNode),/mce-item-(flash|shockwave|windowsmedia|quicktime|realmedia|divx|silverlight|audio|video|iframe)/.test(n.className)}function isPopup(n){return!!ed.dom.is(n,"a.jcepopup")&&(/(flash|quicktime|director|shockwave|windowsmedia|mplayer|real|realaudio|divx|video|audio)/.test(n.type)||/(youtube|google|metacafe)/.test(n.href))}var self=this;self.editor=ed,self.url=url,ed.addCommand("mceMedia",function(){var se=ed.selection,n=se.getNode();isPopup(n)&&se.select(n),ed.windowManager.open({file:ed.getParam("site_url")+"index.php?option=com_jce&task=plugin.display&plugin=mediamanager",size:"mce-modal-portrait-full"},{plugin_url:url})}),ed.onNodeChange.add(function(ed,cm,n){cm.setActive("mediamanager",isMediaElm(n)||isPopup(n))}),ed.onPreInit.add(function(){var params=ed.getParam("mediamanager",{});if(params.basic_dialog===!0){var urlCtrl,cm=ed.controlManager,form=cm.createForm("media_form"),args={label:ed.getLang("url","URL"),name:"url",clear:!0};params.basic_dialog_filebrowser&&tinymce.extend(args,{picker:!0,picker_icon:"media",onpick:function(){ed.execCommand("mceFileBrowser",!0,{caller:"mediamanager",callback:function(selected,data){if(data.length){var src=data[0].url;urlCtrl.value(src),window.setTimeout(function(){urlCtrl.focus()},10)}},filter:params.filetypes.join(",")})}}),urlCtrl=cm.createUrlBox("media_url",args),form.add(urlCtrl);var attribs={src:""};ed.addCommand("mceMedia",function(){ed.windowManager.open({title:ed.getLang("mediamanager.desc","Video"),items:[form],size:"mce-modal-landscape-small",open:function(){var type,label=ed.getLang("insert","Insert"),node=ed.selection.getNode(),data={};if(attribs.src="",isMedia(node)){if(/mce-item-preview/.test(node.className))node=node.firstChild,type="iframe",data[type]=attribsToObject(node);else{var jsonString=ed.dom.getAttrib(node,"data-mce-json"),type=ed.dom.getAttrib(node,"data-mce-type");try{data=JSON.parse(jsonString)}catch(e){}}type&&data[type]&&(attribs=data[type]),each(["width","height","style","class","id","title"],function(name){var val=ed.dom.getAttrib(node,name);if(""!==val&&("class"===name&&(val=val.replace(/mce-item-(\w+)/gi,"").replace(/\s+/g," "),val=tinymce.trim(val)),attribs[name]=val),"width"===name||"height"===name){var val=ed.dom.getStyle(node,name)||ed.dom.getAttrib(node,name);attribs[name]=parseInt(val)}})}attribs.src&&(label=ed.getLang("update","Update")),urlCtrl.value(attribs.src),window.setTimeout(function(){urlCtrl.focus()},10),DOM.setHTML(this.id+"_insert",label)},buttons:[{title:ed.getLang("common.cancel","Cancel"),id:"cancel"},{title:ed.getLang("insert","Insert"),id:"insert",onsubmit:function(e){var data=form.submit();ed.selection.getNode();return Event.cancel(e),!!data.url&&(attribs=tinymce.extend(params.attributes||{},attribs),data=tinymce.extend(data,{attributes:attribs}),void getDataAndInsert(ed,data).then(function(){}))},classes:"primary",scope:self}]})})}}),ed.onInit.add(function(){var params=ed.getParam("mediamanager",{});if(ed&&ed.plugins.contextmenu&&ed.plugins.contextmenu.onContextMenu.add(function(th,m,e){m.add({title:"mediamanager.desc",icon:"mediamanager",cmd:"mceMedia"})}),ed.theme&&ed.theme.onResolveName&&ed.theme.onResolveName.add(function(theme,o){var n=o.node;if(n){var cls=ed.dom.getAttrib(n,"class","");if(cls.indexOf("mce-item-iframe")!==-1){cls.indexOf("mce-item-preview")!==-1&&(n=n.firstChild);var str=isSupportedMedia(ed,n.src)||"";str&&(str=ucfirst(str)),o.name=str||"iframe"}}}),params.quickmedia!==!1&&ed.plugins.clipboard){var ux="^((http|https)://[-!#$%&'*+\\/0-9=?A-Z^_`a-z{|}~;]+.[-!#$%&'*+\\./0-9=?A-Z^_`a-z{|}~;]+)$";ed.onGetClipboardContent.add(function(ed,content){var text=content["text/plain"]||"";if(text){var match=new RegExp(ux).exec(text);if(match){var value=tinymce.trim(match[0]),provider=isSupportedMedia(ed,value);if(provider){content["text/plain"]="";var data=getMediaProps(ed,{url:value},provider),args=tinymce.extend({"data-mce-clipboard-media":value},data[provider]),html=getMediaHtml(ed,args,provider);content["text/html"]=content["x-tinymce/html"]=html}}}}),ed.onPasteBeforeInsert.add(function(ed,o){var node=ed.dom.create("div",0,o.content),media=ed.dom.select("[data-mce-clipboard-media]",node);media.length&&(each(media,function(el){var value=el.getAttribute("data-mce-clipboard-media"),provider=isSupportedMedia(ed,value);if(provider){ed.setProgressState(!0);var attribs=self.getAttributes(params.attributes||{}),props=getMediaProps(ed,{url:value,attributes:attribs},provider);getEmbedData(ed,props,provider).then(function(data){each(ed.dom.select("[data-mce-clipboard-media]",ed.getBody()),function(el){el.getAttribute("data-mce-clipboard-media")===value&&(ed.selection.select(el),el.removeAttribute("data-mce-clipboard-media"),insertMedia(ed,data,provider))}),ed.setProgressState(!1)})}}),o.content=ed.serializer.serialize(node,{getInner:1,forced_root_block:""}),ed.dom.remove(node))})}})},createControl:function(n,cm){var self=this,ed=this.editor;if("mediamanager"!==n)return null;var params=ed.getParam("mediamanager",{});if(params.quickmedia===!1||params.basic_dialog===!0)return cm.createButton("mediamanager",{title:"mediamanager.desc",cmd:"mceMedia"});var html='<div class="mceToolbarRow">   <div class="mceToolbarItem mceFlexAuto">       <input type="text" id="'+ed.id+'_media_input" aria-label="'+ed.getLang("mediamanager.src","URL")+'" />   </div>   <div class="mceToolbarItem">       <button type="button" id="'+ed.id+'_media_submit" class="mceButton mceButtonMedia">           <span class="mceIcon mce_check"></span>       </button>   </div></div>',ctrl=cm.createSplitButton("mediamanager",{title:"mediamanager.desc",cmd:"mceMedia",max_width:264,onselect:function(node){""!==node.value&&getDataAndInsert(ed,{url:node.value}).then(function(){})}});return ctrl.onRenderMenu.add(function(c,m){var item=m.add({onclick:function(e){e.preventDefault(),item.setSelected(!1);var n=ed.dom.getParent(e.target,".mceButton");if(n&&!n.disabled){var value=DOM.getValue(ed.id+"_media_input");""!==value&&getDataAndInsert(ed,{url:value,attributes:self.getAttributes(params.attributes||{})}).then(function(){}),m.hideMenu()}},html:html});m.onShowMenu.add(function(){var type,selection=ed.selection,value="",data={};if(!selection.isCollapsed()){var node=selection.getNode();if(isMedia(node))if(/mce-item-preview/.test(node.className))node=node.firstChild,type="iframe",data[type]=attribsToObject(node);else{var jsonString=ed.dom.getAttrib(node,"data-mce-json"),type=ed.dom.getAttrib(node,"data-mce-type");try{data=JSON.parse(jsonString)}catch(e){}}}window.setTimeout(function(){DOM.get(ed.id+"_media_input").focus()},10),type&&data[type]&&(value=data[type].src||""),DOM.setValue(ed.id+"_media_input",value)})}),ctrl},getAttributes:function(data){var ed=this.editor;return data.style&&tinymce.is(data.style,"string")&&(data.style=ed.dom.parseStyle(data.style)),data.styles&&tinymce.is(data.styles,"object")&&(data.style=extend(data.styles,data.style||{})),data.style&&(data.style=ed.dom.serializeStyle(data.style)),data},insertUploadedFile:function(o){var ed=this.editor,data=this.getUploadConfig();if(data&&data.filetypes&&new RegExp(".("+data.filetypes.join("|")+")$","i").test(o.name)){var attribs=this.getAttributes(o.attributes||{});return getDataAndInsert(ed,{url:o.file,attributes:attribs}).then(function(){}),!0}return!1},getUploadURL:function(file){var ed=this.editor,data=this.getUploadConfig();return!!(data&&data.filetypes&&new RegExp(".("+data.filetypes.join("|")+")$","i").test(file.name))&&ed.getParam("site_url")+"index.php?option=com_jce&task=plugin.display&plugin=mediamanager"},getUploadConfig:function(){var ed=this.editor,data=ed.getParam("mediamanager",{});return data.upload||{}}}),tinymce.PluginManager.add("mediamanager",tinymce.plugins.MediaManagerPlugin,["media"])}();