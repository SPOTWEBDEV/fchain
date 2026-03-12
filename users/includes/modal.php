
<div id="customAlert" class="fixed top-6 right-6 hidden z-50">
  <div class="bg-[#111827] border border-[#1e293b] text-white px-6 py-4 rounded-xl shadow-xl flex items-center gap-3">

    <div id="alertIcon" class="text-green-400">
      <i class="fa-solid fa-circle-check text-xl"></i>
    </div>

    <div id="alertMessage" class="text-sm font-medium">
      
    </div>

  </div>
</div>



<script>

function showAlert(message,type="success"){

    let alertBox = document.getElementById("customAlert");
    let alertMsg = document.getElementById("alertMessage");
    let alertIcon = document.getElementById("alertIcon");

    alertMsg.innerText = message;

    if(type === "success"){
        alertIcon.innerHTML = '<i class="fa-solid fa-circle-check text-green-400 text-xl"></i>';
    }else if(type === "error"){
        alertIcon.innerHTML = '<i class="fa-solid fa-circle-xmark text-red-400 text-xl"></i>';
    }

    alertBox.classList.remove("hidden");

    setTimeout(()=>{
        alertBox.classList.add("hidden");
    },8000);

}

</script>