export const pieChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    elements: {
    },
    tooltips: {
        callbacks: {
            label: function (tooltipItem, data) {
                var dataset = data.datasets[tooltipItem.datasetIndex];
                var meta = dataset._meta[Object.keys(dataset._meta)[0]];
                var total = meta.total;
                var currentValue = dataset.data[tooltipItem.index];
                var percentage = parseFloat((currentValue / total * 100).toFixed(1));
                return currentValue + ' (' + percentage + '%)';
            },
            title: function (tooltipItem, data) {
                return data.labels[tooltipItem[0].index];
            }
        },
        mode: 'index',
        intersect: true,
    },
    legend: {
        display: true,
        position: 'bottom'
    }
};
