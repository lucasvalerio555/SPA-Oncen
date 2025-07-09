//import Chart from '../js/chart.js'
//import Chart from 'bhttps://cdn.jsdelivr.net/npm/chart.js';
const chartCanvas = document.getElementById('Chart');
const ctx = chartCanvas.getContext('2d');

let chart = null;
let chartProgress = null;

// Mapa de acceso rápido (O(1)) por nombre de archivo
const graphTypeMap = {
  'manager.html': 'doughnut',
  'viewreserva.html': 'bar+progress'
};

const chartConfigs = {
  circle: {
    type: 'pie',
    data: {
      datasets: [{
        data: [10, 20, 30],
        backgroundColor: ['red', 'green', 'blue'],
        label: 'Círculos',
        hoverOffset: 4,
        hoverBackgroundColor: ['darkred', 'darkgreen', 'darkblue'],
        hoverBorderColor: 'white',
        hoverBorderWidth: 3,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' },
        title: { display: true, text: 'Gráfica de Círculos' }
      }
    }
  },

  bar: {
    type: 'bar',
    data: {
      labels: ['Piscina Termal', 'Zona Pilates', 'Orrenal Pool'],
      datasets: [{
        label: 'Reservas',
        data: [12, 7, 5],
        backgroundColor: [
          'rgba(54, 162, 235, 0.7)',
          'rgba(255, 159, 64, 0.7)',
          'rgba(75, 192, 192, 0.7)'
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
          'rgba(255, 159, 64, 1)',
          'rgba(75, 192, 192, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  },

  doughnut: {
    type: 'doughnut',
    data: {
      labels: ['Piscinas', 'Masajes', 'Yoga'],
      datasets: [{
        data: [25, 15, 7],
        backgroundColor: ['#4CAF50', '#FF9800', '#03A9F4']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' },
        title: { display: true, text: 'Gráfico de Servicios' }
      }
    }
  },

  progress: {
    type: 'doughnut',
    data: {
      datasets: [{
        data: [65, 35],
        backgroundColor: ['#36A2EB', '#e0e0e0'],
        borderWidth: 0,
        cutout: '80%'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        tooltip: { enabled: false },
        legend: { display: false }
      }
    }
  }
};

// Acceso optimizado O(1) al nombre de archivo y tipo de gráfico
function getGraphTypeFromFilename() {
  const path = window.location.pathname;
  const slashIndex = path.lastIndexOf('/');
  const filename = path.slice(slashIndex + 1).toLowerCase(); // O(1) práctico

  return graphTypeMap[filename] || 'circle'; // acceso directo O(1)
}

function drawGraphs(type) {
  if (chart) chart.destroy();
  if (chartProgress) chartProgress.destroy();

  if (type === 'bar+progress') {
    chart = new Chart(ctx, chartConfigs.bar);

    const progressCtx = document.getElementById('progressChart')?.getContext('2d');
    if (progressCtx) {
      chartProgress = new Chart(progressCtx, chartConfigs.progress);
    }

  } else {
    const config = chartConfigs[type] || chartConfigs.circle;
    chart = new Chart(ctx, config);
  }
}

window.addEventListener('DOMContentLoaded', () => {
  drawGraphs(getGraphTypeFromFilename());
});
