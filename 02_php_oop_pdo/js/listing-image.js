const fileInput = document.getElementById('image');
//
fileInput.addEventListener('change', (e) => {
	//
	e.target.nextElementSibling.textContent = fileInput.files[0].name;
	//
});
