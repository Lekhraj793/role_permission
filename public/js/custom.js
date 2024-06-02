function displaySuccessMessage(message) {
    hide_loader();
    Toast.fire({
      icon: 'success',
      title: message
    });
  
}
  
function displayErrorMessage(message) {
    hide_loader();
    Toast.fire({
      icon: 'error',
      title: message
    });
}

function show_loader() {
    $("body").addClass('page-loading');
}

function hide_loader() {
    $("body").removeClass('page-loading');
}