document.addEventListener('DOMContentLoaded', function () {

    // ---------- BAR CHART ----------
    const barOptions = {
        chart: {
            type: 'bar',
            height: 500,
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                }
            }
        },
        title: {
          //  text: 'Количество кредитов по филиалам',
            align: 'center',
            style: {fontSize: '18px', fontWeight: 'bold'}
        },
        plotOptions: {
            bar: {
                borderRadius: 3,
                horizontal: false,
                columnWidth: '55%'
            }
        },
        dataLabels: {
            enabled: true,
            formatter: val => val,
            style: {fontSize: '13px', colors: ['#000']}
        },
        xaxis: {
            categories: [],
            title: {text: 'Филиалы'},
            labels: {rotate: -45, style: {fontSize: '11px'}}
        },
        yaxis: {
            title: {text: 'Количество кредитов'},
            min: 0
        },
        colors: ['#008FFB'],
        tooltip: {
            theme: 'light',
            y: {formatter: val => val + ' кредитов'}
        },
        series: [{name: 'Количество кредитов', data: []}]
    };

    let element = document.querySelector("#companyColumnChart");
    let before = element.getAttribute('data-begin');
    let now = element.getAttribute('data-end');
    console.log(before)
    console.log(now)
    const barChart = new ApexCharts(element, barOptions);
    barChart.render();


    // ---------- PIE CHART ----------
    const pieOptions = {
        chart: {
            type: 'pie',
            height: 500,
            toolbar: {show: true}
        },
        title: {
           // text: 'Оплаты',
            align: 'center',
            style: {fontSize: '18px', fontWeight: 'bold'}
        },
        labels: [],
        series: [],
        legend: {position: 'bottom'},
        tooltip: {
            y: {
                formatter: function (val) {
                    return formatNumber(val, 'UZS')
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: (val, opts) =>
                `${opts.w.globals.labels[opts.seriesIndex]}: ${val.toFixed(1)}%`
        },
        colors: [
            '#008FFB', '#00E396', '#FEB019', '#FF4560',
            '#775DD0', '#4ecdc4', '#ff7b00', '#26a69a', '#9C27B0'
        ]
    };
    const pieChart = new ApexCharts(document.querySelector("#companyPieChart"), pieOptions);
    pieChart.render();

    const lineOptions = {
        chart: {
            type: 'line',
            height: 400,
            zoom: {enabled: true},
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                }
            },
            locales: [{
                name: "ru",
                options: {
                    months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
                    shortMonths: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
                    days: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    shortDays: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                }
            }],
            defaultLocale: 'ru'
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        title: {
          //  text: 'График оплат',
            align: 'center',
            style: {fontSize: '18px', fontWeight: 'bold'}
        },
        xaxis: {
            categories: [],
            title: {text: 'Дата'},
            labels: {
                rotate: -45,
                datetimeUTC: false
            },
            type: 'datetime'
        },
        yaxis: {
            title: {text: 'Сумма (сум)'},
            labels: {
                formatter: val => val.toLocaleString('ru-RU')
            }
        },
        tooltip: {
            x: {format: 'dd MMM yyyy'},
            y: {
                formatter: val => val.toLocaleString('ru-RU') + ' сум'
            }
        },
        series: [{
            name: 'Оплаты',
            data: []
        }],
        colors: ['#00E396']
    };
    paymentsLineChart = new ApexCharts(document.querySelector('#paymentLineChart'), lineOptions);
    paymentsLineChart.render();


    const horizontalBarChartOptions = {
        chart: {
            type: 'bar',
            height: 500,
            toolbar: {show: true}
        },
        title: {
           // text: 'Доходы по филиалам',
            align: 'center',
            style: {fontSize: '18px', fontWeight: 'bold'}
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 1,
                distributed: true, // даёт каждому филиалу свой цвет
                barHeight: '70%',
                dataLabels: {position: 'right'}
            }
        },
        dataLabels: {
            enabled: true,
            formatter: val => val.toLocaleString('ru-RU') + ' сум',
            style: {
                fontSize: '13px',
                colors: ['#000']
            }
        },
        xaxis: {
            categories: [],
            title: {text: 'Сумма (сум)'},
            labels: {
                formatter: val => val.toLocaleString('ru-RU')
            }
        },
        yaxis: {
            title: {text: 'Филиалы'}
        },
        colors: [
            '#00E396', '#FEB019', '#775DD0', '#FF4560',
            '#4ecdc4', '#008FFB', '#26a69a', '#9C27B0'
        ],
        tooltip: {
            y: {
                formatter: val => val.toLocaleString('ru-RU') + ' сум'
            }
        },
        series: [{
            name: 'Сумма оплат',
            data: []
        }]
    };

    const horizontalBarChart = new ApexCharts(document.querySelector("#paymentCompanyBarChart"), horizontalBarChartOptions);
    horizontalBarChart.render();

    fetch('/analytics/payment-company-bar-chart?begin='+before+'&end='+now)
        .then(response => response.json())
        .then(data => {
            const categories = data.map(item => item.company);
            const values = data.map(item => item.total);

            horizontalBarChart.updateOptions({
                xaxis: {categories},
                series: [{name: 'Сумма оплат', data: values}]
            });
        })
        .catch(error => console.error('Ошибка загрузки данных:', error));


    // ---------- FETCH для BAR ----------
    fetch('/analytics/company-chart?begin='+before+'&end='+now)
        .then(response => {
            if (!response.ok) throw new Error('Ошибка сети при загрузке столбчатого графика');
            return response.json();
        })
        .then(data => {
            const categories = data.map(item => item.name || 'Без названия');
            const values = data.map(item => parseInt(item.credit_count) || 0);

            barChart.updateOptions({
                xaxis: {categories},
                series: [{name: 'Количество кредитов', data: values}]
            });
        })
        .catch(error => console.error(error));


    // ---------- FETCH для PIE ----------
    fetch('/analytics/payment-pie-chart?begin='+before+'&end='+now)
        .then(response => {
            if (!response.ok) throw new Error('Ошибка сети при загрузке кругового графика');
            return response.json();
        })
        .then(data => {
            const labels = data.map(item => item.method || 'Без названия');
            const values = data.map(item => item.total || 0);

            pieChart.updateOptions({
                labels,
                series: values
            });
        })
        .catch(error => console.error(error));

    // FETCH для LINE
    // Загружаем данные с бэка
    fetch('/analytics/payment-line-chart?begin='+before+'&end='+now)
        .then(res => res.json())
        .then(data => {
            const seriesData = data.map(item => ({
                x: new Date(item.pay_date).getTime(),
                y: parseFloat(item.total_amount)
            }));

            paymentsLineChart.updateSeries([{name: 'Оплаты', data: seriesData}]);
        })
        .catch(err => console.error('Ошибка загрузки данных:', err));


    //


    const ageOptions = {
        chart: {
            type: 'bar',
            height: 420,
            toolbar: { show: true }
        },
        title: {
            text: 'Возраст клиентов',
            align: 'center'
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 3,
                barHeight: '70%',
                distributed: true
            }
        },
        dataLabels: {
            enabled: true
        },
        xaxis: {
            categories: [],
            title: { text: 'Количество клиентов' }
        },
        yaxis: {
            title: { text: 'Возраст' }
        },
        series: [{
            name: 'Клиенты',
            data: []
        }]
    };

    const ageChart = new ApexCharts(
        document.querySelector("#clientAgeBarChart"),
        ageOptions
    );
    ageChart.render();

    fetch('/analytics/client-age-bar?begin='+before+'&end='+now)
        .then(r => r.json())
        .then(data => {
            ageChart.updateOptions({
                xaxis: { categories: data.map(i => i.age_group) },
                series: [{ data: data.map(i => i.total) }]
            });
        });

    const avgDepay = {
        chart: {
            height: 450,
            type: 'line',
            stacked: false,
            toolbar: { show: true }
        },
        title: {
            text: 'Просрочка и неоплата по возрастным группам',
            align: 'center'
        },
        colors: ['#008ffb', '#ff4560'],
        stroke: {
            width: [0, 3]
        },
        plotOptions: {
            bar: {
                columnWidth: '50%',
                borderRadius: 4
            }
        },
        dataLabels: {
            enabled: true,
            enabledOnSeries: [1] // показываем цифры только на линии
        },
        xaxis: {
            categories: []
        },
        yaxis: [
            {
                title: {
                    text: 'Средняя просрочка (дней)'
                }
            },
            {
                opposite: true,
                title: {
                    text: 'Неоплачено (%)'
                },
                labels: {
                    formatter: function (val) {
                        return val + '%';
                    }
                }
            }
        ],
        tooltip: {
            shared: true,
            intersect: false
        },
        series: []
    };

    const avgDepayChart = new ApexCharts(
        document.querySelector("#clientDelayAgeBarChart"),
        avgDepay
    );

    avgDepayChart.render();

    fetch('/analytics/delays-by-age?begin=' + before + '&end=' + now)
        .then(r => r.json())
        .then(data => {
            avgDepayChart.updateOptions({
                xaxis: { categories: data.labels },
                series: data.series
            });
        });

});

/**
 * Форматирует число с разделением тысяч и опциональной валютой
 * @param {number|string} num - исходное число
 * @param {string} [currency=''] - символ валюты ('сум', '$', '€' и т.п.)
 * @param {number} [decimals=0] - количество знаков после запятой
 * @returns {string}
 */
function formatNumber(num, currency = '', decimals = 0) {
    if (num === null || num === undefined || isNaN(num)) return '0';
    const fixed = parseFloat(num).toFixed(decimals);

    // Разделяем тысячи и ставим запятую/точку по локали
    const formatted = fixed.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    return currency ? `${formatted} ${currency}` : formatted;
}