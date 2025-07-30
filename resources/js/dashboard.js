const ctx = document.getElementById('weekly-sales');
const labels = JSON.parse(ctx.dataset.labels);
const sales = JSON.parse(ctx.dataset.sales);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Penjualan Mingguan',
            data: sales
        }]
    }
});
