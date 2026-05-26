// active sidebar link

const links = document.querySelectorAll(".sidebar a");

links.forEach(link => {

  if (link.href === window.location.href) {
    link.classList.add("active");
  }

});



// sidebar toggle

document.addEventListener("DOMContentLoaded", function () {

  const toggleBtn = document.getElementById("toggleBtn");
  const dashboardLayout = document.getElementById("dashboardLayout");
  const icon = toggleBtn.querySelector("i");

  if (toggleBtn && dashboardLayout) {

    toggleBtn.addEventListener("click", function () {

      // toggle sidebar
      dashboardLayout.classList.toggle("close");

      // icon change
      if (dashboardLayout.classList.contains("close")) {

        icon.classList.remove("fa-angle-left");
        icon.classList.add("fa-angle-right");

      } else {

        icon.classList.remove("fa-angle-right");
        icon.classList.add("fa-angle-left");

      }

    });

  }

});







// this is file edit js logic 
function previewThumb(event) {
  let reader = new FileReader();

  reader.onload = function () {
    document.getElementById('thumbPreview').style.display = "block";
    document.getElementById('thumbPreview').src = reader.result;
  }

  reader.readAsDataURL(event.target.files[0]);
}

function previewFile(event) {
  let file = event.target.files[0];
  let url = URL.createObjectURL(file);

  let iframe = document.getElementById('filePreview');
  iframe.style.display = "block";
  iframe.src = url;
}



// profile js code 
function togglePass(id, icon) {

  let input = document.getElementById(id);

  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}

function showForgot() {
  document.getElementById("changeBox").style.display = "none";
  document.getElementById("forgotBox").style.display = "block";
}

function showChange() {
  document.getElementById("forgotBox").style.display = "none";
  document.getElementById("changeBox").style.display = "block";
}


