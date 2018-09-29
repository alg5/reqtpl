var reqtpl = new function () {
	this.initialize = function () {
		//идиотский сабсильвер... Вот нет бы через нормальный селектор добавить кнопку, как в кошерном просильвере, ан-нет, приходится всякой **йнёй заниматься. Спасибо вам, разработчики этого сраного сабсильвера.
		var elem = document.getElementById('abbc3_font');
		if(elem != null) {
			elem = elem.parentNode;
		} else {
			elem = document.getElementById('bbcodes_cell1');
		}
		$('<input type="button" id="reqtpl_btn" class="btnbbcode" onclick="reqtpl.run();return false;" />').appendTo(elem);
	};
	this.run = function () {
		$('#reqtpl_main dl .important').attr('title', lang[1]);
		$('#reqtpl_main #reqtpl_btn_cancel').unbind('click').click(function () { $.unblockUI({ onUnblock: reqtpl.unblock }); });
		$('#reqtpl_main #reqtpl_btn_submit').unbind('click').click(function () {
			if (reqtpl.validate()) {
				reqtpl.append();
			}
		});
		$.blockUI({
			message: $('#reqtpl_main'),
			css: {
				top: '100px',
				left: ($(window).width() - 600) / 2 + 'px',
				width: '600px',
				cursor: 'default',
				background: 'none',
				border: 'none',
				display: 'block'
			},
			overlayCSS: {
				cursor: 'default'
			},
			onBlock: function () {
				reqtpl.resize();
				$(window).unbind('resize').resize(function () {
					reqtpl.resize();
				});
			}
		});
		$('.blockOverlay').attr('title', lang[2]).click(function () {
			$.unblockUI({ onUnblock: reqtpl.unblock });
		});
	
    
    
    };
	this.unblock = function () {
		return true;
	};
	this.resize = function () {
		var rt = document.getElementById('reqtpl_main');
		if (rt.style.display != 'none') {
			if ($('.reqtpl_body #reqtpl_table', rt).height() > $(window).height() - 250) {
				var h = ($(window).height() - 250);
				if (h < 100) h = 100;
				$('.reqtpl_body', rt).css({ height: h + 'px' });
			} else {
				$('.reqtpl_body', rt).css({ height: '' });
			}
		}
	};
	this.validate = function () {
		for (field_id in fields) {
			if (!fields.hasOwnProperty(field_id)) continue;
			var field = fields[field_id];
			var control = document.getElementById(field);
			var _val = $(control).val();
			if (important[field]) {
				if ((control.tagName == 'INPUT' && control.type == 'checkbox' && !control.checked) || (_val.length == 0)) {
					//alert('Important field is empty: ' + this);
					$('.error', control.parentNode).html('<br />' + lang[1]);
					$(control).click(function () {
						$('.error', this.parentNode).html('');
					});
					return false;
				}
			}
			if (typeof (regexps[field]) != 'undefined' && regexps[field].length > 0) {
				var r = new RegExp(regexps[field], 'ig');
				if (!r.test(_val)) {
					//alert('Value is invalid: ' + this);
					$('.error', control.parentNode).html('<br />' + lang[3]);
					$(control).click(function () {
						$('.error', this.parentNode).html('');
					});
					return false;
				}
			}
		};

		return true;
	};
	this.append = function () {
		var _output = reqtpl_title;
		$(fields).each(function () {
			var control = document.getElementById(this);
			var _val;
			var _pattern = patterns[this];

			if (control.tagName == 'SELECT') {
				_val = $('option:selected', control).text();
			} else {
				_val = $(control).val();
			}

			if (control.tagName == 'INPUT' && control.type == 'checkbox') {
				_output += ((_output) ? "\r\n" : '') + _pattern.replace('%s', (control.checked) ? lang[4] : lang[5]);
			} else {
				if (control.id.startsWith('reqtpl_image')) {
					$('#'+control.id+'_container .img_field').each(function() {
						if ($('input', this).val())
						{
							_output += ((_output) ? "\r\n" : '') + _pattern.replace('%s', $('input', this).val());
							if ($('textarea', this).val())
							{
								_output += ((_output) ? "\r\n" : '') + '[i]' + $('textarea', this).val() + '[/i]';
							}
						}
					});
				} else {
					_output += ((_output) ? "\r\n" : '') + _pattern.replace('%s', _val);
				}
			}
		});
		insert_text(_output + "\r\n");
		$.unblockUI({ onUnblock: reqtpl.unblock });
		return true;
	};
};
String.prototype.startsWith = function(str) {return (this.match("^"+str)==str)};

