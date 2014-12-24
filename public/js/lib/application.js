!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

// Events --------------------------
// app:init                         scope, element

.config(['$sceDelegateProvider', '$sceProvider', '$compileProvider', function($sceDelegateProvider, $sceProvider, $compileProvider){
    $sceProvider.enabled(false);
    $sceDelegateProvider.resourceUrlWhitelist(['self', '**']);
    $compileProvider.aHrefSanitizationWhitelist(/.+/);
    $compileProvider.imgSrcSanitizationWhitelist(/.+/);
}])

.constant('agent', (function(){
    var a = navigator.userAgent.toLowerCase();
    var r = {device:'desktop', name:'unnamed', os:'UnknownOS', browser:'unknown', version:'0', engine:'unknown', jsPrefix:'', cssPrefix:'', transition:false, isHttps:false};
    var s = document.body.style;
    var v = function(){
        var n = navigator.appName, u = navigator.userAgent, t;
        var m = u.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
        if (m && (t = u.match(/version\/([\.\d]+)/i)) != null) {
            m[2] = t[1];
        }
        m = m ? [m[1], m[2]] : [n, navigator.appVersion, '-?'];
        return m;
    };
    if(/ipad/.test(a))                            {r.name = 'ipad'; r.os = 'iOS'; r.device = 'mobile'; r.transition = true;}
    else if(/ipod/.test(a))                       {r.name = 'ipod'; r.os = 'iOS'; r.device = 'mobile'; r.transition = true;}
    else if(/iphone/.test(a))                     {r.name = 'iphone'; r.os = 'iOS'; r.device = 'mobile'; r.transition = true;}
    else if(/android/.test(a))                    {r.name = 'android'; r.os = 'Android'; r.device = 'mobile'; r.transition = true;}
    else if(/blackberry|playbook|bb10/.test(a))   {r.name = 'blackberry'; r.os = 'BlackBerryOS'; r.device = 'mobile'; r.transition = true;}
    else if(/palm/.test(a))                       {r.name = 'palm'; r.os = 'PalmOS'; r.device = 'mobile';}
    else if(/win/.test(a))                        {r.name = 'windows'; r.os = 'WindowsOS';}
    else if(/mac/.test(a))                        {r.name = 'mac'; r.os = 'MacOS';}
    else if (/linux/.test(a)) {
        r.name = 'linux';
        r.os = 'LinuxOS';
    }
        if (/mobile/.test(a)) {
            r.device = 'mobile';
            r.name == 'windows' && (r.os = 'WindowsPhoneOS');
        }
        if(/webkit/.test(a))                          {r.engine = 'webkit'; r.jsPrefix = 'webkit'; r.cssPrefix = '-webkit-'; r.transition = true;}
    else if(/opera/.test(a))                      {r.browser = 'opera'; r.engine = 'opera'; r.jsPrefix = 'O'; r.cssPrefix = '-o-';}
    else if(/msie/.test(a))                       {r.browser = 'msie'; r.engine = 'msie'; r.jsPrefix = 'ms'; r.cssPrefix = '-ms-';}
    else if (/firefox/.test(a)) {
        r.browser = 'firefox';
        r.engine = 'mozilla';
        r.jsPrefix = 'Moz';
        r.cssPrefix = '-moz-';
    }
        if (/safari/.test(a)) {
            r.browser = 'safari';
        }
        if (/chrome/.test(a)) {
            r.browser = 'chrome';
        }
        r.webkit = (/applewebkit\/([\d.]+)/.exec(a) || [0,0])[1];
    r.webkitInt = parseInt(r.webkit, 10) || 0;
    r.webkitFloat = parseFloat(r.webkit, 10) || 0;
    r.version = parseInt(v()[1], 10);
    r.device == 'mobile' && (r.os += a.replace(/.*(os|android)\s*([0-9\.]+).*/, '$2').replace(/\./g, '_') || '');
    r.transition = (typeof(s['webkitTransition']) + typeof(s['MozTransition']) + typeof(s['msTransition']) + typeof(s['OTransition']) + typeof(s['transition'])).indexOf('string') >= 0;
    r.isHttps = document.location.protocol == 'https:';
    r.isStandalone = angular.isDefined(window.navigator.standalone) && window.navigator.standalone;
    r.uuid = (window.device && window.device.uuid) || '';
    r.cordova = (window.device && window.device.cordova) || '';
    app.$Agent = r;
    return r;
})())

