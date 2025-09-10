
function logout() {
  window.location.href = "../php/logout.php";
}


function selectCar(car) {
  localStorage.setItem("selectedCar", car); // 👈 儲存選擇的車到 localStorage
  window.location.href = "search-station.html";
}

function selectStation(station) {
  window.location.href = "reserve.html";
}

function reserve() {
  window.location.href = "charging.html";
}

document.addEventListener("DOMContentLoaded", function () {
  const car = localStorage.getItem("selectedCar");
  document.getElementById("car-name").textContent = car ?? "未選擇車款";

  let progress = 0;
  let timeRemaining = 30; // 充電預估30分鐘

  const progressFill = document.getElementById("progress");
  const progressText = document.getElementById("status");
  const timeText = document.getElementById("time-left");

  const timer = setInterval(() => {
    if (progress >= 100) {
      clearInterval(timer);
      progressText.textContent = "充電進度：100%";
      timeText.textContent = "充電完成！";
    } else {
      progress += 1;
      timeRemaining -= 0.3;
      progressFill.style.width = progress + "%";
      progressText.textContent = "充電進度：" + progress + "%";
      timeText.textContent = "剩餘時間：約 " + Math.ceil(timeRemaining) + "分鐘";
    }
  }, 300); // 每0.3秒前進
});

