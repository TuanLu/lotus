
var timeFormat = 'MM/DD/YYYY HH:mm';
function newDateString(days) {
	return moment().add(days, 'd').format(timeFormat);
}
//alert(randomScalingFactor());
var color = Chart.helpers.color;
var config = {
	type: 'bar',
	data: {
		labels: [
			'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
		],
		datasets: [{
			type: 'bar',
			label: 'Thận Khí Khang',
			backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
			borderColor: window.chartColors.red,
			borderWidth : 0,
			data: [
				46,56,76,88,122,151,155,177,222
			],
		}, {
			type: 'bar',
			label: 'Tuệ Đức Kids',
			backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
			borderColor: window.chartColors.blue,
			borderWidth : 0,
			data: [
				12,14,16,22,25,34,67
			],
		}, {
			type: 'line',
			label: 'Kế hoạch TKK',
			backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
			borderColor: window.chartColors.red,
			borderWidth : 0,
			fill: false,
			data: [
				48,56,76,88,122,151,213,224,226
			],
		}, {
			type: 'line',
			label: 'Kế hoạch Kids',
			backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
			borderColor: window.chartColors.blue,
			borderWidth : 0,
			fill: false,
			data: [
				12,14,16,22,25,34,67
			],
		}]
	},
	options: {
		title: {
			text: 'Báo Cáo Doanh Thu Theo Sản Phẩm'
		},
		scales: {
			xAxes: [{
				//type: 'time',
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Thống Kê Năm 2018',
				},
				time: {
					//format : day,
					format: timeFormat,
					//round: 'day'
				},
				tick: {
					beginAtZero: true
				}
			}],
			yAxes: [{
				tick: {
					beginAtZero: true
				},
				scaleLabel: {
					display: true,
					labelString: 'Triệu VNĐ',
				},
			}]
		},
	}
};
var config_tinhthanh = {
	type: 'bar',
	data: {
		labels: [
			'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
		],
		datasets: [{
			type: 'bar',
			label: 'Hà Nội',
			backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
			borderColor: window.chartColors.red,
			borderWidth : 0,
			data: [
				46,56,76,88,122,151,155,177,222
			],
		}, {
			type: 'line',
			label: 'Kế hoạch HN',
			backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
			borderColor: window.chartColors.red,
			borderWidth : 0,
			fill: false,
			data: [
				48,56,76,88,122,151,213,224,226
			],
		}, {
			type: 'bar',
			label: 'Hồ Chí Minh',
			backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
			borderColor: window.chartColors.blue,
			borderWidth : 0,
			data: [
				12,14,16,22,25,34,67
			],
		}, {
			type: 'line',
			label: 'Kế hoạch HCM',
			backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
			borderColor: window.chartColors.blue,
			borderWidth : 0,
			fill: false,
			data: [
				12,14,16,22,25,34,67
			],
		}]
	},
	options: {
		title: {
			text: 'Báo Cáo Doanh Thu Theo Tỉnh Thành'
		},
		scales: {
			xAxes: [{
				//type: 'time',
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Thống Kê Năm 2018',
				},
				time: {
					//format : day,
					format: timeFormat,
					//round: 'day'
				},
				tick: {
					beginAtZero: true
				}
			}],
			yAxes: [{
				tick: {
					beginAtZero: true
				},
				scaleLabel: {
					display: true,
					labelString: 'Triệu VNĐ',
				},
			}]
		},
	}
};
Chart.plugins.register({
	afterDatasetsDraw: function(chart) {
		var ctx = chart.ctx;

		chart.data.datasets.forEach(function(dataset, i) {
			var meta = chart.getDatasetMeta(i);
			if (!meta.hidden) {
				meta.data.forEach(function(element, index) {
					// Draw the text in black, with the specified font
					ctx.fillStyle = 'rgb(0, 0, 0)';

					var fontSize = 14;
					var fontStyle = 'normal';
					var fontFamily = 'Helvetica Neue';
					ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

					// Just naively convert to string for now
					var dataString = dataset.data[index].toString();

					// Make sure alignment settings are correct
					ctx.textAlign = 'center';
					ctx.textBaseline = 'middle';

					var padding = 5;
					var position = element.tooltipPosition();
					//ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
				});
			}
		});
	}
});
window.onload = function() {
	var ctx = document.getElementById('isd_baocao_nam_bieudo').getContext('2d');
	window.myLine = new Chart(ctx, config);
};
var colorNames = Object.keys(window.chartColors);
document.getElementById('addDataset').addEventListener('click', function() {
	var colorName = colorNames[config.data.datasets.length % colorNames.length];
	var newColor = window.chartColors[colorName];
	var newDataset = {
		label: 'Dataset ' + config.data.datasets.length,
		borderColor: newColor,
		backgroundColor: color(newColor).alpha(0.5).rgbString(),
		data: [],
	};

	for (var index = 0; index < config.data.labels.length; ++index) {
		newDataset.data.push(randomScalingFactor());
	}

	config.data.datasets.push(newDataset);
	window.myLine.update();
});

document.getElementById('addData').addEventListener('click', function() {
	if (config.data.datasets.length > 0) {
		config.data.labels.push(newDateString(config.data.labels.length));

		for (var index = 0; index < config.data.datasets.length; ++index) {
			config.data.datasets[index].data.push(randomScalingFactor());
		}

		window.myLine.update();
	}
});

document.getElementById('removeDataset').addEventListener('click', function() {
	config.data.datasets.splice(0, 1);
	window.myLine.update();
});

document.getElementById('removeData').addEventListener('click', function() {
	config.data.labels.splice(-1, 1); // remove the label first

	config.data.datasets.forEach(function(dataset, datasetIndex) {
		config.data.datasets[datasetIndex].data.pop();
	});
	window.myLine.update();
});
jQuery(document).ready(function($){
	$('.header_left').click(function(){
		$('.menu').toggleClass('menu_active');
		$('.grid-container').toggleClass('hidden_menu');
	})
	$('.action_filter').click(function(){
		var filter = $(this).attr('filter'),
			baocao = $('#isd_baocao_nam_bieudo');
		$(this).toggleClass('active');
		//$('.adv_search > div').addClass('hidden');
		switch (filter) {
			case 'week' : 
				$('.action_filter.filter_date').removeClass('active'); 
				$(this).addClass('active'); 
				break;
			case 'month' : 
				$('.action_filter.filter_date').removeClass('active'); 
				$(this).addClass('active'); 
				break;
			case 'year' : 
				$('.action_filter.filter_date').removeClass('active'); 
				$(this).addClass('active'); 
				break;
			case 'doanhso' : $('.ds_doanhso').toggleClass('hidden'); break;
			case 'product' : $('.ds_sanpham').toggleClass('hidden'); break;
			case 'dialy' : $('.ds_vungmien').toggleClass('hidden'); break;
			case 'nhathuoc' : $('.ds_nhathuoc').toggleClass('hidden'); break;
		}
	})
})