.constant('cookie', (function(){
    var obj = this;
    obj.set = function(name, value, timeinterval, path, domain ,secure){
        var expires = new Date();
        expires.setTime(expires.getTime() + (timeinterval || 0));
        value = escape(value);
        document.cookie = name + '=' + value +
        (timeinterval ? '; expires=' + expires.toGMTString() : '') +
        (path ? '; path=' + path : '') +
        (domain ? '; domain=' + domain : '') +
        (secure ? '; secure' : '');
        return obj;
    };
    obj.get = function(name){
        var prefix = name + '=', begin = document.cookie.indexOf('; ' + prefix);
        if(begin == -1){
            begin = document.cookie.indexOf(prefix);
            if (begin != 0) {
                return null;
            }
        }else{
            begin += 2;
        }
        var end = document.cookie.indexOf(';', begin);
        if (end == -1) {
            end = document.cookie.length;
        }
        var value = document.cookie.substring(begin + prefix.length, end);
        return value ? decodeURIComponent(decodeURI(value)) : '';
    };
    obj.del = function(name, path, domain){
        if(obj.get(name)){
            document.cookie = name + '=' +
            (path ? '; path=' + path : '') +
            (domain ? '; domain=' + domain : '') +
            '; expires=Thu, 01-Jan-70 00:00:01 GMT';
        }
        return obj;
    };
    app.$Cookie = obj;
    return obj;
}).call({}))

.constant('storage', (function(){
    var obj = this;
    obj.is = function(){
        return Boolean(window['localStorage'] || false);
    };
    obj.save = function(key, value, saveEmpty){
        if (!obj.is) {
            return false;
        }
        try{
            if(value || saveEmpty){
                localStorage.setItem(key, angular.toJson(value));
            }else{
                localStorage.removeItem(key);
            }
        }catch(e){
            // 5 MB quota
        }
        return obj;
    };
    obj.load = function(key){
        if (!obj.is) {
            return null;
        }
        var str = localStorage.getItem(key);
        return str ? angular.fromJson(str) : undefined;
    };
    obj.delete = function(key){
        if (!obj.is) {
            return null;
        }
        localStorage.removeItem(key);
        return obj;
    };
    obj.clear = function(){
        if (!obj.is) {
            return null;
        }
        localStorage.clear();
        return obj;
    };
    app.$Storage = obj;
    return obj;
}).call({}))

