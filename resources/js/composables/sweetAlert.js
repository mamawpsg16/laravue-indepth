// useSweetAlert.js
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


let defaultConfig = {
  timer: 2000,
  toast: true, // Use toast mode
  position: 'top-end', // You can adjust the position as needed
  showClass: {
      popup: 'swal2-noanimation',
      backdrop: 'swal2-noanimation',
  },
  hideClass: {
      popup: '',
      backdrop: '',
  },
};

const baseSwal = function(config = null){
    Swal.fire(config ?? defaultConfig)
}

const swalSuccess = function(config) {
  const configOptions = {...defaultConfig,...config };
  Swal.fire(configOptions)
}

const swalError = function(config) {
  const configOptions = {...defaultConfig,...config };
  Swal.fire(configOptions)
}

export { swalSuccess,swalError }
