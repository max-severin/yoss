function getProductBlock(product) {
	var									
		wrapperBlock, wrapperLeft, wrapperRight, productImg, productName, productBrands, productCategory, productPrice, productLink;

	wrapperBlock    = $('<div/>').addClass("yoss-result-wrapper");
	wrapperLeft     = $('<div/>').addClass("yoss-result-left");
	wrapperRight    = $('<div/>').addClass("yoss-result-right");

	productImg      = $('<a/>').attr("href", product.url).addClass("product-image").html(product.image);
	productName     = $('<a/>').attr("href", product.url).addClass("product-name").html(product.name);
	productBrands   = $('<div/>').addClass("product-brand");
	productCategory = $('<div/>').addClass("product-category").html(product.category);

	productPrice    = $('<div/>').addClass("product-price").html(product.price);
	productLink     = $("<a/>").addClass("product-link").attr("href", product.url).html("{_wp('to product')} &rarr;");

	if (product.brands.length > 0) {
		for(var b in product.brands) {
			productBrands = productBrands.append(product.brands[b].brand);
		}
	}

	wrapperLeft.append(productImg, productName, productBrands, productCategory);
	wrapperRight.append(productPrice, productLink);
	wrapperBlock.append(wrapperLeft, wrapperRight);

	return wrapperBlock
}

function onResultScroll(event) {
	var resultBlock = $(this);

	if(resultBlock.scrollTop() + resultBlock.innerHeight() >= this.scrollHeight) {

		var query = $("{$yoss_settings.id_in_html}").val();
        var nextPage = resultBlock.find("#next_page").val();
        var loadingBlock = $('<div/>').addClass("yoss-result-wrapper loading");
        var lastEl = resultBlock.find('.yoss-result-wrapper:last-child');

        if (query.length > 0 && nextPage > 0 ) {
        	lastEl.after(loadingBlock);

            $.ajax({
				type: "POST",
				url: "{$search_url}",
				data: "query="+query+"&pg="+nextPage,
				success: function(response){

					var result = $.parseJSON(response);

					$(".yoss-result-wrapper.loading").remove();

					if (result.status === 'ok' && result.data.products.length > 0) {
						

						if (result.data.next_page !== false) {
							resultBlock.find('#next_page').val(result.data.next_page);
						} else {
							resultBlock.find('#next_page').val('0');
						}

						for(var key in result.data.products) {
							var getProductBlock = addProduct(result.data.products[key]);

							lastEl.after(productBlock);
						}	

					}
				}
			}, 'json');
		}

    }
}

function onSearchKeyup(event) {
	var t = $(this);

	if ( t.val().length >= {$yoss_settings.min_char_count} ) {

		var resultBlock = $('<div/>').addClass("yoss-result loading");

		if ($(".yoss-result").length > 0) {
			$(".yoss-result").remove();
		} 

		t.addClass('active');
		t.after(resultBlock);

		$.ajax({
			type: "POST",
			url: "{$search_url}",
			data: "query="+t.val()+"&pg=1",
			success: function(response){

				var result = $.parseJSON(response);

				resultBlock.removeClass("loading");

				if (result.status === 'ok') {

					if (result.data.products.length > 0) {

						if (result.data.next_page !== false) {
							var nextPage = $('<input/>').attr('type', 'hidden').attr('id', 'next_page').val(result.data.next_page);
						} else {
							var nextPage = $('<input/>').attr('type', 'hidden').attr('id', 'next_page').val('0');
						}
						resultBlock.append(nextPage);

						var wrapperBlock = $('<div/>').addClass("yoss-result-wrapper");
						var productCountSpan = $('<span/>').addClass("yoss-result-product-count").html("{_wp('Products found')}: " + result.data.product_count);
						var searchAllUrlLink = $("<a/>").addClass("yoss-result-show-all").attr("href", result.data.searh_all_url).html("{_wp('to results')}");

						wrapperBlock.append(productCountSpan, searchAllUrlLink);
						resultBlock.append(wrapperBlock);

						for(var key in result.data.products) {
							var productBlock = getProductBlock(result.data.products[key]);

							resultBlock.append(productBlock);
						}


					} else {

						resultBlock.addClass("no-products").html("{_wp('Sorry, but nothing was found, try to change your query')}");

					}
				} else {

					// error occured

				}
			}
		}, 'json');
		
		{if $yoss_settings.lazy_loading === 'on'}
		$(".yoss-result").scroll(onResultScroll);
		{/if}

	} else {

		t.removeClass('active');
        $(".yoss-result").remove();
		return false;

	}
}

$(document).ready(function() {

	$("{$yoss_settings.id_in_html}").keyup(onSearchKeyup);

});