//jQuery blockUI
//if(typeof($.blockUI)=='undefined'){eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}(';(I($){5(/1\\.(0|1|2)\\.(0|1|2)/.1r($.Y.1g)||/^1.1/.1r($.Y.1g)){2U(\'H 3q 1H 3c.2.3 3F 33!  3u 3B 3J v\'+$.Y.1g);R}$.Y.1X=$.Y.1n;7 B=I(){};7 C=L.3i||0;7 D=$.12.1o&&(($.12.2j<8&&!C)||C<8);7 E=$.12.1o&&/2Y 6.0/.1r(2w.3f)&&!C;$.H=I(a){23(17,a)};$.2o=I(a){1L(17,a)};$.2z=I(a,b,c,d){7 e=$(\'<J Q="2z"></J>\');5(a)e.1R(\'<1V>\'+a+\'</1V>\');5(b)e.1R(\'<2m>\'+b+\'</2m>\');5(c==1W)c=38;$.H({1v:e,1n:3m,Z:2s,1z:1j,19:c,1d:1j,1J:d,M:$.H.18.2i})};$.Y.2W=I(a){R O.1Z({Z:0}).1t(I(){5($.M(O,\'P\')==\'3d\')O.N.P=\'3k\';5($.12.1o)O.N.3z=1;23(O,a)})};$.Y.1Z=I(a){R O.1t(I(){1L(O,a)})};$.H.2j=2.35;$.H.18={1v:\'<1V>3s 25...</1V>\',2f:1i,1P:T,1l:1j,M:{1U:0,2d:0,1e:\'30%\',V:\'40%\',1f:\'35%\',36:\'2b\',2u:\'#22\',1b:\'31 3N #3w\',2c:\'#2l\',2g:\'25\'},2a:{1e:\'30%\',V:\'40%\',1f:\'35%\'},1x:{2c:\'#22\',2e:0.6,2g:\'25\'},2i:{1e:\'3h\',V:\'1B\',1f:\'\',3H:\'1B\',1b:\'W\',1U:\'3Q\',2e:0.6,2g:\'3a\',2u:\'#2l\',2c:\'#22\',\'-3Y-1b-28\':\'1B\',\'-3O-1b-28\':\'1B\',\'1b-28\':\'1B\'},2q:/^3D/i.1r(17.3U.3o||\'\')?\'3L:1j\':\'3S:3W\',1T:1j,2x:2s,2D:T,1z:T,2F:T,2h:T,2O:T,1n:2V,Z:3j,19:0,1d:T,2G:T,2T:T,1s:1i,1J:1i,2t:4,1h:\'3r\'};7 F=1i;7 G=[];I 23(c,d){7 e=(c==17);7 f=d&&d.1v!==1W?d.1v:1W;d=$.1F({},$.H.18,d||{});d.1x=$.1F({},$.H.18.1x,d.1x||{});7 g=$.1F({},$.H.18.M,d.M||{});7 h=$.1F({},$.H.18.2a,d.2a||{});f=f===1W?d.1v:f;5(e&&F)1L(17,{Z:0});5(f&&2K f!=\'34\'&&(f.1a||f.1g)){7 j=f.1g?f[0]:f;7 k={};$(c).16(\'H.1Y\',k);k.15=j;k.1A=j.1a;k.S=j.N.S;k.P=j.N.P;5(k.1A)k.1A.2A(j)}7 z=d.2x;7 m=($.12.1o||d.1T)?$(\'<2S Q="H" N="z-1k:\'+(z++)+\';S:W;1b:W;2d:0;1U:0;P:1w;1e:1D%;1G:1D%;V:0;1f:0" 2Z="\'+d.2q+\'"></2S>\'):$(\'<J Q="H" N="S:W"></J>\');7 n=$(\'<J Q="H 3g" N="z-1k:\'+(z++)+\';S:W;1b:W;2d:0;1U:0;1e:1D%;1G:1D%;V:0;1f:0"></J>\');7 p,s;5(d.1l&&e){s=\'<J Q="H \'+d.1h+\' 2k K-1c K-13 K-1I-1M" N="z-1k:\'+z+\';S:W;P:21">\'+\'<J Q="K-13-2p K-1c-24 K-1I-1M 2M">\'+(d.2f||\'&2Q;\')+\'</J>\'+\'<J Q="K-13-1p K-1c-1p"></J>\'+\'</J>\'}U 5(d.1l){s=\'<J Q="H \'+d.1h+\' 2C K-1c K-13 K-1I-1M" N="z-1k:\'+z+\';S:W;P:1w">\'+\'<J Q="K-13-2p K-1c-24 K-1I-1M 2M">\'+(d.2f||\'&2Q;\')+\'</J>\'+\'<J Q="K-13-1p K-1c-1p"></J>\'+\'</J>\'}U 5(e){s=\'<J Q="H \'+d.1h+\' 2k" N="z-1k:\'+z+\';S:W;P:21"></J>\'}U{s=\'<J Q="H \'+d.1h+\' 2C" N="z-1k:\'+z+\';S:W;P:1w"></J>\'}p=$(s);5(f){5(d.1l){p.M(h);p.39(\'K-13-1p\')}U p.M(g)}5(!d.2T||!($.12.3n&&/3G/.1r(2w.3y)))n.M(d.1x);n.M(\'P\',e?\'21\':\'1w\');5($.12.1o||d.1T)m.M(\'2e\',0.0);7 q=[m,n,p],$2I=e?$(\'X\'):$(c);$.1t(q,I(){O.3v($2I)});5(d.1l&&d.1P&&$.Y.1P){p.1P({3C:\'.K-1c-24\',2X:\'3I\'})}7 r=D&&(!$.1u||$(\'3t,3P\',e?1i:c).1E>0);5(E||r){5(e&&d.2F&&$.1u)$(\'3e,X\').M(\'1G\',\'1D%\');5((E||!$.1u)&&!e){7 t=1C(c,\'2v\'),l=1C(c,\'2H\');7 u=t?\'(0 - \'+t+\')\':0;7 v=l?\'(0 - \'+l+\')\':0}$.1t([m,n,p],I(i,o){7 s=o[0].N;s.P=\'1w\';5(i<2){e?s.11(\'1G\',\'37.3A(L.X.3T, L.X.1y) - (1H.1u?0:\'+d.2t+\') + "14"\'):s.11(\'1G\',\'O.1a.1y + "14"\');e?s.11(\'1e\',\'1H.1u && L.1m.2n || L.X.2n + "14"\'):s.11(\'1e\',\'O.1a.26 + "14"\');5(v)s.11(\'1f\',v);5(u)s.11(\'V\',u)}U 5(d.1z){5(e)s.11(\'V\',\'(L.1m.2B || L.X.2B) / 2 - (O.1y / 2) + (3l = L.1m.1q ? L.1m.1q : L.X.1q) + "14"\');s.3M=0}U 5(!d.1z&&e){7 a=(d.M&&d.M.V)?2L(d.M.V):0;7 b=\'((L.1m.1q ? L.1m.1q : L.X.1q) + \'+a+\') + "14"\';s.11(\'V\',b)}})}5(f){5(d.1l)p.3X(\'.K-13-1p\').1R(f);U p.1R(f);5(f.1g||f.3Z)$(f).1K()}5(($.12.1o||d.1T)&&d.1d)m.1K();5(d.1n){7 w=d.1s?d.1s:B;7 x=(d.1d&&!f)?w:B;7 y=f?w:B;5(d.1d)n.1X(d.1n,x);5(f)p.1X(d.1n,y)}U{5(d.1d)n.1K();5(f)p.1K();5(d.1s)d.1s()}1Q(1,c,d);5(e){F=p[0];G=$(\':32:3x:3R\',F);5(d.2G)1O(1S,20)}U 2b(p[0],d.2D,d.1z);5(d.19){7 A=1O(I(){e?$.2o(d):$(c).1Z(d)},d.19);$(c).16(\'H.19\',A)}};I 1L(a,b){7 c=(a==17);7 d=$(a);7 e=d.16(\'H.1Y\');7 f=d.16(\'H.19\');5(f){3K(f);d.2y(\'H.19\')}b=$.1F({},$.H.18,b||{});1Q(0,a,b);7 g;5(c)g=$(\'X\').2J().2P(\'.H\').3b(\'X > .H\');U g=$(\'.H\',a);5(c)F=G=1i;5(b.Z){g.Z(b.Z);1O(I(){27(g,e,b,a)},b.Z)}U 27(g,e,b,a)};I 27(a,b,c,d){a.1t(I(i,o){5(O.1a)O.1a.2A(O)});5(b&&b.15){b.15.N.S=b.S;b.15.N.P=b.P;5(b.1A)b.1A.3E(b.15);$(d).2y(\'H.1Y\')}5(2K c.1J==\'I\')c.1J(d,c)};I 1Q(b,a,c){R;7 d=a==17,$15=$(a);5(!b&&(d&&!F||!d&&!$15.16(\'H.2r\')))R;5(!d)$15.16(\'H.2r\',b);5(!c.2h||(b&&!c.1d))R;7 e=\'3V 41 42 43\';b?$(L).1Q(e,c,29):$(L).3p(e,29)};I 29(e){5(e.2E&&e.2E==9){5(F&&e.16.2O){7 a=G;7 b=!e.2N&&e.1N===a[a.1E-1];7 c=e.2N&&e.1N===a[0];5(b||c){1O(I(){1S(c)},10);R 1j}}}7 d=e.16;5($(e.1N).2R(\'J.\'+d.1h).1E>0)R T;R $(e.1N).2R().2J().2P(\'J.H\').1E==0};I 1S(a){5(!G)R;7 e=G[a===T?G.1E-1:0];5(e)e.1S()};I 2b(a,x,y){7 p=a.1a,s=a.N;7 l=((p.26-a.26)/2)-1C(p,\'2H\');7 t=((p.1y-a.1y)/2)-1C(p,\'2v\');5(x)s.1f=l>0?(l+\'14\'):\'0\';5(y)s.V=t>0?(t+\'14\'):\'0\'};I 1C(a,p){R 2L($.M(a,p))||0}})(1H);',62,252,'|||||if||var||||||||||||||||||||||||||||||||||||blockUI|function|div|ui|document|css|style|this|position|class|return|display|true|else|top|none|body|fn|fadeOut||setExpression|browser|widget|px|el|data|window|defaults|timeout|parentNode|border|dialog|showOverlay|width|left|jquery|blockMsgClass|null|false|index|theme|documentElement|fadeIn|msie|content|scrollTop|test|onBlock|each|boxModel|message|absolute|overlayCSS|offsetHeight|centerY|parent|10px|sz|100|length|extend|height|jQuery|corner|onUnblock|show|remove|all|target|setTimeout|draggable|bind|append|focus|forceIframe|padding|h1|undefined|_fadeIn|history|unblock||fixed|000|install|titlebar|wait|offsetWidth|reset|radius|handler|themedCSS|center|backgroundColor|margin|opacity|title|cursor|bindEvents|growlCSS|version|blockPage|fff|h2|clientWidth|unblockUI|header|iframeSrc|isBlocked|1000|quirksmodeOffsetHack|color|borderTopWidth|navigator|baseZ|removeData|growlUI|removeChild|clientHeight|blockElement|centerX|keyCode|allowBodyStretch|focusInput|borderLeftWidth|par|children|typeof|parseInt|blockTitle|shiftKey|constrainTabKey|filter|nbsp|parents|iframe|applyPlatformOpacityRules|alert|200|block|cancel|MSIE|src||3px|input|later|string||textAlign|Math|3000|addClass|default|add|v1|static|html|userAgent|blockOverlay|350px|documentMode|400|relative|blah|700|mozilla|href|unbind|requires|blockMsg|Please|object|You|appendTo|aaa|enabled|platform|zoom|max|are|handle|https|appendChild|or|Linux|right|li|using|clearTimeout|javascript|marginTop|solid|moz|embed|5px|visible|about|scrollHeight|location|mousedown|blank|find|webkit|nodeType||mouseup|keydown|keypress'.split('|'),0,{}));}
