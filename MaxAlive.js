$(function () {
  $('#graph').highcharts({
    chart: {
      type: 'area'
    },
    title: {
      text: 'Population by Year'
    },
    subtitle: {
      text: 'Source: Randomly generated value'
    },
    xAxis: {
      categories: dataYear,
      tickmarkPlacement: 'on',
      title: {
        enabled: false
      }
    },
    yAxis: {
      title: {
        text: 'Population'
      }
    },
    tooltip: {
      shared: true
    },
    plotOptions: {
      area: {
        stacking: 'normal',
        lineColor: '#666666',
        lineWidth: 1,
        marker: {
          lineWidth: 1,
          lineColor: '#666666'
        }
      }
    },
    series: dataAlive
  });
  
  var str = '';
  for(var x in dataset){
    str += '<div style="float: left; border: 1px solid; padding: 2px;">';
    str += dataset[x][0];
    str += ' - ';
    str += dataset[x][1];
    str += '</div>';
  }
  $('#dataset').html(str);
  
  $('#yearMax').html(yearMax);
  $('#aliveMax').html(aliveMax);
});