$(document).ready(function() {

	$("{$yoss_settings.idHtml}").keyup(function() {

		var t = $(this);

		if ( t.val().length >= {$yoss_settings.minCharCount} ) {

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

					if (result.status === 'ok') {

						resultBlock.removeClass("loading");

						if (result.data.products.length > 0) {

							if (result.data.next_page !== false) {
								var nextPage = $('<input/>').attr('type', 'hidden').attr('id', 'next_page').val(result.data.next_page);
							} else {
								var nextPage = $('<input/>').attr('type', 'hidden').attr('id', 'next_page').val('0');
							}
							resultBlock.append(nextPage);

							var wrapperBlock = $('<div/>').addClass("yoss-result-wrapper");
							var productCountSpan = $('<span/>').addClass("yoss-result-product-count").html("Найдено товаров: " + result.data.product_count);
							var searchAllUrlLink = $("<a/>").addClass("yoss-result-show-all").attr("href", result.data.searh_all_url).html('перейти к результатам');

							wrapperBlock.append(productCountSpan, searchAllUrlLink);
							resultBlock.append(wrapperBlock);

							for(var key in result.data.products) {
								var wrapperBlock = $('<div/>').addClass("yoss-result-wrapper");
								var wrapperLeft = $('<div/>').addClass("yoss-result-left");
								var wrapperRight = $('<div/>').addClass("yoss-result-right");

								var productImg = $('<a/>').attr("href", result.data.products[key].url).addClass("product-image").html(result.data.products[key].image);
								var productName = $('<a/>').attr("href", result.data.products[key].url).addClass("product-name").html(result.data.products[key].name);

								var productBrands = $('<div/>').addClass("product-brand");
								if (result.data.products[key].brands.length > 0) {
									for(var b in result.data.products[key].brands) {
										productBrands = productBrands.append(result.data.products[key].brands[b].brand);
									}
								}
								var productCategory = $('<div/>').addClass("product-category").html(result.data.products[key].category);
								var productPrice = $('<div/>').addClass("product-price").html(result.data.products[key].price);
								var productLink = $("<a/>").addClass("product-link").attr("href", result.data.products[key].url).html('к товару &rarr;');

								wrapperLeft.append(productImg, productName, productBrands, productCategory);
								wrapperRight.append(productPrice, productLink);
								wrapperBlock.append(wrapperLeft, wrapperRight);
								resultBlock.append(wrapperBlock);
							}


						} else {

							resultBlock.addClass("no-products").html("Извините, но ничего не было найдено, попробуйте изменить свой запрос");

						}
					}
				}
			}, 'json');

			$(".yoss-result").scroll(function() {

				var resBlock = $(this);

				if(resBlock.scrollTop() + resBlock.innerHeight() >= this.scrollHeight) {

					var query = $("{$yoss_settings.idHtml}").val();
		            var nextPage = resBlock.find("#next_page").val();
		            var loadingBlock = $('<div/>').addClass("yoss-result-wrapper loading");
		            var lastEl = resBlock.find('.yoss-result-wrapper:last-child');

		            if (query.length > 0 && nextPage > 0 ) {
		            	lastEl.after(loadingBlock);

			            $.ajax({
							type: "POST",
							url: "{$search_url}",
							data: "query="+query+"&pg="+nextPage,
							success: function(response){

								var result = $.parseJSON(response);

								if (result.status === 'ok') {

									$(".yoss-result-wrapper.loading").remove();

									if (result.data.products.length > 0) {
										if (result.data.next_page !== false) {
											resBlock.find('#next_page').val(result.data.next_page);
										} else {
											resBlock.find('#next_page').val('0');
										}

										for(var key in result.data.products) {
											var wrapperBlock = $('<div/>').addClass("yoss-result-wrapper");
											var wrapperLeft = $('<div/>').addClass("yoss-result-left");
											var wrapperRight = $('<div/>').addClass("yoss-result-right");

											var productImg = $('<a/>').attr("href", result.data.products[key].url).addClass("product-image").html(result.data.products[key].image);
											var productName = $('<a/>').attr("href", result.data.products[key].url).addClass("product-name").html(result.data.products[key].name);

											var productBrands = $('<div/>').addClass("product-brand");
											if (result.data.products[key].brands.length > 0) {
												for(var b in result.data.products[key].brands) {
													productBrands = productBrands.append(result.data.products[key].brands[b].brand);
												}
											}
											var productCategory = $('<div/>').addClass("product-category").html(result.data.products[key].category);
											var productPrice = $('<div/>').addClass("product-price").html(result.data.products[key].price);
											var productLink = $("<a/>").addClass("product-link").attr("href", result.data.products[key].url).html('к товару &rarr;');

											wrapperLeft.append(productImg, productName, productBrands, productCategory);
											wrapperRight.append(productPrice, productLink);
											wrapperBlock.append(wrapperLeft, wrapperRight);
											lastEl.after(wrapperBlock);
										}							

									}

								}
							}
						}, 'json');
					}

		        }

			});

		} else {

			t.removeClass('active');
            $(".yoss-result").remove();
			return false;

		}

	});

});