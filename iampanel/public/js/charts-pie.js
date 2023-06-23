/**
 * For usage, visit Chart.js docs https://www.chartjs.org/docs/latest/
 */
const pieConfig = {
  type: 'doughnut',
  data: {
    datasets: [
      {
        data: [33, 33, 33],
        /**
         * These colors come from Tailwind CSS palette
         * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
         */
        backgroundColor: ['#0694a2', '#1c64f2', '#7e3af2'],
        label: 'Dataset 1',
      },
    ],
    labels: ['Shoes', 'Shirts', 'Bags'],
  },
  options: {
    responsive: true,
    cutoutPercentage: 80,
    /**
     * Default legends are ugly and impossible to style.
     * See examples in charts.html to add your own legends
     *  */
    legend: {
      display: false,
    },
  },
}

// change this to the id of your chart element in HMTL
const pieAkademik = document.getElementById('pie-akademik')
window.myPie = new Chart(pieAkademik, pieConfig)

const pieSDM = document.getElementById('pie-sdm')
window.myPie = new Chart(pieSDM, pieConfig)

const pieKeuangan = document.getElementById('pie-keuangan')
window.myPie = new Chart(pieKeuangan, pieConfig)

const pieKemahasiswaan = document.getElementById('pie-kemahasiswaan')
window.myPie = new Chart(pieKemahasiswaan, pieConfig)

const pieAlumni = document.getElementById('pie-alumni')
window.myPie = new Chart(pieAlumni, pieConfig)
