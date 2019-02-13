$(document).ready(function(){
    $.ajax({
      url: "http://127.0.0.1/forecast/data.php",
      method: "GET",
    success: function(data) {
      console.log(data);

      var rainfall = [];
      var time = [];
      var waterlevel=[];

      for(var i in data) {
          time.push("time"+data[i].forecast_time);
          rainfall.push( data[i].rainfall_intensity);
          waterlevel.push(data[i].water_level);
      }
      
      var graphdata={
          labels: time,
          datasets: [
              {
                label: "rainfall intensity",
                fill:false,
                lineTension: 0.1,
                backgroundColor: "rgba(59, 89, 152, 0.75)",
                borderColor: "rgba(59, 89, 152, 1)",
                pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
                pointHoverBorderColor: "rgba(59, 89, 152, 1)",
                data: rainfall
              },

              {
                label: "water level",
                fill: false,
                lineTension: 0.1,
                backgroundColor: "rgba(29, 202, 255, 0.75)",
                borderColor: "rgba(29, 202, 255, 1)",
                pointHoverBackgroundColor: "rgba(29, 202, 255, 1)",
                pointHoverBorderColor: "rgba(29, 202, 255, 1)",
                data: waterlevel
              }
          ]
      };

      var ctx = $("#mycanvas");

      var LineGraph = new Chart(ctx, {
        type: 'line',
        data: graphdata
      });
    },
    error : function(data) {

    }
  });
});