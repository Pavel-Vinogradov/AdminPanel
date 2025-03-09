@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Статистика просмотров и комментариев</h1>

        <div class="flex space-x-4 mb-4">
            <button class="btn-range bg-blue-500 text-white px-4 py-2 rounded" data-range="day">Сегодня</button>
            <button class="btn-range bg-gray-300 px-4 py-2 rounded" data-range="week">Неделя</button>
            <button class="btn-range bg-gray-300 px-4 py-2 rounded" data-range="month">Месяц</button>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <canvas id="statChart"></canvas>
        </div>

        <table class="min-w-full bg-white mt-6 border rounded-lg shadow">
            <thead>
            <tr>
                <th class="border px-4 py-2">Дата</th>
                <th class="border px-4 py-2">Просмотры</th>
                <th class="border px-4 py-2">Комментарии</th>
            </tr>
            </thead>
            <tbody id="statsTable">
            <tr>
                <td colspan="3" class="text-center py-4">Загрузка данных...</td>
            </tr>
            </tbody>
        </table>
    </div>

    <script type="module">
        let chart;
        let activeRange = 'day';

        document.querySelectorAll('.btn-range').forEach(btn => {
            btn.addEventListener('click', function () {
                activeRange = this.dataset.range;
                document.querySelectorAll('.btn-range').forEach(b => b.classList.replace('bg-blue-500', 'bg-gray-300'));
                this.classList.replace('bg-gray-300', 'bg-blue-500');
                fetchStats();
            });
        });

        function fetchStats() {
            axios.get('{{ route('dashboard.statistic.index') }}', {
                params: {range: activeRange}
            })
                .then(response => {
                    updateTable(response.data);
                    updateChart(response.data);

                })
                .catch(error => {
                    console.error('Ошибка загрузки данных:', error);
                });
        }

        function updateChart(data) {
            const ctx = document.getElementById('statChart');
            const labels = data.map(d => d.id);
            const views = data.map(d => d.views);
            const comments = data.map(d => d.comments);

            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Просмотры',
                            data: views,
                            borderColor: 'blue',
                            backgroundColor: 'rgba(0, 0, 255, 0.2)',
                            fill: true
                        },
                        {
                            label: 'Комментарии',
                            data: comments,
                            borderColor: 'red',
                            backgroundColor: 'rgba(255, 0, 0, 0.2)',
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function updateTable(data) {
            const tbody = document.getElementById('statsTable');
            tbody.innerHTML = data.length
                ? data.map(d => `
                    <tr>
                        <td class="border px-4 py-2">${d.id}</td>
                        <td class="border px-4 py-2">${d.views}</td>
                        <td class="border px-4 py-2">${d.comments}</td>
                    </tr>
                `).join('')
                : `<tr><td colspan="3" class="text-center py-4">Нет данных</td></tr>`;
        }

        fetchStats();
        setInterval(fetchStats, 10000); // Обновление раз в 10 сек
    </script>
@endsection
