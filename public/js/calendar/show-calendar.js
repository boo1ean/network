	$(document).ready(function() {
	
                var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			editable: true,
                        firstDay: 1,
			events: [
				{
					title: 'DEMO',
					start: new Date(y, m, 5, 17, 0),
					allDay: false
				},
			]
		});
		
	});

