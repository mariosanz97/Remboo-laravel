function enable_umbral()
{
document.getElementById("vecin").disabled = true;
document.getElementById("umbral").disabled = false;
document.getElementById("vecin").value = 0;
document.getElementById("umbral").value = 0.85;
}

function enable_vecin()
{
document.getElementById("vecin").disabled = false;
document.getElementById("umbral").disabled = true;
document.getElementById("umbral").value = 0;
document.getElementById("vecin").value = 5;
}