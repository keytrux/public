document.addEventListener('DOMContentLoaded', function() {
    // Для начального состояния
    document.querySelectorAll('tr').forEach((row) => {
        row.addEventListener('click', function() {
            this.classList.toggle('expanded');
        });
    });

    var ctx = document.getElementById('salaryChart').getContext('2d');

    var salaryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: vacancyNames,
            datasets: [{
                label: 'Зарплата от',
                data: salariesFrom,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            },
                {
                    label: 'Зарплата до',
                    data: salariesTo,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                }]
        },
        options: {
            onClick: function(e) {
                var activePoints = salaryChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                if (activePoints.length > 0) {
                    var index = activePoints[0].index;
                    var url = vacancyUrls[index]; // Получение URL по индексу
                    window.open(url, '_blank'); // Открытие ссылки в новой вкладке
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            let currency = currencies[tooltipItem.dataIndex]; // Получаем соответствующую валюту
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' ' + currency;
                        }
                    }
                }
            }
        }
    });
});

function toggleRow(rowId) {
    var content = document.querySelector('#row' + rowId + ' .toggle-content');
    if (content.classList.contains('collapsed')) {
        content.classList.remove('collapsed');
        content.classList.add('expanded'); // Переключаем на «раскрытая» строка
    } else {
        content.classList.remove('expanded');
        content.classList.add('collapsed'); // Переключаем обратно на «сжатая» строка
    }
}