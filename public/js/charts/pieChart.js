// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';


console.log(user);
// Pie Chart Example
var ctx = document.getElementById("overall");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Positive", "Negative", "Neutral"],
    datasets: [{
      data: [user.totalpositivepercent , user.totalnegativepercent, user.totalneutralpercent],
      backgroundColor: ['#28A745', '#DC3545', '#36b9cc'],
      hoverBackgroundColor: ['#1c8a35', '#b52634', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});


var ctx = document.getElementById("posts");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Positive", "Negative", "Neutral"],
    datasets: [{
      data: [user.postpositivepercent  , user.postnegativepercent, user.postneutralpercent],
      backgroundColor: ['#28A745', '#DC3545', '#36b9cc'],
      hoverBackgroundColor: ['#1c8a35', '#b52634', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});

var ctx = document.getElementById("comments");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Positive", "Negative", "Neutral"],
    datasets: [{
      data: [user.commentpositivepercent  , user.commentnegativepercent, user.commentneutralpercent],
      backgroundColor: ['#28A745', '#DC3545', '#36b9cc'],
      hoverBackgroundColor: ['#1c8a35', '#b52634', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});