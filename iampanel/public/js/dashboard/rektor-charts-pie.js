/**
 * For usage, visit Chart.js docs https://www.chartjs.org/docs/latest/
 */
 const dataPieJenjang = {
    type: 'doughnut',
    data: {
      datasets: [
        {
          data: [300, 800, 200, 50, 100],
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: ['#0694a2', '#1c64f2', '#7e3af2', '#4b5563', '#dc2626'],
          label: 'Dataset 1',
        },
      ],
      labels: ['S0', 'S1', 'S2', 'S3', 'SP2'],
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
  const pieJenjang = document.getElementById('pie-jenjang')
  window.myPie = new Chart(pieJenjang, dataPieJenjang)

  
 const dataPieStatus = {
    type: 'doughnut',
    data: {
      datasets: [
        {
          data: [300, 800, 200, 50],
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: ['#0694a2', '#1c64f2', '#7e3af2', '#4b5563'],
          label: 'Dataset 1',
        },
      ],
      labels: ['Aktif', 'KRS', 'Cuti', 'Tidak Aktif'],
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
  const pieStatus = document.getElementById('pie-status')
  window.myPie = new Chart(pieStatus, dataPieStatus)

const pieConfigStatusDosen = {
    type: 'doughnut',
    data: {
      datasets: [
        {
          data: [200, 20, 30],
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: ['#0694a2', '#dc2626', '#4b5563'],
          label: 'Dataset 1',
        },
      ],
      labels: ['Aktif', 'Tugas Belajar', 'Ijin Belajar'],
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
  
  const pieStatusDosen = document.getElementById('pie-status-dosen')
  window.myPie = new Chart(pieStatusDosen, pieConfigStatusDosen)

  
const pieConfigPendidikanDosen = {
    type: 'doughnut',
    data: {
      datasets: [
        {
          data: [50, 200, 10, 300, 10],
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: ['#2563eb', '#ef4444', '#4b5563', '#ea580c', '#0d9488'],
          label: 'Dataset 1',
        },
      ],
      labels: ['Prof', 'S3', 'SP2', 'S2', 'SP1'],
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
  
  const piePendidikanDosen = document.getElementById('pie-pendidikan-dosen')
  window.myPie = new Chart(piePendidikanDosen, pieConfigPendidikanDosen)

  
const pieConfigKeuangan = {
    type: 'doughnut',
    data: {
      datasets: [
        {
          data: [1000, 800, 200],
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: ['#2563eb', '#14b8a6', '#9333ea'],
          label: 'Dataset 1',
        },
      ],
      labels: ['Jumlah POK', 'Penerimaan Riil', 'Pagu'],
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
  
  const pieKeuangan = document.getElementById('pie-keuangan')
  window.myPie = new Chart(pieKeuangan, pieConfigKeuangan)

  
const pieConfigJumlahMahasiswa = {
    type: 'doughnut',
    data: {
      datasets: [
        {
          data: [1200, 800, 900, 1100, 2100],
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: ['#2563eb', '#14b8a6', '#9333ea', '#000000', '#ea580c'],
          label: 'Dataset 1',
        },
      ],
      labels: ['FK', 'FH', 'FIB', 'FT', 'FEB'],
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
  
  const pieJumlahMahasiswa = document.getElementById('pie-jumlah-mahasiswa')
  window.myPie = new Chart(pieJumlahMahasiswa, pieConfigJumlahMahasiswa)