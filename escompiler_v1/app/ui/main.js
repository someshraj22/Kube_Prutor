$(document).ready(function() {
	handlers();
});

function handlers() {
	$('.category').click(function(e) {
		e.preventDefault();
		var hash = $(this).attr('data-hash');
		$.get('/services.php', {action: 'instances', hash: hash}, function(response) {
			console.log(response);
			$('#instance-list').empty();
			for(var i = 0; i < response.length; i++) {
				$('#instance-list').append(
					$('<tr>')
						.append(
							$('<td>').append(
								$('<a>')
								.attr('href', '')
								.text(response[i].assignment_id + '/' + response[i].code_id)
							)
						)
						.append($('<td>').text(': ' + response[i].message))
				);
			}
		});
	});
}