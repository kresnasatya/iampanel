/**
 * For usage, visit Chart.js docs https://www.chartjs.org/docs/latest/
 */
 const barConfigJumlahDosen = {
    type: 'bar',
    data: {
      labels: ['FK', 'FH', 'FIB', 'FT', 'FEB'],
      datasets: [
        {
          label: 'Jumlah',
          backgroundColor: '#0694a2',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [400, 50, 50, 60, 80],
        },
      ],
    },
    options: {
      responsive: true,
      legend: {
        display: false,
      },
    },
  }
  
  const barsJumlahDosen = document.getElementById('bars-jumlah-dosen')
  window.myBar = new Chart(barsJumlahDosen, barConfigJumlahDosen)

  
 const barConfigKegiatan = {
    type: 'bar',
    data: {
      labels: ['Kegiatan', 'SPJ', 'SP2D'],
      datasets: [
        {
          label: 'Jumlah',
          backgroundColor: '#0694a2',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [100, 45, 55],
        },
      ],
    },
    options: {
      responsive: true,
      legend: {
        display: false,
      },
    },
  }
  
  const barsKegiatan = document.getElementById('bars-kegiatan')
  window.myBar = new Chart(barsKegiatan, barConfigKegiatan)
  
  
const barConfigWisudawan = {
    type: 'bar',
    data: {
      labels: ['2020', '2021', '2022'],
      datasets: [
        {
          label: 'FK',
          backgroundColor: '#2563eb',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [689, 749, 489],
        },
        {
          label: 'FH',
          backgroundColor: '#14b8a6',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [694, 502, 890],
        },
        {
          label: 'FIB',
          backgroundColor: '#9333ea',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [500, 600, 300],
        },
        {
          label: 'FT',
          backgroundColor: '#000000',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [920, 1192, 748],
        },
        {
          label: 'FEB',
          backgroundColor: '#ea580c',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [2100, 1873, 1193],
        },
      ],
    },
    options: {
      responsive: true,
      legend: {
        display: false,
      },
    },
  }
  
  const barsWisudawan = document.getElementById('bars-wisudawan')
  window.myBar = new Chart(barsWisudawan, barConfigWisudawan)

  
const barConfigMahasiswaLulus = {
    type: 'bar',
    data: {
        labels: ['FK', 'FH', 'FIB', 'FT', 'FEB'],
      datasets: [
        {
          label: 'Jumlah',
          backgroundColor: '#2563eb',
          // borderColor: window.chartColors.red,
          borderWidth: 1,
          data: [689, 749, 489, 820, 1309],
        },
      ],
    },
    options: {
      responsive: true,
      legend: {
        display: false,
      },
    },
  }
  
  const barsMahasiswaLulus = document.getElementById('bars-mahasiswa-lulus')
  window.myBar = new Chart(barsMahasiswaLulus, barConfigMahasiswaLulus)