.constant('tools', (function(){
    var self = this;
    
    // Objects
    self.tags = (function(){
        var obj = this, tags = {loading:true}, el = angular.element(document.documentElement);
        obj.set = function(name, value){
            if(value && !tags[name]){
                tags[name] = true;
                el.addClass('app-' + name);
            }else if(!value && tags[name]){
                delete tags[name];
                el.removeClass('app-' + name);
            }
            return obj;
        };
        obj.get = function(name){
            return !!tags[name];
        };
        obj.del = function(mask){
            for (var name in tags) {
                name.indexOf(mask) >= 0 && obj.set(name, false);
            }
            return obj;
        };
        obj.all = function(prefix){
            var data = [''];
            for (var name in tags) {
                data.push(name)
            }
            return data.join(' ' + (prefix == undefined ? 'app-' : prefix)).substring(1);            
        };
        obj.list = function(){
            return tags;
        };
        return obj;
    }).call({});

    self.getUrlString = function(data, json){
        var serialize = function(obj, prefix){
            var result = [];
            for(var key in obj){
                var value = obj[key] == null ? '' : obj[key];
                var key = prefix ? prefix + '[' + key + ']' : key;
                value != 'undefined' && result.push(
                    angular.isObject(value) ? (json ? key + '=' + encodeURIComponent(angular.toJson(value)) : serialize(value, key)) : key + '=' + encodeURIComponent(value)
                );
            }
            return result.join('&');
        };
        return serialize(data);
    };

    self.getUrlParams = function(url){
        var result = {};
        var isArray = /^([a-zA-Z0-9]+)((\[\d+\])|(%5B\d+%5D))$/;
        url.replace(/#.*$/, '').replace(/([^=&\?\/\.]+)=([^&]*)/g, function(str, key, value){
            value = decodeURIComponent(decodeURI(value));
            if(isArray.test(key)){
                key = key.replace(isArray, '$1');
                result[key] = result[key] || [];
                result[key].push(value);
            }else{
                result[key] = value;
            }
        });
        return result;
    };
    
    self.getUrlParam = function(url, name){
        return ((new RegExp('[?&#]+' + name + '=([^&#]*)')).exec(url) || [])[1] || '';
    };
    
    self.strMap = function(str, map){
        if(angular.isObject(map)){
            for (var i in map) {
                str = str.replace(new RegExp('\{\{' + i + '\}\}', 'g'), map[i]);
            }
            str = str.replace(/\{\{[a-zA-Z0-9]+\}\}/, '');
        }else{
            str = str.replace(/\{\{[a-zA-Z0-9]+\}\}/g, map);
        }
        return str;
    };

    self.getAppdataParams = function(url){
        var result = {};
        url.replace(/([0-9a-zA-Z]+)\((.+?)(\)|$)/g, function(str, key, value){ //...?app_data=key1(value1)key2(value2)
            result[key] = decodeURIComponent(decodeURI(value));
        });
        return result;
    };

    self.getRange = function(start, end){
        start *= 1; end *= 1;
        var arr = [], dec = end < start;
        while (start != (dec ? end - 1 : end + 1)) {
            arr.push(dec ? start-- : start++);
        }
        return arr;
    };
    
    self.getRandom = function(min, max){
        return Math.floor(Math.random() * (max - min + 1) + min);
    };

    self.getRandomItem = function(list){
        return list[Math.floor(Math.random() * list.length)];
    };	
    
    self.getElementOffset = function(el, parent, isScroll){
        var result = {x:0, y:0};
        while(el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop) && (!parent || (parent && parent != el))){
            result.x += el.offsetLeft - (isScroll ? el.scrollLeft : 0);
            result.y += el.offsetTop - (isScroll ? el.scrollTop : 0);
            el = el.offsetParent;
        }
        return result;
    };
    
    self.getWindow = function(url, w, h){
        var p =['location'];
        var l = w ? (screen.width - w) / 2 >> 0 : 0;
        var t = h ? (screen.height - h) / 2 >> 0 : 0;
        w && p.push('width=' + w) && p.push('left=' + l);
        h && p.push('height=' + h) && p.push('top=' + t);
        var win = window.open(url, 'win' + new Date().getTime(), p.join(','));
        win.focus();
        return win;
    };
    
    // Set
    self.setViewportContent = function(str){
        var content = '';
        if(typeof(document.querySelector) != 'undefined'){
            var element = document.querySelector('meta[name="viewport"]');
            content = element.getAttribute('content');
            element.setAttribute('content', str);
        }
        return content;
    };   

    self.setViewport = function(width, initScale, minScale, maxScale, scalable){
        var content = '', attr = [], delim = ',';
        width && attr.push('width=' + width);
        initScale && attr.push('initial-scale=' + initScale);
        minScale && attr.push('minimum-scale=' + minScale);
        maxScale && attr.push('maximum-scale=' + maxScale);
        scalable && attr.push('user-scalable=' + scalable);
        return self.setViewportContentfunction(attr.join(delim));
    };
    
    self.setScroll = function(x, y){
        var underFB = angular.isDefined(window['FB']) && app.$Config && app.$Config.fb && (app.$Config.fb.isTab || app.$Config.fb.isCanvas);
        underFB ? FB.Canvas.scrollTo(x, y) : window.scrollTo(x, y);
    };

    self.scrollToEx = function(holderSelector, contentSelector, xOffset, yOffset){
        var h = document.querySelector(holderSelector);
        var c = document.querySelector(contentSelector);
        if (!h || !c) {
            return;
        }
        var offset = self.getElementOffset(c, h);
        h.scrollLeft = offset.x + (xOffset || 0);
        h.scrollTop = offset.y + + (yOffset || 0);
    };
    
    // Require
    self.require = function(condition, src1, src2){
        self.include(condition ? src1 : src2);
    };
    
    self.include = function(src){
        if(/.+.js$/.test(src)){
            document.writeln('<script src="' + src + '" type="text/javascript"></' + 'script>');
        }else if(/.+.css$/.test(src)){
            document.writeln('<link rel="stylesheet" type="text/css" href="' + src + '" />');
        }
    };
    
    app.$Tools = self;
    return self;
}).call({}))

