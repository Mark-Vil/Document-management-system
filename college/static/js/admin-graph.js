var ctx = document.getElementById('viewsChart').getContext('2d');
var chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [
      {
        label: 'Highest Views Per College as of 2024',
        data: [100, 20, 30, 40, 50, 60, 50, 80, 90, 30, 70, 10], // Replace this with the highest views for each month
        backgroundColor: 'rgba(204, 0, 41, 0.2)',
        borderColor: 'rgba(204, 0, 41, 1)',
        borderWidth: 1
      }
    ]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    },
    plugins: {
      tooltip: {
        callbacks: {
          title: function(context) {
            var month = context[0].label;
            var college = ['College of Computing Studies', 'College of Engineering', 'College of Business Administration', 'College of Arts and Sciences', 'College of Education', 'College of Health Sciences', 'College of Law', 'College of Medicine', 'College of Dentistry', 'College of Nursing', 'College of Pharmacy', 'College of Science']; // Replace this with the college with the highest views for each month
            return month + ': ' + college[context[0].dataIndex];
          }
        }
      }
    }
  }
});

var ctx = document.getElementById('downloadsChart').getContext('2d');
var chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [
      {
        label: 'Highest Downloads Per College as of 2024',
        data: [15, 20, 32, 40, 50, 60, 12, 34, 12, 54, 70, 10], // Replace this with the highest views for each month
        backgroundColor: 'rgba(204, 0, 41, 0.2)',
        borderColor: 'rgba(204, 0, 41, 1)',
        borderWidth: 1
      }
    ]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    },
    plugins: {
      tooltip: {
        callbacks: {
          title: function(context) {
            var month = context[0].label;
            var college = ['College of Computing Studies', 'College of Engineering', 'College of Business Administration', 'College of Arts and Sciences', 'College of Education', 'College of Health Sciences', 'College of Law', 'College of Medicine', 'College of Dentistry', 'College of Nursing', 'College of Pharmacy', 'College of Science']; // Replace this with the college with the highest views for each month
            return month + ': ' + college[context[0].dataIndex];
          }
        }
      }
    }
  }
});


var ctx = document.getElementById('trendingTopicsChart').getContext('2d');
var trendingTopicsChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['Machine Learning', 'Climate Change', 'Renewable Energy', 'Augmented Reality', 'Artificial Intelligence'],
    datasets: [{
      data: [20, 10, 12, 15, 25],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)'
      ],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Most Trending Topics'
      }
    }
  }
});