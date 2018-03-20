var pilot = angular.module('pilot', ['color.picker','ui.tinymce', 'ngSanitize']);

pilot.controller("popup", ['$scope', '$sce', '$http', function($scope, $sce, $http)
{
	var ctrl = this;
	$scope.saving = false;
	$scope.tinymcesettings = {
		trusted : true,
		inline: true,
	    plugins: [
	         "advlist autolink link image lists charmap print preview hr anchor pagebreak autoresize",
	         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking colorpicker",
	         "table contextmenu directionality emoticons paste textcolor responsivefilemanager fullscreen code"
	   ],
	   toolbar1: "undo redo | bold italic underline | fontsizeselect forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect image code fullscreen",
	   skin: 'lightgray',
	   theme_advanced_resizing : true,
	   image_advtab: true ,
	   theme : 'modern',
	   language: 'hu_HU',
	   content_css: [
		    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css'
		  ]
	};

	$http.defaults.headers.post["Content-Type"] = 'application/x-www-form-urlencoded;charset=utf-8';
	var param = function(obj) {
	    var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

	    for(name in obj) {
	      value = obj[name];

	      if(value instanceof Array) {
	        for(i=0; i<value.length; ++i) {
	          subValue = value[i];
	          fullSubName = name + '[' + i + ']';
	          innerObj = {};
	          innerObj[fullSubName] = subValue;
	          query += param(innerObj) + '&';
	        }
	      }
	      else if(value instanceof Object) {
	        for(subName in value) {
	          subValue = value[subName];
	          fullSubName = name + '[' + subName + ']';
	          innerObj = {};
	          innerObj[fullSubName] = subValue;
	          query += param(innerObj) + '&';
	        }
	      }
	      else if(value !== undefined && value !== null)
	        query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
	    }

	    return query.length ? query.substr(0, query.length - 1) : query;
	  };
	$http.defaults.transformRequest = [function(data) {
	    return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
	}];

	$scope.init = function (view, container_id, screen_id)
	{
		if (view == 'screen')
		{
			$http.post('/ajax/post/',{
				type : 'getPopupScreenTemplateStrings',
				screen_id : screen_id
			}).success(function(d,s,h,c){
				var template = {
					'settings' 	: angular.fromJson(d.data.settings),
					'screen' 	: angular.fromJson(d.data.screen),
					'content' 	: angular.fromJson(d.data.content),
					'interacion': angular.fromJson(d.data.interacion),
					'links' 	: angular.fromJson(d.data.links),
				}
				ctrl.loadTemplate(template);
			});
		};
	}

	this.loadTemplate = function( savedTemplate )
	{
		// Settings
		$scope.settings = {};
		$scope.settings.width 	= 50;
		$scope.settings.type 	= '%';
		$scope.settings.width_types = ['px', '%'];
		$scope.settings.background_color = 'rgba(255, 121, 154, 0.79)';
		angular.extend($scope.settings, savedTemplate.settings);

		// Screen
		$scope.screen = {};
		$scope.screen.padding 			= 10;
		$scope.screen.background_color 	= 'rgba(212, 28, 79, 1)';
		$scope.screen.background_image 	= '';
		$scope.screen.background_pos 	= {
			'left top' : 'Balra fentre',
			'left center' : 'Balra középre',
			'left bottom' : 'Balra alulra',
			'right top' : 'Jobbra fentre',
			'right center' : 'Jobbra középre',
			'right bottom' : 'Jobbra alulra',
			'center top' : 'Középre fentre',
			'center center' : 'Középre',
			'center bottom' : 'Középre alulra'
		};
		$scope.screen.background_pos_sel= 'center center';
		$scope.screen.background_reps   = { 'no-repeat' : 'Nincs ismétlődés', 'repeat' : 'Ismétlődik', 'repeat-x' : 'Horizontális tengelyen ismétlődik', 'repeat-y' : 'Vertikális tengelyen ismétlődik'};
		$scope.screen.background_repeat = 'no-repeat';
		$scope.screen.background_sizes 	= { '' : 'Eredeti méret', 'contain' : 'Tartalomhoz igazít', 'cover' : 'Kitöltés', '100%' : '100% szélesség'};
		$scope.screen.background_size 	= '';
		$scope.screen.border_color 		= 'rgba(255, 255, 255, 0.2)';
		$scope.screen.border_size 		= 5;
		$scope.screen.border_type 		= "solid";
		$scope.screen.border_types 		= ['dotted','dashed','solid','double','groove','ridge','inset','outset'];
		$scope.screen.shadow_radius		= 50;
		$scope.screen.shadow_color		= '#000';
		$scope.screen.shadow			= { 'x' : 0, 'y' : 15 };
		$scope.screen.shadow_width		= -5;
		$scope.screen.cssstyles			= '';

		// Szöveg
		$scope.screen.text_color 		= "#fff";
		$scope.screen.text_size 		= 1;
		$scope.screen.text_align		= 'center';

		if (typeof savedTemplate.screen !== 'undefined')
		{
			savedTemplate.screen.background_pos = $scope.screen.background_pos;
			savedTemplate.screen.background_reps = $scope.screen.background_reps;
			savedTemplate.screen.background_sizes = $scope.screen.background_sizes;
			savedTemplate.screen.border_types = $scope.screen.border_types;
		}

		angular.extend($scope.screen, savedTemplate.screen);

		// Content
		$scope.content 					= {};
		$scope.content.title 			= {};
		$scope.content.title.text 		= 'Főcím';
		$scope.content.title.color 		= '';
		$scope.content.title.size 		= 2.4;
		$scope.content.title.align 		= '';

		$scope.content.subtitle 			= {};
		$scope.content.subtitle.text 		= 'Alcím';
		$scope.content.subtitle.color 		= '';
		$scope.content.subtitle.size 		= 1.4;
		$scope.content.subtitle.align 		= '';

		$scope.content.fill 			= {};
		$scope.content.fill.text 		= 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel metus id arcu fermentum rutrum. Aenean neque ante, dignissim non massa non, cursus malesuada nulla. Ut sodales volutpat leo vel lobortis. Nulla sagittis tempor dolor at laoreet. Donec at pharetra mauris. Cras at tortor at sapien condimentum facilisis. Vivamus quis erat non nisl dapibus fermentum in sit amet mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vivamus non dapibus ligula. Donec ac nunc interdum, ultricies ligula vitae, cursus lacus. Cras imperdiet ultrices turpis a pulvinar. Phasellus id tortor vitae ante ultrices elementum eget at elit. Duis cursus arcu et magna porttitor, eget maximus mauris dignissim.';
		$scope.content.fill.color 		= '';
		$scope.content.fill.size 		= 1;
		$scope.content.fill.align 		= '';

		$scope.textHTML 	= function(){
	 		return $sce.trustAsHtml($scope.content.fill.text);
		}

		angular.extend($scope.content, savedTemplate.content);

		// Interakció
		$scope.interacion 					= {};
		$scope.interacion.main 				= {};
		$scope.interacion.main.text 		= 'Tovább';
		$scope.interacion.main.text_color 	= 'rgba(255,255,255,1)';
		$scope.interacion.main.text_size 	= 1.8;
		$scope.interacion.main.text_custom 	= '';
		$scope.interacion.main.text_align 	= 'center';
		$scope.interacion.main.background  	= 'rgba(0,0,0,1)';
		$scope.interacion.main.width 		= 60;
		$scope.interacion.main.width_type   = '%';
		$scope.interacion.main.width_types  = ['%', 'px'];
		$scope.interacion.main.padding  	= 10;
		$scope.interacion.main.margin  		= 10;
		$scope.interacion.main.border_color = '#fff';
		$scope.interacion.main.border_width = 2;
		$scope.interacion.main.border_style = 'solid';
		$scope.interacion.main.border_radius = 10;

		// Kilépő
		$scope.interacion.exit 				= {};
		$scope.interacion.exit.text 		= 'Nem érdekel';
		$scope.interacion.exit.text_color 	= 'rgba(255,255,255,0.8)';
		$scope.interacion.exit.text_style 	= 'italic';
		$scope.interacion.exit.text_styles 	= { 'bold' : 'Félkövér', 'italic' : 'Dölt', 'normal' : 'Normál' };
		$scope.interacion.exit.text_size 	= 0.8;
		$scope.interacion.exit.text_custom 	= '';

		angular.extend($scope.interacion, savedTemplate.interacion);

		// Linkek
		$scope.links 			= {};
		$scope.links.to_url 	= '#';
		$scope.links.exit_url 	= '';
		$scope.links.open_type 	= '_blank';
		$scope.links.open_types = {'_blank': 'Új ablakban', '_self':'Helyben'};

		angular.extend($scope.links, savedTemplate.links);
	}

	$scope.save = function( container_id, screen_id )
	{
		$scope.saving = true;
		$http.post( "/ajax/post/",
		{
	 		type: "savePopupScreenDatasets",
            container_id: container_id,
            screen_id: screen_id,
            settings: angular.toJson($scope.settings),
			screen: angular.toJson($scope.screen),
			content: angular.toJson($scope.content),
			interacion: angular.toJson($scope.interacion),
			links: angular.toJson($scope.links)
	 	}
	 	)
		.success(function(data, status, headers, config) {
			$scope.saving = false;
	    });
	};

}]);