// Events --------------------------
// popup                         type, scope
// popup:open                    scope
// popup:load                    scope
// popup:animate:load:start      scope
// popup:animate:load:end        scope
// popup:close                   scope
// popup:animate:close:start     scope
// popup:animate:close:end       scope
.factory('popup', ['$injector', function($injector){
    var
    $compile   = $injector.get('$compile'),
    $timeout   = $injector.get('$timeout'),
    $window    = $injector.get('$window'),
    $tools     = $injector.get('tools'),
    $rootScope = $injector.get('$rootScope');
    
    var broadcast = function(type, data){
        return $timeout(function(){
            $rootScope.$broadcast('popup', {type:type, data:data});
            $rootScope.$broadcast('popup:' + type, data);
        }, 10);
    };
    
    var self = {
        count: 0,
        stack: {},
        countBySkin: {},
        create: function(path, $scope, skin){
            path = path.replace(/\/+$/, '');
            return {
                show: function(file, data, options){
                    var
                    popup,
                    popupTmpl,
                    scrollTop,
                    options = angular.extend({
                        onShow:null,
                        onLoad:null,
                        onClose:null,
                        autoClose:true,
                        autoCloseOutside:true,
                        isClosable:true,
                        isFocusable:false,
                        cssClass:'',
                        closeDelay:0,
                        loadDelay:0,
                        useScope:null,
                        isolateScope:false
                    }, options || {}),
                    underFB = angular.isDefined(window['FB']) && $rootScope.config && $rootScope.config.fb && ($rootScope.config.fb.isTab || $rootScope.config.fb.isCanvas),                    
                    $popupScope = (options.useScope || $scope || $rootScope).$new(options.isolateScope);
                    
                    var callListeners = function(action){
                        angular.forEach($popupScope.popupListeners, function(method){
                            angular.isFunction(method) && method.call($popupScope, action);
                        })
                    };
                    var escape = function(e){
                        if (e.keyCode != 27) {
                            return;
                        }
                        var el = popup.nextSibling;
                        while (el) {
                            if (el.className && el.className.indexOf('popup' >= 0)) {
                                return;
                            } else {
                                el = el.nextSibling;
                            }
                        }
                        angular.element(popup).scope().popupClose();
                    };
                    var duration = function(el){
                        if(angular.isDefined(window.getComputedStyle)){
                            var s = window.getComputedStyle(el);
                            return parseFloat(
                                s['transition-duration'] ||
                                s['-webkit-transition-duration'] ||
                                s['-ms-transition-duration'] ||
                                s['-moz-transition-duration'] ||
                                s['-o-transition-duration']
                            ) || 0;
                        }
                        return 0;
                    };
                    
                    $popupScope.popupId = 'popup_' + String((new Date).getTime()) + Math.round(Math.random() * 1000000);
                    $popupScope.popupIsAutoClose = options.autoClose;
                    $popupScope.popupIsAutoCloseOutside = options.autoCloseOutside;
                    $popupScope.popupIsClosable = options.isClosable;
                    $popupScope.popupLoadDelay = options.loadDelay;
                    $popupScope.popupCloseDelay = options.closeDelay;
                    $popupScope.popupIsFocusable = options.isFocusable;
                    $popupScope.popupCssClass = options.cssClass;
                    $popupScope.popupSkinClass = (path).replace(/[\.\/\\]+/g, '-').replace(/^-+|-+$/g, '');
                    $popupScope.popupInstanceClass = (file).replace(/[\.\/\\]+/g, '-').replace(/^-+|-+$/g, '');
                    $popupScope.popupClass = $popupScope.popupSkinClass + ' ' + $popupScope.popupInstanceClass;
                    $popupScope.popupFile = file;
                    $popupScope.popupTpl = path + '/' + file;
                    $popupScope.popupData = data || '';
                    $popupScope.popupSkin = path + '/' + (skin || 'skin.html');
                    $popupScope.popupListeners = [];

                    $popupScope.popupIsAutoClose && $popupScope.popupIsClosable && angular.element(document).bind('keydown', escape);
                    
                    $popupScope.popupClose = function(noOnClose){
                        var close = function(){
                            popup && angular.element(popup).removeClass('popup-animate-close');
                            callListeners('animate:close:end');
                            broadcast('animate:close:end', $popupScope);

                            self.count--;
                            self.countBySkin[$popupScope.popupSkinClass]--;
                            
                            callListeners('close');
                            broadcast('close', $popupScope);
                            $tools.tags.set($popupScope.popupSkinClass, !!self.countBySkin[$popupScope.popupSkinClass]).set($popupScope.popupInstanceClass, false);
                            
                            delete self.stack[$popupScope.popupId];
                            angular.isDefined(scrollTop) && $tools.setScroll(0, scrollTop);
                            angular.element(document).unbind('keydown', escape);
                            $timeout(function(){
                                popup && popup.parentElement && popup.parentElement.removeChild(popup);
                                popupTmpl && popupTmpl.parentElement && popupTmpl.parentElement.removeChild(popupTmpl);
                                $popupScope.$destroy();
                            }, 100);
                        };
                        var tmp = function(){
                            if (!popup) {
                                return;
                            }
                            document.activeElement.blur();
                            popup && angular.element(popup).addClass('popup-on-close popup-animate-close');
                            callListeners('animate:close:start');
                            broadcast('animate:close:start', $popupScope);
                            $timeout(function(){
                                if(duration(popup)){
                                    angular.element(popup).one('webkitTransitionEnd msTransitionEnd mozTransitionEnd oTransitionEnd transitionend', close);
                                }else{
                                    $timeout(close, $popupScope.popupCloseDelay || 10);
                                }
                            }, 100);
                        };
                        if(!noOnClose && angular.isFunction(options.onClose)){
                            if (options.onClose.call($popupScope)) {
                                return;
                            }
                            tmp();
                        }else{
                            tmp();
                        }
                    };
                    $popupScope.popupAutoClose = function(e){
                        if(
                            $popupScope.popupIsAutoClose &&
                            $popupScope.popupIsClosable
                        ){
                            if($popupScope.popupIsAutoCloseOutside && e.target.id != $popupScope.popupId){
                                return;
                            }
                            var el = e.target;
                            while(el){
                                if(el.id == $popupScope.popupId){
                                    $popupScope.popupClose();
                                    e.stopPropagation();
                                    e.preventDefault();
                                    return false;        
                                }else{
                                    el = el.parentElement;
                                    if (el.nodeName == 'BODY') {
                                        return;
                                    }
                                }
                            }
                        }
                    };
                    $popupScope.popupOnload = function(){
                        $tools.tags.set($popupScope.popupSkinClass, true).set($popupScope.popupInstanceClass, true);
                        $timeout(function(){
                            popup = document.getElementById($popupScope.popupId);
                            if(popup){
                                angular.element(popup).addClass('popup-on-load popup-animate-load');
                                callListeners('animate:load:start');
                                broadcast('animate:load:start', $popupScope);
                                
                                var done = function(){
                                    popup && angular.element(popup).removeClass('popup-animate-load');
                                    callListeners('animate:load:end');
                                    broadcast('animate:load:end', $popupScope);
                                };
                                
                                if(duration(popup)){
                                    angular.element(popup).one('webkitTransitionEnd msTransitionEnd mozTransitionEnd oTransitionEnd transitionend', done);
                                }else{
                                    $timeout(done, $popupScope.popupLoadDelay || 10);
                                }
                                if(options.isFocusable){
                                    var el = angular.isDefined(popup.querySelector) ? popup.querySelector('[ng-include]') : popup;
                                    var pos = $tools.getElementOffset(el);
                                    if(underFB){
                                        FB.Canvas.getPageInfo(function(data){scrollTop = data.scrollTop;});
                                        FB.Canvas.scrollTo(0, pos.y - 20);
                                    }else{
                                        scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                                        $window.scrollTo(0, pos.y - 20);
                                    }
                                }
                            }
                        }, 100);
                        callListeners('load');
                        broadcast('load', $popupScope);
                        angular.isFunction(options.onLoad) && options.onLoad.call($popupScope);
                    };
                    var tmp = ['<div',
                    ' id="', $popupScope.popupId, '"',
                    ' class="popup popup-on-show ', [$popupScope.popupClass, $popupScope.popupCssClass].join(' '), '"',
                    ' ng-click="popupAutoClose($event)"',
                    ' ng-include="\'' + $popupScope.popupSkin + '\'"',
                    '></div>'].join('');
                    $compile(tmp)($popupScope, function(result){
                        popupTmpl = result[0];
                        if($popupScope.popupIsFocusable){
                            scrollTop = document.documentElement.scrollTop || document.body.scrollTop || 0;
                        }
                        document.activeElement.blur();
                        document.body.appendChild(popupTmpl);
                        angular.isFunction(options.onShow) && options.onShow.call($popupScope);
                        callListeners('open');
                        broadcast('open', $popupScope);
                    });
                    self.count++;
                    self.countBySkin[$popupScope.popupSkinClass] = self.countBySkin[$popupScope.popupSkinClass] || 0;
                    self.countBySkin[$popupScope.popupSkinClass]++;
                    self.stack[$popupScope.popupId] = $popupScope;
                    return $popupScope;
                },
                hide: function(id, noOnClose){
                    if(id){
                        var scope = self.stack[id];
                        scope && scope.popupClose(noOnClose);
                    }else{
                        angular.forEach(self.stack, function(scope){
                            scope.popupClose(noOnClose);
                        });
                    }
                },
                close: function(file, noOnClose){
                    var me = this;
                    angular.forEach(self.stack, function(scope){
                        if(scope.popupFile == file){
                            me.hide(scope.popupId, noOnClose);
                        }
                    });
                }                
            }
        },
        closeTop: function(){
            var list = document.querySelectorAll('body > .popup');
            if (!list.length) {
                return;
            }
            angular.element(document.querySelectorAll('body > .popup')[list.length - 1]).scope().popupClose();
        }
    };
    return self;
}])

