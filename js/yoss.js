/*
 * @author Max Severin <makc.severin@gmail.com>
 */

$(document).ready(function(){

	$("{$yoss_settings.idHtml}").keyup(function(){
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
				data: "query="+t.val(),
				success: function(response){
					var result = $.parseJSON(response);
					if (result.status === 'ok') {
						resultBlock.removeClass("loading");
						if (result.data.products.length > 0) {
							if (result.data.brands.length > 0) {
								var wrapperBlock = $('<div/>').addClass("yoss-result-wrapper");
								var leftBlock = $('<div/>').addClass("yoss-result-left");
								var rightBlock = $('<div/>').addClass("yoss-result-right");
								var labelSpan = $('<span/>').html("Бренды");
								leftBlock.html(labelSpan);
								for(var key in result.data.brands) {
									var link = $("<a/>").addClass("brand").attr("href", result.data.brands[key].url).html(result.data.brands[key].name);
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
							var productsCountSpan = $("<span/>").addClass("yoss-result-count").html('('+result.data.products_count+' шт)');
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
							resultBlock.addClass("no-products").html("Извините, ничего не было найдено, попробуйте изменить свой запрос");
						}						
					}
				}
			}, 'json');
		} else {
			return false;
		}
	});

});