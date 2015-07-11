// JavaScript Document

$(document).ready(function(e) {
	$('.need_confirm').click(function() {
		var e = confirm("Confirmer votre action");
		
		
		if(!e) {
			return false;
		}
	});
	
	$('.load_bar').click(function(e) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4 && xhr.status == 200) {
				var obj = JSON.parse(xhr.responseText);
    $('#graphe_container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: obj.title.text
        },
        subtitle: {
            text: 'Source: Wikipedia.org'
        },
        xAxis: {
            categories: ['Reponse'],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'reponse echelle 1 / 1',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' millions'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: obj.series
    });
			}
		}
		var id = e.target.id;
		xhr.open("GET", "http://localhost/sondage/Controller/bar.php?ID="+id);
		xhr.send();
	});

/////////////////////////////////////////////////////////////////////////////////////////////////


	$('.load_pie').click(function(e) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4 && xhr.status == 200) {
				var obj = JSON.parse(xhr.responseText);
    $('#graphe_container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: obj.title.text
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: obj.series
    });
			}
		}

		var id = e.target.id;
		xhr.open("GET", "http://localhost/sondage/Controller/pie.php?ID="+id);
		xhr.send();
	});
});

