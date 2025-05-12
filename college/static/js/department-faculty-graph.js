var ctx = document.getElementById('approvedChart').getContext('2d');
var chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['College of Computing Studies', 'College of Engineering', 'College of Business Administration', 'College of Arts and Sciences', 'College of Education', 'College of Health Sciences', 'College of Law', 'College of Medicine', 'College of Dentistry', 'College of Nursing', 'College of Pharmacy', 'College of Science'],
    datasets: [
      {
        label: 'Highest Approved Submission Per College as of 2024',
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

var ctx = document.getElementById('submissionChart').getContext('2d');
var chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['College of Computing Studies', 'College of Engineering', 'College of Business Administration', 'College of Arts and Sciences', 'College of Education', 'College of Health Sciences', 'College of Law', 'College of Medicine', 'College of Dentistry', 'College of Nursing', 'College of Pharmacy', 'College of Science'],
    datasets: [
      {
        label: 'Highest Submission Per College as of 2024',
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
