$(document).ready(function() {

	$("{$yoss_settings.idHtml}").keyup(function() {

		var t = $(this);

		if ( t.val().length >= {$yoss_settings.minCharCount} ) {

			var resultBlock = $('<div/>').addClass("yoss-result loading");

			if ($(".yoss-result").length > 0) {
				$(".yoss-result").remove();
			} 

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
							if (result.data.brands.length > 0) {
								var wrapperBlock = $('<div/>').addClass("yoss-result-wrapper");
								var leftBlock = $('<div/>').addClass("yoss-result-left");
								var rightBlock = $('<div/>').addClass("yoss-result-right");
								var labelSpan = $('<span/>').html("Бренды");
								leftBlock.html(labelSpan);
								for(var key in result.data.brands) {
									var link = $("<a/>").addClass("brand").attr("href", result.data.brands[key].url).html(result.data.brands[key].image);
									rightBlock.append(link);
								}								
								wrapperBlock.append(leftBlock).append(rightBlock);
								resultBlock.append(wrapperBlock);
							}
							if (result.data.categories.length > 0) {
								var wrapperBlock = $('<div/>').addClass("yoss-result-wrapper");
								var leftBlock = $('<div/>').addClass("yoss-result-left");
								var rightBlock = $('<div/>').addClass("yoss-result-right");
								var labelSpan = $('<span/>').html("Категории");
								leftBlock.html(labelSpan);
								for(var key in result.data.categories) {
									var link = $("<a/>").addClass("category").attr("href", result.data.categories[key].url).html(result.data.categories[key].name);
									rightBlock.append(link);
								}								
								wrapperBlock.append(leftBlock).append(rightBlock);
								resultBlock.append(wrapperBlock);
							}
							var wrapperBlock = $('<div/>').addClass("yoss-result-wrapper");
							var leftBlock = $('<div/>').addClass("yoss-result-left");
							var labelSpan = $('<span/>').html("Товары");
							var brEl = $('<br/>');
							var productsCountSpan = $("<span/>").addClass("yoss-result-count").html('('+result.data.product_count+' шт)');
							var searchAllUrlLink = $("<a/>").addClass("yoss-result-show-all").attr("href", result.data.searh_all_url).html('показать все');
							leftBlock.append(labelSpan, brEl.clone(), productsCountSpan, brEl.clone(), searchAllUrlLink);
							wrapperBlock.append(leftBlock);
							for(var key in result.data.products) {
								var rightBlock = $('<div/>').addClass("yoss-result-right");
								var productImg = $('<div/>').addClass("product-image").html(result.data.products[key].image);
								var productName = $('<div/>').addClass("product-name").html(result.data.products[key].name);
								var productBrand = $('<div/>').addClass("product-brand").html(result.data.products[key].brand);
								var link = $("<a/>").addClass("product").attr("href", result.data.products[key].url).append(productImg, productName, productBrand);
								rightBlock.append(link);
								wrapperBlock.append(rightBlock);
							}								
							resultBlock.append(wrapperBlock);

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
		            var loadingBlock = $('<div/>').addClass("yoss-result-right loading");
		            var lastEl = resBlock.find('.yoss-result-wrapper:last-child .yoss-result-right:last-child');

		            if (query.length > 0 && nextPage > 0 ) {
		            	lastEl.after(loadingBlock);

			            $.ajax({
							type: "POST",
							url: "{$search_url}",
							data: "query="+query+"&pg="+nextPage,
							success: function(response){

								var result = $.parseJSON(response);

								if (result.status === 'ok') {

									$(".loading").remove();

									if (result.data.products.length > 0) {
										if (result.data.next_page !== false) {
											resBlock.find('#next_page').val(result.data.next_page);
										} else {
											resBlock.find('#next_page').val('0');
										}

										for(var key in result.data.products) {
											var rightBlock = $('<div/>').addClass("yoss-result-right");
											var productImg = $('<div/>').addClass("product-image").html(result.data.products[key].image);
											var productName = $('<div/>').addClass("product-name").html(result.data.products[key].name);
											var productBrand = $('<div/>').addClass("product-brand").html(result.data.products[key].brand);
											var link = $("<a/>").addClass("product").attr("href", result.data.products[key].url).append(productImg, productName, productBrand);
											rightBlock.append(link);
											lastEl.after(rightBlock);
										}							

									}

								}
							}
						}, 'json');
					}

		        }

			});

		} else {

            $(".yoss-result").remove();
			return false;

		}

	});

});