$.widget( "custom.catcomplete", $.ui.autocomplete, {
	_create: function() {
	  this._super();
	  this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
	},
	_renderMenu: function( ul, items ) {
	  var that = this,
		currentCategory = "";
	  $.each( items, function( index, item ) {
		var li;
		if ( item.category != currentCategory ) {
		  ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
		  currentCategory = item.category;
		}
		li = that._renderItemData( ul, item );
		if ( item.category ) {
		  li.attr( "aria-label", item.category + " : " + item.label );
		}
	  });
	}
});
$(document).ready(function(){
	var data = [
	  { label: "anders", category: "" },
	  { label: "andreas", category: "" },
	  { label: "antal", category: "" },
	  { label: "annhhx10", category: "Products" },
	  { label: "annk K12", category: "Products" },
	  { label: "annttop C13", category: "Products" },
	  { label: "anders andersson", category: "People" },
	  { label: "andreas andersson", category: "People" },
	  { label: "andreas johnson", category: "People" }
	];
 
	$( "#search" ).catcomplete({
	  delay: 0,
	  source: data
	});		
	$('.categories > div').on('click', function(e){
		e.preventDefault();
	});
	var $isotope = $('.products').isotope({
		itemSelector: '.product',
		percentPosition: false,
		layoutMode: 'fitRows',
		columnWidth: '20%'
		// masonry: {
			// // use element for option
			// columnWidth: '.grid-sizer'
		// }			
	});
	$('.categories .category').on('click', function(){
		var $this = $(this),
			category = $this.data('category');
		$this.siblings().removeClass('selected');
		$this.addClass('selected');
		$isotope.isotope({ 
			filter: '[data-category="' + category + '"]' 
		});
	});
    $(window).hashchange(function(){
		var hash = location.hash;
		try {
			if(hash.substring(0, 7) == "#status") {
				console.log(hash.substring(8, hash.length));
				if(hash.substring(8, hash.length) == "0") var filter = '*';
				else var filter = '.status-' + parseInt(hash.substring(8, hash.length));
				$('.categories .category').removeClass('selected');
			}
			if(filter) {
				$isotope.isotope({
					filter: filter
				});
			}			
		} catch(e) {
			
		}
    });
    $(window).scroll(function(){
		var top = $(document).scrollTop(),
			elem = $('.top_nav');
			
		if (top < 200) $(".floating").css({top: '0', position: 'relative'});
		else $(".floating").css({top: '10px', position: 'fixed'});
	});
	$(window).scroll();
	$(window).hashchange();
});
  