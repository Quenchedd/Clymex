function show(){
    document.querySelector('.menu').classList.toggle('open')
    document.querySelector('.navigation').classList.toggle('active')
}

var dropdownVisible = false;

function toggleDropdown() {
    var dropdownContent = document.getElementById("myDropdown");
    dropdownVisible = !dropdownVisible; // Toggle dropdown visibility

    if (dropdownVisible) {
        dropdownContent.classList.add("show");
    } else {
        dropdownContent.classList.remove("show");
    }
}

window.onclick = function(event) {
    if (!event.target.closest('.dropdown-container')) {
        var dropdownContent = document.getElementById("myDropdown");
        if (dropdownContent.classList.contains('show')) {
            dropdownContent.classList.remove('show');
            dropdownVisible = false; // Update dropdown state
        }
    }
}

document.getElementById("image").onchange = function(){
    document.getElementById("form").submit();
};
