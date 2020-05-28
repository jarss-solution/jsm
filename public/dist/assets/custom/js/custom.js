$(function() {
    // setTimeout() function will be fired after page is loaded
    // it will wait for 5 sec. and then will fire
    setTimeout(function() {
        $('.push-on-sidebar-open').html('');
    }, 3000);


    //quickview hide script
	$('body').click(function(evt){   
		// Check if click was triggered on or within .quickview-wrapper
		if($(evt.target).closest('.quickview-wrapper').length)
			return;
		// if open button is pressed
		if($(evt.target).closest('.add-task-btn').length)
			return;
		if($(evt.target).closest('.add-project').length)
			return;
		if($(evt.target).closest('.project-processlist').length)
			return;

		//Do processing of click event here for every element except with id ccEle
		if($('.quickview-wrapper').hasClass('open')) {
			$('.quickview-wrapper').removeClass('open');
		}
	});
});

$(".chosen-select").chosen({});

$('.notification-item').on('click', function() {
	var notification_id = $(this).data('notification-id');
	var $this = $(this);

	$.ajax({
		type: 'get',
		url: '/notification/' + notification_id + '/mark-seen',
		success: function(res) {
			var item = $this.find('.notification-text').removeClass('bold');
			$this.find('.mark-seen').css('color', 'green');

			$('.notification-count').html(res);
		}
	})
});

$('.notification-mark-seen-all').on('click', function() {
	$.ajax({
		type: 'get',
		url: '/notification/mark-seen-all',
		success: function(res) {
			$('.notification-text').removeClass('bold');
			$('.mark-seen').css('color', 'green');

			$('.notification-count').html(res);
		}
	})
});