$(document).ready(function() {
	$("a.user_like").click(function() {
		var href = $(this).attr("href");
		var text =  $(this).text();
		var likeLink = $(this);
		var namesContainer = $(this).next("div");
		var names = '';

		$.ajax({
			url: href,
			dataType: 'json',
			success: function(data) {
					console.log(data);
					console.log(data.answer);
					if(data.answer == "Y") {
						likeLink.text("Нравится");
					} else {
						likeLink.text("Не нравится");
					}
					namesContainer.empty();
					if(data.userNames.length > 0) {
						$.each(data.userNames, function(index, value){
							names += '<span>' + value + '</span>'
						});
						console.log(names);
						namesContainer.html(names);
					}
				}
			});
		return false;
	});	
});
