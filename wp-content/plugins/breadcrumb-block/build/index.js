!function(){var e,t={890:function(e,t,r){"use strict";var a,l,n=window.wp.blocks,o=window.wp.element,c=r(184),i=r.n(c),m=window.wp.i18n,u=window.wp.blockEditor,s=window.wp.components,b=window.React;function d(){return d=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var a in r)Object.prototype.hasOwnProperty.call(r,a)&&(e[a]=r[a])}return e},d.apply(this,arguments)}var p=JSON.parse('{"TN":"Breadcrumb Block"}');(0,n.registerBlockType)("boldblocks/breadcrumb-block",{title:p.TN,icon:function(e){return b.createElement("svg",d({xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 256 256",role:"img"},e),a||(a=b.createElement("rect",{width:256,height:256,ry:33.703,fill:"#d20962"})),l||(l=b.createElement("g",{fill:"#fff",strokeWidth:1.093},b.createElement("path",{d:"M142.502 139.779 69.19 214.875c-8.639 8.863-22.173 1.66-22.173-11.79V52.892a13.373 15.658 0 0 1 22.187-11.79l73.286 75.096a13.373 15.658 0 0 1 0 23.58z"}),b.createElement("path",{fillRule:"evenodd",d:"M126.28 38.641a6.032 7.032 0 0 1 8.542 0l72.39 84.382a6.032 7.032 0 0 1 0 9.957l-72.39 84.382a6.04 7.04 0 0 1-8.542-9.957l68.13-79.403-68.13-79.403a6.032 7.032 0 0 1 0-9.958z"}))))},edit:function(e){let{attributes:{gap:t,separator:r,hideHomePage:a,hideCurrentPage:l,homeText:n},setAttributes:c,isSelected:b}=e;const d=[{label:"/",value:"/"},{label:(0,o.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"currentColor",width:"1em",height:"1em",viewBox:"0 0 16 16"},(0,o.createElement)("path",{d:"m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"})),value:'<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 16 16">\n      <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>\n    </svg>'},{label:(0,o.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"currentColor",width:"1em",height:"1em",viewBox:"0 0 16 16"},(0,o.createElement)("path",{"fill-rule":"evenodd",d:"M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"})),value:'<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 16 16">\n      <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>\n    </svg>'},{label:(0,o.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"currentColor",width:"1em",height:"1em",viewBox:"0 0 16 16"},(0,o.createElement)("path",{"fill-rule":"evenodd",d:"M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"})),value:'<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 16 16">\n      <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>\n    </svg>'}];return(0,o.createElement)(o.Fragment,null,b&&(0,o.createElement)(o.Fragment,null,(0,o.createElement)(u.InspectorControls,null,(0,o.createElement)(s.PanelBody,{title:(0,m.__)("Block settings","breadcrumb-block")},(0,o.createElement)("div",{className:"breadrumb-setings"},(0,o.createElement)(s.__experimentalUnitControl,{label:(0,m.__)("Gap","breadcrumb-block"),value:t,onChange:e=>c({gap:e})}),(0,o.createElement)("div",{className:"toggle-group-control"},(0,o.createElement)(s.BaseControl,{className:"toggle-group-control__label",label:(0,m.__)("Separator","breadcrumb-block")}),(0,o.createElement)(s.ButtonGroup,{"aria-label":(0,m.__)("Separator icon","breadcrumb-block")},d.map((e=>{let{label:t,value:a,disabled:l=!1}=e;return(0,o.createElement)(s.Button,{key:a,isSmall:!0,variant:a===r?"primary":void 0,onClick:()=>c({separator:a}),style:{verticalAlign:"top"},disabled:l},t)})))),(0,o.createElement)(s.ToggleControl,{label:(0,m.__)("Hide the home page","breadcrumb-block"),checked:a,onChange:e=>c({hideHomePage:e})}),!a&&(0,o.createElement)(s.TextControl,{label:(0,m.__)("Custom home text","breadcrumb-block"),value:n,onChange:e=>c({homeText:e}),help:(0,m.__)("Input a custom home text. Leave it blank to use the default text.","breadcrumb-block")}),(0,o.createElement)(s.ToggleControl,{label:(0,m.__)("Hide current page","breadcrumb-block"),checked:l,onChange:e=>c({hideCurrentPage:e})}))))),(0,o.createElement)("div",(0,u.useBlockProps)({style:{"--bb--crumb-gap":t},className:i()({"hide-current-page":l,"hide-home-page":a})}),(0,o.createElement)("nav",{role:"navigation","aria-label":"breadcrumb",class:"breadcrumb"},(0,o.createElement)("ol",{class:"breadcrumb-items"},(0,o.createElement)("li",{class:"breadcrumb-item breadcrumb-item--home"},(0,o.createElement)("a",{href:"#"},(0,o.createElement)("span",{class:"breadcrumb-item-name"},n||(0,m.__)("Home","breadcrumb-block"))),(0,o.createElement)("span",{className:"sep",dangerouslySetInnerHTML:{__html:r}})),(0,o.createElement)("li",{class:"breadcrumb-item breadcrumb-item--parent"},(0,o.createElement)("a",{href:"#"},(0,o.createElement)("span",{class:"breadcrumb-item-name"},(0,m.__)("Dummy parent","breadcrumb-block"))),(0,o.createElement)("span",{className:"sep",dangerouslySetInnerHTML:{__html:r}})),(0,o.createElement)("li",{class:"breadcrumb-item breadcrumb-item--current"},(0,o.createElement)("span",{class:"breadcrumb-item-name"},(0,m.__)("Dummy title","breadcrumb-block")))))))}})},184:function(e,t){var r;!function(){"use strict";var a={}.hasOwnProperty;function l(){for(var e=[],t=0;t<arguments.length;t++){var r=arguments[t];if(r){var n=typeof r;if("string"===n||"number"===n)e.push(r);else if(Array.isArray(r)){if(r.length){var o=l.apply(null,r);o&&e.push(o)}}else if("object"===n){if(r.toString!==Object.prototype.toString&&!r.toString.toString().includes("[native code]")){e.push(r.toString());continue}for(var c in r)a.call(r,c)&&r[c]&&e.push(c)}}}return e.join(" ")}e.exports?(l.default=l,e.exports=l):void 0===(r=function(){return l}.apply(t,[]))||(e.exports=r)}()}},r={};function a(e){var l=r[e];if(void 0!==l)return l.exports;var n=r[e]={exports:{}};return t[e](n,n.exports,a),n.exports}a.m=t,e=[],a.O=function(t,r,l,n){if(!r){var o=1/0;for(u=0;u<e.length;u++){r=e[u][0],l=e[u][1],n=e[u][2];for(var c=!0,i=0;i<r.length;i++)(!1&n||o>=n)&&Object.keys(a.O).every((function(e){return a.O[e](r[i])}))?r.splice(i--,1):(c=!1,n<o&&(o=n));if(c){e.splice(u--,1);var m=l();void 0!==m&&(t=m)}}return t}n=n||0;for(var u=e.length;u>0&&e[u-1][2]>n;u--)e[u]=e[u-1];e[u]=[r,l,n]},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,{a:t}),t},a.d=function(e,t){for(var r in t)a.o(t,r)&&!a.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={826:0,431:0};a.O.j=function(t){return 0===e[t]};var t=function(t,r){var l,n,o=r[0],c=r[1],i=r[2],m=0;if(o.some((function(t){return 0!==e[t]}))){for(l in c)a.o(c,l)&&(a.m[l]=c[l]);if(i)var u=i(a)}for(t&&t(r);m<o.length;m++)n=o[m],a.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return a.O(u)},r=self.webpackChunkbreadcrumb_block=self.webpackChunkbreadcrumb_block||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))}();var l=a.O(void 0,[431],(function(){return a(890)}));l=a.O(l)}();