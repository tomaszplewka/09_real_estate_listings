const exploreBtn = document.querySelector('#explore-btn');
const doorShadow = document.querySelector('#door-shadow');
const body = document.querySelector('body');
//
exploreBtn.addEventListener('click', (e) => {
	//
	const exploreListings = e.target.parentElement;
	exploreListings.style.backgroundColor = 'var(--primary-color)';
	exploreListings.parentElement.style.backgroundColor = 'var(--helper-light-color)';
	doorShadow.style.backgroundColor = 'rgba(240, 226, 231, 0.75)';
	//
	setTimeout(() => {
		//
		exploreListings.classList.add('explore-listings-open');
		exploreListings.parentElement.classList.add('explore-listings-wrapper-scale');
		//
		setTimeout(() => {
			//
			exploreListings.parentElement.parentElement.classList.add('hide');
			body.classList.remove('vw-100');
			body.classList.remove('vh-100');
			body.classList.remove('overflow-hidden');
			//
		}, 3000);
		//
	}, 500);
	//
});