.directive('appInit', ['$injector', function($injector){
    return{
        restrict: 'A',
        link: function($scope, $element, attributes){
            $injector.get('tools').tags.set('loading', false);
            $injector.get('$rootScope').$broadcast('app:init', {scope:$scope, element:$element});
        }
    };
}])

.run(['$injector', function($injector){
    var
    $config    = $injector.get('config'),
    $tools     = $injector.get('tools'),
    $agent     = $injector.get('agent'),
    $cookie    = $injector.get('cookie'),
    $timeout   = $injector.get('$timeout'),
    $rootScope = $injector.get('$rootScope'),
    $location  = $injector.get('$location'),
    $http      = $injector.get('$http');
    
    document.documentElement.setAttribute('app-init', 'true');

    $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    $http.defaults.transformRequest = [function(data){
        return angular.isObject(data) && String(data) !== '[object File]' ? $tools.getUrlString(data) : data;
    }];

    $rootScope.agent = $agent;
    $rootScope.cookie = $cookie;
    $rootScope.config = $config;
    $rootScope.tags = $tools.tags;

    $tools.tags
    .set('phase' + ($config.app.phase || 1), true)
    .set($agent.name, true)
    .set($agent.device, true)
    .set($agent.browser, true)
    .set($agent.browser + '-' + $agent.version, true)
    .set($agent.os, true)
    .set($agent.engine, true)
    .set('webkit-' + $agent.webkitInt, true)
    .set('standalone', $agent.isStandalone)
    .set('transition', $agent.transition);

    $rootScope.$on('$routeChangeStart', function(e, next, current){
        $tools.tags.set('process', true);
    });
    $rootScope.$on('$routeChangeSuccess', function(e, next, current){
        var r = $location.path().replace(/(^\/+)|(\/.+)/g, '');
        var p = $location.path().replace(/^\/+/, '').replace(/\/+/, '-');
        $tools.tags.del('route-');
        r && $tools.tags.set('route-' + r, true); 
        p && $tools.tags.set('route-' + p, true); 
        $rootScope.route = r;
        $tools.tags.set('process', false);
    });

    app.$Root = $rootScope;
    app.$Config = $config;
}]);

//------------------------------------------------------ end
}(angular);
!function(){
    var f = function(){/* ... */};
    if (!window.console) {
        window.console = {log: f, info: f, warn: f, debug: f, error: f};
    }
}();